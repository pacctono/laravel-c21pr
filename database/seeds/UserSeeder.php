<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Feriado',
            'telefono' => '4141234567',
            'email' => 'feriado@example.com',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'name' => 'Pablo Caraballo',
            'telefono' => '4148403147',
            'email' => 'pacctono@gmail.com',
            'password' => bcrypt('1234567'),
            'is_admin' => true
        ]);

        User::create([
            'name' => 'Alirio Mendoza Carrero',
            'telefono' => '4243002814',
            'email' => 'aliriomendozacarrero@gmail.com',
            'password' => bcrypt('1234567'),
            'is_admin' => true
        ]);

        User::create([
            'name' => 'David Hernandez',
            'telefono' => '4248363742',
            'email' => 'davidh.plc@gmail.com',
            'password' => bcrypt('1234567'),
            'is_admin' => true
        ]);

        User::create([
            'name' => 'Silvia Rosa Caraballo Correa',
            'telefono' => '4248495148',
            'email' => 'silviarosacaraballoc@gmail.com',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'name' => 'Migdamar Brito',
            'telefono' => '4248806081',
            'email' => 'migdamar.1988@gmail.com',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'name' => 'Yanet Correa',
            'telefono' => '4147950592',
            'email' => 'yanetcorrea@gmail.com',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'name' => 'Pablo Antonio Caraballo',
            'telefono' => '4147950421',
            'email' => 'pablo@udo.edu.ve',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'name' => 'Yanet Matilde Correa',
            'telefono' => '4143223438',
            'email' => 'yanet@c21pr.com',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'name' => 'Ermes Alirio Mendoza Carrero',
            'telefono' => '4243002814',
            'email' => 'ermes@c21pr.com',
            'password' => bcrypt('1234567'),
        ]);

    }
}
