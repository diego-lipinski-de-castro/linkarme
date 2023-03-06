<?php

namespace App\Http\Controllers\Seller;

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
            ->orderBy('favorites.created_at', 'desc')
            ->take(10)
            ->get()
            ->sortByDesc('dr')
            ->values();
        
        $new = Site::query()
            ->where('inserted_at', '>', now()->subDays(60)->endOfDay())
            ->orderBy('dr', 'desc')
            ->take(10)
            ->get();

        $recommended = Site::query()
            ->withCount('orders')
            ->having('orders_count', '>', 5)
            ->whereDoesntHave('orders', function ($query) {
                $query->ofClient(auth()->id());
            })
            ->orderBy('dr', 'desc')
            ->take(10)
            ->get();

        return Inertia::render('Seller/DashboardNew', [
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
