<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
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
        $orders = QueryBuilder::for(Order::class)
            ->ofClient(auth()->id())
            ->defaultSort('-created_at')
            ->allowedSorts([])
            ->allowedFilters([
            ])
            ->paginate(15)
            ->appends(request()->query());

        return Inertia::render('Client/Orders/Index', [
            'orders' => $orders,
        ]);
    }
}
