<?php

namespace App\Http\Controllers;

use App\Propiedad;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;        // PC
use Illuminate\Support\Facades\DB;          // PC
use Illuminate\Support\Facades\Storage;     // PC
use Carbon\Carbon;                          // PC
use App\MisClases\Fecha;                    // PC
use Jenssegers\Agent\Agent;                 // PC

class PropiedadController extends Controller
{
    protected $tipo = 'Propiedad';
    protected $tipoPlural = 'Propiedades';
    protected function columnas()
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

/*    protected function totalizar($props, $metodo, $dec=2) {
        $tot = 0.00;
        foreach ($props->get() as $p) {
            $tot += $p->$metodo();
        }
        return round($tot, 2);
    }*/
    protected function totales($propiedads, $valido=True, $cap=0, $cer=0)
    {
        $propiedades = clone $propiedads;               // Los query modifican el arreglo propiedades.
        if ($valido) $propiedades = $propiedades->valido();
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
        array_push($arrRetorno, $tCaptadorPrbrSel, $tCerradorPrbrSel,
            $tLadosCap, $tLadosCer, $tPvrCaptadorPrbrSel, $tPvrCerradorPrbrSel);
        //dd($arrRetorno);
        return $arrRetorno;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($orden = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $title = 'Listado de ' . $this->tipoPlural;
        $ruta = request()->path();
        $dato = request()->all();
        //dd($dato);
        if (1 >= count($dato)) $paginar = True;
        else $paginar = False;
// Todo se inicializa, cuando se selecciona 'periodos' desde el menú horizontal.
        if (('GET' == request()->method()) and ('' == $orden) and (0 == count($dato))) {
            session(['fecha_desde' => '', 'fecha_hasta' => '', 'estatus' => '',
                        'captador' => '0', 'cerrador' => '0']);
        }
/*
 * Manejo de las variables de la forma superior. $dato (fecha_desde, fecha_hasta, estatus,
 * asesor captador y asesor cerrador).
 * Cuando el arreglo $dato contiene un solo item, este es el número de página (page=n).
 * Si el arreglo $dato está vacio (count($arreglo) == 0, esta opcion fue manejada arriba),
 * es una ruta 'GET' con o sin $orden.
 * Si $dato tiene más de 1 item. Fue seleccionado una fecha y un asesor.
 */
        if (1 >= count($dato)) {
            $fecha_desde = session('fecha_desde', '');
            $fecha_hasta = session('fecha_hasta', '');
            $estatus     = session('estatus', '');
            $captador    = session('captador', '0');
            $cerrador    = session('cerrador', '0');
        } else {
            if (isset($dato['captador'])) $captador = $dato['captador'];
            else $captador = 0;
            if (isset($dato['cerrador'])) $cerrador = $dato['cerrador'];
            else $cerrador = 0;

            if (isset($dato['estatus'])) $estatus = $dato['estatus'];
            else $estatus = '';

            if ('' == $dato['fecha_desde'])
                $fecha_desde = (new Carbon(Propiedad::min('fecha_firma')));
            else $fecha_desde = (new Carbon($dato['fecha_desde']));
            if ('' == $dato['fecha_hasta'])
                $fecha_hasta = (new Carbon(Propiedad::max('fecha_firma')));
            else $fecha_hasta = (new Carbon($dato['fecha_hasta']));
/*            list ($fecha_desde, $fecha_hasta) = Fecha::periodo($dato, $fecha_min,
                                                                $fecha_max);*/
        }
//        dd($dato, $fecha_desde, $fecha_hasta);
        if ('' == $orden or null == $orden) {
            $orden = 'id';
        }
        if (Auth::user()->is_admin) {
            $users   = User::get(['id', 'name']);     // Todos los usuarios (asesores).
            $users[0]['name'] = 'Asesor otra oficina';
            $propiedades = Propiedad::where('id', '>', 0);   // condición dummy, solo para continuar armando la consulta.
            $asesor = 0;
        } else {
            $user   = User::find(Auth::user()->id);
            $title .= ' de ' . $user->name;
            $asesor = $user->id;
            $propiedades = Propiedad::where(
                                    function ($query) use ($asesor) {
                                $query->where('user_id', $asesor)
                                        ->orWhere('asesor_captador_id', $asesor)
                                        ->orWhere('asesor_cerrador_id', $asesor);
                                })
                            ->whereNull('user_borro');
        }

        if (0 < $captador) {        // Se selecciono un asesor captador o esta conectado.
            if ($captador != $cerrador)     // Se selecciono un asesor cerrador o esta conectado.
                $propiedades = $propiedades->where('asesor_captador_id', $captador);
            else
                $propiedades = $propiedades->where(
                                    function ($query) use ($captador, $cerrador) {
                                        $query->where('asesor_captador_id', $captador)
                                            ->orWhere('asesor_cerrador_id', $cerrador);
                                });
        }
        if (0 < $cerrador) {      // Se selecciono un asesor cerrador o esta conectado.
            if ($cerrador != $captador)
                $propiedades = $propiedades->where('asesor_cerrador_id', $cerrador);
        }
        if ('' != $estatus) {       // Se selecciono un estatus.
            $propiedades = $propiedades->where('estatus', $estatus);
        }
        if ('' != $fecha_desde and '' != $fecha_hasta) {    // Se seleccionaron fechas.
            $fecha_desde = substr($fecha_desde, 0, 10);
            $fecha_hasta = substr($fecha_hasta, 0, 10);
            $propiedades = $propiedades
                            ->whereBetween('fecha_firma', [$fecha_desde, $fecha_hasta]);
        } else {
            $propiedades = $propiedades
                            ->where('fecha_firma', '<=', now(Fecha::$ZONA));
        }
//  Esto es un fastidio o muy pobre; pero, hay varias fecha de la firma nulas. Para mayor informacion, leer comentarios.
        if (Auth::user()->is_admin) {
            if ((0 == $captador) and (0 == $cerrador)) {    // El usuario es administrador y no se ha seleccionado captador o cerrador.
                if ('' == $estatus)         // Tampoco se ha seleccionado estatus. Incluir todas las fechas de la firma nulas.
                    $propiedades = $propiedades->orWhereNull('fecha_firma');
                else    // Incluir las fechas de la firma nulas con estatus: "or (estatus and fecha_firma nula)".
                    $propiedades = $propiedades->orWhere(
                                        function ($query) use ($estatus) {
                                            $query->where('estatus', $estatus)
                                                ->whereNull('fecha_firma');
                                    });
            } else {    // El usuario es administrador y se ha seleccionado un captador o un cerrador.
                if ('' == $estatus)     // Incluir fecha nulas y (captador o cerrador): "or (captador and fecha nula) or (cerrador and fecha nula)
                    $propiedades = $propiedades->orWhere(
                                        function ($query) use ($captador) {
                                            $query->where('asesor_captador_id', $captador)
                                                ->whereNull('fecha_firma');
                                    })->orWhere(
                                        function ($query) use ($cerrador) {
                                            $query->where('asesor_cerrador_id', $cerrador)
                                                ->whereNull('fecha_firma');
                                    });
                else     // Incluir fecha nulas y estatus y (captador o cerrador): "or (estatus and captador and fecha nula) or (estatus and cerrador and fecha nula)
                    $propiedades = $propiedades->orWhere(
                                        function ($query) use ($captador, $estatus) {
                                            $query->where('estatus', $estatus)
                                                ->where('asesor_captador_id', $captador)
                                                ->whereNull('fecha_firma');
                                    })->orWhere(
                                        function ($query) use ($cerrador, $estatus) {
                                            $query->where('estatus', $estatus)
                                                ->where('asesor_cerrador_id', $cerrador)
                                                ->whereNull('fecha_firma');
                                    });
            }
        } else {    // El usuario no es administrador. El usuario es el captador y cerrador ($asesor).
            if ('' == $estatus)     // Incluir fecha nulas y captador/cerrador = $asesor.
                $propiedades = $propiedades->orWhere(
                                    function ($query) use ($asesor) {
                                        $query->where('asesor_captador_id', $asesor)
                                            ->whereNull('fecha_firma');
                                })->orWhere(
                                    function ($query) use ($asesor) {
                                        $query->where('asesor_cerrador_id', $asesor)
                                            ->whereNull('fecha_firma');
                                });
            else     // Incluir fecha nulas y estatus y captador/cerrador = $asesor.
                $propiedades = $propiedades->orWhere(
                                    function ($query) use ($asesor, $estatus) {
                                        $query->where('estatus', $estatus)
                                            ->where('asesor_captador_id', $asesor)
                                            ->whereNull('fecha_firma');
                                })->orWhere(
                                    function ($query) use ($asesor, $estatus) {
                                        $query->where('estatus', $estatus)
                                            ->where('asesor_cerrador_id', $asesor)
                                            ->whereNull('fecha_firma');
                                });
        }
        $propiedades = $propiedades->orderBy($orden);   // Ordenar los items de los propiedades.
        if ('user_id' == $orden) {              // Si se pidió ordenar por id de usuario,
            $propiedades = $propiedades->orderBy('fecha_firma');   // ordenar por fecha_firma en cada usuario.
        }
        list ($filas, $tPrecio, $tLados, $tCompartidoConIva, $tFranquiciaSinIva,
                $tFranquiciaConIva, $tFranquiciaPagarR, $tRegalia, $tSanaf5PorCiento,
                $tOficinaBrutoReal, $tBaseHonorariosSo, $tBaseParaHonorari,
                $tCaptadorPrbr, $tGerente, $tCerradorPrbr, $tBonificaciones,
                $tComisionBancaria, $tIngresoNetoOfici, $tPrecioVentaReal,
                $tCaptadorPrbrSel, $tCerradorPrbrSel, $tLadosCap, $tLadosCer,
                $tPvrCaptadorPrbrSel, $tPvrCerradorPrbrSel) =
                $this->totales($propiedades, True, (($captador)?$captador:(($asesor)?$asesor:0)),
                                (($cerrador)?$cerrador:(($asesor)?$asesor:0)));
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        $paginar = ($paginar)?!$movil:$paginar;
        if ($paginar) $propiedades = $propiedades->paginate(10);      // Pagina la impresión de 10 en 10
        else $propiedades = $propiedades->get();                      // Mostrar todos los registros.
// Devolver las fechas sin la hora. Los diez primeros caracteres son: yyyy-mm-dd.
        $cols = $this->columnas();
        $arrEstatus = $cols['estatus']['opcion'];
        unset($cols);
        session(['fecha_desde' => $fecha_desde,    // Asignar valores en sesión.
                    'fecha_hasta' => $fecha_hasta, 'estatus' => $estatus,
                    'captador' => $captador, 'cerrador' => $cerrador]);
        return view((($movil)?'celular.indexPropiedades':'propiedades.index'),
                compact('title', 'users', 'propiedades',
                'filas', 'tPrecio', 'tCompartidoConIva', 'tLados',
                'tFranquiciaSinIva', 'tFranquiciaConIva', 'tFranquiciaPagarR',
                'tRegalia', 'tSanaf5PorCiento', 'tOficinaBrutoReal',
                'tBaseHonorariosSo', 'tBaseParaHonorari', 'tIngresoNetoOfici',
                'tCaptadorPrbr', 'tGerente', 'tCerradorPrbr', 'tBonificaciones',
                'tComisionBancaria', 'tPrecioVentaReal', 'tCaptadorPrbrSel',
                'tCerradorPrbrSel', 'tLadosCap', 'tLadosCer',
                'tPvrCaptadorPrbrSel', 'tPvrCerradorPrbrSel', 'ruta',
                'fecha_desde', 'fecha_hasta', 'arrEstatus', 'captador', 'cerrador',
                'estatus', 'paginar'));
    }       // Final del metodo index.

    public function grabarArchivo()
    {
        function nulo($valor, $def='') {
            if (is_null($valor)) {
                $valor = $def;
            }
            return $valor;
        }
        if (!(Auth::check())) {
            return redirect('login');
        }

        if (1 == Auth::user()->is_admin) {
            $users   = User::get();                     // Todos los usuarios (asesores).
            $users[0]['name'] = 'Asesor otra oficina';
            $propiedades = Propiedad::where('id', '>', 0);   // condición dummy, solo para continuar armando la consulta.
        } else {
            return redirect()->back();
        }

        $totales = '';
/*
 * Calculo de totales por 'asesor' (user).
 */
        foreach ($users as $user) {
            $props = clone $propiedades;               // Los query modifican el arreglo propiedades.
            $props = $props->where('asesor_captador_id', $user->id)
                        ->orWhere('asesor_cerrador_id', $user->id);
            $arreglo = $this->totales($props, True, $user->id, $user->id);
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
                $arreglo = $this->totales($props);
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
        $cols = $this->columnas();
        $estatus = $cols['estatus']['opcion'];
        foreach ($estatus as $op=>$desc) {
            $props = clone $propiedades;               // Los query modifican el arreglo propiedades.
            $props = $props->where('estatus', $op);
            $arreglo = $this->totales($props, False);
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
                    $arreglo = $this->totales($props, True, $user->id, $user->id);
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
                    $arreglo = $this->totales($props, True, $user->id, $user->id);
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
        $arreglo = $this->totales($propiedades);
        array_unshift($arreglo, 'T', 'T');
        $totales .= json_encode($arreglo) . "\n";
        $propiedades = $propiedades->get();
        $props       = '';
        foreach ($propiedades as $p) {
            $props .= json_encode(array ($p->id, $p->codigo, $p->reserva_en,
                        $p->firma_en, $p->negociacion, $p->nombre, $p->estatus,
                        $p->moneda, $p->precio, $p->comision,
                        $p->reserva_sin_iva, $p->iva, $p->reserva_con_iva,
                        $p->compartido_con_iva, $p->compartido_sin_iva,
                        $p->lados, $p->franquicia_reservado_sin_iva,
                        $p->franquicia_reservado_con_iva, $p->porc_franquicia,
                        $p->franquicia_pagar_reportada, $p->reportado_casa_nacional,
                        $p->porc_regalia, $p->regalia, $p->sanaf5_por_ciento,
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

        //return redirect('/propiedades');
        return redirect()->back();
    }       // Final del metodo grabarArchivo.

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $users = User::get(['id', 'name']);     // Todos los usuarios (asesores).
//        dd($users[0]['name']);
        $users[0]['name'] = 'Asesor otra oficina';

        $cols = $this->columnas();
        $title = 'Crear ' . $this->tipo;
        $exito = session('exito', '');
        session(['exito' => '']);

        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        return view((($movil)?'celular.createPropiedades':'propiedades.create'),
                    compact('title', 'users', 'cols', 'exito'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'codigo' => ['required', 'digits_between:6,8'],
            'fecha_reserva' => ['sometimes', 'nullable', 'date'],
            'fecha_firma' => ['sometimes', 'nullable', 'date'],
            'negociacion' => 'required',
            'nombre' => 'required',
            'estatus' => 'required',
            'moneda' => 'required',
            'precio' => 'required',
            'comision' => 'required',
            'iva' => 'required',
            'lados' => '',
            'porc_franquicia' => 'required',
            'aplicar_porc_franquicia' => '',
            'aplicar_porc_franquicia_pagar_reportada' => '',
            'aplicar_franquicia_pagar_reportada_bruto' => '',
            'reportado_casa_nacional' => 'required',
            'porc_regalia' => 'required',
            'porc_captador_prbr' => 'required',
            'aplicar_porc_captador' => '',
            'porc_gerente' => 'required',
            'aplicar_porc_gerente' => '',
            'porc_cerrador_prbr' => 'required',
            'aplicar_porc_cerrador' => '',
            'porc_bonificacion' => 'required',
            'aplicar_porc_bonificacion' => '',
            'comision_bancaria' => '',
            'numero_recibo' => '',
            'asesor_captador_id' => 'required',
            'asesor_captador' => '',
            'asesor_cerrador_id' => 'required',
            'asesor_cerrador' => '',
            'pago_gerente' => '',
            'factura_gerente' => '',
            'pago_asesores' => '',
            'factura_asesores' => '',
            'pago_otra_oficina' => '',
            'pagado_casa_nacional' => '',
            'estatus_sistema_c21' => '',
            'reporte_casa_nacional' => '',
            'factura_AyS' => '',
            'comentarios' => '',
        ], [
            'codigo.required' => 'Debe siministrarse el <codigo> de la propiedad.',
            'codigo.digits_between' => 'El <codigo> debe contener entre 6 y 8 caracteres.',
            'fecha_reserva.date' => 'La <fecha de reserva> debe ser una fecha valida.',
            'fecha_firma.date' => 'La <fecha de la firma> debe ser una fecha valida.',
            'negociacion.required' => 'El campo <negociacion> es obligatorio',
            'nombre.required' => 'El campo <nombre> DE LA PROPIEDAD es obligatorio',
            'moneda.required' => 'El campo <moneda> es obligatorio',
            'precio.required' => 'El campo <precio> es obligatorio',
            'comision.required' => 'El campo <comision> es obligatorio',
            'iva.required' => 'El campo <iva> es obligatorio',
            'porc_franquicia.required' => 'El campo <% franquicia> es obligatorio',
            'reportado_casa_nacional.required' => 'El campo <Reportado casa nacional> es obligatorio',
            'porc_regalia.required' => 'El campo <% Regalia> es obligatorio',
            'porc_captador_prbr.required' => 'El campo <Porcentaje captador PRBR> es obligatorio',
            'porc_gerente.required' => 'El campo <Porcentaje gerente> es obligatorio',
            'porc_cerrador_prbr.required' => 'El campo <Porcentaje cerrador PRBR> es obligatorio',
            'porc_bonificacion.required' => 'El campo <Porcentaje bonificacion> es obligatorio',
            'asesor_captador_id.required' => 'El campo <Asesor captador> es obligatorio',
            'asesor_cerrador_id.required' => 'El campo <Asesor cerrador> es obligatorio',
        ]);

        //$data['user_id'] = Auth::user()->id;
        //$data['user_id'] = intval($data['user_id']);

/*        if (null != $data['fecha_reserva'])
            $data['fecha_reserva'] = Carbon::createFromFormat('Y-m-d', $data['fecha_reserva']);
        if (null != $data['fecha_firma'])
            $data['fecha_firma'] = Carbon::createFromFormat('Y-m-d', $data['fecha_firma']);*/
        //dd($data);
        if (!array_key_exists('estatus', $data)) $data['estatus'] = 'A';
        if (!array_key_exists('estatus_sistema_c21', $data)) $data['estatus_sistema_c21'] = 'A';
        if (!array_key_exists('aplicar_porc_franquicia', $data))
            $data['aplicar_porc_franquicia'] = false;
        elseif ('on' == $data['aplicar_porc_franquicia'])
            $data['aplicar_porc_franquicia'] = true;
        if (!array_key_exists('aplicar_porc_franquicia_pagar_reportada', $data))
            $data['aplicar_porc_franquicia_pagar_reportada'] = false;
        elseif ('on' == $data['aplicar_porc_franquicia_pagar_reportada'])
            $data['aplicar_porc_franquicia_pagar_reportada'] = true;
        if (!array_key_exists('aplicar_franquicia_pagar_reportada_bruto', $data))
            $data['aplicar_franquicia_pagar_reportada_bruto'] = false;
        elseif ('on' == $data['aplicar_franquicia_pagar_reportada_bruto'])
            $data['aplicar_franquicia_pagar_reportada_bruto'] = true;
        if (!array_key_exists('aplicar_porc_captador', $data))
            $data['aplicar_porc_captador'] = false;
        elseif ('on' == $data['aplicar_porc_captador'])
            $data['aplicar_porc_captador'] = true;
        if (!array_key_exists('aplicar_porc_gerente', $data))
            $data['aplicar_porc_gerente'] = false;
        elseif ('on' == $data['aplicar_porc_gerente'])
            $data['aplicar_porc_gerente'] = true;
        if (!array_key_exists('aplicar_porc_cerrador', $data))
            $data['aplicar_porc_cerrador'] = false;
        elseif ('on' == $data['aplicar_porc_cerrador'])
            $data['aplicar_porc_cerrador'] = true;
        if (!array_key_exists('aplicar_porc_bonificacion', $data))
            $data['aplicar_porc_bonificacion'] = false;
        elseif ('on' == $data['aplicar_porc_bonificacion'])
            $data['aplicar_porc_bonificacion'] = true;
/*        if (!array_key_exists('pagado_casa_nacional', $data))
            $data['pagado_casa_nacional'] = false;
        elseif ('on' == $data['pagado_casa_nacional'])
            $data['pagado_casa_nacional'] = true;*/

        propiedad::create([
            'codigo' => $data['codigo'],
            'fecha_reserva' => $data['fecha_reserva'],
            'fecha_firma' => $data['fecha_firma'],
            'negociacion' => $data['negociacion'],
            'nombre' => $data['nombre'],
            'estatus' => $data['estatus'],
            'user_id' => Auth::user()->id,
            'moneda' => $data['moneda'],
            'precio' => $data['precio'],
            'comision' => $data['comision'],
            'iva' => $data['iva'],
            'lados' => $data['lados'],
            'porc_franquicia' => $data['porc_franquicia'],
            'aplicar_porc_franquicia' => $data['aplicar_porc_franquicia'],
            'aplicar_porc_franquicia_pagar_reportada' => $data['aplicar_porc_franquicia_pagar_reportada'],
            'aplicar_franquicia_pagar_reportada_bruto' => $data['aplicar_franquicia_pagar_reportada_bruto'],
            'reportado_casa_nacional' => $data['reportado_casa_nacional'],
            'porc_regalia' => $data['porc_regalia'],
            'porc_captador_prbr' => $data['porc_captador_prbr'],
            'aplicar_porc_captador' => $data['aplicar_porc_captador'],
            'porc_gerente' => $data['porc_gerente'],
            'aplicar_porc_gerente' => $data['aplicar_porc_gerente'],
            'porc_cerrador_prbr' => $data['porc_cerrador_prbr'],
            'aplicar_porc_cerrador' => $data['aplicar_porc_cerrador'],
            'porc_bonificacion' => $data['porc_bonificacion'],
            'aplicar_porc_bonificacion' => $data['aplicar_porc_bonificacion'],
            'comision_bancaria' => (isset($data['comision_bancaria'])?$data['comision_bancaria']:null),
            'numero_recibo' => (isset($data['numero_recibo'])?$data['numero_recibo']:null),
            'asesor_captador_id' => $data['asesor_captador_id'],
            'asesor_captador' => (isset($data['asesor_captador'])?$data['asesor_captador']:null),
            'asesor_cerrador_id' => $data['asesor_cerrador_id'],
            'asesor_cerrador' => (isset($data['asesor_cerrador'])?$data['asesor_cerrador']:null),
            'pago_gerente' => (isset($data['pago_gerente'])?$data['pago_gerente']:null),
            'factura_gerente' => (isset($data['factura_gerente'])?$data['factura_gerente']:null),
            'pago_asesores' => (isset($data['pago_asesores'])?$data['pago_asesores']:null),
            'factura_asesores' => (isset($data['factura_asesores'])?$data['factura_asesores']:null),
            'pago_otra_oficina' => (isset($data['pago_otra_oficina'])?$data['pago_otra_oficina']:null),
            'pagado_casa_nacional' => (isset($data['pagado_casa_nacional']) and
                                        ('on' == $data['pagado_casa_nacional'])),
            'estatus_sistema_c21' => $data['estatus_sistema_c21'],
            'reporte_casa_nacional' => (isset($data['reporte_casa_nacional'])?$data['reporte_casa_nacional']:null),
            'comentarios' => (isset($data['comentarios'])?$data['comentarios']:null),
            'factura_AyS' => (isset($data['factura_AyS'])?$data['factura_AyS']:null),
        ]);

        session(['exito' => "La propiedad '" . $data['codigo'] . ' ' . $data['nombre'] .
                            "' fue agregada con exito."]);
        return redirect()->route('propiedades.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Propiedad  $propiedad
     * @return \Illuminate\Http\Response
     */
    public function show(Propiedad $propiedad, $rutRetorno='propiedades.index')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $col_id = '';
        if (17 < strlen($rutRetorno)) {     // 17 = longitud de 'propiedades.index'
            $col_id = strtolower(substr($rutRetorno, 19)) . '_id';
        }
        
        //dd($propiedad);
        if (Auth::user()->is_admin) {
            $agente = new Agent();
            $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
            return view((($movil)?'celular.showPropiedad':'propiedades.show'),
                        compact('propiedad', 'rutRetorno', 'col_id'));
        }
        if ($propiedad->user_borro != null) {
            return redirect()->back();
        }
        if (($propiedad->user_id == Auth::user()->id) or
            ($propiedad->asesor_captador_id == Auth::user()->id) or
            ($propiedad->asesor_cerrador_id == Auth::user()->id)) {
            $agente = new Agent();
            $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
            return view((($movil)?'celular.showPropiedad':'propiedades.show'),
                        compact('propiedad', 'rutRetorno', 'col_id'));
        } else {
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Propiedad  $propiedad
     * @return \Illuminate\Http\Response
     */
    public function edit(Propiedad $propiedad)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if ($propiedad->user_borro != null) {
            return redirect('/propiedades');
        }

        $cols = $this->columnas();
        $title = 'Editar ' . $this->tipo;
        $users = User::get(['id', 'name']);     // Todos los usuarios (asesores).
        $users[0]['name'] = 'Asesor otra oficina';
//        dd($propiedad);
        if ((Auth::user()->is_admin) or ($propiedad->user_id == Auth::user()->id) or
            ($propiedad->asesor_captador_id == Auth::user()->id) or
            ($propiedad->asesor_cerrador_id == Auth::user()->id)) {
            $agente = new Agent();
            $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
            return view((($movil)?'celular.editPropiedades':'propiedades.edit'),
                        ['propiedad' => $propiedad, 'users' => $users, 'cols' => $cols, 'title' => $title]);
        }
        return redirect('/propiedades');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Propiedad  $propiedad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Propiedad $propiedad)
    {
        //print_r($request);
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'fecha_reserva' => ['sometimes', 'nullable', 'date'],
            'fecha_firma' => ['sometimes', 'nullable', 'date'],
            'negociacion' => 'required',
            'nombre' => 'required',
            'estatus' => 'required',
            'moneda' => 'required',
            'precio' => 'required',
            'comision' => 'required',
            'iva' => 'required',
            'lados' => '',
            'porc_franquicia' => 'required',
            'aplicar_porc_franquicia' => '',
            'aplicar_porc_franquicia_pagar_reportada' => '',
            'aplicar_franquicia_pagar_reportada_bruto' => '',
            'reportado_casa_nacional' => 'required',
            'porc_regalia' => 'required',
            'porc_captador_prbr' => 'required',
            'aplicar_porc_captador' => '',
            'porc_gerente' => 'required',
            'aplicar_porc_gerente' => '',
            'porc_cerrador_prbr' => 'required',
            'aplicar_porc_cerrador' => '',
            'porc_bonificacion' => 'required',
            'aplicar_porc_bonificacion' => '',
            'comision_bancaria' => '',
            'numero_recibo' => '',
            'asesor_captador_id' => 'required',
            'asesor_captador' => '',
            'asesor_cerrador_id' => 'required',
            'asesor_cerrador' => '',
            'pago_gerente' => '',
            'factura_gerente' => '',
            'pago_asesores' => '',
            'factura_asesores' => '',
            'pago_otra_oficina' => '',
            'pagado_casa_nacional' => '',
            'estatus_sistema_c21' => 'required',
            'reporte_casa_nacional' => '',
            'factura_AyS' => '',
            'comentarios' => '',
        ], [
            'fecha_reserva.date' => 'La <fecha de reserva> debe ser una fecha valida.',
            'fecha_firma.date' => 'La <fecha de la firma> debe ser una fecha valida.',
            'negociacion.required' => 'El campo <negociacion> es obligatorio',
            'nombre.required' => 'El campo <nombre> DE LA PROPIEDAD es obligatorio',
            'estatus' => 'El campo <estatus> es obligatorio',
            'moneda.required' => 'El campo <moneda> es obligatorio',
            'precio.required' => 'El campo <precio> es obligatorio',
            'comision.required' => 'El campo <comision> es obligatorio',
            'iva.required' => 'El campo <iva> es obligatorio',
            'porc_franquicia.required' => 'El campo <% franquicia> es obligatorio',
            'reportado_casa_nacional.required' => 'El campo <Reportado casa nacional> es obligatorio',
            'porc_regalia.required' => 'El campo <% Regalia> es obligatorio',
            'porc_captador_prbr.required' => 'El campo <Porcentaje captador PRBR> es obligatorio',
            'porc_gerente.required' => 'El campo <Porcentaje gerente> es obligatorio',
            'porc_cerrador_prbr.required' => 'El campo <Porcentaje cerrador PRBR> es obligatorio',
            'porc_bonificacion.required' => 'El campo <Porcentaje bonificacion> es obligatorio',
            'asesor_captador_id.required' => 'El campo <Asesor captador> es obligatorio',
            'asesor_cerrador_id.required' => 'El campo <Asesor cerrador> es obligatorio',
            'estatus_sistema_c21.required' => 'El campo <Estatus sistema C21> es obligatorio',
        ]);

        //print_r($data);
        if (!array_key_exists('estatus', $data)) $data['estatus'] = 'I';
        if (!array_key_exists('aplicar_porc_franquicia', $data))
            $data['aplicar_porc_franquicia'] = false;
        elseif ('on' == $data['aplicar_porc_franquicia'])
            $data['aplicar_porc_franquicia'] = true;
        if (!array_key_exists('aplicar_porc_franquicia_pagar_reportada', $data))
            $data['aplicar_porc_franquicia_pagar_reportada'] = false;
        elseif ('on' == $data['aplicar_porc_franquicia_pagar_reportada'])
            $data['aplicar_porc_franquicia_pagar_reportada'] = true;
        if (!array_key_exists('aplicar_franquicia_pagar_reportada_bruto', $data))
            $data['aplicar_franquicia_pagar_reportada_bruto'] = false;
        elseif ('on' == $data['aplicar_franquicia_pagar_reportada_bruto'])
            $data['aplicar_franquicia_pagar_reportada_bruto'] = true;
        if (!array_key_exists('aplicar_porc_captador', $data))
            $data['aplicar_porc_captador'] = false;
        elseif ('on' == $data['aplicar_porc_captador'])
            $data['aplicar_porc_captador'] = true;
        if (!array_key_exists('aplicar_porc_gerente', $data))
            $data['aplicar_porc_gerente'] = false;
        elseif ('on' == $data['aplicar_porc_gerente'])
            $data['aplicar_porc_gerente'] = true;
        if (!array_key_exists('aplicar_porc_cerrador', $data))
            $data['aplicar_porc_cerrador'] = false;
        elseif ('on' == $data['aplicar_porc_cerrador'])
            $data['aplicar_porc_cerrador'] = true;
        if (!array_key_exists('aplicar_porc_bonificacion', $data))
            $data['aplicar_porc_bonificacion'] = false;
        elseif ('on' == $data['aplicar_porc_bonificacion'])
            $data['aplicar_porc_bonificacion'] = true;
        if (!array_key_exists('pagado_casa_nacional', $data))
            $data['pagado_casa_nacional'] = false;
        elseif ('on' == $data['pagado_casa_nacional'])
            $data['pagado_casa_nacional'] = true;

        $data['user_actualizo'] = Auth::user()->id;
        //dd($data);
        $propiedad->update($data);

        return redirect()->route('propiedades.show', ['propiedad' => $propiedad]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Propiedad  $propiedad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Propiedad $propiedad)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        if (!(Auth::user()->is_admin)) {
            return redirect('/propiedades');
        }
        if ($propiedad->user_borro != null) {
            return redirect()->route('propiedades.show', ['propiedad' => $propiedad]);
        }

        $data['user_borro'] = Auth::user()->id;
        //$data['borrado_at'] = Carbon::now();
        $data['borrado_at'] = new Carbon();

        //dd($data);
        $propiedad->update($data);

        return redirect()->route('propiedades.index');
    }
}
