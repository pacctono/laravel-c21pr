<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Propiedad;

class VencerPropiedad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vencer:propiedad';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Cambia el estatus de la propiedad que tenga mas de n (90) dias en estatus 'activo'";

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
	    Propiedad::vencerPropiedad();
    }
}
