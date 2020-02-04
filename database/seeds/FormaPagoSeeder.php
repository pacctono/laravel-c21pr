<?php

use App\FormaPago;
use Illuminate\Database\Seeder;

class FormaPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FormaPago::create([
            'descripcion' => 'BanPa',
        ]);
        FormaPago::create([
            'descripcion' => 'BanVe',
        ]);
        FormaPago::create([
            'descripcion' => 'WF',
        ]);
        FormaPago::create([
            'descripcion' => 'Efectivo A&S',
        ]);
        FormaPago::create([
            'descripcion' => 'Efectivo M&D',
        ]);
    }
}
