<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

        $data = json_decode(json_encode($data));

        return Inertia::render('DashboardNew', [
            'data' => $data,
        ]);
    }
}
