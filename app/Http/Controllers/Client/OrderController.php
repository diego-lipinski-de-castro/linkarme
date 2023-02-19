<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
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
            ->orderBy('name')
            ->get();

        $query = request()->query();

        // $filters = [
        //     'sort' => Arr::get($query, 'sort', 'url'),
        //     'filter' => [
        //         'language_id' => Arr::get($query, 'filter.language_id', []),
        //     ],
        // ];

        $top = Site::query()
            ->whereHas('orders', function ($query) {
                $query->ofClient(auth()->id());
            })
            ->withCount('orders')
            ->orderByRaw('orders_count')
            ->take(5)
            ->get();

        $orders = QueryBuilder::for(Order::class)
            ->ofClient(auth()->id())
            ->defaultSort('-created_at')
            ->allowedSorts([])
            ->allowedFilters([

            ])
            ->paginate(15)
            ->appends(request()->query());

        return Inertia::render('Client/Orders/IndexNew', [
            'top' => $top,
            'coins' => $coins,
            'favorites' => $favorites,
            'interests' => $interests,
            'languages' => $languages,
            'orders' => $orders,
        ]);
    }
}
