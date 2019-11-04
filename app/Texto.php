<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Texto extends Model
{
    protected $fillable = ['descripcion', 'enlace', 'textoEnlace'];
    protected $dates = [        // Mutan a una instancia de Carbon.
        'created_at', 'updated_at'
    ];
}
