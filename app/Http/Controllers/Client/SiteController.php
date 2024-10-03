<?php

namespace App\Http\Controllers\Client;

use App\Filters\FilterLimiter;
use App\Filters\UrlFilter;
use App\Helper;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use App\Models\Project;
use App\Models\Site;
use App\Models\Type;
use App\Models\View;
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

        $favorites = auth('client')->user()->favorites_ids;
        $interests = auth('client')->user()->interests_ids;

        $projects = Project::query()
            ->ofClient(auth('client')->id())
            ->orderBy('name')
            ->get();

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

                'sale' => [
                    'from' => Arr::get($query, 'filter.sale.from', Site::ofStatus('APPROVED')->min('sale')),
                    'to' => Arr::get($query, 'filter.sale.to', Site::ofStatus('APPROVED')->max('sale')),
                ],

                'da' => [
                    'from' => Arr::get($query, 'filter.da.from', Site::ofStatus('APPROVED')->min('da')),
                    'to' => Arr::get($query, 'filter.da.to', Site::ofStatus('APPROVED')->max('da')),
                ],

                'dr' => [
                    'from' => Arr::get($query, 'filter.dr.from', Site::ofStatus('APPROVED')->min('dr')),
                    'to' => Arr::get($query, 'filter.dr.to', Site::ofStatus('APPROVED')->max('dr')),
                ],

                'gambling' => filter_var(Arr::get($query, 'filter.gambling', true), FILTER_VALIDATE_BOOL),
                'sponsor' => filter_var(Arr::get($query, 'filter.sponsor', false), FILTER_VALIDATE_BOOL),

                'favorites' => filter_var(Arr::get($query, 'filter.favorites', false), FILTER_VALIDATE_BOOL),
                'interests' => filter_var(Arr::get($query, 'filter.interests', false), FILTER_VALIDATE_BOOL),
                'recommended' => filter_var(Arr::get($query, 'filter.recommended', false), FILTER_VALIDATE_BOOL),
                'new' => filter_var(Arr::get($query, 'filter.new', false), FILTER_VALIDATE_BOOL),

                'language_id' => Arr::get($query, 'filter.language_id', []),
                'country_id' => Arr::get($query, 'filter.country_id', []),
                'category_id' => Arr::get($query, 'filter.category_id', null),

                'types' => Arr::get($query, 'filter.types', null),
            ],
        ];

        $sites = QueryBuilder::for(Site::class)
            ->ofStatus('APPROVED')
            ->withCount('orders')
            ->with([
                'category',
                'types',
            ])
            ->defaultSort('url')
            ->allowedSorts([
                'url',
                'sale',
                'da',
                'dr',
                'inserted_at',
                'last_updated_at',
                AllowedSort::custom('recommended', new RecommendedSort()),
                AllowedSort::custom('new', new NewSort()),
            ])
            ->allowedFilters([
                AllowedFilter::custom('url', new UrlFilter),
                AllowedFilter::callback('sale', function ($query, $value) use ($ratios) {
                    $from = Helper::extractNumbersFromString($value['from']);
                    $to = Helper::extractNumbersFromString($value['to']);

                    $query->where(function ($query) use ($from, $to, $ratios) {

                        foreach($ratios as $coin => $ratio) {
                            $query->orWhere(function ($query) use ($from, $to, $coin, $ratio) {
                                $query->where('sale_coin', $coin)->where('sale', '>=', $from * (float) $ratio)->where('sale', '<=', $to * (float) $ratio);
                            });
                        }
                    });
                }),
                AllowedFilter::custom('da', new FilterLimiter, null, ''),
                AllowedFilter::custom('dr', new FilterLimiter, null, ''),
                'gambling',
                'sponsor',
                AllowedFilter::scope('recommended', 'auth_recommended'),
                AllowedFilter::scope('favorites', 'auth_favorites'),
                AllowedFilter::scope('interests', 'auth_interests'),
                AllowedFilter::scope('used', 'auth_used'),
                AllowedFilter::scope('new', 'auth_new'),
                AllowedFilter::exact('language_id'),
                AllowedFilter::exact('country_id'),
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('types', 'of_types'),
            ])
            ->paginate(50)
            ->appends(request()->query());

        $cartItems = Cart::query()
            ->ofClient(auth('client')->id())
            ->with('items')
            ->first()
            ?->items->pluck('url');

        return Inertia::render('Client/Sites/IndexNew', [
            'sites' => $sites,
            'coins' => $coins,
            'filters' => $filters,
            'favorites' => $favorites,
            'interests' => $interests,
            'countries' => $countries,
            'languages' => $languages,
            'categories' => $categories,
            'types' => $types,
            'projects' => $projects,
            'list' => session('list'),
            'cartItems' => $cartItems ?? [],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $coins = config('coins');

        $favorites = auth()->user()->favorites_ids;
        $interests = auth()->user()->interests_ids;

        $query = request()->query();

        $filters = [
            'sort' => Arr::get($query, 'sort', 'url'),
            'filter' => array_filter([
                'favorites' => filter_var(Arr::get($query, 'filter.favorites', false), FILTER_VALIDATE_BOOL),
                'interests' => filter_var(Arr::get($query, 'filter.interests', false), FILTER_VALIDATE_BOOL),
                'project' => filter_var(Arr::get($query, 'filter.project', null), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE),
            ], fn($v, $k) => !blank($v), ARRAY_FILTER_USE_BOTH),
        ];

        $project = null;

        if(Arr::has($filters, 'filter.project')) {
            $project = Project::query()
                ->ofClient(auth()->id())
                ->where('id', Arr::get($filters, 'filter.project'))
                ->firstOrFail();
        }

        $sites = QueryBuilder::for(Site::class)
            ->ofStatus('APPROVED')
            // ->withCount('orders')
            ->with('category')
            ->defaultSort('url')
            ->allowedSorts([
                'url',
                'sale',
                'da',
                'dr',
                'inserted_at',
            ])
            ->allowedFilters([
                AllowedFilter::scope('favorites', 'auth_favorites'),
                AllowedFilter::scope('interests', 'auth_interests'),
                AllowedFilter::scope('project', 'auth_project'),
            ])
            ->paginate(50)
            ->appends(request()->query());

        return Inertia::render('Client/Sites/List', [
            'sites' => $sites,
            'coins' => $coins,
            'filters' => $filters,
            'favorites' => $favorites,
            'interests' => $interests,
            'project' => $project,
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
        View::create([
            'client_id' => auth()->id(),
            'site_id' => $site->id,
        ]);

        $site->load([
            'category',
            'language',
            'country',
            'types',
        ]);

        $coins = config('coins');

        $note = auth()->user()->notes()->where('site_id', $site->id)->first();

        return Inertia::render('Client/Sites/ShowNew', [
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

    public function interest(Site $site)
    {
        auth('client')->user()->interests()->toggle([$site->id]);

        return back();
    }

    public function project(Site $site, Project $project)
    {
        $project->sites()->toggle([$site->id]);

        return back();
    }

    public function go(Request $request)
    {
        $input = $request->validate([
            'selected' => ['required', 'array'],
            'selected.*' => ['required', 'string'],
        ]);

        $sites = [];

        foreach($input['selected'] as $site) {
            $sites[$site] = Site::query()
                ->where('url', $site)
                ->first();
        }

        $request->session()->flash('list', $sites);
        
        return back();
    }
}
