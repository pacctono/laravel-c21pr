<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\MisClases\Fecha;

class Propiedad extends Model
{
    protected $fillable = [
        'codigo', 'fecha_reserva', 'fecha_firma', 'negociacion',
        'nombre', 'estatus', 'user_id', 'moneda', 'precio', 'comision',
//        'reserva_sin_iva', 'iva', 'reserva_con_iva', 'lados',
        'iva', 'lados',
//        'compartido_con_iva', 'compartido_sin_iva',
        'porc_franquicia', 'aplicar_porc_franquicia',
        'aplicar_porc_franquicia_pagar_reportada',
//        'franquicia_sin_iva', 'franquicia_pagar_reportada',
        'reportado_casa_nacional', 'porc_regalia', //'regalia',
//        'sanaf_5_porciento', 'oficina_bruto_real',
//        'base_para_honorarios',
        'porc_captador_prbr', 'aplicar_porc_captador', 'captador_prbr',
        'porc_gerente', 'aplicar_porc_gerente', // 'gerente',
        'porc_cerrador_pbr', 'aplicar_porc_cerrador', 'cerrador_pbr',
        'porc_bonificacion', 'aplicar_porc_bonificacion',
//        'bonificacion', 'comision_bancaria', 'numero_recibo',
        'comision_bancaria', 'numero_recibo',
        'asesor_captador_id', 'asesor_captador',
        'asesor_cerrador_id', 'asesor_cerrador',
        'pago_gerente', 'factura_gerente',
        'pago_asesores', 'factura_asesores',
        'pagado_casa_nacional', 'estatus_sistema_c21',
        'reporte_casa_nacional', 'comentarios', 'factura_AyS',
        'user_actualizo', 'user_borro', 'borrado_at'
    ];
    protected $dates = [        // Mutan a una instancia de Carbon.
        'fecha_reserva',
        'fecha_firma',
        'borrado_at',
        'created_at',
        'updated_at'
    ];
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

    public function scopeValido($query)
    {
        return $query->where('estatus', '<>', 'S');
    }

    public function scopeNoValido($query)
    {
        return $query->where('estatus', 'S');
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
        if ('I' == $this->estatus) return 'Inmueble pendiente';
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

/*    public function franquiciaReservadoSinIva()     // 'O'
    {
	    $factor = $this->porc_franquicia/100;

//        if ($this->aplicar_porc_franquicia)
            $valor = $factor*$this->getCompartidoSinIvaAttribute();
//        else $valor = $factor*$this->compartidoConIva();
        return round($valor, 2);
    }
 */
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

/*    public function franquiciaReservadoConIva()                 // 'P'
    {
	    $factor = $this->porc_franquicia/100;

        return round($factor*$this->getCompartidoConIvaAttribute(), 2);
    }
 */
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

/*    public function franquiciaPagarReportada()                  // 'Q'
    {
//        if ($this->aplicar_porc_franquicia_pagar_reportada) {
            $factor = ($this->porc_franquicia/100)*($this->reportado_casa_nacional/100);
            return round(($factor*$this->precio)/$this->div(), 2);
//        }
//	    else return round(($this->porc_franquicia/100) * $this->compartidoSinIva(), 2);
    }
 */
    public function getFranquiciaPagarReportadaAttribute()                  // 'Q'
    {
//        if ($this->aplicar_porc_franquicia_pagar_reportada) {
            $factor = ($this->porc_franquicia/100)*($this->reportado_casa_nacional/100);
            return round(($factor*$this->precio)/$this->div(), 2);
//        }
//	    else return round(($this->porc_franquicia/100) * $this->compartidoSinIva(), 2);
    }

    public function getFranquiciaPagarReportadaVenAttribute()      // 'Q'
    {
        return $this->agregarComaMoneda($this->getFranquiciaPagarReportadaAttribute());
    }

    public function getPorcRegaliaPAttribute()
    {
        return number_format($this->porc_regalia, 2, ',', '.') . '%';
    }

/*    public function regalia()                                   // 'S'
    {
        $factor = $this->porc_regalia/100;

        return round($factor*$this->getFranquiciaPagarReportadaAttribute(), 2);
    }
 */
    public function getRegaliaAttribute()                                   // 'S'
    {
        $factor = $this->porc_regalia/100;

        return round($factor*$this->getFranquiciaPagarReportadaAttribute(), 2);
    }

    public function getRegaliaVenAttribute()                       // 'S'
    {
        return $this->agregarComaMoneda($this->getRegaliaAttribute());
    }

/*    public function sanaf5PorCiento()                          // 'T'
    {
        $factor1 = 20.0/100;
        $factor2 = 0.1/100;
        $monto1 = $factor1*$this->getFranquiciaPagarReportadaAttribute();
        $monto2 = $factor2*$this->getReservaSinIvaAttribute()/$this->div();

        return round(($monto1 - $monto2), 2);
    }
 */
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

/*    public function oficinaBrutoReal()                      // 'U' = 'L' - 'Q'
    {
//        if ($this->aplicar_franquicia_pagar_reportada_bruto)
            $valor = $this->compartidoConIva() - $this->franquiciaPagarReportada(); // L - Q
//        else
//            $valor = $this->compartidoConIva() - $this->franquiciaReservadoSinIva();// L - O
        return round($valor, 2);
    }
 */
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

/*    public function baseHonorariosSocios()              // 'V' = 'L' - 'P'
    {
        return round($this->compartidoConIva() - $this->franquiciaReservadoConIva(), 2);
    }
 */
    public function getBaseHonorariosSociosAttribute()              // 'V' = 'L' - 'P'
    {
        return round($this->getCompartidoConIvaAttribute() -
                        $this->getFranquiciaReservadoConIvaAttribute(), 2);
    }

    public function getBaseHonorariosSociosVenAttribute()          // 'V' = 'L' - 'P'
    {
        return $this->agregarComaMoneda($this->getBaseHonorariosSociosAttribute());
    }

/*    public function baseParaHonorarios()                // 'W' = 'M' - 'O'
    {
        $valor = $this->compartidoSinIva() - $this->franquiciaReservadoSinIva();

        if (0 == $this->iva) $valor /= ((100 + $this->IVA)/100);
        return round($valor, 2);
    }
 */
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

/*    public function captadorPrbr()          	    // 'X' = % * 'U' o exp('W')
    {
        $factor1 = $this->porc_captador_prbr/100;
        $factor2 = 0.002;					// 0,2%

//	    if ($this->aplicar_porc_captador)
        if ((1 < $this->asesor_captador_id) and ($this->captador->socio))
            $valor = $factor1 * $this->baseHonorariosSocios();
        else {
            $bph = $this->baseParaHonorarios();
            $valor = ($factor1 * $bph) - ($factor2 * $bph) +
                        ($factor1 * $bph * ($this->IVA/100.00));
        }
        return round($valor, 2);
    }
 */
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

/*    public function gerente()          		                // 'Y' = % * 'U'
    {
        $factor1 = $this->porc_gerente/100;

//	    if ($this->aplicar_porc_gerente)
//            $valor = $factor1 * $this->oficinaBrutoReal();
//        else
//            $valor = $factor1 * $this->baseParaHonorarios();
            $valor = $factor1 * $this->getBaseHonorariosSociosAttribute();
        return round($valor, 2);
    }
 */
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

/*    public function cerradorPrbr()          	        // 'Z' = % * 'U' o exp('W')
    {
        $factor1 = $this->porc_cerrador_prbr/100;
        $factor2 = 0.002;					// 0,2%

//	    if ($this->aplicar_porc_cerrador)
//            $valor = $factor1 * $this->oficinaBrutoReal();
        if ((1 < $this->asesor_cerrador_id) and ($this->cerrador->socio))
            $valor = $factor1 * $this->baseHonorariosSocios();
        else {
            $bph = $this->baseParaHonorarios();
            $valor = ($factor1 * $bph) - ($factor2 * $bph) +
                        ($factor1 * $bph * ($this->IVA/100.00));
        }
        return round($valor, 2);
    }
 */
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

/*    public function bonificaciones()          		        // 'AA' = % * 'W'
    {
        $factor1 = $this->porc_bonificacion/100;
        $bph = $this->baseParaHonorarios();

//	    if ($this->aplicar_porc_bonificacion)
            $valor = $factor1 * $bph;
//	    else
//            $valor = 0.00;
        return round($valor, 2);
    }
 */
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

/*    public function ingresoNetoOficina()                    // 'AC' = L - Q - X - Y - Z
    {
        $neto = $this->compartidoConIva() - $this->franquiciaPagarReportada() -
                $this->getCaptadorPrbrAttribute() - $this->getGerenteAttribute() -
                $this->getCerradorPrbrAttribute() - $this->getBonificacionesAttribute() -
                $this->comision_bancaria;
        return round($neto, 2);
    }
 */
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

 /*   public function precioVentaReal()
    {
        $factor1 = 100.0/($this->IVA + 100.0);
        $factor2 = 100.0/$this->comision;

        if ($this->IVA == $this->iva)
            $valor = $this->precio;
        else
            $valor = $this->getCompartidoConIvaAttribute() * $factor1 * $factor2;
        return round($valor, 2);
    }
 */
    public function precioVentaReal()
    {
        $factor1 = 100.0/($this->IVA + 100.0);
        $factor2 = 100.0/$this->comision;

        if ($this->IVA == $this->iva)
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

    public static function sumaXAsesor($idAsesor, $tipoAsesor, $fecha='fecha_reserva',
                                        $fecha_desde=null, $fecha_hasta=null)
    {
        if(null == $fecha_desde)
            $fecha_desde = (new Carbon(Propiedad::min($fecha, Fecha::$ZONA)))->startOfDay();
        if(null == $fecha_hasta)
            $fecha_hasta = (new Carbon(Propiedad::max($fecha, Fecha::$ZONA)))->endOfDay();
        return self::where('estatus', '!=', 'S')->where('asesor_' . $tipoAsesor . '_id', $idAsesor)
                     ->get()->sum($tipoAsesor . '_prbr');
    }

    public static function ladosXMes($fecha='fecha_reserva', $fecha_desde=null, $fecha_hasta=null, $user=0)
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
                                    DB::raw('YEAR(fecha_reserva) agno,
                                    MONTH(fecha_reserva) mes'))
                        ->where('estatus', '!=', 'S')
                        ->whereBetween($fecha, [$fecha_desde, $fecha_hasta])
                        ->where(function ($query) use ($user, $signo) {
                                $query->where('asesor_captador_id', $signo, $user)
                                        ->orWhere('asesor_cerrador_id', $signo, $user);
                        })
                        ->groupBy('agno', 'mes');
        return $sql;
    }

    public static function negociacionesXMes($fecha='fecha_reserva', $fecha_desde=null, $fecha_hasta=null, $user=0)
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
                                    DB::raw('YEAR(fecha_reserva) agno,
                                    MONTH(fecha_reserva) mes'))
                        ->where('estatus', '!=', 'S')
                        ->whereBetween($fecha, [$fecha_desde, $fecha_hasta])
                        ->where(function ($query) use ($user, $signo) {
                                $query->where('asesor_captador_id', $signo, $user)
                                        ->orWhere('asesor_cerrador_id', $signo, $user);
                        })
                        ->groupBy('agno', 'mes');
        return $sql;
    }

    public static function comisionXMes($fecha='fecha_reserva', $fecha_desde=null, $fecha_hasta=null, $user=0)
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
                            ->where('estatus', '!=', 'S')
                            ->whereNull($fecha)
                            ->get()->sum('captadorPrbr');
            $cerrado = self::where('asesor_cerrador_id', $signo, $user)
                            ->where('estatus', '!=', 'S')
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
                                ->where('estatus', '!=', 'S')
                                ->whereYear($fecha, $agno)
                                ->whereMonth($fecha, $mes)
                                ->get()->sum('captadorPrbr');
                $cerrado = self::where('asesor_cerrador_id', $signo, $user)
                                ->where('estatus', '!=', 'S')
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
        if (null == $this->borrado_at) return '';
        return $this->borrado_at->timezone(Fecha::$ZONA)->format('d/m/Y');
    }

    public function getBorradoDiaSemanaAttribute()
    {
        if (null == $this->borrado_at) return '';
        return substr(Fecha::$diaSemana[$this->borrado_at->timezone(Fecha::$ZONA)
                        ->dayOfWeek], 0, 3);
    }

    public function getBorradoConHoraAttribute()
    {
        if (null == $this->borrado_at) return '';
        return $this->borrado_at->timezone(Fecha::$ZONA)->format('d/m/Y h:i a');
    }

}
