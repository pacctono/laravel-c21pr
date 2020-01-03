<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MisClases\Fecha;
use App\Http\Controllers\AgendaController;

class CitasManana extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citas:manana';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia por correo electronico las citas de mañana a cada asesor.';

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
	    $manana = Fecha::manana()->format('Y-m-d');
	    AgendaController::correoTodasCitas($manana, $manana, 'C');
    }
}
