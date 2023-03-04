<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Client;
use App\Models\Country;
use App\Models\Language;
use App\Models\Order;
use App\Models\Seller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            [
                'label' => 'Pedidos em aberto',
                'value' => Order::status('WAITING')->count(),
                'link' => route('orders.index'),
            ],
            [
                'label' => 'Pedidos esse mês',
                'value' => Order::whereMonth('created_at', date('m'))->count(),
                'link' => route('orders.index'),
            ],
            [
                'label' => 'Pedidos no total',
                'value' => Order::count(),
                'link' => route('orders.index'),
            ],
        ];

        $list = [
            [
                [
                    'label' => 'Países com mais sites',
                    'value' => Country::withCount('sites')->orderByRaw('sites_count DESC')->take(5)->get(),
                    'link' => route('sites.index'),
                    'columns' => ['Name', 'Sites'],
                    'attributes' => ['name', 'sites_count'],
                ],
                [
                    'label' => 'Linguagens com mais sites',
                    'value' => Language::withCount('sites')->orderByRaw('sites_count DESC')->take(5)->get(),
                    'link' => route('sites.index'),
                    'columns' => ['Name', 'Sites',],
                    'attributes' => ['name', 'sites_count'],
                ],
                [
                    'label' => 'Categorias com mais sites',
                    'value' => Category::withCount('sites')->orderByRaw('sites_count DESC')->take(5)->get(),
                    'link' => route('sites.index'),
                    'columns' => ['Name', 'Sites',],
                    'attributes' => ['title', 'sites_count'],
                ],
            ],
            [
                [
                    'label' => 'Vendedores com mais sites',
                    'value' => Seller::withCount('sites')->orderByRaw('sites_count DESC')->take(5)->get(),
                    'link' => route('sites.index'),
                    'columns' => ['Name', 'Sites',],
                    'attributes' => ['name', 'sites_count'],
                ],
                [
                    'label' => 'Vendedores com mais sites (mês)',
                    'value' => Seller::withCount('sites')->where('created_at', '>=', today()->subMonths(1))->orderByRaw('sites_count DESC')->take(5)->get(),
                    'link' => route('sites.index'),
                    'columns' => ['Name', 'Sites',],
                    'attributes' => ['name', 'sites_count'],
                ],
                [
                    'label' => 'Vendedores com mais sites (trimestre)',
                    'value' => Seller::withCount('sites')->where('created_at', '>=', today()->subMonths(3))->orderByRaw('sites_count DESC')->take(5)->get(),
                    'link' => route('sites.index'),
                    'columns' => ['Name', 'Sites',],
                    'attributes' => ['name', 'sites_count'],
                ],
            ],
            [
                [
                    'label' => 'Clientes com mais pedidos',
                    'value' => Client::withCount('orders')->orderByRaw('orders_count DESC')->take(5)->get(),
                    'link' => route('orders.index'),
                    'columns' => ['Name', 'Sites',],
                    'attributes' => ['name', 'orders_count'],
                ],
            ],
        ];

        $data = json_decode(json_encode($data));
        $list = json_decode(json_encode($list));

        return Inertia::render('DashboardNew', [
            'data' => $data,
            'list' => $list,
        ]);
    }
}
