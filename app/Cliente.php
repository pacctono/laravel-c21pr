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
}
