<?php

namespace App\Http\Controllers;

use App\Exports\SitesExport;
use App\Filters\FilterLimiter;
use App\Filters\FollowFilter;
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
use App\Services\GoogleSheetService;
use App\Sorts\NewSort;
use App\Sorts\RecommendedSort;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
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

        $ratios = [
            'BRL' => Arr::get($query, 'ratios.BRL', '1'),
            'EUR' => Arr::get($query, 'ratios.EUR', '1'),
            'USD' => Arr::get($query, 'ratios.USD', '1'),
            'GBP' => Arr::get($query, 'ratios.GBP', '1'),
        ];

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

                'sponsor' => filter_var(Arr::get($query, 'filter.sponsor', false), FILTER_VALIDATE_BOOL),
                'follow' => filter_var(Arr::get($query, 'filter.follow', false), FILTER_VALIDATE_BOOL),

                'new' => filter_var(Arr::get($query, 'filter.new', false), FILTER_VALIDATE_BOOL),

                'language_id' => Arr::get($query, 'filter.language_id', []),
                'country_id' => Arr::get($query, 'filter.country_id', []),
                'category_id' => Arr::get($query, 'filter.category_id', null),
                'seller_id' => Arr::get($query, 'filter.seller_id', null),

                'types' => Arr::get($query, 'filter.types', null),
            ],
        ];

        // dd($filters);

        $sites = QueryBuilder::for(Site::class)
            ->withTrashed()
            ->when(! isset($query['filter']['of_status']), function ($query) {
                $query->ofStatus('APPROVED');
            })
            ->with([
                'country',
                'language',
                'category',
                'types',
            ])
            ->withCount([
                'views',
                'orders',
                'favorites',
                'interests',
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
                AllowedFilter::callback('sale', function ($query, $value) use ($ratios) {
                    $from = Helper::extractNumbersFromString($value['from']);
                    $to = Helper::extractNumbersFromString($value['to']);

                    $query->where(function ($query) use ($from, $to, $ratios) {

                        foreach($ratios as $coin => $ratio) {
                            $query->orWhere(function ($query) use ($from, $to, $coin, $ratio) {
                                $query->where('sale_coin', $coin)->where('sale', '>=', $from * (float) $ratio)->where('sale', '<=', $to * (float) $ratio);
                            });
                        }
                    });
                }),
                AllowedFilter::custom('da', new FilterLimiter, null, ''),
                AllowedFilter::custom('dr', new FilterLimiter, null, ''),
                // 'sponsor',
                // AllowedFilter::custom('follow', new FollowFilter),
                // AllowedFilter::custom('new', new NewFilter),
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
            'orders' => fn($query) => $query->latest()->with('client'),
            'views' => fn($query) => $query->latest()->with('client'),
            'offers',
        ]);

        $site->loadCount([
            'offers'
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
        /** @var Site $site */
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
        /** @var \App\Models\Site $site */
        $site = Site::withTrashed()->findOrFail($id);

        $site->types()->detach();

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

    public function exportUrls()
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

    public function exportPending()
    {
        return Excel::download(new SitesExport, 'sites.csv');
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:csv',
            'notify_added' => 'required|boolean',
            'notify_updated' => 'required|boolean',
        ]);

        $before = Site::count();

        $import = new SitesImport(
            $validated['notify_added'],
            $validated['notify_updated'],
        );

        $start = now();

        $import->import(request()->file('file'));

        $stop = now();

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

        $request->session()->flash('importFailures', $importFailures);
        $request->session()->flash('importDiff', $diff);

        return back();
    }

    public function sync(Request $request)
    {
        $googleSheetService = new GoogleSheetService();

        $sheet = 'Lista';

        $sites = Site::query()
            ->limit(10)
            ->get();

        $list = [];
        
        foreach($sites as $site) {
            $list[] = [
                $site->url,
                $site->da,
                $site->dr
            ];
        };

        $googleSheetService->appendRows($sheet, $list);

        return back();
    }
}
