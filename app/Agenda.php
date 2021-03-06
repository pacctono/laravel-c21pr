<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\MisClases\Fecha;

class Agenda extends Model
{
    protected $fillable = [
        'user_id', 'contacto_id', 'fecha_evento', 'hora_evento', 'descripcion',
        'tipo', 'name', 'telefono', 'email', 'direccion'
    ];
    protected $dates = [
        'fecha_evento'
    ];
    protected $hidden = [
//        'fecha_evento', 'hora_evento'
    ];
    protected $appends = [
        'fecEve', 'fecha'
    ];
    protected $table = 'vista_agenda';

    public function user()    // user_id
    {
        return $this->belongsTo(User::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function contacto()    // contacto_id
    {
        return $this->belongsTo(Contacto::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function scopeOfUsuario($query, $user)
    {
        return $query->where('user_id', $user);
    }

    public function scopeOfContacto($query, $contacto)
    {
        return $query->where('contacto_id', $contacto);
    }

    public function getFecEveAttribute()
    {
        if (null == $this->fecha_evento) return '';
        return $this->fecha_evento->format('d/m/Y');
    }

    public function getFechaAttribute()
    {
        if (null == $this->fecha_evento) return '';
        return $this->getEventoDiaSemanaAttribute() . ', ' .
                $this->getFecEveAttribute() .
                (is_null($this->hora_evento)?'':' '.$this->hora_evento);
    }

    public function getEventoDiaSemanaAttribute()
    {
        if (null == $this->fecha_evento) return '';
        return substr(Fecha::$diaSemana[$this->fecha_evento->dayOfWeek], 0, 3);
    }

    public function getEventoConHoraAttribute()
    {
        if (null == $this->fecha_evento) return '';
        return $this->fecha_evento->format('d/m/Y') . ' ' .
                $this->getEventoHoraAttribute();
    }

    public function getEventoHoraAttribute()
    {
        if (null == $this->fecha_evento) return '';
        if ('T' == $this->tipo) return $this->hora_evento;
        $fecha = new Carbon(now()->format('Y-m-d ') . $this->hora_evento);
        return $fecha->format('H:i (h:i a)');
    }

    public function getTelefonoFAttribute()
    {
        $value = $this->telefono;
        if (null == $value) return '';
        return '0' . substr($value, 0, 3) . '-' . substr($value, 3, 3) . '-' . substr($value, 6);
    }
}
