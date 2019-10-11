<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\MisClases\Fecha;
use App\MisClases\General;

class Cliente extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'cedula', 'rif', 'name', 'telefono',
        'user_id', 'email', 'fecha_nacimiento', 'direccion', 'observaciones',
        'user_actualizo', 'user_borro'
    ];
    protected $dates = [
        'fecha_nacimiento', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function user()    // user_id
    {
        return $this->belongsTo(User::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function propiedades()    // cliente_id
    {
        return $this->hasMany(Propiedad::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function getCedulaFAttribute()     // Cedula formateado.
    {
        return General::enteroEn($this->cedula);
    }

    public function getRifFAttribute()     // Cedula formateado.
    {
        return General::rifF($this->rif);
    }

    public function getTelefonoFAttribute()
    {
        return General::telefonoF($this->telefono);
    }

    public function getFechaNacimientoEnAttribute()
    {
        return General::fechaEn($this->fecha_nacimiento);
    }

    public function getFechaNacimientoBdAttribute()
    {
        return General::fechaBd($this->fecha_nacimiento);
    }

    public static function propiedadesBorradas($id)
    {
        return self::find($id)->propiedades->where('user_borro', '!=', null);
    }

    public static function propiedadesXCliente($fecha_desde, $fecha_hasta)
    {
        return self::withCount(['propiedades as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                    }]);
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

    public function scopeOfUsuario($query, $user)
    {
        return $query->where('user_id', $user);
    }
}
