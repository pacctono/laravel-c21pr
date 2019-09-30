<?php

use App\Ciudad;
use Illuminate\Database\Seeder;

class CiudadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ciudad::create([
            'descripcion' => 'Otra',
        ]);
        Ciudad::create([
            'descripcion' => 'Anaco',
        ]);
        Ciudad::create([
            'descripcion' => 'Barcelona',
        ]);
        Ciudad::create([
            'descripcion' => 'Lechería',
        ]);
        Ciudad::create([
            'descripcion' => 'Piritu',
        ]);
        Ciudad::create([
            'descripcion' => 'Puerto La Cruz',
        ]);
    }
}
