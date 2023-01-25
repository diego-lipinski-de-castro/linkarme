<?php

namespace App\Http\Controllers\Seller;

use App\Filters\FilterLimiter;
use App\Filters\NewFilter;
use App\Filters\UrlFilter;
use App\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\StoreSiteRequest;
use App\Http\Requests\Seller\UpdateSiteRequest;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use App\Models\Offer;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
    public function index(Request $request)
    {
        $coins = config('coins');

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
                AllowedFilter::custom('url', new UrlFilter),
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
                'menu',
                AllowedFilter::custom('new', new NewFilter),
            ])
            ->paginate(50)
            ->appends(request()->query());

        return Inertia::render('Seller/Sites/Index', [
            'sites' => $sites,
            'coins' => $coins,
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
        $categories = Category::orderBy('name')->get();
        $languages = Language::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();

        $coins = config('coins');

        return Inertia::render('Seller/Sites/Create', [
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
        Site::create(array_merge($request->validated(), [
            'seller_id' => auth()->id(),
        ]));

        return redirect()->route('seller.sites.index');
    }

    public function edit($id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        $site->load([
            'category',
            'language',
            'country',
        ]);

        $countries = Country::orderBy('name')->get();
        $languages = Language::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $note = auth()->user()->notes()->where('site_id', $site->id)->first();

        $coins = config('coins');

        return Inertia::render('Seller/Sites/Edit', [
            'site' => $site,
            'countries' => $countries,
            'languages' => $languages,
            'categories' => $categories,
            'coins' => $coins,
            'note' => $note,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiteRequest $request, $id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        $site->update($request->validated());

        return redirect()->route('seller.sites.index');
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

    public function toggle(Request $request, $id)
    {
        $validated = $request->validate([
            'deleted_why' => 'nullable|string',
        ]);

        $site = Site::withTrashed()->findOrFail($id);

        if ($site->trashed()) {
            $site->restore();

            return back();
        }

        $site->update([
            'deleted_why' => $validated['deleted_why'],
        ]);

        $site->delete();

        return back();
    }

    public function offer(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required',
            'cost' => 'required',
            'cost_coin' => 'required|in:BRL,EUR,USD',
        ]);

        $url = Str::contains($validated['url'], '://') ?
            str_replace('www.', '', parse_url($validated['url'], PHP_URL_HOST)) :
            str_replace('www.', '', parse_url($validated['url'], PHP_URL_PATH));

        $site = Site::withTrashed()->firstWhere('url', $url);

        if (blank($site)) {
            return back();
        }

        Offer::create([
            'seller_id' => auth()->id(),
            'site_id' => $site->id,
            'cost' => Helper::extractNumbersFromString($validated['cost']),
            'cost_coin' => $validated['cost_coin'],
        ]);

        return redirect()->route('seller.sites.index');
    }
}
