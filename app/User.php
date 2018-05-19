<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cedula', 'name', 'telefono', 'email', 'email_c21', 'licencia_mls',
        'fecha_ingreso', 'fecha_nacimiento', 'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $dates = [
        'created_at', 'updated_at', 'fecha_nacimiento', 'fecha_ingreso'
    ];

    protected $casts = [
        'is_admin' => 'boolean'
    ];
    protected $diaSemana = [
        'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'
    ];

    public function contactos()    // user_id
    {
        return $this->hasMany(Contacto::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function turnos()    // user_id
    {
        return $this->hasMany(Turno::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function agendas()    // user_id
    {
        return $this->hasMany(Agenda::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function bitacoras()    // user_id
    {
        return $this->hasMany(Bitacora::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function scopeOfAdmin($query)
    {
        return $query->where('is_admin', 1);
    }

    public static function contactosXAsesor($fecha_desde, $fecha_hasta)
    {
        return self::where('id', '>', 1)
                    ->withCount(['contactos as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                    }]);
    }

    public static function conexionXAsesor($fecha_desde, $fecha_hasta)
    {
        return self::where('id', '>', 1)
                    ->withCount(['bitacoras as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {
                            $query->where('tx_tipo', 'L')
                            ->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                    }]);
    }

    public function getFechaNacimientoEnAttribute()
    {
        return $this->fecha_nacimiento->format('d/m/Y');
    }

    public function getFechaNacimientoBdAttribute()
    {
        return $this->fecha_nacimiento->format('Y-m-d');
    }

    public function getFechaIngresoEnAttribute()
    {
        return $this->fecha_ingreso->format('d/m/Y');
    }

    public function getFechaIngresoBdAttribute()
    {
        return $this->fecha_ingreso->format('Y-m-d');
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
        return $this->created_at->timezone('America/Caracas')->format('d/m/Y H:i a');
    }
}
