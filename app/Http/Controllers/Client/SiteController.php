<?php

namespace App\Http\Controllers\Client;

use App\Filters\FilterLimiter;
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
                'url',
                'da',
                'dr',
                'tf',
                AllowedSort::custom('orders_count', new OrderCountSort()),
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
                AllowedFilter::scope('favorites', 'auth_favorites'),
            ])
            ->paginate(10)
            ->appends(request()->query());

        return Inertia::render('Client/Sites/Index', [
            'sites' => $sites,
            'favorites' => $favorites,
            'countries' => $countries,
            'languages' => $languages,
            'categories' => $categories,
        ]);
    }

    public function favorite(Site $site)
    {
        auth()->user()->favorites()->toggle([$site->id]);

        return back();
    }

    /**
     * Display the specified resource.
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

        $note = auth()->user()->notes()->where('site_id', $site->id)->first();

        return Inertia::render('client.sites.edit', [
            'site' => $site,
            'note' => $note,
            'categories' => $categories,
            'languages' => $languages,
            'countries' => $countries,
            'coins' => $coins,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site)
    {
        Note::updateOrCreate(
            ['client_id' => auth()->id(), 'site_id' => $site->id],
            ['text' => $request->input('text')]
        );

        return back();
    }
}
