<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    protected $fillable = [
        'cedula', 'name', 'veces_name', 'telefono', 'veces_telefono',
        'user_id', 'email', 'veces_email', 'direccion', 'deseo_id',
        'propiedad_id', 'zona_id', 'precio_id', 'origen_id', 'resultado_id',
        'fecha_evento', 'observaciones', 'user_actualizo', 'user_borro',
        'borrado_en'
    ];
    protected $dates = [        // Mutan a una instancia de Carbon.
        'created_at',
        'updated_at',
        'borrado_en',
        'fecha_evento'
    ];
    protected $diaSemana = [
        'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'
    ];

    public function user()    // user_id
    {
        return $this->belongsTo(User::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function deseo()    // deseo_id
    {
        return $this->belongsTo(Deseo::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function propiedad()    // propiedad_id
    {
        return $this->belongsTo(Propiedad::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function zona()    // zona_id
    {
        return $this->belongsTo(Zona::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function precio()    // precio_id
    {
        return $this->belongsTo(Precio::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function origen()    // propiedad_id
    {
        return $this->belongsTo(Origen::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function resultado()    // resultado_id
    {
        return $this->belongsTo(Resultado::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userActualizo()    // user_id
    {
        return $this->belongsTo(User::class, 'user_actualizo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userBorro()    // user_id
    {
        return $this->belongsTo(User::class, 'user_borro'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function scopeOfFecha($query, $fechaDesde, $fechaHasta)
    {
        return $query->whereBetween('created_at', [$fechaDesde, $fechaHasta]);
    }
/*
    public function scopeOfDiaSemana($query, $indDia)
    {
        return $this->diaSemana[$indDia];
    }
 */
    public function scopeOfUsuario($query, $user)
    {
        return $query->where('user_id', $user);
    }

    public function scopeOfVeces($query, $nombre, $col)
    {
        return $query->where($col, $nombre)
            ->whereDate('created_at', '<', date('Y-m-d'))
            ->groupBy('veces_'.$col)
            ->count();
    }

    public static function contactosXFecha($fecha_desde, $fecha_hasta)
    {
        return self::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),
                        DB::raw('DATE_FORMAT(created_at, "%d/%m/%Y") as fecha'),
                        DB::raw('count(*) as atendidos'))
                    ->whereBetween('created_at', [$fecha_desde, $fecha_hasta])
                       ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),
                        DB::raw('DATE_FORMAT(created_at, "%d/%m/%Y")'));
    }

    public function fechaEn($fecha)
    {
        return $this[$fecha]->timezone('America/Caracas')->format('d/m/Y');
    }

    public function fechaDiaSemana($fecha)
    {
        return substr($this->diaSemana[$this[$fecha]->timezone('America/Caracas')
                        ->dayOfWeek], 0, 3);
    }

    public function fechaConHora($fecha)
    {
        return $this[$fecha]->timezone('America/Caracas')->format('d/m/Y h:i a');
    }

    public function getCreadoEnAttribute()
    {
        return $this->created_at->timezone('America/Caracas')->format('d/m/Y');
    }

    public function getCreadoDiaSemanaAttribute()
    {
        return substr($this->diaSemana[$this->created_at->timezone('America/Caracas')
                        ->dayOfWeek], 0, 3);
    }

    public function getCreadoConHoraAttribute()
    {
        return $this->created_at->timezone('America/Caracas')->format('d/m/Y h:i a');
    }

    public function getActualizadoEnAttribute()
    {
        return $this->updated_at->timezone('America/Caracas')->format('d/m/Y');
    }

    public function getActualizadoDiaSemanaAttribute()
    {
        return substr($this->diaSemana[$this->updated_at->timezone('America/Caracas')
                        ->dayOfWeek], 0, 3);
    }

    public function getActualizadoConHoraAttribute()
    {
        return $this->updated_at->timezone('America/Caracas')->format('d/m/Y h:i a');
    }

    public function getBorradoEnAttribute()
    {
        return $this->borrado_en->timezone('America/Caracas')->format('d/m/Y');
    }

    public function getBorradoDiaSemanaAttribute()
    {
        return substr($this->diaSemana[$this->borrado_en->timezone('America/Caracas')
                        ->dayOfWeek], 0, 3);
    }

    public function getBorradoConHoraAttribute()
    {
        return $this->borrado_en->timezone('America/Caracas')->format('d/m/Y h:i a');
    }

    public function getEventoEnAttribute()
    {
        return $this->fecha_evento->format('d/m/Y');
    }

    public function getEventoDiaSemanaAttribute()
    {
        return substr($this->diaSemana[$this->fecha_evento
                        ->dayOfWeek], 0, 3);
    }

    public function getEventoConHoraAttribute()
    {
        return $this->fecha_evento->format('d/m/Y H:i (h:i a)');
    }
}
