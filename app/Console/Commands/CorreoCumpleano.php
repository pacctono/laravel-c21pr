<?php

namespace App\Console\Commands;

use App\Http\Controllers\UserController;
use Illuminate\Console\Command;

class CorreoCumpleano extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'correo:cumpleano';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia correo de felicitaciones al asesor que cumpleano hoy';

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
        UserController::correoCumpleano();
    }
}
