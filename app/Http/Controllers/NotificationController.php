<?php

namespace App\Http\Controllers;

use App\Mail\WeekUpdates;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use OwenIt\Auditing\Models\Audit;

class NotificationController extends Controller
{
    public function index()
    {
        // return back();

        $updates = Audit::query()
            ->with([
                'auditable' => function ($query) {
                    $query->withTrashed();
                },
            ])
            ->where('auditable_type', 'App\\Models\\Site')
            ->whereIn('event', ['updated', 'deleted', 'restored'])
            ->whereBetween('created_at', [
                now()->subYear()->format('Y-m-d'),
                now()->addDay()->format('Y-m-d'),
            ])
            ->whereHas('auditable')
            ->limit(10)
            ->get()
            ->filter(function ($item) {

                if($item->event == 'updated') {

                    if(
                        isset($item->getModified()['status']) &&
                        isset($item->getModified()['status']['new']) &&
                        $item->getModified()['status']['new'] == 'REJECTED'
                    ) {
                        return false;
                    }

                    return Arr::hasAny(
                        $item->getModified(), 
                        ['sale', 'gambling', 'cdb', 'cripto', 'sponsor', 'status']
                    );
                }

                return true;
            })
            ->transform(function ($item) {
                $item->modified = $item->getModified();

                return $item;
            });

        return new WeekUpdates($updates);
        
        return Inertia::render('Notifications');
    }

    public function store(Request $request)
    {
        return back();

        $validated = $request->validate([

        ]);

        // dd($validated);

        $clients = Client::query()
            ->get();

        foreach($clients as $client){
            // Mail::to($client)->send(new DailyUpdates($updates));
        }

        return back()->with('', '');
    }
}
