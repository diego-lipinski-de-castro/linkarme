<?php

namespace App\Http\Controllers;

use App\Filters\FilterLimiter;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Imports\SitesImport;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use App\Models\Site;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
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
        
        $sites = QueryBuilder::for(Site::class)
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
                'ssl',
                'gambling',
                'sponsor',
                'cripto',
            ])
            ->paginate(50)
            ->appends(request()->query());

        return view('sites.index', [
            'sites' => $sites,
            'countries' => $countries,
            'languages' => $languages,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $languages = Language::all();
        $countries = Country::all();

        $coins = config('coins');

        return view('sites.create', [
            'categories' => $categories,
            'languages' => $languages,
            'countries' => $countries,
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

        return redirect(route('sites.index'));
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
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        $site->load([
            'category',
            'language',
            'country',
        ]);

        $categories = Category::all();
        $languages = Language::all();
        $countries = Country::all();

        $coins = config('coins');

        return view('sites.edit', [
            'site' => $site,
            'categories' => $categories,
            'languages' => $languages,
            'countries' => $countries,
            'coins' => $coins,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSiteRequest  $request
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSiteRequest $request, Site $site)
    {
        $site->update($request->validated());

        return redirect(route('sites.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        $site->forceDelete();

        return back();
    }

    public function toggle(Site $site)
    {
        $site->trashed() ? $site->restore() : $site->delete();

        return back();
    }

    public function import()
    {
        return view('sites.import');
    }

    public function importSubmit(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
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

        if(!$import->errors()->empty()) {
            dd($import->errors());
        }

        $after = Site::count();

        $diff = $after - $before;

        return back()
            ->with('failures', $importFailures)
            ->with('diff', $diff);
    }
}
