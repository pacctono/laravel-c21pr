<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'name', 'telefono', 'email', 'direccion',
        'deseo', 'propiedad', 'zona_id', 'origen_id', 'resultado_id',
        'observaciones'
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
}
