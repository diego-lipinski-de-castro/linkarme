<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Language;
use App\Models\Order;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
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
        $interests = auth()->user()->interests_ids;

        $languages = Language::query()
            ->whereHas('sites', function ($query) {
                $query->whereHas('orders', function ($query) {
                    $query->ofClient(auth()->id());
                });
            })
            ->orderBy('name')
            ->get();

        $countries = Country::query()
            ->whereHas('sites', function ($query) {
                $query->whereHas('orders', function ($query) {
                    $query->ofClient(auth()->id());
                });
            })
            ->orderBy('name')
            ->get();

        $sites = Site::query()
            ->whereHas('orders', function ($query) {
                $query->ofClient(auth()->id());
            })
            ->withCount([
                'orders' => function ($query) {
                    $query->ofClient(auth()->id());
                },
            ])
            ->orderBy('orders_count', 'DESC')
            ->take(5)
            ->get();

        $query = request()->query();

        $filters = [
            'sort' => Arr::get($query, 'sort', 'url'),
            'filter' => [

            ],
        ];

        $orders = QueryBuilder::for(Order::class)
            ->ofClient(auth()->id())
            ->with([
                'site',
                'site.category',
            ])
            ->defaultSort('-created_at')
            ->allowedSorts([])
            ->allowedFilters([

            ])
            ->paginate(30)
            ->appends(request()->query());

        return Inertia::render('Client/Orders/IndexNew', [
            'coins' => $coins,
            'filters' => $filters,
            'favorites' => $favorites,
            'interests' => $interests,
            'languages' => $languages,
            'countries' => $countries,
            'sites' => $sites,
            'orders' => $orders,
        ]);
    }
}
