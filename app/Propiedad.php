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
        return $this->fecha_reserva->timezone(Fecha::$ZONA)->format('d/m/Y');
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
        return $this->fecha_firma->timezone(Fecha::$ZONA)->format('d/m/Y');
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

    public function agregarComaMoneda($valor)
    {
        if (!$this->mMoZero and 0 == $valor) return '';
        $valor = number_format($valor, 2, ',', '.');
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

    public function reservaSinIva()             // I
    {
        return (($this->comision)/100)*$this->precio;
    }

    public function reservaConIva()             // K
    {
        return ((100+$this->iva)/100)*$this->reservaSinIva();
    }

    public function getReservaSinIvaAttribute() // Muestra col 'I'
    {
        return $this->agregarComaMoneda($this->reservaSinIva());
    }

    public function getReservaConIvaAttribute() // Muestra col 'K'
    {
        return $this->agregarComaMoneda($this->reservaConIva());
    }

    public function div()                       // Creado desde 'lados', col N
    {
        if (2==$this->lados) return 1;
        else return 2;
    }

    public function compartidoConIva()                 // 'L'
    {
        return $this->reservaConIva()/$this->div();
    }

    public function getCompartidoConIvaAttribute()  // 'L'
    {
        return $this->agregarComaMoneda($this->compartidoConIva());
    }

    public function compartidoSinIva()              // 'M'
    {
        return $this->reservaSinIva()/$this->div();
    }

    public function getCompartidoSinIvaAttribute()  // 'M'
    {
        return $this->agregarComaMoneda($this->compartidoSinIva());
    }

    public function franquiciaReservadoSinIva()     // 'O'
    {
	    $factor = $this->porc_franquicia/100;

        if ($this->aplicar_porc_franquicia)
            $valor = $factor*$this->reservaSinIva()/$this->div();
        else $valor = $factor*$this->reservaConIva()/$this->div();
        return $valor;
    }

    public function getFranquiciaReservadoSinIvaAttribute()     // 'O'
    {
        return $this->agregarComaMoneda($this->franquiciaReservadoSinIva());
    }

    public function franquiciaReservadoConIva()                 // 'P'
    {
	    $factor = $this->porc_franquicia/100;

        return $factor*$this->reservaConIva()/$this->div();
    }

    public function getFranquiciaReservadoConIvaAttribute()     // 'P'
    {
        return $this->agregarComaMoneda($this->franquiciaReservadoConIva());
    }

    public function getReportadoCasaNacionalPAttribute()        // 'R'
    {
        return number_format($this->reportado_casa_nacional, 2, ',', '.') . '%';
    }

    public function franquiciaPagarReportada()                  // 'Q'
    {
        if ($this->aplicar_porc_franquicia_pagar_reportada) {
            $factor = ($this->porc_franquicia/100)*($this->reportado_casa_nacional/100);
            return ($factor*$this->precio)/$this->div();
        }
	    else return ($this->porc_franquicia/100) * $this->compartidoSinIva();
    }

    public function getFranquiciaPagarReportadaAttribute()      // 'Q'
    {
        return $this->agregarComaMoneda($this->franquiciaPagarReportada());
    }

    public function getPorcRegaliaPAttribute()
    {
        return number_format($this->porc_regalia, 2, ',', '.') . '%';
    }

    public function regalia()                                   // 'S'
    {
        $factor = $this->porc_regalia/100;

        return $factor*$this->franquiciaPagarReportada();
    }

    public function getRegaliaAttribute()                       // 'S'
    {
        return $this->agregarComaMoneda($this->regalia());
    }

    public function sanaf5PorCiento()                          // 'T'
    {
        $factor1 = 20.0/100;
        $factor2 = 0.1/100;
        $monto1 = $factor1*$this->franquiciaPagarReportada();
        $monto2 = $factor2*$this->reservaSinIva()/$this->div();

        return ($monto1 - $monto2);
    }

    public function getSanaf5PorCientoAttribute()               // 'T'
    {
        return $this->agregarComaMoneda($this->sanaf5PorCiento());
    }

    public function oficinaBrutoReal()                      // 'U' = 'L' - 'Q'
    {
        if ($this->aplicar_franquicia_pagar_reportada_bruto)
            $valor = $this->compartidoConIva() - $this->franquiciaPagarReportada(); // L - Q
        else
            $valor = $this->compartidoConIva() - $this->franquiciaReservadoSinIva();// L - O
        return $valor;
    }

    public function getOficinaBrutoRealAttribute()              // 'U' = 'L' - 'Q'
    {
        return $this->agregarComaMoneda($this->oficinaBrutoReal());
    }

    public function baseHonorariosSocios()              // 'V' = 'L' - 'P'
    {
        return $this->compartidoConIva() - $this->franquiciaReservadoConIva();
    }

    public function getBaseHonorariosSociosAttribute()          // 'V' = 'L' - 'P'
    {
        return $this->agregarComaMoneda($this->baseHonorariosSocios());
    }

    public function baseParaHonorarios()                // 'W' = 'M' - 'O'
    {
        $valor = $this->compartidoSinIva() - $this->franquiciaReservadoSinIva();

        if (0 == $this->iva) $valor /= ((100 + $this->IVA)/100);
        return $valor;
    }

    public function getBaseParaHonorariosAttribute()          // 'W' = 'M' - 'O'
    {
        return $this->agregarComaMoneda($this->baseParaHonorarios());
    }

    public function getPorcCaptadorPrbrPAttribute()
    {
        return $this->porc_captador_prbr . '%';
    }

    public function captadorPrbr()          	    // 'X' = % * 'U' o exp('W')
    {
        $factor1 = $this->porc_captador_prbr/100;
        $factor2 = 0.002;					// 0,2%

	    if ($this->aplicar_porc_captador)
            $valor = $factor1 * $this->oficinaBrutoReal();
        else {
            $bph = $this->baseParaHonorarios();
            $valor = ($factor1 * $bph) - ($factor2 * $bph) +
                        ($this->iva/100.00) * ($factor1 * $bph);
        }
        return $valor;
    }

    public function getCaptadorPrbrAttribute()          	// 'X' = % * 'U' o exp('W')
    {
        return $this->agregarComaMoneda($this->captadorPrbr());
    }

    public function getPorcGerentePAttribute()
    {
        return $this->porc_gerente . '%';
    }

    public function gerente()          		                // 'Y' = % * 'U'
    {
        $factor1 = $this->porc_gerente/100;

	    if ($this->aplicar_porc_gerente)
            $valor = $factor1 * $this->oficinaBrutoReal();
        else
            $valor = $factor1 * $this->baseParaHonorarios();
        return $valor;
    }

    public function getGerenteAttribute()          		// 'Y' = % * 'U'
    {
        return $this->agregarComaMoneda($this->gerente());
    }

    public function getPorcCerradorPrbrPAttribute()
    {
        return $this->porc_cerrador_prbr . '%';
    }

    public function cerradorPrbr()          	        // 'Z' = % * 'U' o exp('W')
    {
        $factor1 = $this->porc_cerrador_prbr/100;
        $factor2 = 0.002;					// 0,2%

	    if ($this->aplicar_porc_cerrador)
            $valor = $factor1 * $this->oficinaBrutoReal();
        else {
            $bph = $this->baseParaHonorarios();
            $valor = ($factor1 * $bph) - ($factor2 * $bph) +
                        ($this->iva/100.00) * ($factor1 * $bph);
        }
        return $valor;
    }

    public function getCerradorPrbrAttribute()          	// 'Z' = % * 'U' o exp('W')
    {
        return $this->agregarComaMoneda($this->cerradorPrbr());
    }

    public function bonificaciones()          		        // 'AA' = % * 'W'
    {
        $factor1 = $this->porc_bonificacion/100;
        $bph = $this->baseParaHonorarios();

	    if ($this->aplicar_porc_bonificacion)
            $valor = $factor1 * $bph;
	    else
            $valor = 0.00;
        return $valor;
    }

    public function getBonificacionesAttribute()          		// 'AA' = % * 'W'
    {
        $val = $this->bonificaciones();

        return $this->agregarComaMoneda($val);
    }

    public function getComisionBancariaVenAttribute()          		// 'AB'
    {
        if ($this->comision_bancaria) $val = $this->comision_bancaria;
        else $val = 0.00;

        return $this->agregarComaMoneda($val);
    }

    public function ingresoNetoOficina()                    // 'AC' = L - Q - X - Y - Z
    {
        $neto = $this->compartidoConIva() - $this->franquiciaPagarReportada() -
                $this->captadorPrbr() - $this->gerente() - $this->cerradorPrbr();
        return $neto;
    }

    public function getIngresoNetoOficinaAttribute()        // 'AC' = L - Q - X - Y - Z
    {
        return $this->agregarComaMoneda($this->ingresoNetoOficina());
    }

    public function getEstatusC21AlfaAttribute()
    {
        if ('V' == $this->estatus_sistema_c21) return 'Vendido';
	    elseif ('P' == $this->estatus_sistema_c21) return 'Pendiente';
	    else return 'Nulo';
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
