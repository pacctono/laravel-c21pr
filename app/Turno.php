<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\MisClases\Fecha;

class Turno extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'turno', 'user_id', 'llegada', 'user_creo', 'user_actualizo', 'user_borro'
    ];
    protected $dates = [
        'turno', 'deleted_at', 'updated_at', 'created_at'
    ];
    protected $hidden = [
        'turno', 'deleted_at', 'updated_at', 'created_at'
    ];
    protected $appends = [
        'fecha', 'turnoFecha', 'fecTur', 'tarde', 'creado'
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
        if (is_null($this->turno)) return '';    // No tiene sentido, pero.........
        return $this->turno->format('d/m/Y');
    }

    public function getTipoTurAttribute()
    {
        if (is_null($this->turno)) return '';    // No tiene sentido, pero.........
        return (('08' == $this->turno->format('H'))?'M':'T');
    }

    public function getFecTurAttribute()
    {
        if (is_null($this->turno)) return '';    // No tiene sentido, pero.........
        if (is_null($this->llegada))
            return ('08' == $this->turno->format('H'))?'Mañana':'Tarde';
        return $this->getTipoTurAttribute() . '-' . $this->llegada;
    }

    public function getTurnoDiaSemanaAttribute()
    {
        if (is_null($this->turno)) return '';    // No tiene sentido, pero.........
        return substr(Fecha::$diaSemana[$this->turno->dayOfWeek], 0, 3);
    }

    public function getFechaAttribute()
    {
        if (is_null($this->turno)) return '';
        return $this->getTurnoDiaSemanaAttribute() . ', ' .
                $this->getTurnoFechaAttribute() . ' ' .
                $this->getFecTurAttribute();
    }

    public function getTurnoConHoraAttribute()
    {
        if (is_null($this->turno)) return '';    // No tiene sentido, pero.........
        return $this->turno->format('d/m/Y H:i a');
    }

    public function getHoraTurnoAttribute()
    {
        if (is_null($this->turno)) return '';    // No tiene sentido, pero.........
        return $this->turno->format('H');
    }

    public function getTardeAttribute()
    {
        $ZONA = Fecha::$ZONA;
        $ahora = now($ZONA);
        if ($this->turno > $ahora) return '';
        else {
            if (is_null($this->llegada)) return 'C';
            else {
                if (('08' == $this->getHoraTurnoAttribute()) and ('09:00' < $this->llegada))
                    return 'm';
                if (('12' == $this->getHoraTurnoAttribute()) and ('13:00' < $this->llegada))
                    return 't';
                return '';
            }
        }
    }

    public function getObservacionAttribute()
    {
        switch($this->getTardeAttribute()) {
            case '':
                $mensaje = 'Puntual en su turno';
                break;
            case 'C':
                $mensaje = 'No se conecto';
                break;
            case 'm':
                $mensaje = 'Llego tarde a su turno de la mañana';
                break;
            case 't':
                $mensaje = 'Llego tarde a su turno de la tarde';
                break;
            default:
                $mensaje = 'Mensaje inesperado';
                break;
        }
        return $mensaje;
    }

    public function getCreadoAttribute()
    {
        if (is_null($this->create_at)) return '';
        return $this->created_at->timezone(Fecha::$ZONA)->format('d/m/Y');
    }
}
