<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            [
                'label' => 'Pedidos em aberto',
                'value' => Order::status('WAITING')->count(),
            ],
            [
                'label' => 'Pedidos esse mês',
                'value' => Order::whereMonth('created_at', date('m'))->count(),
            ],
            [
                'label' => 'Pedidos no total',
                'value' => Order::count(),
            ],
        ];

        $data = json_decode(json_encode($data));

        return view('client.dashboard', [
            'data' => $data,
        ]);
    }
}
