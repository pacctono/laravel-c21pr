<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'user_id', 'contacto_id', 'fecha_evento', 'hora_evento', 'descripcion',
        'name', 'telefono', 'email', 'direccion'
    ];
    protected $dates = [
        'fecha_evento'
    ];
    protected $table = 'vista_agenda';
    protected $diaSemana = [
        'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'
    ];

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

    public function getEventoEnAttribute()
    {
        if (null == $this->fecha_evento) return '';
        return $this->fecha_evento->format('d/m/Y');
    }

    public function getEventoDiaSemanaAttribute()
    {
        if (null == $this->fecha_evento) return '';
        return substr($this->diaSemana[$this->fecha_evento->dayOfWeek], 0, 3);
    }

    public function getEventoConHoraAttribute()
    {
        if (null == $this->fecha_evento) return '';
        return $this->fecha_evento->format('d/m/Y H:i (h:i a)');
    }

    public function getEventoHoraAttribute()
    {
        if (null == $this->fecha_evento) return '';
        return $this->fecha_evento->format('H:i');
    }

    public function getTelefonoAttribute($value)
    {
        return substr($value, 0, 3) . '-' . substr($value, 3, 3) . '-' . substr($value, 6);
    }
}
