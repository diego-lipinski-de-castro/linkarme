<?php

namespace App\Http\Controllers\Client;

use App\Filters\FilterLimiter;
use App\Filters\UrlFilter;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use App\Models\Site;
use App\Sorts\NewSort;
use App\Sorts\RecommendedSort;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
    public function index(Request $request)
    {
        $coins = config('coins');

        $favorites = auth()->user()->favorites_ids;

        $countries = Country::query()
            ->orderBy('name')
            ->get();

        $languages = Language::query()
            ->orderBy('name')
            ->get();

        $categories = Category::orderBy('name')->get();

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
                    'to' => Arr::get($query, 'filter.da.from', Site::ofStatus('APPROVED')->max('da')),
                ],

                'dr' => [
                    'from' => Arr::get($query, 'filter.dr.from', Site::ofStatus('APPROVED')->min('dr')),
                    'to' => Arr::get($query, 'filter.dr.from', Site::ofStatus('APPROVED')->max('dr')),
                ],

                'gambling' => filter_var(Arr::get($query, 'filter.gambling', false), FILTER_VALIDATE_BOOL),
                'sponsor' => filter_var(Arr::get($query, 'filter.sponsor', false), FILTER_VALIDATE_BOOL),

                'favorites' => filter_var(Arr::get($query, 'filter.favorites', false), FILTER_VALIDATE_BOOL),
                'recommended' => filter_var(Arr::get($query, 'filter.recommended', false), FILTER_VALIDATE_BOOL),
                'new' => filter_var(Arr::get($query, 'filter.new', false), FILTER_VALIDATE_BOOL),

                'language_id' => Arr::get($query, 'filter.language_id', []),
            ],
        ];

        $sites = QueryBuilder::for(Site::class)
            ->ofStatus('APPROVED')
            ->withCount('orders')
            ->with('category')
            ->defaultSort('url')
            ->allowedSorts([
                'url',
                'sale',
                'da',
                'dr',
                'inserted_at',
                AllowedSort::custom('recommended', new RecommendedSort()),
                AllowedSort::custom('new', new NewSort()),
            ])
            ->allowedFilters([
                AllowedFilter::custom('url', new UrlFilter),
                AllowedFilter::custom('sale', new FilterLimiter, null, ''),
                AllowedFilter::custom('da', new FilterLimiter, null, ''),
                AllowedFilter::custom('dr', new FilterLimiter, null, ''),
                // AllowedFilter::custom('traffic', new FilterLimiter),
                'gambling',
                'sponsor',
                AllowedFilter::scope('favorites', 'auth_favorites'),
                AllowedFilter::scope('recommended', 'auth_recommended'),
                AllowedFilter::scope('new', 'auth_new'),
                AllowedFilter::exact('language_id'),
            ])
            ->paginate(50)
            ->appends(request()->query());

        return Inertia::render('Client/Sites/IndexNew', [
            'sites' => $sites,
            'coins' => $coins,
            'filters' => $filters,
            'favorites' => $favorites,
            'countries' => $countries,
            'languages' => $languages,
            'categories' => $categories,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        $site->load([
            'category',
            'language',
            'country',
        ]);

        $coins = config('coins');

        $note = auth()->user()->notes()->where('site_id', $site->id)->first();

        return Inertia::render('Client/Sites/Show', [
            'site' => $site,
            'coins' => $coins,
            'note' => $note,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site)
    {
        auth()->user()->notes()->updateOrCreate([
            'site_id' => $site->id
        ], [
            'text' => $request->input('text')
        ]);

        return back();
    }

    public function favorite(Site $site)
    {
        auth()->user()->favorites()->toggle([$site->id]);

        return back();
    }
}
