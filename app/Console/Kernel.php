<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('otp:clean-expired')->everyTenSeconds();
        $schedule->command('sms:timeout-reminder')->everyTenSeconds();
        $schedule->command('sms:checkout-reminder')->everyTenSeconds();
        $schedule->command('sms:overtime-reminder')->everyTenSeconds();
        $schedule->command('sms:process-scheduled-blasts')->everyTenSeconds();
        $schedule->command('sms:birthday-greetings')->dailyAt('09:00')->timezone('Asia/Manila');
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
