<?php

namespace App\Console\Commands;

use App\Mail\DailyUpdates;
use App\Models\Client;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use OwenIt\Auditing\Models\Audit;

class MailDailyUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:daily {--id=*} {--locale=en}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily updates emails';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ids = $this->option('id');
        $locale = $this->option('locale');

        $updates = Audit::query()
            ->with([
                'auditable' => function ($query) {
                    // $query->withTrashed();
                },
            ])
            ->whereIn('auditable_type', ['App\\Models\\Site', 'App\\Models\\SiteType'])
            ->whereIn('event', ['updated', 'deleted', 'restored'])
            // ->whereDate('created_at', now()->subDay()->format('Y-m-d'))
            ->whereDate('created_at', '>', now()->subDay()->format('Y-m-d'))
            // ->whereHas('auditable')
            ->get()
            ->filter(function (Audit $item) {
                
                if($item->auditable_type == 'App\\Models\\SiteType') {

                    $item->auditable->load([
                        'site', 'type',
                    ]);

                    return true;
                }

                if($item->auditable_type == 'App\\Models\\Site') {
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
                }

                return true;
            })
            ->transform(function (Audit $item) {
                $modified = $item->getModified();
                
                if(isset($modified['cost'])) {
                    unset($modified['cost']);
                }

                if(isset($modified['cost_coin'])) {
                    unset($modified['cost_coin']);
                }

                $item->modified = $modified;

                return $item;
            });

        if(count($updates) == 0) {
            return 0;
        }

        if(count($ids) > 0) {
            foreach($ids as $user){
                Mail::to(User::find($user))->locale($locale)->send(new DailyUpdates($updates));
            }

            return 0;
        }

        $clients = Client::query()
            ->where('notify_updates_via_email', 'DAILY')
            ->get();

        foreach($clients as $client){
            Mail::to($client)->send(new DailyUpdates($updates));
        }

        return 0;
    }
}
