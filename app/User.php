<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cedula', 'name', 'telefono', 'email', 'email_c21', 'licencia_mls',
        'fecha_ingreso', 'fecha_nacimiento', 'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'is_admin' => 'boolean'
    ];

    public function clientes()    // user_id
    {
        return $this->hasMany(Cliente::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function turnos()    // user_id
    {
        return $this->hasMany(Turno::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function scopeOfAdmin($query)
    {
        return $query->where('is_admin', 1);
    }
}
