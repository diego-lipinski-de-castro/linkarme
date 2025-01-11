<?php

namespace App\Http\Controllers;

use App\Filters\DateBetweenFilter;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Imports\OrdersImport;
use App\Models\Client;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seller;
use App\Models\Site;
use App\Models\Type;
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
        $coins = config('coins');

        $statuses = Order::STATUSES;

        $clients = Client::query()
            ->orderBy('name')
            ->get();

        $sellers = Seller::query()
            ->orderBy('name')
            ->get();

        $query = request()->query();

        $filters = [
            'search' => $query['filter']['search'] ?? null,
            'client' => $query['filter']['client'] ?? null,
            'status' => $query['filter']['status'] ?? null,
            'created_at' => $query['filter']['created_at'] ?? [],
        ];

        $orders = QueryBuilder::for(Order::class)
            ->with([
                'client',
                'invoice',
            ])
            ->defaultSort('-created_at')
            // ->allowedSorts([])
            ->allowedFilters([
                AllowedFilter::scope('search', 'smart'),
                AllowedFilter::exact('client', 'client_id'),
                AllowedFilter::exact('status'),
                AllowedFilter::custom('created_at', new DateBetweenFilter),
            ])
            ->paginate(15)
            ->appends(request()->query());

        return Inertia::render('Orders/IndexNew', [
            'orders' => $orders,
            'filters' => $filters,
            'statuses' => $statuses,
            'clients' => $clients,
            'sellers' => $sellers,
            'coins' => $coins,
            'importFailures' => session('importFailures'),
            'importDiff' => session('importDiff'),
            'sites' => session('sites'),
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

        $clients = Client::query()
            ->orderBy('name')
            ->get();

        $types = Type::query()
            ->orderBy('name')
            ->get();

        return Inertia::render('Orders/CreateNew', [
            'coins' => $coins,
            'statuses' => $statuses,
            'clients' => $clients,
            'types' => $types,
            'sites' => session('sites'),
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
        $order = Order::create($request->validated());

        foreach($request->validated()['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'site_id' => optional($item)['site_id'],
                
                'seller_id' => optional($item)['seller_id'],

                'link' => optional($item)['link'],

                'cost' => optional($item)['cost'],
                'sale' => optional($item)['sale'],
                'comission' => optional($item)['comission'],

                'cost_coin' => optional($item)['cost_coin'],
                'sale_coin' => optional($item)['sale_coin'],
                'comission_coin' => optional($item)['comission_coin'],

                'received' => optional($item)['received'],
                'paid' => optional($item)['paid'],
                'comissioned' => optional($item)['comissioned'],

                'link_status' => optional($item)['link_status'],
            ]);
        }

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
            'items' => function ($query) {
                $query->with([
                    'seller',
                ]);
            },
            'client',
        ]);

        $coins = config('coins');

        $statuses = Order::STATUSES;

        $clients = Client::query()
            ->orderBy('name')
            ->get();

        $types = Type::query()
            ->orderBy('name')
            ->get();

        return Inertia::render('Orders/EditNew', [
            'order' => $order,
            'coins' => $coins,
            'statuses' => $statuses,
            'clients' => $clients,
            'types' => $types,
            'sites' => session('sites'),
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

        foreach($request->validated()['items'] as $item) {
            $data = [
                'site_id' => optional($item)['site_id'],
                    
                'seller_id' => optional($item)['seller_id'],

                'link' => optional($item)['link'],

                'cost' => optional($item)['cost'],
                'sale' => optional($item)['sale'],
                'comission' => optional($item)['comission'],

                'cost_coin' => optional($item)['cost_coin'],
                'sale_coin' => optional($item)['sale_coin'],
                'comission_coin' => optional($item)['comission_coin'],

                'received' => optional($item)['received'],
                'paid' => optional($item)['paid'],
                'comissioned' => optional($item)['comissioned'],

                'link_status' => optional($item)['link_status'],
            ];

            if(!isset($item['id'])) {
                OrderItem::create([
                    'order_id' => $order->id,
                    ...$data,
                ]);

                continue;
            }

            $item = OrderItem::find($item['id']);

            if($item) {
                $item->update($data);
            }
        }

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

    public function go(Request $request)
    {
        $input = $request->validate([
            'list' => ['required', 'string'],
            'type' => ['required', 'exists:types,id'],
        ]);

        $type = Type::findOrFail($input['type']);

        $list = explode(PHP_EOL, $input['list']);

        $sites = [];

        foreach($list as $item) {
            $sites[$item] = Site::query()
                ->with([
                    'seller',
                    'types',
                ])
                ->where('url', $item)
                ->first();

            // $offers = Offer::query()
            //         ->with([
            //             'site',
            //             'seller',
            //             'types',
            //         ])
            //         ->where('site_id', $sites[$item]->id)
            //         ->get();

            // dd($sites[$item], $offers);
        }

        $request->session()->flash('sites', $sites);
        
        return back();
    }
}
