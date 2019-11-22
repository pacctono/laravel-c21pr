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
        'App\Console\Commands\CorreoCumpleano',
    ];

    /**
     * Get the timezone that should be used by default for scheduled events. version +5.8
     *
     * @return \DateTimeZone|string|null
     */
    protected function scheduleTimezone()
    {
        return 'America/Caracas';
    }

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
                    ->timezone('America/Caracas')   // Definido en la funcion anterior +5.8.
                    ->weekdays()->everyThirtyMinutes()
                    ->between('9:00', '21:00')
//                    ->sendOutputTo('/home/pablo/salidas/cronLaravelGrabarArchivo.txt')
                    ;
        $schedule->command('grabar:archivo')
                    ->timezone('America/Caracas')   // Definido en la funcion anterior +5.8.
                    ->saturdays()->everyThirtyMinutes()
                    ->between('9:00', '13:00');
//                    ->sendOutputTo('/home/pablo/salidas/cronLaravelGrabarArchivo.txt');

        $schedule->command('correo:cumpleano')
                    ->timezone('America/Caracas')   // Definido en la funcion anterior +5.8.
                    ->twiceDaily(6, 10)
//                    ->twiceDaily(21, 23)
//                    ->sendOutputTo('/home/pablo/salidas/cronLaravelCorreoCumpleano.txt')
                    ;
//        $schedule->command('correo:cumpleano')
//                    ->dailyAt('8:00')
//                    ;
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
