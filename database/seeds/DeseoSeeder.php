<?php

use App\Deseo;
use Illuminate\Database\Seeder;

class DeseoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Deseo::create([
            'descripcion' => 'Comprar',
        ]);

        Deseo::create([
            'descripcion' => 'Vender',
        ]);

        Deseo::create([
            'descripcion' => 'Alquilar',
        ]);

        Deseo::create([
            'descripcion' => 'Dar en alquiler',
        ]);

    }
}
