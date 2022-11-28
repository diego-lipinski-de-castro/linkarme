<?php

namespace App\Console\Commands;

use App\Mail\WeekUpdates;
use App\Models\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use OwenIt\Auditing\Models\Audit;

class MailWeekUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send week updates emails';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $updates = Audit::query()
            ->with('auditable')
            ->where('auditable_type', 'App\\Models\\Site')
            ->whereIn('event', ['created', 'updated'])
            ->whereBetween('created_at', [
                now()->subWeek()->format('Y-m-d'),
                // now()->subDay()->format('Y-m-d'),
                now()->addDay()->format('Y-m-d'),
            ])
            ->whereHas('auditable')
            ->get()
            // ->filter(function ($item) {

            //     return Arr::hasAny(
            //         $item->getModified(), 
            //         ['sale', 'gambling', 'cdb', 'cripto', 'sponsor', 'menu', 'banner']
            //     );
            // })
            ->transform(function ($item) {
                $item->modified = $item->getModified();

                return $item;
            });

        Log::debug($updates);

        return 0;

        $clients = Client::query()
            ->get();

        foreach($clients as $client){
                Mail::to($client)->send(new WeekUpdates($updates));
        }

        return 0;
    }
}
