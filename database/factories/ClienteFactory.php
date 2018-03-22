<?php

use App\User;
use App\Deseo;
use App\Propiedad;
use App\Zona;
use App\Precio;
use App\Origen;
use App\Resultado;
use Faker\Generator as Faker;

$factory->define(App\Cliente::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'telefono' => $faker->unique()->isbn10,
        'veces_telefono' => 1,
        'user_id' => $faker->numberBetween(1, User::count()),
        'email' => $faker->unique()->safeEmail,
        'veces_email' => 1,
        'direccion' => $faker->address(),
        'deseo_id' => $faker->numberBetween(1, Deseo::count()),
        'propiedad_id' => $faker->numberBetween(1, Propiedad::count()),
        'zona_id' => $faker->numberBetween(1, Zona::count()),
        'precio_id' => $faker->numberBetween(1, Precio::count()),
        'origen_id' => $faker->numberBetween(1, Origen::count()),
        'resultado_id' => $faker->numberBetween(1, Resultado::count()),
        'observaciones' => $faker->sentence(7, false),
        'created_at' => $faker->dateTimeThisMonth('now'),
    ];
});
