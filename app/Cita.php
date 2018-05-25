<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MisClases\Fecha;

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
        if (null == $this->fecha_cita) return '';
        return $this->fecha_cita->format('d/m/Y');
    }

    public function getCitaDiaSemanaAttribute()
    {
        if (null == $this->fecha_cita) return '';
        return substr(Fecha::$diaSemana[$this->fecha_cita->dayOfWeek], 0, 3);
    }

    public function getCitaHoraAttribute()
    {
        if (null == $this->fecha_cita) return '';
        return $this->fecha_cita->format('H:i');
    }

    public function getCitaConHoraAttribute()
    {
        if (null == $this->fecha_cita) return '';
        return $this->fecha_cita->format('d/m/Y H:i (h:i a)');
    }
}
