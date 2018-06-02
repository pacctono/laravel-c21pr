<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    protected $fillable = ['descripcion'];

    public function clientes()    // cliente_id
    {
        return $this->hasMany(Cliente::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function contactos()    // contacto_id
    {
        return $this->hasMany(Contacto::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public static function contactosXPropiedad($fecha_desde, $fecha_hasta)
    {
        return self::withCount(['contactos as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                    }]);
    }
}
