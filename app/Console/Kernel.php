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
        'App\Console\Commands\GrabarArchivo',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('grabar:archivo')
                    ->weekdays()->everyThirtyMinutes()
                    ->between('9:00', '23:00');
/*                    ->between('9:00', '23:00')
                    ->sendOutputTo('/home/pablo/salidas/cronLaravelGrabarArchivo.txt');*/
        $schedule->command('grabar:archivo')
                    ->saturdays()->everyThirtyMinutes()
                    ->between('9:00', '13:00');
/*                    ->between('9:00', '13:00')
                    ->sendOutputTo('/home/pablo/salidas/cronLaravelGrabarArchivo.txt');*/
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
