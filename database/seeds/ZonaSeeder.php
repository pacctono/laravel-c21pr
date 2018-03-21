<?php

use App\Zona;
use Illuminate\Database\Seeder;

class ZonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Zona::create([
            'descripcion' => 'Nueva Barcelona',
        ]);
    
        Zona::create([
            'descripcion' => 'Nueva Barcelona',
        ]);
    
        Zona::create([
            'descripcion' => 'Lechería',
        ]);
    
        Zona::create([
            'descripcion' => 'Puerto La Cruz',
        ]);
    
        Zona::create([
            'descripcion' => 'Otra',
        ]);
    
    }
}
