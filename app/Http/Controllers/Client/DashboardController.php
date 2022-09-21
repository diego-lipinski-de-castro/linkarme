<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Site;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()->favorites_ids;

        $orders = Order::ofClient(auth()->id())->count();

        $used = Site::query()
            ->whereHas('orders', function ($query) {
                $query->ofClient(auth()->id());
            })
            ->take(5)
            ->get();

        $unused = Site::query()
            ->whereDoesntHave('orders', function ($query) {
                $query->ofClient(auth()->id());
            })
            ->take(5)
            ->get();
        
        $new = Site::query()
            ->where('created_at', '>', now()->subDays(60)->endOfDay())
            ->take(5)
            ->get();

        $recommended = Site::query()
            ->withCount('orders')
            ->having('orders_count', '>', 5)
            ->take(5)
            ->get();

        return Inertia::render('Client/Dashboard', [
            'orders' => $orders,
            'used' => $used,
            'unused' => $unused,
            'new' => $new,
            'recommended' => $recommended,
            'favorites' => $favorites,
        ]);
    }
}
