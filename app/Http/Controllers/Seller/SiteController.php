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
use App\Models\Team;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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

        $types = Type::query()
            ->orderBy('name')
            ->get();

        $query = request()->query();

        $ratios = [
            'BRL' => Arr::get($query, 'ratios.BRL', '1'),
            'EUR' => Arr::get($query, 'ratios.EUR', '1'),
            'USD' => Arr::get($query, 'ratios.USD', '1'),
            'GBP' => Arr::get($query, 'ratios.GBP', '1'),
        ];

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

                'types' => Arr::get($query, 'filter.types', null),
            ],
        ];

        $sites = QueryBuilder::for(Site::class)
            ->ofSeller(auth()->id())
            ->with([
                'category',
                'types',
            ])
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
                AllowedFilter::callback('cost', function ($query, $value) use ($ratios) {
                    $from = Helper::extractNumbersFromString($value['from']);
                    $to = Helper::extractNumbersFromString($value['to']);

                    $query->where(function ($query) use ($from, $to, $ratios) {

                        foreach($ratios as $coin => $ratio) {
                            $query->orWhere(function ($query) use ($from, $to, $coin, $ratio) {
                                $query->where('cost_coin', $coin)->where('cost', '>=', $from * (float) $ratio)->where('cost', '<=', $to * (float) $ratio);
                            });
                        }
                    });
                }),
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
                AllowedFilter::scope('types', 'of_types'),
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
            'types' => $types,
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
        $teams = Team::orderBy('name')->get();
        $types = Type::orderBy('name')->get();

        $coins = config('coins');

        return Inertia::render('Seller/Sites/CreateNew', [
            'categories' => $categories,
            'languages' => $languages,
            'countries' => $countries,
            'teams' => $teams,
            'types' => $types,
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

            $site = Site::create(array_merge($request->validated(), [
                'seller_id' => auth()->id(),
                'status' => 'PENDING',
            ]));

            $types = collect($request->validated()['types']);

            $types = $types->where('available', true);

            $types = $types->mapWithKeys(function ($type) {
                return [$type['id'] => [
                    'cost' => Helper::extractNumbersFromString($type['cost']),
                    'cost_coin' => $type['cost_coin'],
                    'sale_coin' => $type['cost_coin'],
                ]];
            });

            $site->types()->sync($types);
        });

        return redirect()->route('seller.sites.index');
    }

    public function edit($id)
    {
        $site = Site::withTrashed()->findOrFail($id);

        $site->load([
            'category',
            'language',
            'country',
            'types',
        ]);

        $countries = Country::orderBy('name')->get();
        $languages = Language::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $teams = Team::orderBy('name')->get();
        $types = Type::orderBy('name')->get();

        $note = auth()->user()->notes()->where('site_id', $site->id)->first();

        $coins = config('coins');

        return Inertia::render('Seller/Sites/EditNew', [
            'site' => $site,
            'countries' => $countries,
            'languages' => $languages,
            'categories' => $categories,
            'teams' => $teams,
            'types' => $types,
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

        DB::transaction(function () use($site, $request) {
            
            $site->update(
                Arr::except($request->validated(), 'types')
            );

            $types = collect($request->validated()['types']);

            $types = $types->where('available', true);

            $types = $types->mapWithKeys(function ($type) {
                return [$type['id'] => [
                    'cost' => Helper::extractNumbersFromString($type['cost']),
                    'cost_coin' => $type['cost_coin'],
                    'sale_coin' => $type['cost_coin'],
                ]];
            });

            $site->types()->sync($types);
        });

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
            'cost_coin' => 'required|in:BRL,EUR,USD,GBP',
            'types' => 'present|array|min:0',
        ]);

        $url = Str::contains($validated['url'], '://') ?
            str_replace('www.', '', parse_url($validated['url'], PHP_URL_HOST)) :
            str_replace('www.', '', parse_url($validated['url'], PHP_URL_PATH));

        $site = Site::withTrashed()->firstWhere('url', $url);

        if (blank($site)) {
            return back();
        }

        $offer = Offer::create([
            'seller_id' => auth()->id(),
            'site_id' => $site->id,
            
            'cost' => Helper::extractNumbersFromString($validated['cost']),
            'cost_coin' => $validated['cost_coin'],

            'sale' => 0,
            'sale_coin' => $validated['cost_coin'],
        ]);

        $types = collect($validated['types']);

        $types = $types->where('available', true);

        $types = $types->mapWithKeys(function ($type) {
            return [$type['id'] => [
                'cost' => Helper::extractNumbersFromString($type['cost']),
                'sale' => 0,
                'cost_coin' => $type['cost_coin'],
                'sale_coin' => $type['cost_coin'],
            ]];
        });

        $offer->types()->sync($types);

        return redirect()->route('seller.sites.index');
    }
}
