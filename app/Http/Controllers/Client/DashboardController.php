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
        $coins = config('coins');

        $notifications = auth()->user()->notifications()->take(5)->get();

        $orders = Order::ofClient(auth()->id())->count();

        $usedCount = Site::query()
            ->whereHas('orders', function ($query) {
                $query->ofClient(auth()->id());
            })
            ->count();
            
        $unusedCount = Site::query()
            ->ofStatus('APPROVED')
            ->whereDoesntHave('orders', function ($query) {
                $query->ofClient(auth()->id());
            })
            ->count();
        
        $news = Site::query()
            ->ofStatus('APPROVED')
            ->where('inserted_at', '>', now()->subDays(60)->endOfDay())
            ->whereDoesntHave('orders', function ($query) {
                $query->ofClient(auth()->id());
            })
            ->orderByRaw('dr DESC, da DESC, traffic DESC')
            ->take(5)
            ->get();

        $recommended = Site::query()
            ->ofStatus('APPROVED')
            ->withCount('orders')
            ->whereDoesntHave('orders', function ($query) {
                $query->ofClient(auth()->id());
            })
            ->orderByRaw('orders_count DESC, dr DESC, da DESC, traffic DESC')
            ->take(5)
            ->get();

        $favorites = Site::query()
            ->authFavorites()
            ->join('favorites', 'favorites.site_id', '=', 'sites.id')   
            ->orderByRaw('favorites.created_at DESC')
            ->take(5)
            ->get()
            ->values();

        return Inertia::render('Client/Dashboard', [
            'coins' => $coins,

            'notifications' => $notifications,

            'orders' => $orders,

            'usedCount' => $usedCount,
            'unusedCount' => $unusedCount,
            
            'news' => $news,
            'recommended' => $recommended,

            'favorites' => $favorites,
        ]);
    }
}
