<?php

namespace App\Console;

use App\Console\Commands\MailDailyUpdates;
use App\Console\Commands\MailWeekUpdates;
use App\Jobs\CheckOrdersUrl;
use App\Jobs\CheckSites;
use App\Jobs\ClearChecks;
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
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        // $schedule->job(new ClearChecks)->monthly();

        $schedule->job(new CheckOrdersUrl)->twiceDaily(1, 13);
        $schedule->job(new CheckSites)->twiceDaily(1, 13);

        $schedule->command(MailDailyUpdates::class)->dailyAt('6:00');
        $schedule->command(MailWeekUpdates::class)->weeklyOn(1, '6:00');

        $schedule->command(MailDailyUpdates::class, ['--id=1', '--id=3'])->dailyAt('6:00');
        $schedule->command(MailWeekUpdates::class, ['--id=1', '--id=3'])->weeklyOn(1, '6:00');
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
