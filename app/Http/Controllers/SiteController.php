<?php

namespace App\Http\Controllers;

use App\Filters\FilterLimiter;
use App\Filters\NewFilter;
use App\Filters\UrlFilter;
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
use App\Notifications\SiteAdded;
use App\Notifications\SiteDeleted;
use App\Notifications\SiteRestored;
use App\Notifications\SiteUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
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

        $filters = [
            'da' => [
                'from' => Site::min('da'),
                'to' => Site::max('da'),
            ],

            'dr' => [
                'from' => Site::min('dr'),
                'to' => Site::max('dr'),
            ],
        ];

        $filter = request()->query('filter');

        $sites = QueryBuilder::for(Site::class)
            ->withTrashed()
            ->when(! isset($filter['of_status']), function ($query) {
                $query->ofStatus('APPROVED');
            })
            ->with('category')
            ->defaultSort('url')
            ->allowedSorts([
                'sale',
                'url',
                'da',
                'dr',
                'traffic',
                'inserted_at',
            ])
            ->allowedFilters([
                AllowedFilter::custom('url', new UrlFilter),
                AllowedFilter::exact('seller_id'),
                AllowedFilter::exact('country_id'),
                AllowedFilter::exact('language_id'),
                AllowedFilter::custom('da', new FilterLimiter),
                AllowedFilter::custom('dr', new FilterLimiter),
                // AllowedFilter::custom('traffic', new FilterLimiter),
                // AllowedFilter::custom('tf', new FilterLimiter),
                // AllowedFilter::exact('category_id'),
                // AllowedFilter::exact('seller_id'),
                'ssl',
                'gambling',
                'sponsor',
                'cripto',
                'banner',
                'menu',
                AllowedFilter::custom('new', new NewFilter),
                // AllowedFilter::custom('suggestion', new SuggestionFilter),

                AllowedFilter::scope('of_status'),
            ])
            ->paginate(50)
            ->appends(request()->query());

        return Inertia::render('Sites/Index', [
            'sites' => $sites,
            'coins' => $coins,
            'filters' => $filters,
            'pendingCount' => $pendingCount,
            'offersCount' => $offersCount,
            'countries' => $countries,
            'languages' => $languages,
            'categories' => $categories,
            'sellers' => $sellers,
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

        $coins = config('coins');

        return Inertia::render('Sites/Create', [
            'categories' => $categories,
            'languages' => $languages,
            'countries' => $countries,
            'sellers' => $sellers,
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
            $site = Site::create($request->validated());
            // Notification::send(Client::all(), new SiteAdded($site));
        });

        return redirect()->route('sites.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
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
        ]);

        $categories = Category::orderBy('name')->get();
        $languages = Language::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $sellers = Seller::orderBy('name')->get();

        $note = auth()->user()->notes()->where('site_id', $site->id)->first();

        $coins = config('coins');

        return Inertia::render('Sites/Edit', [
            'site' => $site,
            'categories' => $categories,
            'languages' => $languages,
            'countries' => $countries,
            'sellers' => $sellers,
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
            $site->update($request->validated());

            $last = $site->audits()->latest()->first();

            Notification::send(Client::all(), new SiteUpdated($site, $last));
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

    public function approve($id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        DB::transaction(function () use($site) {
            $site->update([
                'status' => 'APPROVED',
            ]);
    
            Notification::send(Client::all(), new SiteAdded($site));
        });

        return back();
    }

    public function reject($id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        $site->update([
            'status' => 'REJECTED',
        ]);

        // notify?

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

        $import->import(request()->file('file'));

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
