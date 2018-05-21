<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bitacora extends Model
{
    protected $fillable = [
        'user_id', 'tx_modelo', 'tx_tipo', 'tx_data', 'tx_host',    // tipo=I:inserto,M:modifico,B:borro.
    ];
    protected $dates = [
        'created_at', 'updated_at'
    ];
    protected static $diaSemana = [
        'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'
    ];

    public function user()    // user_id
    {
        return $this->belongsTo(User::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function scopeOfUltLogin($query, $user)
    {
        $fecha = $query->where('user_id', $user)->where('tx_tipo', 'L')->max('created_at');
        if (is_string($fecha)) {
            return new Carbon($fecha);
        } else {
            return $fecha;
        }
    }

    public static function fechaUltLogin($user)
    {
        $fechaUltLogin = self::all()->where('user_id', $user)
                            ->where('tx_tipo', 'L')
                            ->max('created_at');
        if (null == $fechaUltLogin) return null;

        $fechaUltLogin = $fechaUltLogin->timezone('America/Caracas');
        return self::$diaSemana[$fechaUltLogin->dayOfWeek] . ' ' .
                $fechaUltLogin->format('d/m/Y H:i (h:i a)');
    }
}
