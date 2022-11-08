<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(10);
        $unreadNotifications = auth()->user()->unreadNotifications;

        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return Inertia::render('Client/Notifications', [
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications,
        ]);
    }
}
