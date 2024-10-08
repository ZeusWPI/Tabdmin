<?php

namespace App\Console;

use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CheckPaymentsController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(new CheckPaymentsController())->everySixHours();
        $schedule->call(new BankAccountController())->dailyAt('08:30');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
