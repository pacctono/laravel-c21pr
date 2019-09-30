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
            'user_id' => 1,
        ]);
        factory(Cliente::class, 20)->create();
    }
}
