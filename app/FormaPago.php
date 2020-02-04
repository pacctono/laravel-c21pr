<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $fillable = ['descripcion'];

    public function propiedadesGerente()    // forma_pago_gerente_id
    {
        return $this->hasMany(Propiedad::class, 'forma_pago_gerente_id'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function propiedadesCaptador()    // forma_pago_captador_id
    {
        return $this->hasMany(Propiedad::class, 'forma_pago_captador_id'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function propiedadesCerrador()    // forma_pago_cerrador_id
    {
        return $this->hasMany(Propiedad::class, 'forma_pago_cerrador_id'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function propiedadesOtraOficina()    // forma_pago_otra_oficina_id
    {
        return $this->hasMany(Propiedad::class, 'forma_pago_cerrador_id'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public static function propiedadesBorradas($id)
    {
        $queryGerente = self::find($id)->propiedadesGerente->where('user_borro', '!=', null);
        $queryCaptador = self::find($id)->propiedadesCaptador->where('user_borro', '!=', null)
                                        ->union($queryGerente);
        $queryCerrador = self::find($id)->propiedadesCerrador->where('user_borro', '!=', null)
                                        ->union($queryCaptador);
        return self::find($id)->propiedadesOtraOficina->where('user_borro', '!=', null)
                                        ->union($queryCerrador);
    }

    public static function propiedadesXFormaPago($fecha_desde, $fecha_hasta)
    {
        $queryGerente = self::withCount(['propiedadesGerente as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                        }]);
        $queryCaptador = self::withCount(['propiedadesCaptador as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                        }])->union($queryGerente);
        $queryCerrador = self::withCount(['propiedadesCerrador as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                        }])->union($queryCaptador);
        return self::withCount(['propiedadesOtraOficina as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                        }])->union($queryCerrador);
    }
}
