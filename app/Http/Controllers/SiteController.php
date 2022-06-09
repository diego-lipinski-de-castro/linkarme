<?php

namespace App\Http\Controllers;

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
        $categories = Category::orderBy('name')->get();
        
        $sites = QueryBuilder::for(Site::class)
            ->when(request()->has('da'), function ($query) {

                $search = explode(' ', request()->query('da'));

                if(count($search) == 2) {
                    $query->where('da', $search[0], $search[1]);
                }

                if(count($search) == 5) {

                    if(in_array('&', $search)) {
                        $query->where('da', $search[0], $search[1])->where('da', $search[3], $search[4]);
                    } elseif(in_array('||', $search)) {
                        $query->where('da', $search[0], $search[1])->orWhere('da', $search[3], $search[4]);
                    }
                }
            })
            ->when(request()->has('dr'), function ($query) {

                $search = explode(' ', request()->query('dr'));

                if(count($search) == 2) {
                    $query->where('dr', $search[0], $search[1]);
                }

                if(count($search) == 5) {

                    if(in_array('&', $search)) {
                        $query->where('dr', $search[0], $search[1])->where('dr', $search[3], $search[4]);
                    } elseif(in_array('||', $search)) {
                        $query->where('dr', $search[0], $search[1])->orWhere('dr', $search[3], $search[4]);
                    }
                }
            })
            ->when(request()->has('traffic'), function ($query) {

                $search = explode(' ', request()->query('traffic'));

                if(count($search) == 2) {
                    $query->where('traffic', $search[0], $search[1]);
                }

                if(count($search) == 5) {

                    if(in_array('&', $search)) {
                        $query->where('traffic', $search[0], $search[1])->where('traffic', $search[3], $search[4]);
                    } elseif(in_array('||', $search)) {
                        $query->where('traffic', $search[0], $search[1])->orWhere('traffic', $search[3], $search[4]);
                    }
                }
            })
            ->when(request()->has('tf'), function ($query) {

                $search = explode(' ', request()->query('tf'));

                if(count($search) == 2) {
                    $query->where('tf', $search[0], $search[1]);
                }

                if(count($search) == 5) {

                    if(in_array('&', $search)) {
                        $query->where('tf', $search[0], $search[1])->where('tf', $search[3], $search[4]);
                    } elseif(in_array('||', $search)) {
                        $query->where('tf', $search[0], $search[1])->orWhere('tf', $search[3], $search[4]);
                    }
                }
            })
            ->withTrashed()
            ->with('category')
            ->defaultSort('url')
            ->allowedSorts(['url', 'da', 'dr', 'traffic', 'tf'])
            ->allowedFilters(['url', 'category_id', 'da'])
            ->paginate(15)
            ->appends(request()->query());

        return view('sites.index', [
            'sites' => $sites,
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
