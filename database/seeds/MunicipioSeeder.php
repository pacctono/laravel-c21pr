<?php

use App\Municipio;
use Illuminate\Database\Seeder;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Municipio::create([
            'descripcion' => 'Otro',
        ]);
        Municipio::create([
            'descripcion' => 'Anaco',
        ]);
        Municipio::create([
            'descripcion' => 'Aragua',
        ]);
        Municipio::create([
            'descripcion' => 'Bolivar',
        ]);
        Municipio::create([
            'descripcion' => 'Bruzual',
        ]);
        Municipio::create([
            'descripcion' => 'Carvajal',
        ]);
        Municipio::create([
            'descripcion' => 'Cajigal',
        ]);
        Municipio::create([
            'descripcion' => 'Diego Bautista Urbaneja',
        ]);
        Municipio::create([
            'descripcion' => 'Freites',
        ]);
        Municipio::create([
            'descripcion' => 'Guanta',
        ]);
        Municipio::create([
            'descripcion' => 'Independencia',
        ]);
        Municipio::create([
            'descripcion' => 'Libertad',
        ]);
        Municipio::create([
            'descripcion' => 'McGregor',
        ]);
        Municipio::create([
            'descripcion' => 'Miranda',
        ]);
        Municipio::create([
            'descripcion' => 'Monagas',
        ]);
        Municipio::create([
            'descripcion' => 'Peñalver',
        ]);
        Municipio::create([
            'descripcion' => 'Píritu',
        ]);
        Municipio::create([
            'descripcion' => 'San Juan de Capistrano',
        ]);
        Municipio::create([
            'descripcion' => 'Santa Ana',
        ]);
        Municipio::create([
            'descripcion' => 'Simón Bolívar',
        ]);
        Municipio::create([
            'descripcion' => 'Simón Rodríguez',
        ]);
        Municipio::create([
            'descripcion' => 'Sotillo',
        ]);
    }
}
