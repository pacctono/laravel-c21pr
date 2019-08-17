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
            'telefono' => '4148403147',
            'email' => 'pacctono@gmail.com',
            'password' => bcrypt('1234567'),
            'is_admin' => true
        ]);

        User::create([
            'cedula' => '18229411',
            'name' => 'Alirio Mendoza',
            'telefono' => '4243002914',
            'email' => 'aliriomendozacarrero@gmail.com',
            'email_c21' => 'emendoza@century21.com.ve',
            'licencia_mls' => '66133',
            'fecha_ingreso' => '2015-06-01',
            'fecha_nacimiento' => '1986-11-24',
            'sexo' => 'M',
            'direccion' => 'Barcelona',
            'estado_civil' => 'C',
            'password' => bcrypt('1234567'),
            'is_admin' => true,
            'socio' => true
        ]);

        User::create([
            'cedula' => '18278808',
            'name' => 'David Hernandez',
            'telefono' => '4248363742',
            'email' => 'davidh.plc@gmail.com',
            'email_c21' => 'dhernandez@century21.com.ve',
            'licencia_mls' => '66780',
            'fecha_ingreso' => '2015-06-01',
            'fecha_nacimiento' => '1986-10-15',
            'sexo' => 'M',
            'direccion' => 'Puerto La Cruz',
            'estado_civil' => 'C',
            'password' => bcrypt('1234567'),
            'is_admin' => true,
            'socio' => true
        ]);

        User::create([
            'cedula' => '16701065',
            'name' => 'Silvia Caraballo',
            'telefono' => '4248495148',
            'email' => 'silviarosacaraballoc@gmail.com',
            'email_c21' => 'scaraballo01@century21.com.ve',
            'licencia_mls' => '66132',
            'fecha_ingreso' => '2015-06-01',
            'fecha_nacimiento' => '1983-04-11',
            'sexo' => 'F',
            'direccion' => 'Barcelona',
            'estado_civil' => 'C',
            'password' => bcrypt('1234567'),
            'is_admin' => true,
            'socio' => true
        ]);

        User::create([
            'cedula' => '18847488',
            'name' => 'Migdamar Brito',
            'telefono' => '4248806081',
            'email' => 'migdamar_1@hotmail.com',
            'email_c21' => 'mbrito01@century21.com.ve',
            'licencia_mls' => '66265',
            'fecha_ingreso' => '2015-06-01',
            'fecha_nacimiento' => '1988-05-07',
            'sexo' => 'F',
            'direccion' => 'Puerto La Cruz',
            'estado_civil' => 'C',
            'password' => bcrypt('1234567'),
            'is_admin' => true,
            'socio' => true
        ]);

        User::create([
            'cedula' => '12200513',
            'name' => 'Doris Carrero',
            'telefono' => '4141944237',
            'email' => 'dorocar67@gmail.com',
            'email_c21' => 'dcarrero01@century21.com.ve',
            'fecha_ingreso' => '2015-06-01',
            'fecha_nacimiento' => '1967-02-09',
            'sexo' => 'F',
            'direccion' => 'Puerto La Cruz',
            'estado_civil' => 'C',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'cedula' => '18117091',
            'name' => 'Lisbeth Andrade',
            'telefono' => '4147960864',
            'email' => 'lisbethandrade@gmail.com',
            'email_c21' => 'landrade@century21.com.ve',
            'fecha_ingreso' => '2015-06-02',
            'fecha_nacimiento' => '1985-09-22',
            'sexo' => 'F',
            'direccion' => 'Lechería',
            'estado_civil' => 'C',
            'password' => bcrypt('1234567'),
            'activo' => false,
        ]);

        User::create([
            'cedula' => '15367426',
            'name' => 'Eduardo Arias',
            'telefono' => '4165027461',
            'email' => 'c21eduardoarias@gmail.com',
            'email_c21' => 'earias@century21.com.ve',
            'licencia_mls' => '66927',
            'fecha_nacimiento' => '1982-11-25',
            'sexo' => 'M',
            'direccion' => 'Lechería',
            'estado_civil' => 'C',
            'password' => bcrypt('1234567'),
            'activo' => false,
        ]);

        User::create([
            'cedula' => '18777968',
            'name' => 'Yulybel Ainagas',
            'telefono' => '4140901641',
            'email' => 'c21yulybelainagas@gmail.com',
            'email_c21' => 'yainagas@century21.com.ve',
            'licencia_mls' => '67663',
            'fecha_ingreso' => '2016-10-17',
            'sexo' => 'F',
            'direccion' => 'Lecheria',
            'estado_civil' => 'C',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'cedula' => '19630174',
            'name' => 'Gexy Ceballos',
            'telefono' => '4148297760',
            'email' => 'gceballoscentury21@gmail.com',
            'licencia_mls' => '68008',
            'fecha_ingreso' => '2016-10-17',
            'fecha_nacimiento' => '1990-04-28',
            'sexo' => 'F',
            'direccion' => 'Lecheria',
            'estado_civil' => 'S',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'cedula' => '12607515',
            'name' => 'Yoselin Cordova',
            'telefono' => '4141940259',
            'email' => 'c21yoselincordova@gmail.com',
            'licencia_mls' => '69020',
            'fecha_ingreso' => '2019-05-18',
            'fecha_nacimiento' => '1976-04-22',
            'sexo' => 'F',
            'direccion' => 'Puerto La Cruz',
            'estado_civil' => 'S',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'cedula' => '12915118',
            'name' => 'Victor Villarroel',
            'telefono' => '4166815315',
            'email' => 'c21victorvillarroel@gmail.com',
            'fecha_ingreso' => '2018-11-15',
            'fecha_nacimiento' => '1976-06-01',
            'sexo' => 'M',
            'direccion' => 'Puerto La Cruz',
            'estado_civil' => 'S',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'cedula' => '24708720',
            'name' => 'Jhon Rios',
            'telefono' => '4129460770',
            'email' => 'century21jrios@gmail.com',
            'fecha_ingreso' => '2019-02-01',
            'fecha_nacimiento' => '1994-11-16',
            'sexo' => 'M',
            'direccion' => 'Puerto La Cruz',
            'estado_civil' => 'S',
            'password' => bcrypt('1234567'),
        ]);

        User::create([
            'cedula' => '20632471',
            'name' => 'Hector Rodriguez',
            'telefono' => '4127968869',
            'email' => 'c21hectorrodriguez@gmail.com',
            'email_c21' => 'hrodriguez02@century21.com.ve',
            'licencia_mls' => '69542',
            'fecha_ingreso' => '2019-03-15',
            'fecha_nacimiento' => '1991-06-27',
            'sexo' => 'M',
            'direccion' => 'Barcelona',
            'estado_civil' => 'S',
            'password' => bcrypt('1234567'),
        ]);
    }
}
