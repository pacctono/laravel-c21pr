<?php

namespace App\misClases;

//use App\Agenda;
use Carbon\Carbon;

class Fecha
{
    protected static $lunesAnt  = 'previous monday';
    protected static $proxLunes = 'next monday';
    protected static $priDiaMes = 'first day of this month';
    protected static $ultDiaMes = 'last day of this month';
    protected static $priDiaUltMes  = 'first day of last month';
    protected static $ultDiaUltMes  = 'last day of last month';
    protected static $priDiaProxMes = 'first day of next month';
    protected static $ultDiaProxMes = 'last day of next month';
    public static $ZONA = 'America/Caracas';

    public static function periodo($periodo, $fecha_min=null, $fecha_max=null)
    {
        $ZONA = self::$ZONA;
        switch ($periodo['periodo']) {
            case 'hoy':
                $fecha_desde = Carbon::today($ZONA);
                $fecha_hasta = Carbon::today($ZONA);
                break;
            case 'ayer':
                $fecha_desde = Carbon::yesterday($ZONA);
                $fecha_hasta = Carbon::yesterday($ZONA);
                break;
            case 'manana':
                $fecha_desde = Carbon::tomorrow($ZONA);
                $fecha_hasta = Carbon::tomorrow($ZONA);
                break;
// Aqui debo revisar, si hoy es lunes va a dar la semana pasada.
            case 'esta_semana':
                if (1 == now($ZONA)->dayOfWeek) {                        // Hoy es lunes?
                    $fecha_desde = (new Carbon())->timezone($ZONA); // Carbon::now($ZONA);
                    $fecha_hasta = (new Carbon())->timezone($ZONA)->addDays(6); // Domingo
                } else {
                    $fecha_desde = (new Carbon(self::$lunesAnt, $ZONA));
                    $fecha_hasta = (new Carbon(self::$lunesAnt, $ZONA))->addDays(6); // Domingo
                }
                break;
// Igual, aqui debo revisar, si hoy es lunes va a dar la semana antepasada.
            case 'semana_pasada':
                $fecha_desde = (new Carbon(self::$lunesAnt, $ZONA));
                $fecha_hasta = (new Carbon(self::$lunesAnt, $ZONA))->addDays(6);
                if (1 != now()->dayOfWeek) {
                    $fecha_desde = $fecha_desde->addWeeks(-1);
                    $fecha_hasta = $fecha_hasta->addWeeks(-1);
                }
                break;
            case 'proxima_semana':
                $fecha_desde = new Carbon(self::$proxLunes, $ZONA);
                $fecha_hasta = (new Carbon(self::$proxLunes, $ZONA))->addDays(6);
                break;
            case 'este_mes':
                $fecha_desde = Carbon::now($ZONA)->startOfMonth();
                $fecha_hasta = new Carbon(self::$ultDiaMes, $ZONA);
                break;
            case 'mes_pasado':
                $fecha_desde = new Carbon(self::$priDiaUltMes);
                $fecha_hasta = new Carbon(self::$ultDiaUltMes);
                break;
            case 'proximo_mes':
                $fecha_desde = new Carbon(self::$priDiaProxMes);
                $fecha_hasta = new Carbon(self::$ultDiaProxMes);
                break;
            case 'todo':
                $fecha_desde = $fecha_min;
                $fecha_hasta = $fecha_max;
                break;
            case 'intervalo':
                $fecha_desde = new Carbon($periodo['fecha_desde']);
                $fecha_hasta = new Carbon($periodo['fecha_hasta']);
                break;
        }
        return array ($fecha_desde->startOfDay(), $fecha_hasta->endOfDay());
    }
}
