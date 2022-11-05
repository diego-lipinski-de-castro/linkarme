<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index()
    {
        // auth()->user()->unreadNotifications->markAsRead();

        // auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        $notifications = auth()->user()->notifications()->paginate(10);
        $unreadNotifications = auth()->user()->unreadNotifications;

        return Inertia::render('Client/Notifications', [
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications,
        ]);
    }
}
