<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\MisClases\Fecha;

class Turno extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'turno', 'user_id', 'user_creo', 'user_actualizo', 'user_borro'
    ];
    protected $dates = [
        'turno', 'deleted_at', 'updated_at'
    ];
    protected $hidden = [
        'turno', 'deleted_at', 'updated_at'
    ];
    protected $appends = [
        'fecTur',
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
        return $this->turno->format('d/m/Y');
    }

    public function getFecTurAttribute()
    {
        if (null == $this->turno) return '';    // No tiene sentido, pero.........
        return ('08' == $this->turno->format('H'))?'Mañana':'Tarde';
    }

    public function getTurnoDiaSemanaAttribute()
    {
        if (null == $this->turno) return '';    // No tiene sentido, pero.........
        return substr(Fecha::$diaSemana[$this->turno->dayOfWeek], 0, 3);
    }

    public function getTurnoConHoraAttribute()
    {
        if (null == $this->turno) return '';    // No tiene sentido, pero.........
        return $this->turno->format('d/m/Y H:i a');
    }
}
