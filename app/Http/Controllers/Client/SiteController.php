<?php

namespace App\Http\Controllers\Client;

use App\Filters\FilterLimiter;
use App\Filters\NewFilter;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use App\Models\Note;
use App\Models\Site;
use App\Sorts\OrderCountSort;
use Illuminate\Http\Request;
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

        $sites = QueryBuilder::for(Site::class)
            // ->ofStatus('APPROVED')
            ->withCount([
                'orders' => fn ($q) => $q->ofClient(auth()->id()),
            ])
            ->with('category')
            ->defaultSort('url')
            ->allowedSorts([
                'sale',
                'url',
                'da',
                'dr',
                'traffic',
                'inserted_at',
                // AllowedSort::custom('orders_count', new OrderCountSort()),
            ])
            ->allowedFilters([
                AllowedFilter::custom('sale', new FilterLimiter),
                'url',
                AllowedFilter::exact('country_id'),
                AllowedFilter::exact('language_id'),
                AllowedFilter::custom('da', new FilterLimiter),
                AllowedFilter::custom('dr', new FilterLimiter),
                // AllowedFilter::custom('traffic', new FilterLimiter),
                // AllowedFilter::custom('tf', new FilterLimiter),
                // AllowedFilter::exact('category_id'),
                'ssl',
                'gambling',
                'sponsor',
                'cripto',
                'banner',
                'menu',
                AllowedFilter::scope('favorites', 'auth_favorites'),
                AllowedFilter::custom('new', new NewFilter),
            ])
            ->paginate(50)
            ->appends(request()->query());

        return Inertia::render('Client/Sites/Index', [
            'sites' => $sites,
            'coins' => $coins,
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
