<?php

namespace App\Console\Commands;

use App\Propiedad;
use Illuminate\Console\Command;

class GrabarArchivo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grabar:archivo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Graba los archivos de datos, en formato texto, para ser manipulados por python u otro lenguaje.';

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
        Propiedad::grabarArchivo();
    }
}
