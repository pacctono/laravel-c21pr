<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'contacto_id', 'fecha_cita', 'comentarios'
    ];
    protected $dates = [
        'fecha_cita'
    ];

    public function contacto()    // contacto_id
    {
        return $this->belongsTo(Contacto::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function getCitaEnAttribute()
    {
        return $this->fecha_cita->format('d/m/Y');
    }

    public function getCitaDiaSemanaAttribute()
    {
        return substr($this->diaSemana[$this->fecha_cita->dayOfWeek], 0, 3);
    }

    public function getCitaHoraAttribute()
    {
        return $this->fecha_cita->format('H:i');
    }

    public function getCitaConHoraAttribute()
    {
        return $this->fecha_cita->format('d/m/Y H:i (h:i a)');
    }
}
