<?php

use App\Propiedad;
use Illuminate\Database\Seeder;

class PropiedadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Propiedad::create([
            'descripcion' => 'Casa',
        ]);
    
        Propiedad::create([
            'descripcion' => 'Apartamento',
        ]);
    
        Propiedad::create([
            'descripcion' => 'Local',
        ]);
    
        Propiedad::create([
            'descripcion' => 'Edificio',
        ]);
    
        Propiedad::create([
            'descripcion' => 'Terreno',
        ]);
    
        Propiedad::create([
            'descripcion' => 'Otro',
        ]);
    
    }
}
