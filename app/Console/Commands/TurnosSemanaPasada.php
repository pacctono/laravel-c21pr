<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TurnoController;

class TurnosSemanaPasada extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'correo:turnosIncSemPas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia correo a la Gerente (Sra Silvia Caraballo) con los turnos incumplidos de la semana pasada.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        TurnoController::emailTurnosSemanaPasada();
    }
}
