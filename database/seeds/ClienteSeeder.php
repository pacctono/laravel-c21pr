<?php

use App\Cliente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cliente::create([
            'cedula' => '11111111',
            'rif' => 'V111111111',
            'name' => 'Otro',
            'telefono' => '1111111111',
            'user_id' => 1,
            'email' => 'otro@correo.com',
            'fecha_nacimiento' => '2000-01-01'
        ]);
        factory(Cliente::class, 20)->create();
    }
}
