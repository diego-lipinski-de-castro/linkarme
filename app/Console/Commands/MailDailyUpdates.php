<?php

namespace App\Console\Commands;

use App\Mail\WeekUpdates;
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
            ->with('auditable')
            ->where('auditable_type', 'App\\Models\\Site')
            ->whereIn('event', ['created', 'updated'])
            ->whereDate('created_at', now()->subDay()->format('Y-m-d'))
            ->whereHas('auditable')
            ->get()
            ->filter(function ($item) {

                if($item->event == 'updated') {
                    return Arr::hasAny(
                        $item->getModified(), 
                        ['sale', 'gambling', 'cdb', 'cripto', 'sponsor', 'menu', 'banner', 'status']
                    );
                }

                return true;
            })
            ->transform(function ($item) {
                $item->modified = $item->getModified();

                return $item;
            });

        if(count($updates) == 0) {
            return 0;
        }

        if(count($ids) > 0) {
            foreach($ids as $user){
                Mail::to(User::find($user))->locale($locale)->send(new WeekUpdates($updates));
            }

            return 0;
        }

        $clients = Client::query()
            ->where('notify_updates_via_email', 'DAILY')
            ->get();

        foreach($clients as $client){
            Mail::to($client)->send(new WeekUpdates($updates));
        }

        return 0;
    }
}