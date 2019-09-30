<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $fillable = ['descripcion'];

    public function propiedades()    // municipio_id
    {
        return $this->hasMany(Propiedad::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public static function propiedadesBorradas($id)
    {
        return self::find($id)->propiedades->where('user_borro', '!=', null);
    }

    public static function propiedadesXMunicipio($fecha_desde, $fecha_hasta)
    {
        return self::withCount(['propiedades as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                    }]);
    }
}
