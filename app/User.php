<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use App\MisClases\Fecha;

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
    protected $dates = [
        'created_at', 'updated_at', 'fecha_nacimiento', 'fecha_ingreso'
    ];

    protected $casts = [
        'is_admin' => 'boolean'
    ];

    public function contactos()    // user_id
    {
        return $this->hasMany(Contacto::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function contactosBorrados()
    {
        return $this->hasMany(Contacto::class, 'user_borro'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function contactosActualizados()
    {
        return $this->hasMany(Contacto::class, 'user_actualizo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function turnos()    // user_id
    {
        return $this->hasMany(Turno::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function agendas()    // user_id
    {
        return $this->hasMany(Agenda::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function bitacoras()    // user_id
    {
        return $this->hasMany(Bitacora::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function scopeOfAdmin($query)
    {
        return $query->where('is_admin', 1);
    }

    public function getCedulaFAttribute()     // Cedula formateado.
    {
        $value = $this->cedula;
        if (null == $value) return '';
        return number_format($value, 0, ',', '.');
    }

    public function setNameAttribute($value) {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function getNameAttribute($value) {
        return ucwords(strtolower($value));
    }
    
    public function getTelefonoFAttribute()     // Telefono formateado.
    {
        $value = $this->telefono;
        if (null == $value) return '';
        return '0' . substr($value, 0, 3) . '-' . substr($value, 3, 3) . '-' . substr($value, 6);
    }

    public static function contactosXAsesor($fecha_desde, $fecha_hasta)
    {
        return self::where('id', '>', 1)
                    ->withCount(['contactos as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                    }]);
    }

    public static function conexionXAsesor($fecha_desde, $fecha_hasta)
    {
        return self::where('id', '>', 1)
                    ->withCount(['bitacoras as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {
                            $query->where('tx_tipo', 'L')
                            ->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                    }]);
    }

    public function getFechaNacimientoEnAttribute()
    {
        if (null == $this->fecha_nacimiento) return '';
        return $this->fecha_nacimiento->format('d/m/Y');
    }

    public function getFechaNacimientoBdAttribute()
    {
        if (null == $this->fecha_nacimiento) return '';
        return $this->fecha_nacimiento->format('Y-m-d');
    }

    public function getFechaCumpleanosAttribute()
    {
        if (null == $this->fecha_nacimiento) return '';
        $anos = now(Fecha::$ZONA)->format('Y') -
                            (new Carbon($this->fecha_nacimiento, Fecha::$ZONA))->format('Y');
        $fecha = (new Carbon($this->fecha_nacimiento, Fecha::$ZONA))->addYears($anos);
        if (now(Fecha::$ZONA) < $fecha) return $fecha;
        else return $fecha->addYears(1);
    }

    public static function cumpleanos($fecha=null, $fecha_hasta=null) {
        if(null == $fecha) $fecha = now(Fecha::$ZONA);
//        dd($fecha);
        $fecha_desde = new Carbon($fecha, Fecha::$ZONA);
        if(null == $fecha_hasta) $fecha_hasta = (new Carbon($fecha, Fecha::$ZONA))->addDays(30);
//        dd($fecha, $fecha_desde, $fecha_hasta);
        return self::whereBetween(DB::raw("DATE_ADD(fecha_nacimiento,
                                        INTERVAL YEAR(now())-YEAR(fecha_nacimiento) +
                                        IF(DAYOFYEAR(now()) > DAYOFYEAR(fecha_nacimiento),1,0)
                                        YEAR)"), [$fecha_desde, $fecha_hasta]);
//                            ->get();
    }

    public function getFechaIngresoEnAttribute()
    {
        if (null == $this->fecha_ingreso) return '';
        return $this->fecha_ingreso->format('d/m/Y');
    }

    public function getFechaIngresoBdAttribute()
    {
        if (null == $this->fecha_ingreso) return '';
        return $this->fecha_ingreso->format('Y-m-d');
    }

    public function fechaEn($fecha)         // La funcion se llama como fechaEn no fecha_en.
    {
        return $this[$fecha]->timezone(Fecha::$ZONA)->format('d/m/Y');
    }

    public function fechaDiaSemana($fecha)
    {
        return substr(Fecha::$diaSemana[$this[$fecha]->timezone(Fecha::$ZONA)
                        ->dayOfWeek], 0, 3);
    }

    public function fechaConHora($fecha)
    {
        return $this[$fecha]->timezone(Fecha::$ZONA)->format('d/m/Y h:i a');
    }

    public function getCreadoEnAttribute()
    {
        return $this->created_at->timezone(Fecha::$ZONA)->format('d/m/Y');
    }

    public function getCreadoDiaSemanaAttribute()
    {
        return substr(Fecha::$diaSemana[$this->created_at->timezone(Fecha::$ZONA)
                        ->dayOfWeek], 0, 3);
    }

    public function getCreadoConHoraAttribute()
    {
        return $this->created_at->timezone(Fecha::$ZONA)->format('d/m/Y H:i a');
    }

    public function getEdadAttribute()
    {
        if (null == $this->fecha_nacimiento) return '';     // Aunque es UTC, tiene hora 0.
        return Carbon::parse($this->fecha_nacimiento)->age; // No debe usarse timezone.
    }

    public function getTiempoServicioAttribute()
    {
        if (null == $this->fecha_ingreso) return '';
        return Carbon::parse($this->fecha_ingreso)          // Aunque es UTC, tiene hora 0.
                        ->diff(Carbon::now(Fecha::$ZONA))  // Aqui si debe usarse timezone.
                        ->format('%y años, %m meses y %d dias');
    }
}
