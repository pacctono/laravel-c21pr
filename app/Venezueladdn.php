<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venezueladdn extends Model
{
    protected $fillable = [
        'id', 'estado_zona', 'ciudad_sector', 'ddn',
    ];
}
