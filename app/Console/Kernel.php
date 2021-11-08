<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\FinalizeEvents;
use App\Console\Commands\MemberGeolocations;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(MemberGeolocations::class)->monthly();
        $schedule->command(FinalizeEvents::class)->at('12:00');
    }
}
