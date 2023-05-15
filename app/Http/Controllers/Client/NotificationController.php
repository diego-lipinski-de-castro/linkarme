<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Inertia\Inertia;
use OwenIt\Auditing\Models\Audit;

class NotificationController extends Controller
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

        $unreadNotifications = auth()->user()->unreadNotifications;

        $unreadNotifications->transform(function ($item) {
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

        $unreadNotifications = $unreadNotifications->filter(function ($item) {
            return !$item->trashed;
        })->values()->all();

        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return Inertia::render('Client/NotificationsNew', [
            'notifications' => auth()->user()->full ? $notifications : [],
            'unreadNotifications' => auth()->user()->full ? $unreadNotifications : [],
            'coins' => $coins,
        ]);
    }
}
