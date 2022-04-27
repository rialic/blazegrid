<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Proxy\Blaze\BlazeProxy;
use Illuminate\Support\Carbon;

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
        $schedule->call(function () {
            $blazeProxy = app('App\Proxy\Blaze\BlazeProxy');
            $now = now();
            $futherDate = now()->addMinutes(1);
            $interval = 60 / 10;

            var_dump('Entrou ');
             do{
                $blazeProxy->fetch();
                var_dump('SAVE DATABASE HERE <=> ' . $interval);

                if ($futherDate->lte(now())) {
                    $interval = 0;
                }

                time_sleep_until($now->addSeconds(10)->timestamp);
            }while ($interval-- > 1);
            var_dump('Saiu fora');
        })->everyMinute();

    }

    protected function getQueueCommand()
    {
        $params = implode(' ', ['--tries=3', '--sleep=3', '--queue=' . implode(',', $this->queues)]);

        return sprintf('queue:work %s', $params);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
