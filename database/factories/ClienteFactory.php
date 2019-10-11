<?php
use App\Cliente;
use App\User;
use App\Deseo;
use App\Tipo;
use App\Zona;
use App\Precio;
use App\Origen;
use App\Resultado;
use Faker\Generator as Faker;

$factory->define(Cliente::class, function (Faker $faker) {
    $cedula = $faker->randomNumber(8);
    $nacionalidad = array('V', 'E');

    $ddns = DB::table('venezueladdns')->distinct()->pluck('ddn')->all();
    $ddn = $faker->randomElement($ddns);
    $telefono = $ddn . $faker->unique()->randomNumber(7, true);

    return [
        'cedula' => $cedula,
        'rif' => $faker->randomElement($nacionalidad) . $cedula . $faker->randomNumber(1),
        'name' => $faker->name,
        'telefono' => $telefono,
        'user_id' => $faker->numberBetween(1, User::count()),
        'email' => $faker->unique()->safeEmail,
        'direccion' => $faker->address(),
        'observaciones' => $faker->sentence(7, false),
        'created_at' => $faker->dateTimeThisYear('now'),
    ];
});
