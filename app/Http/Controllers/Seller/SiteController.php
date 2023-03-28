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
use Illuminate\Support\Arr;
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

        $query = request()->query();

        $filters = [
            'sort' => Arr::get($query, 'sort', 'url'),
            'filter' => [
                'url' => Arr::get($query, 'filter.url', ''),

                'cost' => [
                    'from' => Arr::get($query, 'filter.cost.from', Site::ofSeller(auth()->id())->min('cost')),
                    'to' => Arr::get($query, 'filter.cost.to', Site::ofSeller(auth()->id())->max('cost')),
                ],

                'da' => [
                    'from' => Arr::get($query, 'filter.da.from', Site::ofSeller(auth()->id())->min('da')),
                    'to' => Arr::get($query, 'filter.da.to', Site::ofSeller(auth()->id())->max('da')),
                ],

                'dr' => [
                    'from' => Arr::get($query, 'filter.dr.from', Site::ofSeller(auth()->id())->min('dr')),
                    'to' => Arr::get($query, 'filter.dr.to', Site::ofSeller(auth()->id())->max('dr')),
                ],

                'gambling' => filter_var(Arr::get($query, 'filter.gambling', false), FILTER_VALIDATE_BOOL),
                'sponsor' => filter_var(Arr::get($query, 'filter.sponsor', false), FILTER_VALIDATE_BOOL),

                'new' => filter_var(Arr::get($query, 'filter.new', false), FILTER_VALIDATE_BOOL),

                'language_id' => Arr::get($query, 'filter.language_id', []),
                'country_id' => Arr::get($query, 'filter.country_id', []),
                'category_id' => Arr::get($query, 'filter.category_id', null),
            ],
        ];

        $sites = QueryBuilder::for(Site::class)
            ->ofSeller(auth()->id())
            ->with('category')
            ->defaultSort('url')
            ->allowedSorts([
                'url',
                'cost',
                'da',
                'dr',
                'inserted_at',
                'last_updated_at',
                // AllowedSort::custom('recommended', new RecommendedSort()),
                // AllowedSort::custom('new', new NewSort()),
            ])
            ->allowedFilters([
                AllowedFilter::custom('url', new UrlFilter),
                AllowedFilter::custom('cost', new FilterLimiter, null, ''),
                AllowedFilter::custom('da', new FilterLimiter, null, ''),
                AllowedFilter::custom('dr', new FilterLimiter, null, ''),
                // AllowedFilter::custom('traffic', new FilterLimiter),
                // AllowedFilter::custom('tf', new FilterLimiter),
                'gambling',
                'sponsor',
                // 'ssl',
                // 'cripto',
                // 'banner',
                // 'menu',
                AllowedFilter::custom('new', new NewFilter),
                AllowedFilter::exact('language_id'),
                AllowedFilter::exact('country_id'),
                AllowedFilter::exact('category_id'),
            ])
            ->paginate(50)
            ->appends(request()->query());

        return Inertia::render('Seller/Sites/IndexNew', [
            'sites' => $sites,
            'coins' => $coins,
            'filters' => $filters,
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
            'status' => 'PENDING',
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
