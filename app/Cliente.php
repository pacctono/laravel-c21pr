<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'name', 'veces_name', 'telefono', 'veces_telefono', 'user_id',
        'email', 'veces_email', 'direccion', 'deseo_id', 'propiedad_id',
        'zona_id', 'precio_id', 'origen_id', 'resultado_id', 'observaciones',
        'user_actualizo', 'user_borro', 'borrado_en'
    ];
    protected static $diaSemana = [
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

    public function scopeOfDiaSemana($query, $indDia)
    {
        return $this->diaSemana($indDia);
    }

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
}
