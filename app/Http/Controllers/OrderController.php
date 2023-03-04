<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Imports\OrdersImport;
use App\Models\Client;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Site;
use Illuminate\Http\Request;
use Inertia\Inertia;
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
            ->with([
                'site',
                'client',
                'seller',
            ])
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

        return Inertia::render('Orders/IndexNew', [
            'orders' => $orders,
            'statuses' => $statuses,
            'sites' => $sites,
            'clients' => $clients,
            'sellers' => $sellers,
            'importFailures' => session('importFailures'),
            'importDiff' => session('importDiff'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $coins = config('coins');

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

        return Inertia::render('Orders/Create', [
            'coins' => $coins,
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

        return redirect()->route('orders.index');
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

        $coins = config('coins');

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

        return Inertia::render('Orders/Edit', [
            'order' => $order,
            'coins' => $coins,
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

        return redirect()->route('orders.index');
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv',
        ]);

        $before = Order::count();

        $import = new OrdersImport();

        $import->import(request()->file('file'));

        $importFailures = collect();

        foreach ($import->failures() as $failure) {
            $importFailures->push([
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ]);
        }

        if (! $import->errors()->empty()) {
            dd($import->errors());
        }

        $after = Order::count();

        $diff = $after - $before;

        $request->session()->flash('importFailures', $importFailures);
        $request->session()->flash('importDiff', $diff);

        return back();
    }
}
