<?php

use App\Origen;
use Illuminate\Database\Seeder;

class OrigenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Origen::create([
            'descripcion' => 'Periodico',
        ]);
    
        Origen::create([
            'descripcion' => 'Radio',
        ]);
    
        Origen::create([
            'descripcion' => 'Alquilar',
        ]);
    
        Origen::create([
            'descripcion' => 'Television',
        ]);
    
        Origen::create([
            'descripcion' => 'Llego oficina',
        ]);
    
        Origen::create([
            'descripcion' => 'Recomendado',
        ]);
    
        Origen::create([
            'descripcion' => 'Otro Century 21',
        ]);
    
        Origen::create([
            'descripcion' => 'Valla',
        ]);
    
        Origen::create([
            'descripcion' => 'Otro',
        ]);
    }
}
