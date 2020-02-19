<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = ['menor', 'mayor'];

    public function contactos()    // contacto_id
    {
        return $this->hasMany(Contacto::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function getDescripcionAttribute()
    {
        return 'Entre ' . number_format($this->menor, 2, ',', '.') . ' y ' .
                number_format($this->mayor, 0, ',', '.');
    }

    public function getDescripcionAlquilerAttribute()
    {
        $menor = $this->menor;
        return 'Entre ' .
                number_format(((0 < $menor)?((($menor-0.01)/100)+0.01):'0'), 2, ',', '.') .
                ' y ' . number_format(($this->mayor/100), 0, ',', '.');
    }

    public static function contactosBorrados($id)
    {
        return self::find($id)->contactos->where('user_borro', '!=', null);
    }

    public static function contactosXPrice($fecha_desde, $fecha_hasta)
    {
        return self::withCount(['contactos as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                    }]);
    }
}
