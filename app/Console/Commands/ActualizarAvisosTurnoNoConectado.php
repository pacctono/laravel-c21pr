<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TurnoController;

class ActualizarAvisosTurnoNoConectado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actualizar:turnoNoConectado';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Agregar avisos cuando el asesor no se conecta en su turno.';

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
        TurnoController::actualizarAviso();
    }
}
