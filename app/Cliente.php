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
        'cedula', 'rif', 'name', 'tipo', 'telefono', 'user_id', 'email',
        'fecha_nacimiento', 'direccion', 'observaciones', 'contacto_id',
        'user_actualizo', 'user_borro'
    ];
    protected $dates = [
        'fecha_nacimiento', 'created_at', 'updated_at', 'deleted_at'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    protected $appends = [
        'fecNac', 'borrado', 'creado', 'actualizado',
    ];

    public function user()    // user_id
    {
        return $this->belongsTo(User::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function propiedades()    // cliente_id
    {
        return $this->hasMany(Propiedad::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function contacto()    // contacto_id
    {
        return $this->belongsTo(Contacto::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
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
        $cols = General::columnas('clientes');
        if (array_key_exists($this->tipo, $cols['tipo']['opcion']))
            return str_replace(['[', ']'], '', $cols['tipo']['opcion'][$this->tipo]);
	    else return 'Nulo';
    }

    public function getTelefonoFAttribute()
    {
        return General::telefonoF($this->telefono);
    }

    public function getFecNacAttribute()
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

    public function getCreadoAttribute()
    {
        return General::fechaEn($this->created_at, true);
    }

    public function getActualizadoAttribute()
    {
        return General::fechaEn($this->updated_at, true);
    }

    public function getBorradoAttribute()
    {
        return General::fechaEn($this->deleted_at, true);
    }

}
