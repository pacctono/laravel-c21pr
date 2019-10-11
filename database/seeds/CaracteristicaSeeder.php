<?php

use App\Caracteristica;
use Illuminate\Database\Seeder;

class CaracteristicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Caracteristica::create([
            'descripcion' => 'Otra',
        ]);
        Caracteristica::create([
            'descripcion' => 'Obra gris',
        ]);
        Caracteristica::create([
            'descripcion' => 'Obra limpia',
        ]);
        Caracteristica::create([
            'descripcion' => 'Amoblado',
        ]);
        Caracteristica::create([
            'descripcion' => 'Equipado',
        ]);
        Caracteristica::create([
            'descripcion' => 'Amoblado y equipado',
        ]);
    }
}
