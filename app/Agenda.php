<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'user_id', 'fecha_evento', 'hora_evento', 'descripcion',
        'name', 'telefono', 'email', 'direccion'
    ];
    protected $dates = [
        'fecha_evento'
    ];
    protected $table = 'vista_agenda';

    public function user()    // user_id
    {
        return $this->belongsTo(User::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function scopeOfUsuario($query, $user)
    {
        return $query->where('user_id', $user);
    }
}
