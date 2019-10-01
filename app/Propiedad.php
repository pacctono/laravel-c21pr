<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;     // PC
use App\MisClases\Fecha;

class Propiedad extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'codigo', 'fecha_reserva', 'fecha_firma', 'negociacion', 'nombre',
        'tipo_id', 'metraje', 'habitaciones', 'banos', 'niveles', 'puestos',
        'anoc', 'caracteristica_id', 'descripcion', 'direccion', 'ciudad_id',
        'codigo_postal', 'municipio_id', 'estado_id', 'cliente_id',
        'estatus', 'user_id', 'moneda', 'precio', 'comision', 'iva',
        'lados', 'porc_franquicia', 'porc_comision', 'reportado_casa_nacional',
        'porc_regalia', 'porc_captador_prbr', 'captador_prbr', 'porc_gerente',
        'porc_cerrador_pbr', 'cerrador_pbr', 'porc_bonificacion',
        'comision_bancaria', 'numero_recibo', 'asesor_captador_id',
        'asesor_captador', 'asesor_cerrador_id', 'asesor_cerrador',
        'pago_gerente', 'factura_gerente', 'pago_asesores', 'factura_asesores',
        'pagado_casa_nacional', 'estatus_sistema_c21', 'reporte_casa_nacional',
        'comentarios', 'factura_AyS',
        'user_actualizo', 'user_borro'
    ];
    protected $dates = [        // Mutan a una instancia de Carbon.
        'fecha_reserva', 'fecha_firma',
        'deleted_at', 'created_at', 'updated_at'
    ];
    public static $lineasXPagina = 10;      // Puede definirse como constante: const LINEASXPAGINA = 10;
    protected $META_2019 = 400000;
    protected $COMISION = 5.00;
    protected $IVA = 16.00;
    public $mMoZero = true;
    public $espMonB = true;
    public $monedaB = true;
    public $espDosP = true;
    public $dosPunB = true;

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
        if (null == $this->fecha_reserva) return '';
        return $this->fecha_reserva->format('Y-m-d');
    }

    public function getReservaEnAttribute()
    {
        if (null == $this->fecha_reserva) return '';
        return $this->fecha_reserva->format('d/m/Y');
    }

    public function getReservaDiaSemanaAttribute()
    {
        if (null == $this->fecha_reserva) return '';
        return substr(Fecha::$diaSemana[$this->fecha_reserva->timezone(Fecha::$ZONA)
                        ->dayOfWeek], 0, 3);
    }

    public function getReservaConHoraAttribute()
    {
        if (null == $this->fecha_reserva) return '';
        return $this->fecha_reserva->timezone(Fecha::$ZONA)->format('d/m/Y h:i a');
    }

    public function getFechaFirmaBdAttribute()
    {
        if (null == $this->fecha_firma) return '';
        return $this->fecha_firma->format('Y-m-d');
    }

    public function getFirmaEnAttribute()
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

    public function getNegociacionAlfaAttribute()
    {
        if ('V' == $this->negociacion) return 'Venta';
	    elseif ('A' == $this->negociacion) return 'Alqui';
	    else return 'Nulo';
    }

    public function getEstatusAlfaAttribute()
    {
        if ('A' == $this->estatus) return 'Activo';
        elseif ('I' == $this->estatus) return 'Inmueble pendiente';
	    elseif ('P' == $this->estatus) return 'Pagos pendientes';
	    elseif ('C' == $this->estatus) return 'Inmueble cerrado y pagos realizados';
	    elseif ('S' == $this->estatus) return 'Negociacion caida';
	    else return 'Nulo';
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
        return $this->getPvrCaptadorPrbrAttribute() + $this->getPvrCerradorPrbrAttribute();
    }

    public function getPrecioVentaRealVenAttribute()
    {
        return $this->agregarComaMoneda($this->getPrecioVentaRealAttribute());
    }

    public function getPvrCerradorPrbrVenAttribute()
    {
        return $this->agregarComaMoneda($this->getPvrCerradorPrbrAttribute());
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

    public static function ladosXMes($fecha='fecha_firma', $fecha_desde=null, $fecha_hasta=null, $user=0)
    {
        if(null == $fecha_desde)
            $fecha_desde = (new Carbon(Propiedad::min($fecha, Fecha::$ZONA)))->startOfDay();
        if(null == $fecha_hasta)
            $fecha_hasta = (new Carbon(Propiedad::max($fecha, Fecha::$ZONA)))->endOfDay();
        If (0 == $user) {
            $signo = '>';
            $user = 1;
        }
        else $signo = '=';
 
        $sql = self::select(DB::raw('sum(lados) as lados'),
                                    DB::raw('YEAR(fecha_firma) agno,
                                    MONTH(fecha_firma) mes'))
                        ->whereIn('estatus', ['P', 'C'])
                        ->whereBetween($fecha, [$fecha_desde, $fecha_hasta])
                        ->where(function ($query) use ($user, $signo) {
                                $query->where('asesor_captador_id', $signo, $user)
                                        ->orWhere('asesor_cerrador_id', $signo, $user);
                        })
                        ->groupBy('agno', 'mes');
        return $sql;
    }

    public static function negociacionesXMes($fecha='fecha_firma', $fecha_desde=null, $fecha_hasta=null, $user=0)
    {
        if(null == $fecha_desde)
            $fecha_desde = (new Carbon(Propiedad::min($fecha, Fecha::$ZONA)))->startOfDay();
        if(null == $fecha_hasta)
         $fecha_hasta = (new Carbon(Propiedad::max($fecha, Fecha::$ZONA)))->endOfDay();
        If (0 == $user) {
            $signo = '>';
            $user = 1;
        }
        else $signo = '=';
 
        $sql = self::select(DB::raw('count(*) as negociaciones'),
                                    DB::raw('YEAR(fecha_firma) agno,
                                    MONTH(fecha_firma) mes'))
                        ->whereIn('estatus', ['P', 'C'])
                        ->whereBetween($fecha, [$fecha_desde, $fecha_hasta])
                        ->where(function ($query) use ($user, $signo) {
                                $query->where('asesor_captador_id', $signo, $user)
                                        ->orWhere('asesor_cerrador_id', $signo, $user);
                        })
                        ->groupBy('agno', 'mes');
        return $sql;
    }

    public static function comisionXMes($fecha='fecha_firma', $fecha_desde=null, $fecha_hasta=null, $user=0)
    {
        if(null == $fecha_desde) {
            $fecha_desde = (new Carbon(Propiedad::min($fecha, Fecha::$ZONA)))->startOfDay();
            $nulo = True;
        } else $nulo = False;
        if(null == $fecha_hasta) {
            $fecha_hasta = (new Carbon(Propiedad::max($fecha, Fecha::$ZONA)))->endOfDay();
            $nulo = True;
        } else $nulo = False;
        If (0 == $user) {
            $signo = '>';
            $user = 1;
        }
        else $signo = '=';
        $agnoInicial = date('Y', strtotime($fecha_desde));
        $mesInicial  = date('m', strtotime($fecha_desde));
        $agnoFinal   = date('Y', strtotime($fecha_hasta));
        $mesFinal    = date('m', strtotime($fecha_hasta));
 
        $arrRetorno   = [];
        if ($nulo) {
            $captado = self::where('asesor_captador_id', $signo, $user)
                            ->whereIn('estatus', ['P', 'C'])
                            ->whereNull($fecha)
                            ->get()->sum('captadorPrbr');
            $cerrado = self::where('asesor_cerrador_id', $signo, $user)
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
                $captado = self::where('asesor_captador_id', $signo, $user)
                                ->whereIn('estatus', ['P', 'C'])
                                ->whereYear($fecha, $agno)
                                ->whereMonth($fecha, $mes)
                                ->get()->sum('captadorPrbr');
                $cerrado = self::where('asesor_cerrador_id', $signo, $user)
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

    public static function columnas()
    {
        $valores = DB::select("SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE,
                                        DATA_TYPE, COLUMN_TYPE, COLUMN_COMMENT
                               FROM   INFORMATION_SCHEMA.COLUMNS
                               WHERE  TABLE_NAME = 'propiedads'");
        $cols = Array();
        foreach($valores as $fila) {
            if ('enum' == $fila->DATA_TYPE) {
                $tipos = explode(',',           // Crea arreglo de los valores 'enum' separados por ,
                    str_replace('"', '',                        // Elimina "s
                        str_replace("'", "",                    // Elimina 's
                            substr($fila->COLUMN_TYPE, 5, -1)   // Elimina enum( y )
                        )
                    )
                );
                if ((1 < strlen($fila->COLUMN_COMMENT)) and
                    strpos($fila->COLUMN_COMMENT, ',', 1)) {
                    $come = explode(',', $fila->COLUMN_COMMENT);
                } else $come = $tipos;        // Esto solo debe ocurrir si la col es enum.
                $tipo = Array();
                for ($j=0; $j<count($tipos); $j++) {
                    if ($j < count($come)) $tipo[$tipos[$j]] = $come[$j];
                    else $tipo[$tipos[$j]] = 'S/DESC';
                }
            } else {
                $tipo = $fila->COLUMN_TYPE;
                $come = $fila->COLUMN_COMMENT;
            }
            $cols[$fila->COLUMN_NAME] = array(
                'tipo' => $fila->DATA_TYPE,
                'xdef' => $fila->COLUMN_DEFAULT,
                'opcion' => $tipo,
                'come' => $come,
            );
        }
        return $cols;
    }       // Final del metodo columnas.

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
                                          ->get()->sum('captador_prbr'), 2);            // Indice = 12
        $arrRetorno[] = round($propiedades->get()->sum('gerente'), 2);
        $props = clone $propiedades;
        $arrRetorno[] = round($props->where('asesor_cerrador_id', '>', 1)
                                          ->get()->sum('cerrador_prbr'), 2);            // Indice = 14
        $arrRetorno[] = round($propiedades->get()->sum('bonificaciones'), 2);
        $arrRetorno[] = round($propiedades->sum('comision_bancaria'), 2);               // 'AB'.
        $arrRetorno[] = round($propiedades->get()->sum('ingreso_neto_oficina'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('precio_venta_real'), 2);        // Indice = 18
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
        } else {
            $tLadosCap = $props->where('asesor_captador_id', '>', 1)->count();
            $tCaptadorPrbrSel = 0.00;
            $tPvrCaptadorPrbrSel = 0.00;
        }
        $props = clone $propiedades;                     // Los query modifican el arreglo propiedades.
        if (0 < $cer) {
            $tLadosCer = $props->where('asesor_cerrador_id', $cer)->count();
            $tCerradorPrbrSel = round($props->where('asesor_cerrador_id', $cer) // Aunque aplica el 'where' de
                                            ->get()->sum('cerrador_prbr'), 2);   // la linea anterior. Por si acaso.
            $tPvrCerradorPrbrSel = round($props->where('asesor_cerrador_id', $cer)
                                            ->get()->sum('pvr_cerrador_prbr'), 2);
        } else {
            $tLadosCer = $props->where('asesor_cerrador_id', '>', 1)->count();
            $tCerradorPrbrSel = 0.00;
            $tPvrCerradorPrbrSel = 0.00;
        }
        /*dd($arrRetorno[12], $arrRetorno[14], $arrRetorno[18], $tCaptadorPrbrSel,
                $tCerradorPrbrSel, $tLadosCap, $tLadosCer,
                $tCaptadorPrbrSel, $tCerradorPrbrSel,
                $tPvrCaptadorPrbrSel + $tPvrCerradorPrbrSel);*/
        array_push($arrRetorno, $tCaptadorPrbrSel, $tCerradorPrbrSel,
            $tLadosCap, $tLadosCer, $tPvrCaptadorPrbrSel, $tPvrCerradorPrbrSel);
        //dd($arrRetorno);
        return $arrRetorno;
    }   // totales

    public static function grabarArchivo()
    {
        function nulo($valor, $def='') {
            if (is_null($valor)) {
                $valor = $def;
            }
            return $valor;
        }
        $users   = User::get();                     // Todos los usuarios (asesores).
        $users[0]['name'] = 'Asesor otra oficina';
        $propiedades = Propiedad::where('id', '>', 0);   // condición dummy, solo para continuar armando la consulta.

        $totales = '';
/*
 * Calculo de totales por 'asesor' (user).
 */
        foreach ($users as $user) {
            $props = clone $propiedades;               // Los query modifican el arreglo propiedades.
            $props = $props->where('asesor_captador_id', $user->id)
                        ->orWhere('asesor_cerrador_id', $user->id);
            $arreglo = self::totales($props, True, $user->id, $user->id);
            array_unshift($arreglo, 'A', $user->id);
            $totales .= json_encode($arreglo) . "\n";
        }
/*
 * Calculo de totales por mes.
 */
        $fecha = DB::select("SELECT DATE_FORMAT(fecha_firma, '%Y') AS Agno,
                                    DATE_FORMAT(fecha_firma, '%m') AS Mes
                             FROM   propiedads
                            GROUP BY 1, 2");
        $anoMes = Array();
        foreach($fecha as $fila) {
            if (array_key_exists($fila->Agno, $anoMes))
                $anoMes[$fila->Agno][] = $fila->Mes;
            else $anoMes[$fila->Agno][] = $fila->Mes;
        }
        foreach($anoMes as $agno=>$meses) {
            foreach ($meses as $mes) {
                $props = clone $propiedades;               // Los query modifican el arreglo propiedades.
                if (is_null($agno) or is_null($mes)) {
                    $props = $props->whereNull('fecha_firma');
                } else {
                    $props = $props->whereYear('fecha_firma', $agno)
                                    ->whereMonth('fecha_firma', $mes);
                }
                $arreglo = self::totales($props);
                if (is_null($agno) or is_null($mes)) {
                    array_unshift($arreglo, 'M', FECHA::hoy()->format('Y') . '-' . '00');
                } else {
                    array_unshift($arreglo, 'M', $agno . '-' . $mes);
                }
                $totales .= json_encode($arreglo) . "\n";
            }
        }
        //dd($totales);
/*
 * Calculo de totales por 'estatus'. cols es usado, al final, para grabar las tablas.
 */
        $cols = self::columnas();
        $estatus = $cols['estatus']['opcion'];
        foreach ($estatus as $op=>$desc) {
            $props = clone $propiedades;               // Los query modifican el arreglo propiedades.
            $props = $props->where('estatus', $op);
            $arreglo = self::totales($props, False);
            array_unshift($arreglo, 'E', $op);
            $totales .= json_encode($arreglo) . "\n";
        }
        //dd($totales);
/*
 * Calculo de totales por 'asesor' (user) y mes.
 */
        foreach ($users as $user) {
            foreach($anoMes as $agno=>$meses) {
                foreach ($meses as $mes) {
                    $props = clone $propiedades;               // Los query modifican el arreglo propiedades.
                    if (is_null($agno) or is_null($mes)) {
                        $props = $props->where(function ($Q) use ($user) {
                                            $Q->where('asesor_captador_id', $user->id)
                                                ->orWhere('asesor_cerrador_id', $user->id);
                                        })
                                        ->whereNull('fecha_firma');
                    } else {
                        $props = $props->where(function ($Q) use ($user) {
                                            $Q->where('asesor_captador_id', $user->id)
                                                ->orWhere('asesor_cerrador_id', $user->id);
                                        })
                                        ->whereYear('fecha_firma', $agno)
                                        ->whereMonth('fecha_firma', $mes);
                    }
                    $arreglo = self::totales($props, True, $user->id, $user->id);
                    if (is_null($agno) or is_null($mes)) {
                        array_unshift($arreglo, 'AM', $user->id,
                                            FECHA::hoy()->format('Y') . '-' . '00');
                    } else {
                        array_unshift($arreglo, 'AM', $user->id, $agno . '-' . $mes);
                    }
                    $totales .= json_encode($arreglo) . "\n";
                }
            }
        }
        //dd($totales);
/*
 * Calculo de totales por mes y asesor (user).
 */
        foreach($anoMes as $agno=>$meses) {
            foreach ($meses as $mes) {
                foreach ($users as $user) {
                    $props = clone $propiedades;               // Los query modifican el arreglo propiedades.
                    if (is_null($agno) or is_null($mes)) {
                        $props = $props->where(function ($Q) use ($user) {
                                            $Q->where('asesor_captador_id', $user->id)
                                                ->orWhere('asesor_cerrador_id', $user->id);
                                        })
                                        ->whereNull('fecha_firma');
                    } else {
                        $props = $props->where(function ($Q) use ($user) {
                                            $Q->where('asesor_captador_id', $user->id)
                                                ->orWhere('asesor_cerrador_id', $user->id);
                                        })
                                        ->whereYear('fecha_firma', $agno)
                                        ->whereMonth('fecha_firma', $mes);
                    }
                    $arreglo = self::totales($props, True, $user->id, $user->id);
                    if (is_null($agno) or is_null($mes)) {
                        array_unshift($arreglo, 'MA',
                                            FECHA::hoy()->format('Y') . '-' . '00', $user->id);
                    } else {
                        array_unshift($arreglo, 'MA', $agno . '-' . $mes, $user->id);
                    }
                    $totales .= json_encode($arreglo) . "\n";
                }
            }
        }
        //dd($totales);
/*
 * Calculo de totales generales.
 */
        $arreglo = self::totales($propiedades);
        array_unshift($arreglo, 'T', 'T');
        $totales .= json_encode($arreglo) . "\n";
        $propiedades = $propiedades->get();
        $props       = '';
        foreach ($propiedades as $p) {
            $props .= json_encode(array ($p->id, $p->codigo, $p->reserva_en,
                        $p->firma_en, $p->negociacion, $p->nombre,
                        $p->tipo_id, $p->metraje, $p->habitaciones, $p->banos,
                        $p->niveles, $p->puestos, $p->anoc, $p->caracteristica_id,
                        $p->descripcion, $p->direccion, $p->ciudad_id, $p->codigo_postal,
                        $p->municipio_id, $p->estado_id, $p->cliente_id,
                        $p->estatus, $p->moneda, $p->precio, $p->comision,
                        $p->reserva_sin_iva, $p->iva, $p->reserva_con_iva,
                        $p->compartido_con_iva, $p->compartido_sin_iva,
                        $p->lados, $p->franquicia_reservado_sin_iva,
                        $p->franquicia_reservado_con_iva, $p->porc_franquicia,
                        $p->franquicia_pagar_reportada, $p->reportado_casa_nacional,
                        $p->porc_regalia, $p->porc_compartido, $p->regalia, $p->sanaf5_por_ciento,
                        $p->oficina_bruto_real, $p->base_honorarios_socios,
                        $p->base_para_honorarios, $p->asesor_captador_id,
                        $p->asesor_captador, $p->porc_captador_prbr, $p->captador_prbr,
                        $p->porc_gerente, $p->gerente, $p->asesor_cerrador_id,
                        $p->asesor_cerrador, $p->porc_cerrador_prbr, $p->cerrador_prbr,
                        $p->porc_bonificacion, $p->bonificaciones,
                        nulo($p->comision_bancaria, 0), $p->ingreso_neto_oficina,
                        $p->precio_venta_real, nulo($p->numero_recibo),
                        nulo($p->pago_gerente), nulo($p->factura_gerente),
                        nulo($p->pago_asesores), nulo($p->factura_asesores),
                        nulo($p->pago_otra_oficina), nulo($p->pagado_casa_nacional),
                        nulo($p->estatus_sistema_c21),
                        (($p->reporte_casa_nacional)?
                                number_format($p->reporte_casa_nacional, 0, ',', '.'):''),
                        nulo($p->factura_AyS), nulo($p->comentarios))) . "\n";
        }
        //dd($totales);
        //dd($users);
        $users = json_encode($users);
/*
 * Estos archivos grabados con Storage seran guardados en 'storage/app/public'
 * Usando: composer artisan storage:link, se crea un enlace que permite acceder
 * los archivos desde public/storage
 */
        $control = FECHA::hoy()->format('d-m-Y');
        Storage::put('public/control.txt', $control);
        Storage::put('public/asesores.txt', $users);
        Storage::put('public/propiedades.txt', $props);
        Storage::put('public/totales.txt', $totales);
        foreach($cols as $nombCol => $arr) {
            if ('enum' == $arr['tipo']) {
                Storage::put('public/' . $nombCol . '.txt', json_encode($arr['opcion']));
            }
        }
    }       // Final del metodo grabarArchivo.

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
        return $this->created_at->timezone(Fecha::$ZONA)->format('d/m/Y h:i a');
    }

    public function getTiempoCreadoAttribute()
    {
        return Carbon::parse($this->created_at)
                        ->timezone(Fecha::$ZONA)
                        ->diff(Carbon::now(Fecha::$ZONA))
                        ->format('%y años, %m meses y %d dias');
    }

    public function getActualizadoEnAttribute()
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

    public function getBorradoEnAttribute()
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

}