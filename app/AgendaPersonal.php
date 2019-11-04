<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\MisClases\Fecha;
use App\MisClases\General;

class AgendaPersonal extends Model
{
    protected $fillable = [
        'user_id', 'fecha_cita', 'hora_cita', 'descripcion', 'name',
        'telefono', 'email', 'direccion', 'fecha_evento', 'hora_evento',
        'comentarios', 'user_actualizo', 'user_borro',
    ];
    protected $dates = [
        'fecha_cita', 'fecha_evento', 'created_at', 'updated_at', 'deleted_at'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function user()    // user_id
    {
        return $this->belongsTo(User::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userActualizo()    // user_id
    {
        return $this->belongsTo(User::class, 'user_actualizo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userBorro()    // user_id
    {
        return $this->belongsTo(User::class, 'user_borro'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function getFechaCitaBdAttribute()
    {
        return General::fechaBd($this->fecha_cita);
    }

    public function getFechaEventoBdAttribute()
    {
        return General::fechaBd($this->fecha_evento);
    }

    public function getCitaEnAttribute()
    {
        return General::fechaEn($this->fecha_cita);
    }

    public function getEventoEnAttribute()
    {
        return General::fechaEn($this->fecha_evento);
    }

    public function getCitaDiaSemanaAttribute()
    {
        return General::fechaDiaSemana($this->fecha_cita);
    }

    public function getEventoDiaSemanaAttribute()
    {
        return General::fechaDiaSemana($this->fecha_evento);
    }

    public function getCitaHoraAttribute()
    {
        if (null == $this->hora_cita) return '';
        return $this->hora_cita->format('H:i');
    }

    public function getEventoHoraAttribute()
    {
        if (null == $this->hora_evento) return '';
        return $this->hora_evento->format('H:i');
    }

    public function getCitaConHoraAttribute()
    {
        if (null == $this->fecha_cita) return '';
        if (null == $this->hora_cita) return $this->getCitaEnAttribute();
        return (new Carbon($this->fecha_cita->format('Y-m-d') . $this->hora_cita))
                    ->format('d/m/Y H:i (h:i a)');
    }

    public function getEventoConHoraAttribute()
    {
        if (null == $this->fecha_evento) return '';
        if (null == $this->hora_evento) return $this->getEventoEnAttribute();
        return (new Carbon($this->fecha_evento->format('Y-m-d') . $this->hora_evento))
                    ->format('d/m/Y H:i (h:i a)');
    }

    public function getTelefonoFAttribute()
    {
        return General::telefonoF($this->telefono);
    }

}
