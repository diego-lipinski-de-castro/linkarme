<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index()
    {
        $coins = config('coins');

        $notifications = auth()->user()->notifications()->get();

        $notifications->transform(function ($item) {
            $item->diff = $item->created_at->diffForHumans();

            return $item;
        });
        
        $unreadNotifications = auth()->user()->unreadNotifications;

        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return Inertia::render('Client/NotificationsNew', [
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications,
            'coins' => $coins,
        ]);
    }
}
