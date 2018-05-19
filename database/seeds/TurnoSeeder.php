<?php

use App\Turno;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TurnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Turno::create([
            'turno' => (new Carbon('next monday'))->addDays(0)->format('Y-m-d 08:00:00'),
            'user_id' => 1,
            'user_creo' => 2,
        ]);

        Turno::create([
            'turno' => (new Carbon('next monday'))->addDays(1)->format('Y-m-d 08:00:00'),
            'user_id' => 2,
            'user_creo' => 2,
        ]);

        Turno::create([
            'turno' => (new Carbon('next monday'))->addDays(2)->format('Y-m-d 08:00:00'),
            'user_id' => 3,
            'user_creo' => 2,
        ]);

        Turno::create([
            'turno' => (new Carbon('next monday'))->addDays(0)->format('Y-m-d 12:00:00'),
            'user_id' => 4,
            'user_creo' => 2,
        ]);

        Turno::create([
            'turno' => (new Carbon('next monday'))->addDays(1)->format('Y-m-d 12:00:00'),
            'user_id' => 5,
            'user_creo' => 2,
        ]);

        Turno::create([
            'turno' => (new Carbon('next monday'))->addDays(2)->format('Y-m-d 12:00:00'),
            'user_id' => 6,
            'user_creo' => 2,
        ]);

        Turno::create([
            'turno' => (new Carbon('next monday'))->addDays(3)->format('Y-m-d 08:00:00'),
            'user_id' => 7,
            'user_creo' => 2,
        ]);

        Turno::create([
            'turno' => (new Carbon('next monday'))->addDays(4)->format('Y-m-d 08:00:00'),
            'user_id' => 8,
            'user_creo' => 2,
        ]);

        Turno::create([
            'turno' => (new Carbon('next monday'))->addDays(5)->format('Y-m-d 08:00:00'),
            'user_id' => 9,
            'user_creo' => 2,
        ]);

        Turno::create([
            'turno' => (new Carbon('next monday'))->addDays(3)->format('Y-m-d 12:00:00'),
            'user_id' => 10,
            'user_creo' => 2,
        ]);

        Turno::create([
            'turno' => (new Carbon('next monday'))->addDays(4)->format('Y-m-d 12:00:00'),
            'user_id' => 2,
            'user_creo' => 2,
        ]);
    }
}
