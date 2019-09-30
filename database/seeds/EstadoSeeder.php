<?php

use App\Estado;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estado::create([
            'descripcion' => 'Amazonas',
        ]);
        Estado::create([
            'descripcion' => 'Anzoátegui',
        ]);
        Estado::create([
            'descripcion' => 'Apure',
        ]);
        Estado::create([
            'descripcion' => 'Aragua',
        ]);
        Estado::create([
            'descripcion' => 'Barinas',
        ]);
        Estado::create([
            'descripcion' => 'Bolívar',
        ]);
        Estado::create([
            'descripcion' => 'Carabobo',
        ]);
        Estado::create([
            'descripcion' => 'Cojedes',
        ]);
        Estado::create([
            'descripcion' => 'Delta Amacuro',
        ]);
        Estado::create([
            'descripcion' => 'Distrito Capital',
        ]);
        Estado::create([
            'descripcion' => 'Falcón',
        ]);
        Estado::create([
            'descripcion' => 'Guárico',
        ]);
        Estado::create([
            'descripcion' => 'Lara',
        ]);
        Estado::create([
            'descripcion' => 'Mérida',
        ]);
        Estado::create([
            'descripcion' => 'Miranda',
        ]);
        Estado::create([
            'descripcion' => 'Monagas',
        ]);
        Estado::create([
            'descripcion' => 'Nueva Esparta',
        ]);
        Estado::create([
            'descripcion' => 'Portuguesa',
        ]);
        Estado::create([
            'descripcion' => 'Sucre',
        ]);
        Estado::create([
            'descripcion' => 'Táchira',
        ]);
        Estado::create([
            'descripcion' => 'Trujillo',
        ]);
        Estado::create([
            'descripcion' => 'Vargas',
        ]);
        Estado::create([
            'descripcion' => 'Yaracuy',
        ]);
        Estado::create([
            'descripcion' => 'Zulia',
        ]);
    }
}
