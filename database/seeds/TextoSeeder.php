<?php

use App\Texto;
use Illuminate\Database\Seeder;

class TextoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Texto::create([
           'descripcion' => 'Texto de la primera imagen',
           'enlace' => '#',
           'textoEnlace' => 'Registrese hoy',
       ]);
       Texto::create([
            'descripcion' => 'Texto de la segunda imagen',
            'enlace' => '#',
            'textoEnlace' => 'Aprende'
       ]);
       Texto::create([
            'descripcion' => 'Texto de la tercera imagen',
            'enlace' => '#',
            'textoEnlace' => 'Explora aqui'
       ]);
       Texto::create([
            'descripcion' => 'Texto de la cuarta imagen',
            'enlace' => '#',
            'textoEnlace' => 'Explora'
       ]);
       Texto::create([
            'descripcion' => 'Texto de la quinta imagen',
            'enlace' => '#',
            'textoEnlace' => 'Ir'
       ]);
    }
}
