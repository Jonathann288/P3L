<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\UpdateMonthlyTasks::class, // <-- TAMBAHKAN INI
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // Jalankan tugas bulanan setiap tanggal 1 jam 1 pagi
        $schedule->command('tasks:updatemonthly')->monthlyOn(1, '01:00'); // <-- TAMBAHKAN INI
    }

    // ...
}