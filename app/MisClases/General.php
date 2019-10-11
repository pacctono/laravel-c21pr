<?php

namespace App\misClases;

use App\MisClases\Fecha;

class General {
    public const LINEASXPAGINA = 10;

    public static function enteroEn($numero) {
        if (null == $numero) return '';
        return number_format($numero, 0, ',', '.');
    }

    public static function flotanteEn($numero) {
        if (null == $numero) return '';
        return number_format($numero, 2, ',', '.');
    }

    public static function rifF($nroRif) {
        if (null == $nroRif) return '';
        $numero = substr($nroRif, 1);
        if (9 > strlen($numero)) $numero = str_pad($numero, 9, '0', STR_PAD_LEFT);
        return substr($nroRif, 0, 1) . '-' . substr($numero, 0, 8) . '-' . substr($numero, -1);
    }

    public static function telefonoF($nroTelefono) {
        if (null == $nroTelefono) return '';
        return '0' . substr($nroTelefono, 0, 3) . '-' .
                        substr($nroTelefono, 3, 3) . '-' . substr($nroTelefono, 6);
    }

    public static function fechaEn($fecha) {
        if (null == $fecha) return '';
        return $fecha->format('d/m/Y');
    }

    public static function fechaBd($fecha) {
        if (null == $fecha) return '';
        return $fecha->format('Y-m-d');
    }

    public static function fechaDiaSemana($fecha) {
        return substr(Fecha::$diaSemana[$fecha->timezone(Fecha::$ZONA)
                        ->dayOfWeek], 0, 3);
    }

    public static function fechaConHora($fecha) {
        return $fecha->timezone(Fecha::$ZONA)->format('d/m/Y h:i a');
    }

    public static function tiempoCreado($fecha) {
        return Carbon::parse($fecha)->timezone(Fecha::$ZONA)
                        ->diff(Carbon::now(Fecha::$ZONA))
                        ->format('%y años, %m meses y %d dias');
    }

}