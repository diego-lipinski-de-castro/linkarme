<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
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
            ->ofSeller(auth()->id())
            ->defaultSort('-created_at')
            ->allowedSorts([])
            ->allowedFilters([
            ])
            ->paginate(15)
            ->appends(request()->query());

        return view('seller.orders.index', [
            'orders' => $orders,
        ]);
    }
}
