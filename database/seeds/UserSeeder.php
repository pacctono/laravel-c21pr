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
            'name' => 'Pablo Caraballo',
            'email' => 'pacctono@gmail.com',
            'password' => bcrypt('1234567'),
            'is_admin' => true
        ]);

        User::create([
            'name' => 'Alirio Mendoza Carrero',
            'email' => 'aliriomendozacarrero@gmail.com',
            'password' => bcrypt('1234567'),
            'is_admin' => true
        ]);

        User::create([
            'name' => 'David Hernandez',
            'email' => 'davidh.plc@gmail.com',
            'password' => bcrypt('1234567'),
            'is_admin' => true
        ]);

        User::create([
            'name' => 'Silvia Rosa Caraballo Correa',
            'email' => 'silviarosacaraballoc@gmail.com',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'name' => 'Migdamar Brito',
            'email' => 'migdamar.1988@gmail.com',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'name' => 'Yanet Correa',
            'email' => 'yanetcorrea@gmail.com',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'name' => 'Pablo Antonio Caraballo',
            'email' => 'pablo@udo.edu.ve',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'name' => 'Yanet Matilde Correa',
            'email' => 'yanet@c21pr.com',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'name' => 'Ermes Alirio Mendoza Carrero',
            'email' => 'ermes@c21pr.com',
            'password' => bcrypt('1234567'),
        ]);

    }
}
