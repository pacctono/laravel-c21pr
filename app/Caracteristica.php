<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    protected $fillable = ['descripcion'];

    public function propiedades()    // caracteristica_id
    {
        return $this->hasMany(Propiedad::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public static function propiedadesBorradas($id)     // Usado al borrar una caracteristica.
    {
        return self::find($id)->propiedades->where('user_borro', '!=', null);
    }

    public static function propiedadesXCaracteristica($fecha_desde, $fecha_hasta)
    {
        return self::withCount(['propiedades as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('fecha_firma', [$fecha_desde, $fecha_hasta]);
                    }]);
    }
}
