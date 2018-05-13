<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $fillable = [
        'user_id', 'tx_modelo', 'tx_tipo', 'tx_data', 'tx_host',    // tipo=I:inserto,M:modifico,B:borro.
    ];
    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function user()    // user_id
    {
        return $this->belongsTo(User::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function scopeOfUltLogin($query, $user)
    {
        return $query->where('user_id', $user)->where('tx_tipo', 'L')->max('created_at');
    }
}
