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
use Carbon\Carbon;

$factory->define(Contacto::class, function (Faker $faker) {
    $fecha_contacto = $faker->dateTimeThisMonth('now');
    $fecha_carbon = new Carbon($fecha_contacto->format('Y-m-d H:m'));
    while (($fecha_carbon->timezone('America/Caracas')->format('H') < 8) or
            ($fecha_carbon->timezone('America/Caracas')->format('H') > 18) or
            ($fecha_carbon->timezone('America/Caracas')->format('w') == 0)) {
        $fecha_contacto = $faker->dateTimeThisMonth('now');
        $fecha_carbon = new Carbon($fecha_contacto->format('Y-m-d H:m'));
    }

    $fecha_evento = $faker->datetimeInInterval($fecha_contacto, '+ 10 days');
    $fecha_carbon = new Carbon($fecha_evento->format('Y-m-d H:m'));
    while (($fecha_carbon->format('H') < 8) or ($fecha_carbon->format('H') > 18)) {
        $fecha_evento = $faker->datetimeInInterval($fecha_contacto, '+ 10 days');
        $fecha_carbon = new Carbon($fecha_evento->format('Y-m-d H:m'));
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
        'fecha_evento' => $fecha_evento,
        'created_at' => $fecha_contacto,
    ];
});
