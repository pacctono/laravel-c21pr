<?php

use App\Tipo;
use Illuminate\Database\Seeder;

class TipoSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {
       Tipo::create([
           'descripcion' => 'Casa',
       ]);
       Tipo::create([
           'descripcion' => 'Apartamento',
       ]);
       Tipo::create([
           'descripcion' => 'Local',
       ]);
       Tipo::create([
           'descripcion' => 'Edificio',
       ]);
       Tipo::create([
           'descripcion' => 'Terreno',
       ]);
       Tipo::create([
           'descripcion' => 'Otro',
       ]);
   }
}