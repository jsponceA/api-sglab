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
        $schedule->command('app:cargar-empresas')
            ->timezone("America/Lima")
            ->hourly()
            ->between("9:00","18:00")
            ->withoutOverlapping(2);

        /*$schedule->command("app:test-command")
            ->timezone("America/Lima")
            ->everySecond();*/
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
