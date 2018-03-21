<?php

use App\Resultado;
use Illuminate\Database\Seeder;

class ResultadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Resultado::create([
            'descripcion' => 'Preguntón',
        ]);
    
        Resultado::create([
            'descripcion' => 'Interesado',
        ]);
    
        Resultado::create([
            'descripcion' => 'El llama',
        ]);
    
        Resultado::create([
            'descripcion' => 'Llamarle',
        ]);
    
        Resultado::create([
            'descripcion' => 'Cita en casa',
        ]);
    
        Resultado::create([
            'descripcion' => 'Cita en propiedad',
        ]);
    
        Resultado::create([
            'descripcion' => 'Cita oficina',
        ]);
    
        Resultado::create([
            'descripcion' => 'Otro',
        ]);
    
    }
}
