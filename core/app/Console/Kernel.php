<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Callback;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {

        if (!file_exists("storage")) {

        }

        $schedule->command('transactions:send-callbacks')->everyTwoMinutes()->appendOutputTo('./storage/logs/laravel_commands_output.log');
        $schedule->command('balances:pay-pending')->dailyAt('00:14')->appendOutputTo('./storage/logs/laravel_commands_output.log');
        $schedule->command('treeal:cashins')->everyThirtyMinutes()->appendOutputTo('./storage/logs/laravel_commands_treeal_cashin_pooling.log');
        $schedule->command('treeal:cashouts')->hourly()->appendOutputTo('./storage/logs/laravel_commands_treeal_cashout_pooling.log');
        
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
