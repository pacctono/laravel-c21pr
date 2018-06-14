<?php

use App\Precio;
use Illuminate\Database\Seeder;

class PrecioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Precio::create([
            'descripcion' => 'No suministrado',
        ]);
    
        Precio::create([
            'descripcion' => 'Menor a 10.000',
        ]);
    
        Precio::create([
            'descripcion' => '10.000 a 20.000',
        ]);
    
        Precio::create([
            'descripcion' => '20.000 a 30.000',
        ]);
    
        Precio::create([
            'descripcion' => '30.000 a 40.000',
        ]);
    
        Precio::create([
            'descripcion' => '40.000 a 50.000',
        ]);
    
        Precio::create([
            'descripcion' => '50.000 a 60.000',
        ]);
    
        Precio::create([
            'descripcion' => '60.000 a 70.000',
        ]);
    
        Precio::create([
            'descripcion' => '70.000 a 80.000',
        ]);
    
        Precio::create([
            'descripcion' => '80.000 a 90.000',
        ]);
    
        Precio::create([
            'descripcion' => '90.000 a 100.000',
        ]);
    
        Precio::create([
            'descripcion' => '100.000 a 200.000',
        ]);
    
        Precio::create([
            'descripcion' => '200.000 a 300.000',
        ]);
    
        Precio::create([
            'descripcion' => '300.000 a 400.000',
        ]);
    
        Precio::create([
            'descripcion' => '400.000 a 500.000',
        ]);
    
        Precio::create([
            'descripcion' => '500.000 a 600.000',
        ]);
    
        Precio::create([
            'descripcion' => '600.000 a 700.000',
        ]);
    
        Precio::create([
            'descripcion' => '700.000 a 800.000',
        ]);
    
        Precio::create([
            'descripcion' => '800.000 a 900.000',
        ]);
    
        Precio::create([
            'descripcion' => '900.000 a 1.000.000',
        ]);
    
        Precio::create([
            'descripcion' => 'Mayor a 1.000.000',
        ]);
    
    }
}
