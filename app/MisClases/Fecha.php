<?php

namespace App\misClases;

use App\Agenda;
use Carbon\Carbon;

class Fecha
{
    public static function periodo($periodo)
    {
        switch ($periodo['periodo']) {
            case 'hoy':
                $fecha_desde = Carbon::today()->startOfDay();
                $fecha_hasta = Carbon::today()->endOfDay();
                break;
            case 'ayer':
                $fecha_desde = Carbon::yesterday()->startOfDay();
                $fecha_hasta = Carbon::yesterday()->endOfDay();
                break;
            case 'manana':
                $fecha_desde = Carbon::tomorrow()->startOfDay();
                $fecha_hasta = Carbon::tomorrow()->endOfDay();
                break;
// Aqui debo revisar, si hoy es lunes va a dar la semana pasada.
            case 'esta_semana':
                if (1 != now()->dayOfWeek) {
                    $fecha_desde = (new Carbon('previous monday'))->startOfDay();
                    $fecha_hasta = (new Carbon('previous monday'))->addDays(6)->endOfDay(); // Domingo
                } else {
                    $fecha_desde = (new Carbon())->startOfDay();
                    $fecha_hasta = (new Carbon())->addDays(6)->endOfDay(); // Domingo
                }
                break;
// Igual, aqui debo revisar, si hoy es lunes va a dar la semana antepasada.
            case 'semana_pasada':
                $fecha_desde = (new Carbon('previous monday'))->startOfDay();
                $fecha_hasta = (new Carbon('previous monday'))->addDays(6)->endOfDay();
                if (1 != now()->dayOfWeek) {
                    $fecha_desde = $fecha_desde->addWeeks(-1);
                    $fecha_hasta = $fecha_hasta->addWeeks(-1);
                }
                break;
            case 'proxima_semana':
                $fecha_desde = (new Carbon('next monday'))->startOfDay();
                $fecha_hasta = (new Carbon('next monday'))->addDays(6)->endOfDay();
                break;
            case 'este_mes':
                $fecha_desde = Carbon::now()->startOfMonth()->startOfDay();
                $fecha_hasta = (new Carbon('last day of this month'))->endOfDay();
                break;
            case 'mes_pasado':
                $fecha_desde = (new Carbon('first day of last month'))->startOfDay();
                $fecha_hasta = (new Carbon('last day of last month'))->endOfDay();
                break;
            case 'proximo_mes':
                $fecha_desde = (new Carbon('first day of next month'))->startOfDay();
                $fecha_hasta = (new Carbon('last day of next month'))->endOfDay();
                break;
            case 'todo':
                $fecha_desde = (new Carbon(Agenda::min('fecha_evento')))->startOfDay();;
                $fecha_hasta = (new Carbon(Agenda::max('fecha_evento')))->endOfDay();
                break;
            case 'intervalo':
                $fecha_desde = (new Carbon($periodo['fecha_desde']))->startOfDay();
                $fecha_hasta = (new Carbon($periodo['fecha_hasta']))->endOfDay();
                break;
        }
        return array ($fecha_desde, $fecha_hasta);
    }
}
