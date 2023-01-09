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

        $usedCount = Site::query()
            ->whereHas('orders', function ($query) {
                $query->ofClient(auth()->id());
            })
            ->count();
            
        $unusedCount = Site::query()
            ->whereDoesntHave('orders', function ($query) {
                $query->ofClient(auth()->id());
            })
            ->count();

        $favs = Site::query()
            ->authFavorites()
            ->join('favorites', 'favorites.site_id', '=', 'sites.id')   
            ->orderByRaw('favorites.created_at DESC')
            ->take(10)
            ->get()
            ->values();
        
        $new = Site::query()
            ->where('inserted_at', '>', now()->subDays(60)->endOfDay())
            ->orderByRaw('dr DESC, da DESC, traffic DESC')
            ->take(10)
            ->get();

        $recommended = Site::query()
            ->withCount('orders')
            ->whereDoesntHave('orders', function ($query) {
                $query->ofClient(auth()->id());
            })
            ->orderByRaw('orders_count DESC, dr DESC, da DESC, traffic DESC')
            ->take(10)
            ->get();

        return Inertia::render('Client/Dashboard', [
            'orders' => $orders,
            'usedCount' => $usedCount,
            'favs' => $favs,
            'unusedCount' => $unusedCount,
            'new' => $new,
            'recommended' => $recommended,
            'favorites' => $favorites,
        ]);
    }
}
