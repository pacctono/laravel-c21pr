<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;     // PC
use Illuminate\Database\Eloquent\ModelNotFoundException;     // PC
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\MisClases\Fecha;
use App\MisClases\General;                  // PC

class Propiedad extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'codigo', 'fecha_reserva', 'forma_pago_reserva_id', 'factura_reserva',
        'fecha_firma', 'forma_pago_firma_id', 'factura_firma', 'negociacion', 'nombre',
        'exclusividad', 'fecha_inicial', 'tipo_id', 'metraje', 'habitaciones', 'banos',
        'niveles', 'puestos', 'anoc', 'caracteristica_id', 'descripcion',
        'direccion', 'ciudad_id', 'codigo_postal', 'municipio_id', 'estado_id',
        'cliente_id', 'estatus', 'user_id', 'moneda', 'precio', 'comision',
        'iva', 'lados', 'porc_franquicia', 'reportado_casa_nacional', 'porc_regalia',
        'porc_compartido', 'porc_captador_prbr', 'porc_gerente', 'porc_cerrador_pbr',
        'porc_bonificacion', 'comision_bancaria', 'numero_recibo', 'asesor_captador_id',
        'asesor_captador', 'asesor_cerrador_id', 'asesor_cerrador', 'pago_gerente',
        'forma_pago_gerente_id', 'fecha_pago_gerente', 'factura_gerente', 'pago_asesores',
        'forma_pago_captador_id', 'fecha_pago_captador', 'factura_captador',
        'forma_pago_cerrador_id', 'fecha_pago_cerrador', 'factura_cerrador',
        'factura_asesores', 'pago_otra_oficina', 'forma_pago_otra_oficina_id',
        'fecha_pago_otra_oficina', 'factura_otra_oficina', 'pagado_casa_nacional',
        'estatus_sistema_c21', 'reporte_casa_nacional', 'comentarios', 'factura_AyS',
        'user_actualizo', 'user_borro'
    ];
    protected $dates = [        // Mutan a una instancia de Carbon.
        'fecha_reserva', 'fecha_firma', 'fecha_inicial',
        'deleted_at', 'created_at', 'updated_at'
    ];
    protected $casts = [
        'exclusividad' => 'boolean',
        'pagado_casa_nacional' => 'boolean',
    ];
    protected $hidden = [
//        'fecha_reserva', 'fecha_firma', 'exclusividad',
        'exclusividad',
        'negociacion', 'habitaciones', 'descripcion', 'direccion',
        'codigo_postal', 'porc_franquicia', 'reportado_casa_nacional',
        'porc_regalia', 'porc_compartido', 'porc_captador_prbr',
        'porc_gerente', 'porc_cerrador_prbr', 'porc_bonificacion',
        'comision_bancaria', 'numero_recibo',
        'asesor_captador_id', 'asesor_captador', 'asesor_cerrador_id',
        'asesor_cerrador', 'pago_gerente', 'factura_gerente', 'pago_asesores',
        'factura_asesores', 'pago_otra_oficina', 'pagado_casa_nacional',
        'estatus_sistema_c21', 'reporte_casa_nacional', 'comentarios', 'factura_AyS',
        'deleted_at', 'created_at', 'updated_at',
        'captador', 'cerrador'
    ];
    protected $appends = [
        'fecRes', 'fecFir', 'fecIni',                   // Fechas reserva, firma e incial.
        'negoc', 'exclu', 'habits', 'descr', 'direc',   // negociacion, exclusividad, habitaciones, descripcion y direccion.
        'fpRes', 'fpFir', 'fpGer', 'fpCap', 'fpcer', 'fpOtr',   // Formas de pago Reserva, Firma, Gerente, Captador, Cerrador, Otra Oficina
        'codPos', 'pcFrq', 'pcReCaNa',                  // codigo postal, porcentajes franquicia y reportado casa nacional.
        'pcRega', 'pcCom', 'pcCap',                     // porcentajes regalia, comisio y captador.
        'pcGer', 'pcCer', 'pcBonif',                    // porcentajes gerente, cerrador y bonificacion.
        'comBanc', 'nroRec',                            // comision banco y numero de recibo.
        'asCapId', 'asCap', 'asCerId',                  // asesor captador id, asesor captador y asesor cerrador id.
        'asCer', 'pagGer', 'factGer', 'pagAses',        // asesor cerrador, pago gerente (a eliminar), factura gerente, pago asesores (a eliminar)
        'factAse', 'pagOtOf', 'PagCaNa',                // factura asesores (a eliminar), pago otra oficina (a eliminar), Pago casa nacional.
        'estaC21', 'repCaNa', 'comens', 'factAyS',      // estatus C21, reportado casa nacional, comentarios, factura A&S.
        'resSIva', 'resCIva', 'comCIva', 'comSIva',     // reserva sin IVA, reserva con IVA, compartido con IVA, compartido sin IVA.
        'frqSIva', 'frqCIva', 'frqPaRe',                // franquicia sin IVA, franquicia con IVA, franquicia pagado reportado.
        'regalia', 'sanaf5pc',                          // regalia, sanaf 5 por ciento.
        'ofBrRe', 'baHoSoc', 'baPaHon', 'bonific',      // Oficina bruto real, base honorario socios, base pagar honorarios, bodificacion.
        'capPrbr', 'cerPrbr', 'gerente', 'ingNeOf',     // monto captador, cerrador, gerente, ingreso neto oficina.
        'pvrCap', 'pvrCer', 'prVeRe',                   // precios de venta real captador, cerrador y precio de venta real de la propiedad.
        'ptsCap', 'ptsCer', 'puntos',                   // puntos captador, cerrador y de la oficina.
        'fecGer', 'fpGer', 'fecCap', 'fpCap',           // Factura pago gerente, forma de pago gerente, fecha pago y forma de pago captador.
        'factCap', 'fecCer', 'fpCer', 'factCer',        // Factura pago cerrador, fecha, forma y factura pago cerrador.
        'fecOtr', 'fpOtr', 'factOtr',                   // Fecha, forma y factura pago otra oficina.
        'borrado', 'creado', 'actualizado',
    ];

    protected $META_2019 = 400000;
    protected $COMISION = 5.00;
    protected $IVA = 16.00;
    protected $PORC_ASESORES = 40.00;   // Porcentaje a repartir a asesores.
    public $mMoZero = true;
    public $espMonB = true;
    public $monedaB = true;
    public $espDosP = true;
    public $dosPunB = true;
    public const DIR_IMG = 'public/imgprop';
    public const COLORES = [
                            'A' => 'success',
                            'I' => 'warning',
                            'P' => 'primary',
                            'C' => 'info',
                            'S' => 'danger',
                            'W' => 'light',
                            'V' => 'info'
                        ]; 

    public function user()    // user_id
    {
        return $this->belongsTo(User::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userActualizo()    // user_id
    {
        return $this->belongsTo(User::class, 'user_actualizo'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function userBorro()    // user_id
    {
        return $this->belongsTo(User::class, 'user_borro'); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function captador()    // user_id
    {
        return $this->belongsTo(User::class, 'asesor_captador_id');
    }

    public function cerrador()    // user_id
    {
        return $this->belongsTo(User::class, 'asesor_cerrador_id');
    }

    public function tipo()    // tipo_id
    {
        return $this->belongsTo(Tipo::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function caracteristica()    // caracteristica_id
    {
        return $this->belongsTo(Caracteristica::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function ciudad()    // ciudad_id
    {
        return $this->belongsTo(Ciudad::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function municipio()    // municipio_id
    {
        return $this->belongsTo(Municipio::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function estado()    // estado_id
    {
        return $this->belongsTo(Estado::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function forma_pago_reserva()    // forma_pago_reserva_id
    {
        return $this->belongsTo(FormaPago::class, 'forma_pago_reserva_id')->withDefault(['descripcion' => '']);
    }

    public function forma_pago_firma()    // forma_pago_firma_id
    {
        return $this->belongsTo(FormaPago::class, 'forma_pago_firma_id')->withDefault(['descripcion' => '']);
    }

    public function forma_pago_gerente()    // forma_pago_gerente_id
    {
        return $this->belongsTo(FormaPago::class, 'forma_pago_gerente_id')->withDefault(['descripcion' => '']);
    }

    public function forma_pago_captador()    // forma_pago_captador_id
    {
        return $this->belongsTo(FormaPago::class, 'forma_pago_captador_id')->withDefault(['descripcion' => '']);
    }

    public function forma_pago_cerrador()    // forma_pago_cerrador_id
    {
        return $this->belongsTo(FormaPago::class, 'forma_pago_cerrador_id')->withDefault(['descripcion' => '']);
    }

    public function forma_pago_otra_oficina()    // forma_pago_otra_oficina_id
    {
        return $this->belongsTo(FormaPago::class, 'forma_pago_otra_oficina_id')->withDefault(['descripcion' => '']);
    }

    public function cliente()    // cliente_id
    {
        return $this->belongsTo(Cliente::class); // Si llave foranea, diferente a esperada, usamos 2do parametro.
    }

    public function scopeValido($query)
    {
        return $query->whereIn('estatus', ['P', 'C']);
    }

    public function scopeNoValido($query)
    {
        return $query->whereNotIn('estatus', ['P', 'C']);
    }

    public function scopeOfFecha($query, $fechaDesde, $fechaHasta)
    {
        return $query->whereBetween('created_at', [$fechaDesde, $fechaHasta]);
    }

    public function setNombreAttribute($value) {
        $this->attributes['nombre'] = ucwords(strtolower($value));
    }

    public function getNombreAttribute($value) {
        return ucwords(strtolower($value));
    }

    public function scopeOfUsuario($query, $user)
    {
        return $query->where('user_id', $user);
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

    public function agregarDosPuntos($nombre)
    {
        if ($this->espDosP) $nombre = ' ' . $nombre;
        if ($this->dosPunB) $nombre = ':' . $nombre;
        return $nombre;
    }

    public function getNombreCaptadorAttribute()
    {
        if ('1' == $this->asesor_captador_id) {
            if ($this->asesor_captador) $nombre = $this->asesor_captador;
            else return '';
        }
        else $nombre = $this->captador->name;

        return $this->agregarDosPuntos($nombre);
    }

    public function getNombreCerradorAttribute()
    {
        if ('1' == $this->asesor_cerrador_id) {
            if ($this->asesor_cerrador) $nombre = $this->asesor_cerrador;
            else return '';
        }
        else $nombre = $this->cerrador->name;

        return $this->agregarDosPuntos($nombre);
    }

    public function getFechaReservaBdAttribute()
    {
        if (is_null($this->fecha_reserva)) return '';
        return $this->fecha_reserva->format('Y-m-d');
    }

    public function getFecResAttribute()
    {
        if (is_null($this->fecha_reserva)) return '';
        return $this->fecha_reserva->format('d/m/Y');
    }

    public function getReservaDiaSemanaAttribute()
    {
        if (is_null($this->fecha_reserva)) return '';
// Eliminando timezone. Todas las fechas en la BD, se asumen UTC.
//        return substr(Fecha::$diaSemana[$this->fecha_reserva->timezone(Fecha::$ZONA)
        return substr(Fecha::$diaSemana[$this->fecha_reserva
                        ->dayOfWeek], 0, 3);
    }

    public function getReservaConHoraAttribute()
    {
        if (is_null($this->fecha_reserva)) return '';
//        return $this->fecha_reserva->timezone(Fecha::$ZONA)->format('d/m/Y h:i a');
        return $this->fecha_reserva->format('d/m/Y h:i a');
    }

    public function getFechaFirmaBdAttribute()
    {
        if (null == $this->fecha_firma) return '';
        return $this->fecha_firma->format('Y-m-d');
    }

    public function getFecFirAttribute()
    {
        if (null == $this->fecha_firma) return '';
        return $this->fecha_firma->format('d/m/Y');
    }

    public function getFirmaDiaSemanaAttribute()
    {
        if (null == $this->fecha_firma) return '';
        return substr(Fecha::$diaSemana[$this->fecha_firma->timezone(Fecha::$ZONA)
                        ->dayOfWeek], 0, 3);
    }

    public function getFirmaConHoraAttribute()
    {
        if (null == $this->fecha_firma) return '';
        return $this->fecha_firma->timezone(Fecha::$ZONA)->format('d/m/Y h:i a');
    }

    public function getFechaInicialBdAttribute() {
        return General::fechaBd($this->fecha_inicial);
    }

    public function getFecIniAttribute() {
        return General::fechaEn($this->fecha_inicial);
    }

    public function getInicialDiaSemanaAttribute() {
        return General::fechaDiaSemana($this->fecha_inicial);
    }

    public function getInicialConHoraAttribute() {
        return General::fechaConHora($this->fecha_inicial);
    }

    public function getFechaPagoGerenteBdAttribute() {
        return General::fechaBd($this->fecha_pago_gerente);
    }

    public function getFecGerAttribute() {
        return General::fechaEn($this->fecha_pago_gerente);
    }

    public function getPagoGerenteDiaSemanaAttribute() {
        return General::fechaDiaSemana($this->fecha_pago_gerente);
    }

    public function getPagoGerenteConHoraAttribute() {
        return General::fechaConHora($this->fecha_pago_gerente);
    }

    public function getFechaPagoCaptadorBdAttribute() {
        return General::fechaBd($this->fecha_pago_captador);
    }

    public function getFecCapAttribute() {
        return General::fechaEn($this->fecha_pago_captador);
    }

    public function getPagoCaptadorDiaSemanaAttribute() {
        return General::fechaDiaSemana($this->fecha_pago_captador);
    }

    public function getPagoCaptadorConHoraAttribute() {
        return General::fechaConHora($this->fecha_pago_captador);
    }

    public function getFechaPagoCerradorBdAttribute() {
        return General::fechaBd($this->fecha_pago_cerrador);
    }

    public function getFecCerAttribute() {
        return General::fechaEn($this->fecha_pago_cerrador);
    }

    public function getPagoCerradorDiaSemanaAttribute() {
        return General::fechaDiaSemana($this->fecha_pago_cerrador);
    }

    public function getPagoCerradorConHoraAttribute() {
        return General::fechaConHora($this->fecha_pago_cerrador);
    }

    public function getFechaPagoOtraOficinaBdAttribute() {
        return General::fechaBd($this->fecha_pago_otra_oficina);
    }

    public function getFecOtrAttribute() {
        return General::fechaEn($this->fecha_pago_otra_oficina);
    }

    public function getPagoOtraOficinaDiaSemanaAttribute() {
        return General::fechaDiaSemana($this->fecha_pago_otra_oficina);
    }

    public function getPagoOtraOficinaConHoraAttribute() {
        return General::fechaConHora($this->fecha_pago_otra_oficina);
    }

    public function getNegociacionAlfaAttribute()
    {
        if ('V' == $this->negociacion) return 'Venta';
	    elseif ('A' == $this->negociacion) return 'Alquiler';
	    else return 'Nulo';
    }

    public function getEstatusAlfaAttribute()
    {
        $cols = General::columnas('propiedads');
        $arrEstatus = $cols['estatus']['opcion'];
        if (isset($cols['estatus']['opcion'][$this->estatus]))
            return $cols['estatus']['opcion'][$this->estatus];
        else return 'Nulo';
    }

    public function colorEstatus($estatus, $antes='table', $borrado=False)
    {
        if ($borrado) return $antes.'-danger';
        elseif (array_key_exists($estatus, self::COLORES))
            return $antes . '-' . self::COLORES[$estatus];
        else return $antes.'-suave';                         // No deberia suceder
    }

    public function getEstatusColorAttribute()
    {
        return $this->colorEstatus($this->estatus, 'bg', ($this->user_borro || $this->deleted_at));
    }

    public static function numeroVen($valor, $dec=2)
    {
        return number_format($valor, $dec, ',', '.');
    }

    public function agregarComaMoneda($valor, $dec=2)
    {
        if (!$this->mMoZero and 0 == $valor) return '';
        $valor = number_format($valor, $dec, ',', '.');
        if ($this->espMonB) $valor = ' ' . $valor;
        if ($this->monedaB) $valor = $this->moneda . $valor;
        return $valor;
    }

    public function getPrecioVenAttribute()     // col 'G' en la muestra o ejemplo
    {
        return $this->agregarComaMoneda($this->precio);
    }

    public function getComisionPAttribute()     // H
    {
        return number_format($this->comision, 2, ',', '.') . '%';
    }

    public function getPorcFranquiciaPAttribute()     // H
    {
        return number_format($this->porc_franquicia, 2, ',', '.') . '%';
    }

    public function getIvaPAttribute()          // J
    {
        return number_format($this->iva, 2, ',', '.') . '%';
    }

    public function getMontoIvaAttribute()
    {
        return ($this->iva*$this->precio)/100.00;
    }

    public function getMontoIvaVenAttribute()
    {
        return number_format($this->getMontoIvaAttribute(), 2, ',', '.');
    }

/*    public function reservaSinIva()             // I
    {
        return round((($this->comision)/100)*$this->precio, 2);
    }
 */
    public function getReservaSinIvaAttribute()             // I
    {
        return round((($this->comision)/100)*$this->precio, 2);
    }

/*    public function reservaConIva()             // K
    {
        return round(((100+$this->iva)/100)*$this->reservaSinIva(), 2);
    }
 */
    public function getReservaConIvaAttribute()             // K
    {
        return round(((100+$this->iva)/100)*$this->getReservaSinIvaAttribute(), 2);
    }

    public function getReservaSinIvaVenAttribute() // Muestra col 'I'
    {
        return $this->agregarComaMoneda($this->getReservaSinIvaAttribute());
    }

    public function getReservaConIvaVenAttribute() // Muestra col 'K'
    {
        return $this->agregarComaMoneda($this->getReservaConIvaAttribute());
    }

    public function div()                       // Creado desde 'lados', col N
    {
        if (2==$this->lados) return 1;
        else return 2;
    }

/*    public function compartidoConIva()                 // 'L'
    {
        return round($this->getReservaConIvaAttribute()/$this->div(), 2);
    }
 */
    public function getCompartidoConIvaAttribute()                 // 'L'
    {
        return round($this->getReservaConIvaAttribute()/$this->div(), 2);
    }

    public function getCompartidoConIvaVenAttribute()  // 'L'
    {
        return $this->agregarComaMoneda($this->getCompartidoConIvaAttribute());
    }

/*    public function compartidoSinIva()              // 'M'
    {
        return round($this->getReservaSinIvaAttribute()/$this->div(), 2);
    }
 */
    public function getCompartidoSinIvaAttribute()              // 'M'
    {
        return round($this->getReservaSinIvaAttribute()/$this->div(), 2);
    }

    public function getCompartidoSinIvaVenAttribute()  // 'M'
    {
        return $this->agregarComaMoneda($this->getCompartidoSinIvaAttribute());
    }

    public function getFranquiciaReservadoSinIvaAttribute()     // 'O'
    {
	    $factor = $this->porc_franquicia/100;

        $valor = $factor*$this->getCompartidoSinIvaAttribute();
        return round($valor, 2);
    }

    public function getFranquiciaReservadoSinIvaVenAttribute()     // 'O'
    {
        return $this->agregarComaMoneda($this->getFranquiciaReservadoSinIvaAttribute());
    }

    public function getFranquiciaReservadoConIvaAttribute()                 // 'P'
    {
	    $factor = $this->porc_franquicia/100;

        return round($factor*$this->getCompartidoConIvaAttribute(), 2);
    }

    public function getFranquiciaReservadoConIvaVenAttribute()     // 'P'
    {
        return $this->agregarComaMoneda($this->getFranquiciaReservadoConIvaAttribute());
    }

    public function getReportadoCasaNacionalPAttribute()        // 'R'
    {
        return number_format($this->reportado_casa_nacional, 2, ',', '.') . '%';
    }

    public function getFranquiciaPagarReportadaAttribute()                  // 'Q'
    {
            $factor = ($this->porc_franquicia/100)*($this->reportado_casa_nacional/100);
            return round(($factor*$this->precio)/$this->div(), 2);
    }

    public function getFranquiciaPagarReportadaVenAttribute()      // 'Q'
    {
        return $this->agregarComaMoneda($this->getFranquiciaPagarReportadaAttribute());
    }

    public function getPorcRegaliaPAttribute()
    {
        return number_format($this->porc_regalia, 2, ',', '.') . '%';
    }

    public function getRegaliaAttribute()                                   // 'S'
    {
        $factor = $this->porc_regalia/100;

        return round($factor*$this->getFranquiciaPagarReportadaAttribute(), 2);
    }

    public function getRegaliaVenAttribute()                       // 'S'
    {
        return $this->agregarComaMoneda($this->getRegaliaAttribute());
    }

    public function getSanaf5PorCientoAttribute()                          // 'T'
    {
        $factor1 = 20.0/100;
        $factor2 = 0.1/100;
        $monto1 = $factor1*$this->getFranquiciaPagarReportadaAttribute();
        $monto2 = $factor2*$this->getReservaSinIvaAttribute()/$this->div();

        return round(($monto1 - $monto2), 2);
    }

    public function getSanaf5PorCientoVenAttribute()               // 'T'
    {
        return $this->agregarComaMoneda($this->getSanaf5PorCientoAttribute());
    }

    public function getOficinaBrutoRealAttribute()                      // 'U' = 'L' - 'Q'
    {
        $valor = $this->getCompartidoConIvaAttribute() -
                $this->getFranquiciaPagarReportadaAttribute(); // L - Q
        return round($valor, 2);
    }

    public function getOficinaBrutoRealVenAttribute()              // 'U' = 'L' - 'Q'
    {
        return $this->agregarComaMoneda($this->getOficinaBrutoRealAttribute());
    }

    public function getBaseHonorariosSociosAttribute()              // 'V' = 'L' - 'P'
    {
        return round($this->getCompartidoConIvaAttribute() -
                        $this->getFranquiciaReservadoConIvaAttribute(), 2);
    }

    public function getBaseHonorariosSociosVenAttribute()          // 'V' = 'L' - 'P'
    {
        return $this->agregarComaMoneda($this->getBaseHonorariosSociosAttribute());
    }

    public function getBaseParaHonorariosAttribute()                // 'W' = 'M' - 'O'
    {
        $valor = $this->getCompartidoSinIvaAttribute() -
                $this->getFranquiciaReservadoSinIvaAttribute();

        if (0 == $this->iva) $valor /= ((100 + $this->IVA)/100);
        return round($valor, 2);
    }

    public function getBaseParaHonorariosVenAttribute()          // 'W' = 'M' - 'O'
    {
        return $this->agregarComaMoneda($this->getBaseParaHonorariosAttribute());
    }

    public function getPorcCaptadorPrbrPAttribute()
    {
        return $this->porc_captador_prbr . '%';
    }

    public function getCaptadorPrbrAttribute()          	    // 'X' = % * 'U' o exp('W')
    {
        $factor1 = $this->porc_captador_prbr/100;
        $factor2 = 0.01 * $factor1;					// 0,2%

        if ((1 < $this->asesor_captador_id) and ($this->captador->socio))
            $valor = $factor1 * $this->getBaseHonorariosSociosAttribute();
        else {
            $bph = $this->getBaseParaHonorariosAttribute();
            $valor = ($factor1 * $bph) - ($factor2 * $bph) +
                        ($factor1 * $bph * ($this->IVA/100.00));
        }
        return round($valor, 2);
    }

    public function getCaptadorPrbrVenAttribute()          	// 'X' = % * 'U' o exp('W')
    {
        return $this->agregarComaMoneda($this->getCaptadorPrbrAttribute());
    }

    public function getPorcGerentePAttribute()
    {
        return $this->porc_gerente . '%';
    }

    public function getGerenteAttribute()          		                // 'Y' = % * 'U'
    {
        $factor1 = $this->porc_gerente/100;

        $valor = $factor1 * $this->getBaseHonorariosSociosAttribute();
        return round($valor, 2);
    }

    public function getGerenteVenAttribute()          		// 'Y' = % * 'U'
    {
        return $this->agregarComaMoneda($this->getGerenteAttribute());
    }

    public function getPorcCerradorPrbrPAttribute()
    {
        return $this->porc_cerrador_prbr . '%';
    }

    public function getCerradorPrbrAttribute()          	        // 'Z' = % * 'U' o exp('W')
    {
        $factor1 = $this->porc_cerrador_prbr/100;
        $factor2 = 0.002;					// 0,2%

        if ((1 < $this->asesor_cerrador_id) and ($this->cerrador->socio))
            $valor = $factor1 * $this->getBaseHonorariosSociosAttribute();
        else {
            $bph = $this->getBaseParaHonorariosAttribute();
            $valor = ($factor1 * $bph) - ($factor2 * $bph) +
                        ($factor1 * $bph * ($this->IVA/100.00));
        }
        return round($valor, 2);
    }

    public function getCerradorPrbrVenAttribute()          	// 'Z' = % * 'U' o exp('W')
    {
        return $this->agregarComaMoneda($this->getCerradorPrbrAttribute());
    }

    public function getBonificacionesAttribute()          		        // 'AA' = % * 'W'
    {
        $factor1 = $this->porc_bonificacion/100;
        $bph = $this->getBaseParaHonorariosAttribute();

        $valor = $factor1 * $bph;
        return round($valor, 2);
    }

    public function getBonificacionesVenAttribute()          		// 'AA' = % * 'W'
    {
        $val = $this->getBonificacionesAttribute();

        return $this->agregarComaMoneda($val);
    }

    public function getComisionBancariaVenAttribute()          		// 'AB'
    {
        if ($this->comision_bancaria) $val = $this->comision_bancaria;
        else $val = 0.00;

        return $this->agregarComaMoneda($val);
    }

    public function getIngresoNetoOficinaAttribute()                    // 'AC' = L - Q - X - Y - Z
    {
        $neto = $this->getCompartidoConIvaAttribute() - $this->getFranquiciaPagarReportadaAttribute() -
                $this->getCaptadorPrbrAttribute() - $this->getGerenteAttribute() -
                $this->getCerradorPrbrAttribute() - $this->getBonificacionesAttribute() -
                $this->comision_bancaria;
        return round($neto, 2);
    }

    public function getIngresoNetoOficinaVenAttribute()        // 'AC' = L - Q - X - Y - Z
    {
        return $this->agregarComaMoneda($this->getIngresoNetoOficinaAttribute());
    }

    public function precioVentaReal()
    {
        $factor1 = 100.0/($this->IVA + 100.0);
        $factor2 = 100.0/$this->COMISION;

        if (($this->COMISION == $this->comision) and ($this->IVA == $this->iva))
            $valor = $this->precio;
        else
            $valor = $this->getReservaConIvaAttribute() * $factor1 * $factor2;
        return round($valor, 2);
    }

    public function getPvrCaptadorPrbrAttribute()          	    // 'X' = % * 'U' o exp('W')
    {
        if (1 == $this->asesor_captador_id) $valor = 0.00;
        else $valor = $this->precioVentaReal()/2;
        return round($valor, 2);
    }

    public function getPvrCaptadorPrbrVenAttribute()
    {
        return $this->agregarComaMoneda($this->getPvrCaptadorPrbrAttribute());
    }

    public function getPvrCerradorPrbrAttribute()          	    // 'X' = % * 'U' o exp('W')
    {
        if (1 == $this->asesor_cerrador_id) $valor = 0.00;
        else $valor = $this->precioVentaReal()/2;
        return round($valor, 2);
    }

    public function getPrecioVentaRealAttribute()
    {
        return $this->getPvrCaptadorPrbrAttribute() +
                $this->getPvrCerradorPrbrAttribute();
    }

    public function getPrecioVentaRealVenAttribute()
    {
        return $this->agregarComaMoneda($this->getPrecioVentaRealAttribute());
    }

    public function getPvrCerradorPrbrVenAttribute()
    {
        return $this->agregarComaMoneda($this->getPvrCerradorPrbrAttribute());
    }

    public function getPuntosAttribute()
    {
        $puntos = $this->getOficinaBrutoRealAttribute();
        return round($puntos, 2);
    }

    public function getPuntosVenAttribute()
    {
        return $this->numeroVen($this->getPuntosAttribute(), 2);
    }

    public function getPuntosCaptadorAttribute()
    {
        if ((1 < $this->asesor_captador_id) and (!$this->captador->socio))
            $puntosCaptador = (($this->PORC_ASESORES/100.0)/$this->lados) *
                                $this->getOficinaBrutoRealAttribute();
        else $puntosCaptador = 0.00;

        return round($puntosCaptador, 2);
    }

    public function getPuntosCaptadorVenAttribute()
    {
        return $this->numeroVen($this->getPuntosCaptadorAttribute(), 2);
    }

    public function getPuntosCerradorAttribute()
    {
        if ((1 < $this->asesor_cerrador_id) and (!$this->cerrador->socio))
            $puntosCerrador = (($this->PORC_ASESORES/100.0)/$this->lados) * $this->getOficinaBrutoRealAttribute();    // 40% puntos cerrador
        else $puntosCerrador = 0.00;

        return round($puntosCerrador, 2);
    }

    public function getPuntosCerradorVenAttribute()
    {
        return $this->numeroVen($this->getPuntosCerradorAttribute(), 2);
    }

    public function getPuntosAsesorAttribute()
    {
        $puntosAsesor = $this->getPuntosCaptadorAttribute() + $this->getPuntosCerradorAttribute();
        return round($puntosAsesor, 2);
    }

    public function getPuntosAsesorVenAttribute()
    {
        return $this->numeroVen($this->getPuntosAsesorAttribute(), 2);
    }

    public function getReporteCasaNacionalVenAttribute()
    {
        if (is_numeric($this->reporte_casa_nacional))
            return number_format($this->reporte_casa_nacional, 0, ',', '.');
        else return 'No suministrado';
    }

    public function getEstatusC21AlfaAttribute()
    {
        if ('V' == $this->estatus_sistema_c21) return 'Vendido';
	    elseif ('P' == $this->estatus_sistema_c21) return 'Pendiente';
	    elseif ('A' == $this->estatus_sistema_c21) return 'Activo';
	    else return 'Nulo';
    }

    public function getObservacionAttribute()
    {
        $estatus = $this->estatus;
        $reserva = $this->fecha_reserva;
        $firma   = $this->fecha_firma;
        $captador = $this->asesor_captador_id;
        $cerrador = $this->asesor_cerrador_id;
        $otcaptador = $this->asesor_captador;
        $otcerrador = $this->asesor_cerrador;
        $prop = 'Propiedad ';
        if (('P' == $estatus) or ('C' == $estatus))
            $venta = $prop . (('V' == $this->negociacion)?'vendida':'alquilada');
        else $venta = False;
        if ($venta) {
            if (is_null($reserva))
                $obs = $venta . ' sin <u>fecha de reserva</u>';
            if (is_null($firma)) {
                if (isset($obs)) $obs .= ', ni la <u>fecha de la firma</u>?';
                else $obs = $venta . ' sin la <u>fecha de la firma</u>?';
            } else if (isset($obs)) $obs .= '?';
            if ((1 == $captador) and (is_null($otcaptador) or ('' == $otcaptador))) {
                if (isset($obs)) $obs .= '<br>';
                else $obs = $venta . ' ';
                $obs .= 'no tiene asignado el <u>asesor captador</u>?';
            }
            if ((1 == $cerrador) and (is_null($otcerrador) or ('' == $otcerrador))) {
                if (isset($obs)) $obs .= '<br>';
                else $obs = $venta . ' ';
                $obs .= 'no tiene asignado el <u>asesor cerrador</u>';
            }
        } else if ('A' == $estatus) {
            $prop .= '<u>Activa</u>';
            if (!is_null($reserva))
                $obs = $prop . ' con <u>fecha de reserva</u>';
            if (!is_null($firma)) {
                if (isset($obs)) $obs .= 'y <u>fecha de la firma</u>?';
                else $obs = $prop . ' con <u>fecha de la firma</u>?';
            } else if (isset($obs)) $obs .= '?';
        } else if ('I' == $estatus) {
            $prop = '<u>Inmueble pendiente</u>';
            if (is_null($reserva))
                $obs = $prop . ' sin <u>fecha de reserva</u>';
            if (!is_null($firma)) {
                if (isset($obs)) $obs .= 'y con <u>fecha de la firma</u>?';
                else $obs = $prop . ' con <u>fecha de la firma</u>?';
            } else if (isset($obs)) $obs .= '?';
            if ((1 == $captador) and (is_null($otcaptador) or ('' == $otcaptador))) {
                if (isset($obs)) $obs .= '<br>';
                else $obs = $prop . ' ';
                $obs = 'no tiene asignado el <u>asesor captador</u>';
            }
        }
        return $obs??False;
    }

    public function getImagenesAttribute() {    // Arreglo de extensiones de imagen de propiedad para cada generar una sola query, pero asi uso nuevos metodos.
        $imagenes = [];
        $extensiones = ['jpeg', 'jpg', 'gif', 'png', 'svg'];
        $nombreBaseImagen = $this->id . '_' . $this->codigo;
        for ($i = 0; $i <= 20; $i++) {
            foreach ($extensiones as $ext) {
                //$arreglo = [];
                if (Storage::exists(self::DIR_IMG . "/{$nombreBaseImagen}-{$i}.{$ext}")) {
                    //$arreglo[$i] = $ext;
                    //$imagenes[] = $arreglo;
                    $imagenes[$i] = $ext;
                    break;
                }
            }
        };
        return $imagenes;
    }

    public static function sumaXAsesor($idAsesor, $tipoAsesor, $fecha='fecha_firma',
                                        $fecha_desde=null, $fecha_hasta=null)
    {
        if(null == $fecha_desde)
            $fecha_desde = (new Carbon(Propiedad::min($fecha, Fecha::$ZONA)))->startOfDay();
        if(null == $fecha_hasta)
            $fecha_hasta = (new Carbon(Propiedad::max($fecha, Fecha::$ZONA)))->endOfDay();
        return self::whereIn('estatus', ['P', 'C'])->where('asesor_' . $tipoAsesor . '_id', $idAsesor)
                     ->get()->sum($tipoAsesor . '_prbr');
    }

    public static function ladosXMes($fecha='fecha_firma',
                                    $fecha_desde=null, $fecha_hasta=null, $asesor=0)
    {
        if(null == $fecha_desde)
            $fecha_desde = (new Carbon(Propiedad::min($fecha, Fecha::$ZONA)))->startOfDay();
        if(null == $fecha_hasta)
            $fecha_hasta = (new Carbon(Propiedad::max($fecha, Fecha::$ZONA)))->endOfDay();
        If (0 == $asesor) {
            $signo = '>';
            $asesor = 1;
        }
        else $signo = '=';
 
        $sql = self::select(DB::raw('sum(lados) as lados'),
                                    DB::raw("YEAR($fecha) agno,
                                    MONTH($fecha) mes"))
                        ->whereIn('estatus', ['P', 'C'])
                        ->whereBetween($fecha, [$fecha_desde, $fecha_hasta])
                        ->where(function ($query) use ($asesor, $signo) {
                                $query->where('asesor_captador_id', $signo, $asesor)
                                        ->orWhere('asesor_cerrador_id', $signo, $asesor);
                        })
                        ->groupBy('agno', 'mes');
        return $sql;
    }

    public static function negociacionesXMes($fecha='fecha_firma',
                            $fecha_desde=null,$fecha_hasta=null, $asesor=0)
    {
        if(null == $fecha_desde)
            $fecha_desde = (new Carbon(Propiedad::min($fecha, Fecha::$ZONA)))->startOfDay();
        if(null == $fecha_hasta)
         $fecha_hasta = (new Carbon(Propiedad::max($fecha, Fecha::$ZONA)))->endOfDay();
        If (0 == $asesor) {
            $signo = '>';
            $asesor = 1;
        }
        else $signo = '=';
 
        $sql = self::select(DB::raw('count(*) as negociaciones'),
                                    DB::raw('YEAR(fecha_firma) agno,
                                    MONTH(fecha_firma) mes'))
                        ->whereIn('estatus', ['P', 'C'])
                        ->whereBetween($fecha, [$fecha_desde, $fecha_hasta])
                        ->where(function ($query) use ($asesor, $signo) {
                                $query->where('asesor_captador_id', $signo, $asesor)
                                        ->orWhere('asesor_cerrador_id', $signo, $asesor);
                        })
                        ->groupBy('agno', 'mes');
        return $sql;
    }

    public static function comisionXMes($fecha='fecha_firma',
                            $fecha_desde=null, $fecha_hasta=null, $asesor=0)
    {
        if(null == $fecha_desde) {
            $fecha_desde = (new Carbon(Propiedad::min($fecha, Fecha::$ZONA)))->startOfDay();
            $nulo = True;
        } else $nulo = False;
        if(null == $fecha_hasta) {
            $fecha_hasta = (new Carbon(Propiedad::max($fecha, Fecha::$ZONA)))->endOfDay();
            $nulo = True;
        } else $nulo = False;
        If (0 == $asesor) {
            $signo = '>';
            $asesor = 1;
        }
        else $signo = '=';
        $agnoInicial = date('Y', strtotime($fecha_desde));
        $mesInicial  = date('m', strtotime($fecha_desde));
        $agnoFinal   = date('Y', strtotime($fecha_hasta));
        $mesFinal    = date('m', strtotime($fecha_hasta));
 
        $arrRetorno   = [];
        if ($nulo) {
            $captado = self::where('asesor_captador_id', $signo, $asesor)
                            ->whereIn('estatus', ['P', 'C'])
                            ->whereNull($fecha)
                            ->get()->sum('captadorPrbr');
            $cerrado = self::where('asesor_cerrador_id', $signo, $asesor)
                            ->whereIn('estatus', ['P', 'C'])
                            ->whereNull($fecha)
                            ->get()->sum('cerradorPrbr');
            $arrRetorno[] = (object)[
                                'agno' => '',
                                'mes' => '',
                                'captado' => $captado,
                                'cerrado' => $cerrado,
                                'comision' => $captado + $cerrado,
                            ];
        }
        for ($agno = $agnoInicial; $agno <= $agnoFinal; $agno++) {
            for ($mes = $mesInicial; $mes <= 12; $mes++) {
                if (($agnoFinal == $agno) and ($mes > $mesFinal)) break;
                $captado = self::where('asesor_captador_id', $signo, $asesor)
                                ->whereIn('estatus', ['P', 'C'])
                                ->whereYear($fecha, $agno)
                                ->whereMonth($fecha, $mes)
                                ->get()->sum('captadorPrbr');
                $cerrado = self::where('asesor_cerrador_id', $signo, $asesor)
                                ->whereIn('estatus', ['P', 'C'])
                                ->whereYear($fecha, $agno)
                                ->whereMonth($fecha, $mes)
                                ->get()->sum('cerradorPrbr');
                $arrRetorno[] = (object)[
                                    'agno' => $agno,
                                    'mes' => (string)(int)$mes,
                                    'captado' => $captado,
                                    'cerrado' => $cerrado,
                                    'comision' => $captado + $cerrado,
                                ];
            }
        }
        return collect($arrRetorno);
    }

    public static function totales($propiedads, $valido=True, $cap=0, $cer=0)
    {
        $propiedades = clone $propiedads;               // Los query modifican el arreglo propiedades.
        if ($valido) $propiedades = $propiedades->whereIn('estatus', ['P', 'C']);
        $arrRetorno  = [];                              // Inicializo arreglo a retornar.

        $arrRetorno[] = $propiedades->count();      // # propiedades para vista propiedades.index.
        $arrRetorno[] = $propiedades->sum('precio'); // total precio para vista propiedades.index.
        $arrRetorno[] = (int)$propiedades->sum('lados'); // total lados.
        $arrRetorno[] = round($propiedades->get()->sum('compartido_con_iva'), 2); // total de un elemento calculado.
        $arrRetorno[] = round($propiedades->get()->sum('franquicia_reservado_sin_iva'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('franquicia_reservado_con_iva'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('franquicia_pagar_reportada'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('regalia'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('sanaf5_por_ciento'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('oficina_bruto_real'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('base_honorarios_socios'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('base_para_honorarios'), 2);
        $props = clone $propiedades;
        $arrRetorno[] = round($props->where('asesor_captador_id', '>', 1)
                                          ->get()->sum('captador_prbr'), 2);        // Indice = 12
        $arrRetorno[] = round($propiedades->get()->sum('gerente'), 2);
        $props = clone $propiedades;
        $arrRetorno[] = round($props->where('asesor_cerrador_id', '>', 1)
                                          ->get()->sum('cerrador_prbr'), 2);        // Indice = 14
        $arrRetorno[] = round($propiedades->get()->sum('bonificaciones'), 2);
        $arrRetorno[] = round($propiedades->sum('comision_bancaria'), 2);           // 'AB'.
        $arrRetorno[] = round($propiedades->get()->sum('ingreso_neto_oficina'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('precio_venta_real'), 2);    // Indice = 18
        $arrRetorno[] = round($propiedades->get()->sum('puntos'), 2);               // Indice = 19
/*        $proCap = clone $propiedades;
        $proCer = clone $propiedades;
        $arrRetorno[] = round($proCap->where('asesor_captador_id', '>', 1)
                                          ->get()->sum('pvr_captador_prbr'), 2) +
                        round($proCer->where('asesor_cerrador_id', '>', 1)
                                          ->get()->sum('pvr_cerrador_prbr'), 2);*/

        $props = clone $propiedades;                     // Los query modifican el arreglo propiedades.
        if (0 < $cap) {
            $tLadosCap = $props->where('asesor_captador_id', $cap)->count();
            $tCaptadorPrbrSel = round($props->where('asesor_captador_id', $cap) // Aunque aplica el 'where' de
                                            ->get()->sum('captador_prbr'), 2);   // la linea anterior. Por si acaso.
            $tPvrCaptadorPrbrSel = round($props->where('asesor_captador_id', $cap)
                                            ->get()->sum('pvr_captador_prbr'), 2);
            $tPuntosCaptadorSel = round($props->where('asesor_captador_id', $cap)
                                            ->get()->sum('puntos_captador'), 2);
        } else {
            $tLadosCap = $props->where('asesor_captador_id', '>', 1)->count();
            $tCaptadorPrbrSel = 0.00;
            $tPvrCaptadorPrbrSel = 0.00;
            $tPuntosCaptadorSel = 0.00;
        }
        $props = clone $propiedades;                     // Los query modifican el arreglo propiedades.
        if (0 < $cer) {
            $tLadosCer = $props->where('asesor_cerrador_id', $cer)->count();
            $tCerradorPrbrSel = round($props->where('asesor_cerrador_id', $cer) // Aunque aplica el 'where' de
                                            ->get()->sum('cerrador_prbr'), 2);   // la linea anterior. Por si acaso.
            $tPvrCerradorPrbrSel = round($props->where('asesor_cerrador_id', $cer)
                                            ->get()->sum('pvr_cerrador_prbr'), 2);
            $tPuntosCerradorSel = round($props->where('asesor_cerrador_id', $cap)
                                            ->get()->sum('puntos_cerrador'), 2);
        } else {
            $tLadosCer = $props->where('asesor_cerrador_id', '>', 1)->count();
            $tCerradorPrbrSel = 0.00;
            $tPvrCerradorPrbrSel = 0.00;
            $tPuntosCerradorSel = 0.00;
        }
        /*dd($arrRetorno[12], $arrRetorno[14], $arrRetorno[18], $tCaptadorPrbrSel,
                $tCerradorPrbrSel, $tLadosCap, $tLadosCer,
                $tCaptadorPrbrSel, $tCerradorPrbrSel,
                $tPvrCaptadorPrbrSel + $tPvrCerradorPrbrSel);*/
        array_push($arrRetorno, $tCaptadorPrbrSel, $tCerradorPrbrSel,
                    $tLadosCap, $tLadosCer, $tPvrCaptadorPrbrSel,
                    $tPvrCerradorPrbrSel, $tPuntosCaptadorSel, $tPuntosCerradorSel);
        //dd($arrRetorno);
        return $arrRetorno;
    }   // totales

    protected static function comparar($a, $b) {
        if ($a['id'] == $b['id']) {
            if ($a['sec'] == $b['sec']) return 0;
            return ($a['sec'] < $b['sec']) ? -1 : 1;
        }
        return ($a['id'] > $b['id']) ? -1 : 1;
    }
    public static function misPropiedades($files=null)
    {
        $propiedad = [];
        $files = $file??Storage::files(self::DIR_IMG);
// los nombre de archivo tienen que tener la estructura: {propiedad_id}_{codigo}[-{secuencia}].ext        
        foreach($files as $filename) {
            $n = preg_match('/^(\d{1,4})(?# id de la propiedad)_(\d+)(?# codigo de la propiedad)-(\d{1,2})(?# secuencia)\.([jpegifsvn]{3,4})(?# extension, puede ser: jpeg, jpg, gif, png o svg)$/',
                            basename($filename), $matches);
            if ($n) list($filename, $propiedad_id, $codigo, $sec, $ext) = $matches;  // $n === 1.
            else continue;                                                                      // $n === 0 o $n === false.
            try {
                $prop = self::findOrFail($propiedad_id);   // Si 'findOrFail' falla produce 'ModelNotFoundException'.
                if (Auth::check() and !Auth::user()->is_admin and
                    (Auth::user()->id != $prop->asesor_captador_id) and
                    (Auth::user()->id != $prop->asesor_cerrador_id)) continue;
                if ('A' != $prop->estatus) continue;        // La propiedad no esta activa.
                $todosNombreProp[] = [
                    'nombreImagen' => $filename,
                    'id' => $propiedad_id,
                    'codigo' => $codigo,
                    'sec' => $sec,
                    'asesor_id' => $prop->asesor_captador_id,
                    'nombre' => $prop->nombre,
                ];
                //if (50 <= count($todosNombreProp)) break;
            } catch (ModelNotFoundException $exception) {
            //} catch (\Exception $exception) {     // namespace '\'.
                //dd($todosNombreProp, $filename);
                report($exception);
                //continue;   // return back()->withError($exception->getMessage())->withInput();
            }
        }
        usort($todosNombreProp, "self::comparar");  // Ordenar por id, asc y sec, desc.
        foreach ($todosNombreProp as $nombre) {     // Suprime nombres duplicados, con diferentes sec, se escoge el de menor sec.
            $id = $nombre['id'];
            if (!isset($idant)) $nombreProp[] = $nombre;
            elseif ($id != $idant) $nombreProp[] = $nombre;
            $idant = $id;
            if (20 < count($todosNombreProp)) break;    // Solo mostrar las 20 propiedades más recientes.
        }
        return collect($nombreProp);
    }   // public static function misPropiedades($files=null)

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
        if (null == $this->updated_at) return '';
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

    public static function vencerPropiedad()
    {
        Propiedad::where('fecha_inicial', '<', (new Carbon(now()))->subDays(90))
                ->where('estatus', 'A')
                ->update(['estatus' => 'W']);
    }
// Las proximas funciones seran utilizadas para la conversion en json y toArray.
    public function getNegocAttribute() {
        return $this->negociacion;
    }
    public function getExcluAttribute() {
        return $this->exclusividad;
    }
    public function getHabitsAttribute() {
        return $this->habitaciones;
    }
    public function getDescrAttribute() {
        return $this->descripcion;
    }
    public function getDirecAttribute() {
        return $this->direccion;
    }
    public function getCodPosAttribute() {
        return $this->codigo_postal;
    }
    public function getPcFrqAttribute() {
        return $this->porc_franquicia;
    }
    public function getPcReCaNaAttribute() {
        return $this->reportado_casa_nacional;
    }
    public function getPcRegaAttribute() {
        return $this->porc_regalia;
    }
    public function getPcComAttribute() {
        return $this->porc_compartido;
    }
    public function getPcCapAttribute() {
        return $this->porc_captador_prbr;
    }
    public function getPcGerAttribute() {
        return $this->porc_gerente;
    }
    public function getPcCerAttribute() {
        return $this->porc_cerrador_prbr;
    }
    public function getPcBonifAttribute() {
        return $this->porc_bonificacion;
    }
    public function getComBancAttribute() {
        return $this->comision_bancaria;
    }
    public function getNroRecAttribute() {
        return $this->numero_recibo;
    }
    public function getAsCapIdAttribute() {
        return $this->asesor_captador_id;
    }
    public function getAsCapAttribute() {
        return $this->asesor_captador;
    }
    public function getAsCerIdAttribute() {
        return $this->asesor_cerrador_id;
    }
    public function getAsCerAttribute() {
        return $this->asesor_cerrador;
    }
    public function getPagGerAttribute() {
        return $this->pago_gerente;
    }
    public function getFactGerAttribute() {
        return $this->factura_gerente;
    }
    public function getPagAsesAttribute() {
        return $this->pago_asesores;
    }
    public function getFactAseAttribute() {
        return $this->factura_asesores;
    }
    public function getPagOtOfAttribute() {
        return $this->pago_otra_oficina;
    }
    public function getPagCaNaAttribute() {
        return $this->pagado_casa_nacional;
    }
    public function getEstaC21Attribute() {
        return $this->estatus_sistema_c21;
    }
    public function getRepCaNaAttribute() {
        return $this->reporte_casa_nacional;
    }
    public function getcomensAttribute() {
        return $this->comentarios;
    }
    public function getfactAySAttribute() {
        return $this->factura_AyS;
    }
    public function getResSIvaAttribute() {
        return $this->getReservaSinIvaAttribute();
    }
    public function getResCIvaAttribute() {
        return $this->getReservaConIvaAttribute();
    }
    public function getComCIvaAttribute() {
        return $this->getCompartidoConIvaAttribute();
    }
    public function getComSIvaAttribute() {
        return $this->getCompartidoSinIvaAttribute();
    }
    public function getFrqSIvaAttribute() {
        return $this->getFranquiciaReservadoSinIvaAttribute();
    }
    public function getFrqCIvaAttribute() {
        return $this->getFranquiciaReservadoConIvaAttribute();
    }
    public function getFrqPaReAttribute() {
        return $this->getFranquiciaPagarReportadaAttribute();
    }
    public function getSanaf5pcAttribute() {
        return $this->getSanaf5PorCientoAttribute();
    }
    public function getOfBrReAttribute() {
        return $this->getOficinaBrutoRealAttribute();
    }
    public function getBaHoSocAttribute() {
        return $this->getBaseHonorariosSociosAttribute();
    }
    public function getBaPaHonAttribute() {
        return $this->getBaseParaHonorariosAttribute();
    }
    public function getBonificAttribute() {
        return $this->getBonificacionesAttribute();
    }
    public function getCapPrbrAttribute() {
        return $this->getCaptadorPrbrAttribute();
    }
    public function getCerPrbrAttribute() {
        return $this->getCerradorPrbrAttribute();
    }
    public function getIngNeOfAttribute() {
        return $this->getIngresoNetoOficinaAttribute();
    }
    public function getPvrCapAttribute() {
        return $this->getPvrCaptadorPrbrAttribute();
    }
    public function getPvrCerAttribute() {
        return $this->getPvrCerradorPrbrAttribute();
    }
    public function getPrVeReAttribute() {
        return $this->getPrecioVentaRealAttribute();
    }
    public function getPtsCapAttribute() {
        return $this->getPuntosCaptadorAttribute();
    }
    public function getPtsCerAttribute() {
        return $this->getPuntosCerradorAttribute();
    }
    public function getFpResAttribute() {
        return $this->forma_pago_reserva_id;
    }
    public function getFpFirAttribute() {
        return $this->forma_pago_firma_id;
    }
    public function getFpGerAttribute() {
        return $this->forma_pago_gerente_id;
    }
    public function getFpCapAttribute() {
        return $this->forma_pago_captador_id;
    }
    public function getFactCapAttribute() {
        return $this->factura_captador;
    }
    public function getFpCerAttribute() {
        return $this->forma_pago_cerrador_id;
    }
    public function getFactCerAttribute() {
        return $this->factura_cerrador;
    }
    public function getFpOtrAttribute() {
        return $this->forma_pago_otra_oficina_id;
    }
    public function getFactOtrAttribute() {
        return $this->factura_otra_oficina;
    }
}
