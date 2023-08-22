<?php

namespace App\Http\Controllers;

use App\Filters\FilterLimiter;
use App\Filters\NewFilter;
use App\Filters\UrlFilter;
use App\Helper;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Imports\SitesImport;
use App\Models\Category;
use App\Models\Client;
use App\Models\Country;
use App\Models\Language;
use App\Models\Offer;
use App\Models\Seller;
use App\Models\Site;
use App\Models\Team;
use App\Models\Type;
use App\Notifications\SiteAdded;
use App\Notifications\SiteDeleted;
use App\Notifications\SiteRestored;
use App\Notifications\SiteUpdated;
use App\Sorts\NewSort;
use App\Sorts\RecommendedSort;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coins = config('coins');

        $pendingCount = Site::query()
            ->withTrashed()
            ->ofStatus('PENDING')
            ->count();

        $offersCount = Offer::count();

        $countries = Country::query()
            ->whereHas('sites')
            ->orderBy('name')
            ->get();

        $languages = Language::query()
            ->whereHas('sites')
            ->orderBy('name')
            ->get();

        $categories = Category::query()
            ->whereHas('sites')
            ->orderBy('name')
            ->get();

        $sellers = Seller::query()
            ->orderBy('name')
            ->get();

        $types = Type::query()
            ->orderBy('name')
            ->get();

        $query = request()->query();

        $filters = [
            'sort' => Arr::get($query, 'sort', 'url'),
            'filter' => [
                'url' => Arr::get($query, 'filter.url', ''),

                'sale' => [
                    'from' => Arr::get($query, 'filter.sale.from', Site::ofStatus('APPROVED')->min('sale')),
                    'to' => Arr::get($query, 'filter.sale.to', Site::ofStatus('APPROVED')->max('sale')),
                ],

                'da' => [
                    'from' => Arr::get($query, 'filter.da.from', Site::ofStatus('APPROVED')->min('da')),
                    'to' => Arr::get($query, 'filter.da.to', Site::ofStatus('APPROVED')->max('da')),
                ],

                'dr' => [
                    'from' => Arr::get($query, 'filter.dr.from', Site::ofStatus('APPROVED')->min('dr')),
                    'to' => Arr::get($query, 'filter.dr.to', Site::ofStatus('APPROVED')->max('dr')),
                ],

                'gambling' => filter_var(Arr::get($query, 'filter.gambling', false), FILTER_VALIDATE_BOOL),
                'sponsor' => filter_var(Arr::get($query, 'filter.sponsor', false), FILTER_VALIDATE_BOOL),

                'new' => filter_var(Arr::get($query, 'filter.new', false), FILTER_VALIDATE_BOOL),

                'language_id' => Arr::get($query, 'filter.language_id', []),
                'country_id' => Arr::get($query, 'filter.country_id', []),
                'category_id' => Arr::get($query, 'filter.category_id', null),
                'seller_id' => Arr::get($query, 'filter.seller_id', null),

                'types' => Arr::get($query, 'filter.types', null),
            ],
        ];

        $sites = QueryBuilder::for(Site::class)
            ->withTrashed()
            ->when(! isset($query['filter']['of_status']), function ($query) {
                $query->ofStatus('APPROVED');
            })
            ->withCount('orders')
            ->with([
                'category',
                'types',
            ])
            ->defaultSort('url')
            ->allowedSorts([
                'url',
                'sale',
                'da',
                'dr',
                'inserted_at',
                'last_updated_at',
                // AllowedSort::custom('recommended', new RecommendedSort()),
                // AllowedSort::custom('new', new NewSort()),
            ])
            ->allowedFilters([
                AllowedFilter::custom('url', new UrlFilter),
                AllowedFilter::custom('sale', new FilterLimiter, null, ''),
                AllowedFilter::custom('da', new FilterLimiter, null, ''),
                AllowedFilter::custom('dr', new FilterLimiter, null, ''),
                // AllowedFilter::custom('traffic', new FilterLimiter),
                // AllowedFilter::custom('tf', new FilterLimiter),
                'gambling',
                'sponsor',
                // 'ssl',
                // 'cripto',
                // 'banner',
                // 'menu',
                AllowedFilter::custom('new', new NewFilter),
                AllowedFilter::exact('language_id'),
                AllowedFilter::exact('country_id'),
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('seller_id'),
                AllowedFilter::scope('of_status'),

                AllowedFilter::scope('types', 'of_types'),
            ])
            ->paginate(50)
            ->appends(request()->query());

        return Inertia::render('Sites/IndexNew', [
            'sites' => $sites,
            'coins' => $coins,
            'filters' => $filters,
            'pendingCount' => $pendingCount,
            'offersCount' => $offersCount,
            'countries' => $countries,
            'languages' => $languages,
            'categories' => $categories,
            'sellers' => $sellers,
            'types' => $types,
            'importFailures' => session('importFailures'),
            'importDiff' => session('importDiff'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $languages = Language::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $sellers = Seller::orderBy('name')->get();
        $teams = Team::orderBy('name')->get();
        $types = Type::orderBy('name')->get();

        $coins = config('coins');

        return Inertia::render('Sites/CreateNew', [
            'categories' => $categories,
            'languages' => $languages,
            'countries' => $countries,
            'sellers' => $sellers,
            'teams' => $teams,
            'types' => $types,
            'coins' => $coins,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSiteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSiteRequest $request)
    {
        DB::transaction(function () use($request) {
            
            $site = Site::create(array_merge(
                Arr::except($request->validated(), 'types'), [
                'status' => 'PENDING',
            ]));

            $site->update([
                'status' => 'APPROVED',
            ]);

            $types = collect($request->validated()['types']);

            $types = $types->where('available', true);

            $types = $types->mapWithKeys(function ($type) {
                return [$type['id'] => [
                    'cost' => Helper::extractNumbersFromString($type['cost']),
                    'sale' => Helper::extractNumbersFromString($type['sale']),
                    'cost_coin' => $type['cost_coin'],
                    'sale_coin' => $type['sale_coin'],
                ]];
            });

            $site->types()->sync($types);

            Notification::send(Client::all(), new SiteAdded($site));
        });

        return redirect()->route('sites.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        $site->load([
            'category',
            'language',
            'country',
            'seller',
            'types',
        ]);

        $categories = Category::orderBy('name')->get();
        $languages = Language::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $sellers = Seller::orderBy('name')->get();
        $teams = Team::orderBy('name')->get();
        $types = Type::orderBy('name')->get();

        $note = auth()->user()->notes()->where('site_id', $site->id)->first();

        $coins = config('coins');

        return Inertia::render('Sites/EditNew', [
            'site' => $site,
            'categories' => $categories,
            'languages' => $languages,
            'countries' => $countries,
            'sellers' => $sellers,
            'teams' => $teams,
            'types' => $types,
            'coins' => $coins,
            'note' => $note,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSiteRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSiteRequest $request, $id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        DB::transaction(function () use($site, $request) {
            $site->update(
                Arr::except($request->validated(), 'types')
            );

            $types = collect($request->validated()['types']);

            $types = $types->where('available', true);

            $types = $types->mapWithKeys(function ($type) {
                return [$type['id'] => [
                    'cost' => Helper::extractNumbersFromString($type['cost']),
                    'sale' => Helper::extractNumbersFromString($type['sale']),
                    'cost_coin' => $type['cost_coin'],
                    'sale_coin' => $type['sale_coin'],
                ]];
            });

            $site->types()->sync($types);

            $last = $site->audits()->latest()->first();

            if(!blank($last)) {
                Notification::send(Client::all(), new SiteUpdated($site, $last));
            }
        });

        return redirect()->route('sites.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        $site->forceDelete();

        return redirect()->route('sites.index');
    }

    public function toggle($id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        if($site->trashed()) {
            $site->restore();
            Notification::send(Client::all(), new SiteRestored($site));
        } else {
            $site->delete();
            Notification::send(Client::all(), new SiteDeleted($site));
        }
        
        return back();
    }

    public function approve(UpdateSiteRequest $request, $id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        DB::transaction(function () use($site, $request) {
            $site->update(array_merge($request->validated(), [
                'status' => 'APPROVED',
            ]));

            Notification::send(Client::all(), new SiteAdded($site));
        });

        return back();
    }

    public function reject(UpdateSiteRequest $request, $id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        DB::transaction(function () use($site, $request) {
            $site->update(array_merge($request->validated(), [
                'status' => 'REJECTED',
            ]));
        });

        return back();
    }

    public function export()
    {
        $urls = Site::select('url')->get();

        $urls = $urls->pluck('url')->toArray();

        $text = '';

        foreach ($urls as $url) {
            $text .= "$url\n";
        }

        Storage::put('urls.txt', $text);

        return Storage::download('urls.txt');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv',
        ]);

        $before = Site::count();

        $import = new SitesImport();

        // Site::disableAuditing();

        $import->import(request()->file('file'));

        // Site::enableAuditing();

        $importFailures = collect();

        foreach ($import->failures() as $failure) {
            $importFailures->push([
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ]);
        }

        if (! $import->errors()->empty()) {
            dd($import->errors());
        }

        $after = Site::count();

        $diff = $after - $before;

        // notify sites diff added

        $request->session()->flash('importFailures', $importFailures);
        $request->session()->flash('importDiff', $diff);

        return back();
    }
}
