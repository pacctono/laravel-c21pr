<?php

use App\Contacto;
use App\User;
use App\Deseo;
use App\Propiedad;
use App\Zona;
use App\Precio;
use App\Origen;
use App\Resultado;
use App\Venezueladdn;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

$factory->define(Contacto::class, function (Faker $faker) {
    $fecha_contacto = $faker->dateTimeThisMonth('now');
    while (($fecha_contacto->format('H') < 8) or ($fecha_contacto->format('H') > 18)) {
        $fecha_contacto = $faker->dateTimeThisMonth('now');
    }
    $ddns = DB::table('venezueladdns')->distinct()->pluck('ddn')->all();
    $ddn = $faker->randomElement($ddns);
    $telefono = $ddn . $faker->unique()->randomNumber(7, true);

    return [
        'cedula' => $faker->randomNumber(8),
        'name' => $faker->name,
        'veces_name' => 1,
        'telefono' => $telefono,
        'veces_telefono' => 1,
        'user_id' => $faker->numberBetween(2, User::count()),
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
        'fecha_evento' => $faker->datetimeInInterval($fecha_contacto, '+ 10 days'),
        'created_at' => $fecha_contacto,
    ];
});
