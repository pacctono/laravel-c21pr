<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MisClases\Fecha;

class Turno extends Model
{
    protected $fillable = [
        'turno', 'user_id', 'user_creo', 'user_actualizo', 'user_borro', 'borrado_at'
    ];
    protected $dates = [
        'turno', 'borrado_at', 'turno', 'updated_at'
    ];

    public function user()    // user_id
    {
        return $this->belongsTo(User::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userCreo()    // user_id
    {
        return $this->belongsTo(User::class, 'user_creo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userActualizo()    // user_id
    {
        return $this->belongsTo(User::class, 'user_actualizo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userBorro()    // user_id
    {
        return $this->belongsTo(User::class, 'user_borro'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function getTurnoFechaAttribute()
    {
        if (null == $this->turno) return '';    // No tiene sentido, pero.........
        return $this->turno->timezone('America/Caracas')->format('d/m/Y');
    }

    public function getTurnoEnAttribute()
    {
        if (null == $this->turno) return '';    // No tiene sentido, pero.........
        return ('08' == $this->turno->format('H'))?'Mañana':'Tarde';
    }

    public function getTurnoDiaSemanaAttribute()
    {
        if (null == $this->turno) return '';    // No tiene sentido, pero.........
        return substr(Fecha::$diaSemana[$this->turno->timezone('America/Caracas')
                        ->dayOfWeek], 0, 3);
    }

    public function getTurnoConHoraAttribute()
    {
        if (null == $this->turno) return '';    // No tiene sentido, pero.........
        return $this->turno->timezone('America/Caracas')->format('d/m/Y H:i a');
    }
}
