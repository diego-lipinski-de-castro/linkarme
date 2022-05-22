<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Site;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = Order::STATUSES;

        $sites = Site::query()
            ->orderBy('url')
            ->get();

        $clients = Client::query()
            ->orderBy('name')
            ->get();

        $sellers = Seller::query()
            ->orderBy('name')
            ->get();

        $orders = QueryBuilder::for(Order::class)
            ->defaultSort('-created_at')
            // ->allowedSorts([])
            ->allowedFilters([
                AllowedFilter::exact('site_id'),
                AllowedFilter::exact('client_id'),
                AllowedFilter::exact('seller_id'),
                'url',
                AllowedFilter::exact('status'),
            ])
            ->paginate(15)
            ->appends(request()->query());

        return view('orders.index', [
            'orders' => $orders,
            'statuses' => $statuses,
            'sites' => $sites,
            'clients' => $clients,
            'sellers' => $sellers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = Order::STATUSES;

        $sites = Site::query()
            ->orderBy('url')
            ->get();

        $clients = Client::query()
            ->orderBy('name')
            ->get();

        $sellers = Seller::query()
            ->orderBy('name')
            ->get();

        return view('orders.create', [
            'statuses' => $statuses,
            'sites' => $sites,
            'clients' => $clients,
            'sellers' => $sellers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        Order::create($request->validated());

        return redirect(route('orders.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $order->load([
            'site',
            'client',
            'seller',
        ]);

        $statuses = Order::STATUSES;

        $sites = Site::query()
            ->orderBy('url')
            ->get();

        $clients = Client::query()
            ->orderBy('name')
            ->get();

        $sellers = Seller::query()
            ->orderBy('name')
            ->get();

        return view('orders.edit', [
            'order' => $order,
            'statuses' => $statuses,
            'sites' => $sites,
            'clients' => $clients,
            'sellers' => $sellers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        return redirect(route('orders.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        
        return back();
    }
}
