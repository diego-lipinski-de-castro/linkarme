<?php

namespace App\Http\Controllers\Seller;

use App\Filters\FilterLimiter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\UpdateSiteRequest;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use App\Models\Site;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $countries = Country::orderBy('name')->get();
        $languages = Language::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $sites = QueryBuilder::for(Site::class)
            ->ofSeller(auth()->id())
            ->with('category')
            ->defaultSort('url')
            ->allowedSorts([
                'url',
                'da',
                'dr',
                'tf',
            ])
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

        return view('seller.sites.index', [
            'sites' => $sites,
            'countries' => $countries,
            'languages' => $languages,
            'categories' => $categories,
        ]);
    }

    public function edit(Site $site)
    {
        $site->load([
            'category',
            'language',
            'country',
        ]);

        $countries = Country::orderBy('name')->get();
        $languages = Language::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $coins = config('coins');

        return view('seller.sites.edit', [
            'site' => $site,
            'countries' => $countries,
            'languages' => $languages,
            'categories' => $categories,
            'coins' => $coins,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(UpdateSiteRequest $request, Site $site)
    {
        $site->update($request->validated());

        return redirect(route('seller.sites.index'));
    }
}
