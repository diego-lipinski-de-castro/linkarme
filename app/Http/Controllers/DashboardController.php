<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Client;
use App\Models\Country;
use App\Models\Language;
use App\Models\Order;
use App\Models\Site;
use App\Models\Team;
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
                    'value' => Team::withCount('sites')->orderByRaw('sites_count DESC')->take(5)->get(),
                    'link' => route('sites.index'),
                    'columns' => ['Name', 'Sites',],
                    'attributes' => ['name', 'sites_count'],
                ],
                [
                    'label' => 'Vendedores com mais sites (mês)',
                    'value' => Team::withCount('sites')->where('created_at', '>=', today()->subMonths(1))->orderByRaw('sites_count DESC')->take(5)->get(),
                    'link' => route('sites.index'),
                    'columns' => ['Name', 'Sites',],
                    'attributes' => ['name', 'sites_count'],
                ],
                [
                    'label' => 'Vendedores com mais sites (trimestre)',
                    'value' => Team::withCount('sites')->where('created_at', '>=', today()->subMonths(3))->orderByRaw('sites_count DESC')->take(5)->get(),
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

        $news = Site::query()
            ->ofStatus('APPROVED')
            ->where('inserted_at', '>', now()->subDays(60)->endOfDay())
            ->whereDoesntHave('orders')
            ->orderByRaw('dr DESC, da DESC, traffic DESC')
            ->take(5)
            ->get();

        $recommended = Site::query()
            ->ofStatus('APPROVED')
            ->withCount('orders')
            ->orderByRaw('orders_count DESC, dr DESC, da DESC, traffic DESC')
            ->take(5)
            ->get();

        return Inertia::render('DashboardNew', [
            'data' => $data,
            'list' => $list,
            'news' => $news,
            'recommended' => $recommended,
        ]);
    }

    public function analytics()
    {
        $sitesByCountry = Country::query()
            ->withCount('sites')
            ->get()
            ->transform(function ($country) {
                return [
                    $country->name,
                    $country->sites_count,
                ];
            })
            ->toArray();

        $sitesByCountrySvg = Country::query()
            ->whereNotNull('code')
            ->withCount('sites')
            ->get()
            ->transform(function ($country) {
                return [
                    $country->code,
                    $country->sites_count,
                ];
            })
            ->toArray();

        array_unshift($sitesByCountry, [
            'Country', 'Sites',
        ]);

        return Inertia::render('Analytics', [
            'sitesByCountry' => $sitesByCountry,
            'sitesByCountrySvg' => $sitesByCountrySvg,
        ]);
    }
}
