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
    return [
        'cedula' => $faker->randomNumber(8),
        'name' => $faker->name,
        'veces_name' => 1,
        'telefono' => $faker->unique()->isbn10,
        'veces_telefono' => 1,
        'user_id' => $faker->numberBetween(1, User::count()),
        'email' => $faker->unique()->safeEmail,
        'veces_email' => 1,
        'direccion' => $faker->address(),
        'deseo_id' => $faker->numberBetween(1, Deseo::count()),
        'tipo_id' => $faker->numberBetween(1, Tipo::count()),
        'zona_id' => $faker->numberBetween(1, Zona::count()),
        'precio_id' => $faker->numberBetween(1, Precio::count()),
        'origen_id' => $faker->numberBetween(1, Origen::count()),
        'resultado_id' => $faker->numberBetween(1, Resultado::count()),
        'observaciones' => $faker->sentence(7, false),
        'created_at' => $faker->dateTimeThisYear('now'),
    ];
});
