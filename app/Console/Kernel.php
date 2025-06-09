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
        Commands\UpdateMonthlyTasks::class,
        Commands\UpdateAbandonedItems::class, // <-- [TAMBAH] Daftarkan command harian yang baru
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Tugas bulanan untuk menghitung Top Seller
        $schedule->command('tasks:updatemonthly')->monthlyOn(1, '01:00'); 
        
        // <-- [TAMBAH] Tugas harian untuk mengubah status barang menjadi donasi
        // Akan berjalan setiap hari pada jam 2 pagi.
        $schedule->command('items:update-abandoned')->dailyAt('00:00');
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
