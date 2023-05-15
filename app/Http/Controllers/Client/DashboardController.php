<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Project;
use App\Models\Site;
use Inertia\Inertia;
use OwenIt\Auditing\Models\Audit;

class DashboardController extends Controller
{
    public function index()
    {
        $coins = config('coins');

        $notifications = auth()->user()->notifications()->get();

        $notifications->transform(function ($item) {
            $item->diff = $item->created_at->diffForHumans();
            
            if(isset($item->data['audit_id'])) {
                $audit = Audit::find($item->data['audit_id']);

                // dd($audit->event == 'deleted');

                if(Site::withTrashed()->where('id', $item->data['site_id'])->exists()) {
                    $item->trashed = is_null(Site::find($item->data['site_id']));
                } else {
                    $item->trashed = false;
                }
            } else {
                $item->trashed = false;
            }

            return $item;
        });

        $notifications = $notifications->filter(function ($item) {
            return !$item->trashed;
        })->values()->all();

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
            ->get();

        $interests = Site::query()
            ->authInterests()
            ->join('interests', 'interests.site_id', '=', 'sites.id')   
            ->orderByRaw('interests.created_at DESC')
            ->take(5)
            ->get();

        $projects = Project::query()
            ->ofClient(auth()->id())
            ->withCount('sites')
            ->orderBy('sites_count', 'DESC')
            ->take(5)
            ->get();

        return Inertia::render('Client/DashboardNew', [
            'coins' => $coins,

            'notifications' => auth()->user()->full ? collect($notifications)->take(5) : [],

            'orders' => $orders,

            'usedCount' => $usedCount,
            'unusedCount' => $unusedCount,
            
            'news' => $news,
            'recommended' => $recommended,

            'favorites' => $favorites,
            'interests' => $interests,

            'projects' => $projects,
        ]);
    }
}
