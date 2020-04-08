<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $fillable = ['descripcion'];

    public function propiedadesReserva()    // forma_pago_reserva_id
    {
        return $this->hasMany(Propiedad::class, 'forma_pago_reserva_id'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function propiedadesFirma()    // forma_pago_firma_id
    {
        return $this->hasMany(Propiedad::class, 'forma_pago_firma_id'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

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
        $queryReserva = self::find($id)->propiedadesReserva->where('user_borro', '!=', null);
        $queryFirma = self::find($id)->propiedadesFirma->where('user_borro', '!=', null)
                                        ->union($queryReserva);
        $queryGerente = self::find($id)->propiedadesGerente->where('user_borro', '!=', null)
                                        ->union($queryFirma);
        $queryCaptador = self::find($id)->propiedadesCaptador->where('user_borro', '!=', null)
                                        ->union($queryGerente);
        $queryCerrador = self::find($id)->propiedadesCerrador->where('user_borro', '!=', null)
                                        ->union($queryCaptador);
        return self::find($id)->propiedadesOtraOficina->where('user_borro', '!=', null)
                                        ->union($queryCerrador);
    }

    public static function propiedadesXFormaPago($fecha_desde='2019-01-01', $fecha_hasta='2099-12-31')
    {
        $queryReserva = self::withCount(['propiedadesReserva as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                        }]);
        $queryFirma = self::withCount(['propiedadesFirma as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                        }]);
        $queryGerente = self::withCount(['propiedadesGerente as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                        }]);
        $queryCaptador = self::withCount(['propiedadesCaptador as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                        }]);
        $queryCerrador = self::withCount(['propiedadesCerrador as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                        }]);
        $queryOtraOficina = self::withCount(['propiedadesOtraOficina as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                        }]);
        return $queryReserva->union($queryFirma)->union($queryGerente)->union($queryCaptador)->union($queryCerrador)->union($queryOtraOficina);
    }

    public function getPropiedadesAttribute()
    {
        return $this->propiedades();
    }
    public function propiedades()    // forma_pago_reserva_id
    {
        $id = $this->id;
        return Propiedad::where(function ($query) use ($id) {
                    $query->where('forma_pago_reserva_id', $id)
                        ->orWhere('forma_pago_firma_id', $id)
                        ->orWhere('forma_pago_gerente_id', $id)
                        ->orWhere('forma_pago_captador_id', $id)
                        ->orWhere('forma_pago_cerrador_id', $id)
                        ->orWhere('forma_pago_otra_oficina_id', $id);
                })->get();
    }
}
