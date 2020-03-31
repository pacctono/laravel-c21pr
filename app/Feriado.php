<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MisClases\General;                  // PC

class Feriado extends Model
{
    protected $fillable = ['fecha', 'descripcion', 'tipo'];
    protected $dates = [        // Mutan a una instancia de Carbon.
        'fecha', 'created_at', 'updated_at'
    ];

    public function getFechaBdAttribute()
    {
        return General::fechaBd($this->fecha);
    }

    public function getFechaEnAttribute()
    {
        return General::fechaEn($this->fecha);
    }

    public function getFechaDiaSemanaAttribute()
    {
        return General::fechaDiaSemana($this->fecha);
    }
}
