<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MisClases\Fecha;
use App\MisClases\General;

class VistaCliente extends Model
{
    protected $fillable = [
        'cedula', 'rif', 'name', 'tipo', 'telefono', 'user_id', 'email',
        'otro_telefono', 'fecha_evento', 'direccion', 'observaciones'
    ];
    protected $dates = [
        'fecha_evento'
    ];
    protected $appends = [
    ];

    public function user()    // user_id
    {
        return $this->belongsTo(User::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function getCedulaFAttribute()     // Cedula formateado.
    {
        return General::enteroEn($this->cedula);
    }

    public function getRifFAttribute()     // Cedula formateado.
    {
        return General::rifF($this->rif);
    }

    public function getTipoAlfaAttribute()
    {
        if ('I' == $this->tipo) return 'Contacto Inicial';
	elseif ('C' == $this->tipo) return 'Comprador';
	elseif ('V' == $this->tipo) return 'Vendedor';
	elseif ('A' == $this->tipo) return 'Comprador/Vendedor';
	elseif ('F' == $this->tipo) return 'Familiar';
	else return 'Otro';
    }

    public function getTelefonoFAttribute()
    {
        return General::telefonoF($this->telefono);
    }

    public function getFecEveAttribute()
    {
        return General::fechaEn($this->fecha_evento);
    }

    public function getFechaEventoBdAttribute()
    {
        return General::fechaBd($this->fecha_evento);
    }

    public function getCreadoAttribute()
    {
        return $this->created_at->timezone(Fecha::$ZONA)->format('d/m/Y');
    }

    public function getCreadoDiaSemanaAttribute()
    {
        return substr(Fecha::$diaSemana[$this->created_at->timezone(Fecha::$ZONA)
                        ->dayOfWeek], 0, 3);
    }

}
