<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\MisClases\Fecha;

class Contacto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'cedula', 'name', 'veces_name', 'telefono', 'veces_telefono',
        'user_id', 'email', 'veces_email', 'direccion', 'deseo_id',
        'tipo_id', 'zona_id', 'precio_id', 'origen_id', 'resultado_id',
        'fecha_evento', 'observaciones', 'user_actualizo', 'user_borro'
    ];
    protected $dates = [        // Mutan a una instancia de Carbon.
        'created_at', 'updated_at', 'deleted_at', 'fecha_evento'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at', 'fecha_evento'
    ];
    protected $appends = [
        'fecEve', 'borrado', 'creado', 'actualizado',
    ];

    public function user()    // user_id
    {
        return $this->belongsTo(User::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function deseo()    // deseo_id
    {
        return $this->belongsTo(Deseo::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function tipo()    // tipo_id
    {
        return $this->belongsTo(Tipo::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function zona()    // zona_id
    {
        return $this->belongsTo(Zona::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function precio()    // precio_id
    {
        return $this->belongsTo(Precio::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function origen()    // tipo_id
    {
        return $this->belongsTo(Origen::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function resultado()    // resultado_id
    {
        return $this->belongsTo(Resultado::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userActualizo()    // user_id
    {
        return $this->belongsTo(User::class, 'user_actualizo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userBorro()    // user_id
    {
        return $this->belongsTo(User::class, 'user_borro'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function scopeOfFecha($query, $fechaDesde, $fechaHasta)
    {
        return $query->whereBetween('created_at', [$fechaDesde, $fechaHasta]);
    }

    public function setNameAttribute($value) {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function getNameAttribute($value) {
        return ucwords(strtolower($value));
    }

    public function scopeOfUsuario($query, $user)
    {
        return $query->where('user_id', $user);
    }

    public function scopeOfVeces($query, $nombre, $col)
    {
        return $query->where($col, $nombre)
            ->whereDate('created_at', '<', date('Y-m-d'))
            ->groupBy('veces_'.$col)
            ->count();
    }

    public static function contactosXOrigen($fecha_desde, $fecha_hasta, $user=-1)
    {
        If (0 >= $user) $signo = '>';
        else $signo = '=';
        $sql = self::select(DB::raw(),
                        DB::raw('count(*) as atendidos'))
                    ->whereBetween('created_at', [$fecha_desde, $fecha_hasta])
                    ->where('user_id', $signo, $user)
                    ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),
                        DB::raw('DATE_FORMAT(created_at, "%d/%m/%Y")'));
        return $sql;
    }

    public static function contactosXFecha($fecha_desde, $fecha_hasta, $user=-1)
    {
        If (0 >= $user) $signo = '>';
        else $signo = '=';
        $sql = self::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),
                        DB::raw('DATE_FORMAT(created_at, "%d/%m/%Y") as fecha'),
                        DB::raw('count(*) as atendidos'))
                    ->whereBetween('created_at', [$fecha_desde, $fecha_hasta])
                    ->where('user_id', $signo, $user)
                    ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),
                        DB::raw('DATE_FORMAT(created_at, "%d/%m/%Y")'));
        return $sql;
    }

    public function getCedulaFAttribute()     // Cedula formateado.
    {
        $value = $this->cedula;
        if (null == $value) return '';
        return number_format($value, 0, ',', '.');
    }

    public function getTelefonoFAttribute()
    {
        $value = $this->telefono;
        if (null == $value) return '';
        return '0' . substr($value, 0, 3) . '-' . substr($value, 3, 3) . '-' . substr($value, 6);
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
        return $this->created_at->timezone(Fecha::$ZONA)->format('d/m/Y h:i a');
    }

    public function getTiempoCreadoAttribute()
    {
        return Carbon::parse($this->created_at)
                        ->timezone(Fecha::$ZONA)
                        ->diff(Carbon::now(Fecha::$ZONA))
                        ->format('%y años, %m meses y %d dias');
    }

    public function getActualizadoAttribute()
    {
        return $this->updated_at->timezone(Fecha::$ZONA)->format('d/m/Y');
    }

    public function getActualizadoDiaSemanaAttribute()
    {
        return substr(Fecha::$diaSemana[$this->updated_at->timezone(Fecha::$ZONA)
                        ->dayOfWeek], 0, 3);
    }

    public function getActualizadoConHoraAttribute()
    {
        return $this->updated_at->timezone(Fecha::$ZONA)->format('d/m/Y h:i a');
    }

    public function getBorradoAttribute()
    {
        if (null == $this->deleted_at) return '';
        return $this->deleted_at->timezone(Fecha::$ZONA)->format('d/m/Y');
    }

    public function getBorradoDiaSemanaAttribute()
    {
        if (null == $this->deleted_at) return '';
        return substr(Fecha::$diaSemana[$this->deleted_at->timezone(Fecha::$ZONA)
                        ->dayOfWeek], 0, 3);
    }

    public function getBorradoConHoraAttribute()
    {
        if (null == $this->deleted_at) return '';
        return $this->deleted_at->timezone(Fecha::$ZONA)->format('d/m/Y h:i a');
    }

    public function getFecEveAttribute()
    {
        if (null == $this->fecha_evento) return '';
        return $this->fecha_evento->format('d/m/Y');
    }

    public function getEventoBdAttribute()
    {
        if (null == $this->fecha_evento) return '';
        return $this->fecha_evento->format('Y-m-d');
    }

    public function getEventoDiaSemanaAttribute()
    {
        if (null == $this->fecha_evento) return '';
        return substr(Fecha::$diaSemana[$this->fecha_evento
                        ->dayOfWeek], 0, 3);
    }

    public function getEventoConHoraAttribute()
    {
        if (null == $this->fecha_evento) return '';
        return $this->fecha_evento->format('d/m/Y H:i (h:i a)');
    }

    public function getEventoHoraAttribute()
    {
        if (null == $this->fecha_evento) return '';
        return $this->fecha_evento->format('H:i');
    }
}
