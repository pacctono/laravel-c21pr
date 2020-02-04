<?php

use App\Feriado;
use Illuminate\Database\Seeder;

class FeriadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Feriado::create([
            'fecha' => '2020-01-01',
            'descripcion' => 'Año nuevo',
            'tipo' => 'A',
        ]);
        Feriado::create([
            'fecha' => '2020-02-24',
            'descripcion' => 'Lunes de carnaval',
            'tipo' => 'A',
        ]);
        Feriado::create([
            'fecha' => '2020-02-25',
            'descripcion' => 'Martes de carnaval',
            'tipo' => 'A',
        ]);
        Feriado::create([
            'fecha' => '2020-04-09',
            'descripcion' => 'Jueves de Semana Santa',
            'tipo' => 'A',
        ]);
        Feriado::create([
            'fecha' => '2020-04-10',
            'descripcion' => 'Viernes de Semana Santa',
            'tipo' => 'A',
        ]);
        Feriado::create([
            'fecha' => '2020-12-24',
            'descripcion' => 'Noche buena',
            'tipo' => 'A',
        ]);
        Feriado::create([
            'fecha' => '2020-12-25',
            'descripcion' => 'Navidad',
            'tipo' => 'A',
        ]);
        Feriado::create([
            'fecha' => '2020-12-31',
            'descripcion' => 'Fin de año',
            'tipo' => 'A',
        ]);
    }
}
