<?php

namespace App\Http\Controllers;

use App\Filters\FilterLimiter;
use App\Filters\NewFilter;
use App\Filters\SuggestionFilter;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Imports\SitesImport;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use App\Models\Offer;
use App\Models\Seller;
use App\Models\Site;
use Illuminate\Http\Request;
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

        $pending = Site::ofStatus('PENDING')->count();
        $offers = Offer::count();

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

        $sites = QueryBuilder::for(Site::class)
            ->withTrashed()
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
                'url',
                // AllowedFilter::exact('country_id'),
                // AllowedFilter::exact('language_id'),
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
            ])
            ->paginate(50)
            ->appends(request()->query());

        return Inertia::render('Sites/Index', [
            'coins' => $coins,
            'pending' => $pending,
            'offers' => $offers,
            'sites' => $sites,
            'countries' => $countries,
            'languages' => $languages,
            'categories' => $categories,
            'sellers' => $sellers,
            'importFailures' => session('importFailures'),
            'importDiff' => session('importDiff'),
        ]);
    }

    public function requests()
    {
        $pending = Site::ofStatus('PENDING')->count();
        $offers = Offer::count();

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

        $sites = QueryBuilder::for(Site::class)
            // ->ofStatus('PENDING')
            ->withTrashed()
            ->with('category')
            ->defaultSort('url')
            ->allowedSorts(['url', 'da', 'dr', 'tf'])
            ->allowedFilters([
                'url',
                AllowedFilter::exact('country_id'),
                AllowedFilter::exact('language_id'),
                AllowedFilter::custom('da', new FilterLimiter),
                AllowedFilter::custom('dr', new FilterLimiter),
                AllowedFilter::custom('traffic', new FilterLimiter),
                AllowedFilter::custom('tf', new FilterLimiter),
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('seller_id'),
                'ssl',
                'gambling',
                'sponsor',
                'cripto',
            ])
            ->paginate(50)
            ->appends(request()->query());

        return view('sites.index', [
            'pending' => $pending,
            'offers' => $offers,
            'sites' => $sites,
            'countries' => $countries,
            'languages' => $languages,
            'categories' => $categories,
            'sellers' => $sellers,
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
        Site::create($request->validated());

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

        $coins = config('coins');

        return view('sites.edit', [
            'site' => $site,
            'categories' => $categories,
            'languages' => $languages,
            'countries' => $countries,
            'sellers' => $sellers,
            'coins' => $coins,
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

        $site->update($request->validated());

        return redirect(route('sites.index'));
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

        return back();
    }

    public function toggle($id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        $site->trashed() ? $site->restore() : $site->delete();

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

        $request->session()->flash('importFailures', $importFailures);
        $request->session()->flash('importDiff', $diff);

        return back();
    }

    public function approve($id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        $site->update([
            'status' => 'APPROVED',
        ]);

        return back();
    }

    public function reject($id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        $site->update([
            'status' => 'REJECTED',
        ]);

        return back();
    }
}
