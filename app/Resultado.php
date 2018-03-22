<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    protected $fillable = ['descripcion'];

    public function clientes()    // cliente_id
    {
        return $this->hasMany(Cliente::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }
}
