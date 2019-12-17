<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\MisClases\Fecha;

class Aviso extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'turno_id', 'tipo', 'fecha', 'descripcion',
        'user_creo', 'user_actualizo', 'user_borro'
    ];
    protected $dates = [
        'fecha', 'deleted_at', 'updated_at', 'created_at'
    ];
    protected $hidden = [
        'fecha', 'deleted_at', 'updated_at', 'created_at'
    ];
    protected $appends = [
        'fec', 'creado'
    ];

    public function user()    // user_id
    {
        return $this->belongsTo(User::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function turno()    // turno_id
    {
        return $this->belongsTo(Turno::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userCreo()    // la llave es user_creo
    {
        return $this->belongsTo(User::class, 'user_creo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userActualizo()    // la llave es user_actualizo
    {
        return $this->belongsTo(User::class, 'user_actualizo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userBorro()    // la llave es user_borro
    {
        return $this->belongsTo(User::class, 'user_borro'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function getFecAttribute()
    {
        if (null == $this->fecha) return '';
        return $this->fecha->format('d/m/Y H:i (h:i a)');
    }

    public function getCreadoAttribute()
    {
        return $this->created_at->timezone(Fecha::$ZONA)->format('d/m/Y');
    }
}