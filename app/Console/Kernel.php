<?php

namespace App\Console;

use App\Console\Commands\MailWeekUpdates;
use App\Jobs\CheckOrdersUrl;
use App\Jobs\CheckSites;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        $schedule->job(new CheckOrdersUrl)->twiceDaily(1, 13);
        $schedule->job(new CheckSites)->twiceDaily(1, 13);

        // $schedule->command(MailWeekUpdates::class)->weeklyOn(1, '6:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
