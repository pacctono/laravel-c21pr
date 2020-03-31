<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\MisClases\Fecha;
use App\Propiedad;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    public const CORREO_COPIAR = [
        [ 'email' => 'aliriomendozacarrero@gmail.com', 'name' => 'Alirio Mendoza' ]
    ];
    public const CORREO_GERENTE = [
        ['email' => 'scaraballoc21puentereal@gmail.com', 'name' => 'Silvia Caraballo']
    ];
    public const CORREO_ADMINISTRADOR = [
        ['email' => '', 'name' => 'Javier Aponte']
    ];
    public const CORREO_SOCIOS = [
        ['email' => 'aliriomendozacarrero@gmail.com', 'name' => 'Alirio Mendoza'],
        ['email' => 'davidh.plc@gmail.com', 'name' => 'David Henandez'],
        ['email' => 'scaraballoc21puentereal@gmail.com', 'name' => 'Silvia Caraballo'], 
        ['email' => 'migdamar_1@hotmail.com', 'name' => 'Migdamar Brito']
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cedula', 'name', 'telefono', 'email', 'email_c21', 'licencia_mls',
	    'fecha_ingreso', 'fecha_nacimiento', 'sexo', 'estado_civil', 'profesion',
        'direccion', 'password', 'activo'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'created_at', 'updated_at', 'deleted_at'
    ];
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at', 'fecha_nacimiento', 'fecha_ingreso'
    ];
    protected $casts = [
        'is_admin' => 'boolean',
        'socio' => 'boolean',
        'activo' => 'boolean',
    ];
    protected $appends = [
        'fecNac', 'fecIng', 'genero', 'edoCivil',
        'ladosCaptador', 'ladosCerrador', 'lados',
        'comisionCaptador', 'comisionCerrador', 'comision',
        'pvrCaptador', 'pvrCerrador', 'precioVentaReal',
        'puntosCaptador', 'puntosCerrador', 'puntos',
        'borrado', 'creado', 'actualizado',
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

    public function turnosBorrados()
    {
        return $this->hasMany(Turno::class, 'user_borro'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function turnosActualizados()
    {
        return $this->hasMany(Turno::class, 'user_actualizo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function agendas()    // user_id
    {
        return $this->hasMany(Agenda::class)->orderBy('fecha_evento'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function agenda_personals()    // user_id
    {
        return $this->hasMany(AgendaPersonal::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function agendaPersonalsBorrados()
    {
        return $this->hasMany(AgendaPersonal::class, 'user_borro'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function agendaPersonalsActualizados()
    {
        return $this->hasMany(AgendaPersonal::class, 'user_actualizo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function citas($fecha_desde=null, $fecha_hasta=null)
    {
// Todas las fechas grabadas en la bd se asumen 'UTC'. Esto es un tema un poco complejo.
// 'fecha_evento' asume hora '00:00' UTC, Si $fecha es en Caracas, hora:00:00 es 04:00 UTC.
        $fecha_desde = $fecha_desde??Fecha::hoy()->format('Y-m-d');
        if ($fecha_hasta)
            return $this->agendas   // No existe whereBetween en Collection.
                        ->where('fecha_evento', '>=', new Carbon($fecha_desde)) // Collection funciona mejor
                        ->where('fecha_evento', '<=', new Carbon($fecha_hasta));// con Carbon.
        else return $this->agendas->where('fecha_evento', '>=', $fecha_desde);
    }

    public function propiedades()    // user_id
    {
        return $this->hasMany(Propiedad::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function clientes()    // user_id
    {
        return $this->hasMany(Cliente::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function clientesBorrados()
    {
        return $this->hasMany(Cliente::class, 'user_borro'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function clientesActualizados()
    {
        return $this->hasMany(Cliente::class, 'user_actualizo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function captadorPropiedades()    // asesor_captador_id
    {
        return $this->hasMany(Propiedad::class, 'asesor_captador_id'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function propiedadesCaptadas()    // Deberia ser el numero correcto. asesor_captador_id
    {
        return $this->hasMany(Propiedad::class, 'asesor_captador_id'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function cerradorPropiedades()    // Deberia ser el numero correcto. asesor_cerrador_id
    {
        return $this->hasMany(Propiedad::class, 'asesor_cerrador_id'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function propiedadesCerradas()    // asesor_cerrador_id
    {
        return $this->hasMany(Propiedad::class, 'asesor_cerrador_id'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function propiedadesBorradas()
    {
        return $this->hasMany(Propiedad::class, 'user_borro'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function propiedadesActualizadas()
    {
        return $this->hasMany(Propiedad::class, 'user_actualizo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
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

    public function getNombreAttribute()     // Nombre del asesor, si id es 1, decolver 'Administrador'.
    {
        if (1 < $this->id) return $this->name;
        return 'Administrador';
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
        return self::where('id', '>', 1)->where('activo', true)
                    ->withCount(['contactos as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                    }]);
    }

    public static function conexionXAsesor($fecha_desde, $fecha_hasta)
    {
        return self::where('id', '>', 1)->where('activo', true)
                    ->withCount(['bitacoras as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {
                            $query->where('tx_tipo', 'L')
                            ->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                    }]);
    }

    public function getFecNacAttribute()
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

    public static function cumpleanosHoy()
    {
        return self::where('activo', True)
                    ->where(DB::raw("MONTH(fecha_nacimiento)"),
                            DB::raw("MONTH(CURRENT_DATE)"))
                    ->where(DB::raw("DAYOFMONTH(fecha_nacimiento)"),
                            DB::raw("DAYOFMONTH(CURRENT_DATE)"))
                    ;
    }

    public function getLadosCaptadorAttribute()
    {
        return round($this->captadorPropiedades()
                    ->whereIn('estatus', ['P', 'C'])
                    ->get()
                    ->count(), 2);
    }

    public function getLadosCerradorAttribute()
    {
        return round($this->cerradorPropiedades()
                    ->whereIn('estatus', ['P', 'C'])
                    ->get()
                    ->count(), 2);
    }

    public function getLadosAttribute()
    {
        return round($this->getLadosCaptadorAttribute() +
                    $this->getLadosCerradorAttribute(), 2);
    }

    public function getComisionCaptadorAttribute()
    {
        return round($this->captadorPropiedades()
                    ->whereIn('estatus', ['P', 'C'])
                    ->get()
                    ->sum('captador_prbr'), 2);
    }

    public function getComisionCerradorAttribute()
    {
        return round($this->cerradorPropiedades()
                    ->whereIn('estatus', ['P', 'C'])
                    ->get()
                    ->sum('cerrador_prbr'), 2);
    }

    public function getComisionAttribute()
    {
        return round($this->getComisionCaptadorAttribute() +
                    $this->getComisionCerradorAttribute(), 2);
    }

    public function getPuntosCaptadorAttribute()
    {
        return round($this->captadorPropiedades()
                    ->whereIn('estatus', ['P', 'C'])
                    ->get()
                    ->sum('puntos_captador'), 2);
    }

    public function getPuntosCerradorAttribute()
    {
        return round($this->cerradorPropiedades()
                    ->whereIn('estatus', ['P', 'C'])
                    ->get()
                    ->sum('puntos_cerrador'), 2);
    }

    public function getPuntosAttribute()
    {
        return round($this->getPuntosCaptadorAttribute() +
                    $this->getPuntosCerradorAttribute(), 2);
    }

    public function getPvrCaptadorAttribute()
    {
        return round($this->captadorPropiedades()
                    ->whereIn('estatus', ['P', 'C'])
                    ->get()
                    ->sum('pvr_captador_prbr'), 2);
    }

    public function getPvrCerradorAttribute()
    {
        return round($this->cerradorPropiedades()
                    ->whereIn('estatus', ['P', 'C'])
                    ->get()
                    ->sum('pvr_cerrador_prbr'), 2);
    }

    public function getPrecioVentaRealAttribute()
    {
        return round($this->getPvrCaptadorAttribute() +
                    $this->getPvrCerradorAttribute(), 2);
    }

    public static function ladosXAsesor($fecha='fecha_reserva', $fecha_desde=null,
                                       $fecha_hasta=null, $cond='>', $user=1)
    {
        if(null == $fecha_desde)
            $fecha_desde = (new Carbon(Propiedad::min($fecha)))->startOfDay();
        if(null == $fecha_hasta)
            $fecha_hasta = (new Carbon(Propiedad::max($fecha)))->endOfDay();
        return self::where('id', $cond, $user)->where('activo', true)
                    ->withCount(['captadorPropiedades as captadas' => function ($query)
                                        use ($fecha, $fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereIn('estatus', ['P', 'C'])
                                  ->whereBetween($fecha, [$fecha_desde, $fecha_hasta]);
                                        },
                                'cerradorPropiedades as cerradas' => function ($query)
                                        use ($fecha, $fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereIn('estatus', ['P', 'C'])
                                  ->whereBetween($fecha, [$fecha_desde, $fecha_hasta]);
                    }]);
    }

    public function avisos()    // user_id
    {
        return $this->hasMany(Aviso::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function avisosNoConectados()    // avisos sin conectarse, en su turno, de este asesor.
    {
        return $this->hasMany(Aviso::class)->where('tipo', 'C'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function avisosTarde()       // avisos con llegada tarde, en su turno, de este asesor.
    {
        return $this->hasMany(Aviso::class)->whereIn('tipo', ['M', 'T']); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function avisosCreados()    // user_creo
    {
        return $this->hasMany(Aviso::class, 'user_creo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function avisosBorrados()
    {
        return $this->hasMany(Aviso::class, 'user_borro'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function avisosActualizados()
    {
        return $this->hasMany(Aviso::class, 'user_actualizo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public static function avisosXAsesor($fecha_desde=null, $fecha_hasta=null)  // # de avisos por asesor.
    {
        $fecha_desde = $fecha_desde??date('Y-01-01');
        $fecha_hasta = $fecha_hasta??date('Y-12-31');
        return self::where('is_admin', False)->where('activo', true)
                    ->withCount(['avisos as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                    }]);
    }

    public static function noConectadosXAsesor($fecha_desde=null, $fecha_hasta=null)  // # de no conexiones por asesor en su turno.
    {
        $fecha_desde = $fecha_desde??date('Y-01-01');
        $fecha_hasta = $fecha_hasta??date('Y-12-31');
        return self::where('is_admin', False)->where('activo', true)
                    ->withCount(['avisosNoConectados as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                    }]);
    }

    public static function tardeXAsesor($fecha_desde=null, $fecha_hasta=null)  // # de llegadas tarde por asesor en su turno.
    {
        $fecha_desde = $fecha_desde??date('Y-01-01');
        $fecha_hasta = $fecha_hasta??date('Y-12-31');
        return self::where('is_admin', False)->where('activo', true)
                    ->withCount(['avisosTarde as atendidos' => function ($query)
                                        use ($fecha_desde, $fecha_hasta) {  // 'use' permite heredar variables del scope del padre, donde el closure es definido.
                            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
                    }]);
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
                                        YEAR)"), [$fecha_desde, $fecha_hasta])
                                        ->orderBy(DB::raw("DAYOFYEAR(fecha_nacimiento)"));
//                            ->get();
    }

    public function getFecIngAttribute()
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

    public function getCreadoAttribute()
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

    public function getActualizadoAttribute()
    {
        if (null == $this->updated_at) return '';
        return $this->updated_at->timezone(Fecha::$ZONA)->format('d/m/Y');
    }

    public function getBorradoAttribute()
    {
        if (null == $this->deleted_at) return '';
        return $this->deleted_at->timezone(Fecha::$ZONA)->format('d/m/Y');
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

    public function getGeneroAttribute()
    {
        if ('F' == $this->sexo) return 'Femenino';
        elseif ('M' == $this->sexo) return 'Masculino';
        else return 'Ninguno';
    }

    public function getEdoCivilAttribute()
    {
        if ('F' == $this->sexo) $ultLetra = 'a';
        elseif ('M' == $this->sexo) $ultLetra = 'o';
        if ('C' == $this->estado_civil) return 'Casad' . $ultLetra;
        elseif ('S' == $this->estado_civil) return 'Solter' . $ultLetra;
        else return 'Ninguno';
    }
}
