<?php

namespace App\Http\Controllers;

use App\Propiedad;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;        // PC
use Illuminate\Support\Facades\DB;          // PC
use Illuminate\Support\Facades\Storage;     // PC
use Carbon\Carbon;                          // PC
use App\MisClases\Fecha;

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
        $arrRetorno[] = round($propiedades->get()->sum('franquiciaReservadoSinIva'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('franquiciaReservadoConIva'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('franquiciaPagarReportada'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('regalia'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('sanaf5PorCiento'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('oficinaBrutoReal'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('baseHonorariosSocios'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('baseParaHonorarios'), 2);
        $props = clone $propiedades;
        $arrRetorno[] = round($props->where('asesor_captador_id', '>', 1)
                                          ->get()->sum('captadorPrbr'), 2);         // Indice = 12
        $arrRetorno[] = round($propiedades->get()->sum('gerente'), 2);
        $props = clone $propiedades;
        $arrRetorno[] = round($props->where('asesor_cerrador_id', '>', 1)
                                          ->get()->sum('cerradorPrbr'), 2);         // Indice = 14
        $arrRetorno[] = round($propiedades->get()->sum('bonificaciones'), 2);
        $arrRetorno[] = round($propiedades->get()->sum('ingresoNetoOficina'), 2);
        $arrRetorno[] = round($propiedades->sum('comision_bancaria'), 2);          // 'AB'.

        $props = clone $propiedades;                     // Los query modifican el arreglo propiedades.
        if (0 < $cap) {
            $tLadosCap = $props->where('asesor_captador_id', $cap)->count();
            $tCaptadorPrbrSel = round($props->where('asesor_captador_id', $cap) // Aunque aplica el 'where' de
                                            ->get()->sum('captadorPrBr'), 2);   // la linea anterior. Por si acaso.
        } else {
            $tLadosCap = $props->where('asesor_captador_id', '>', 1)->count();
            $tCaptadorPrbrSel = 0.00;
        }
        $props = clone $propiedades;                     // Los query modifican el arreglo propiedades.
        if (0 < $cer) {
            $tLadosCer = $props->where('asesor_cerrador_id', $cer)->count();
            $tCerradorPrbrSel = round($props->where('asesor_cerrador_id', $cer) // Aunque aplica el 'where' de
                                            ->get()->sum('cerradorPrBr'), 2);   // la linea anterior. Por si acaso.
        } else {
            $tLadosCer = $props->where('asesor_cerrador_id', '>', 1)->count();
            $tCerradorPrbrSel = 0.00;
        }
        array_push($arrRetorno, $tCaptadorPrbrSel, $tCerradorPrbrSel, $tLadosCap, $tLadosCer);
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
            session(['fecha_desde' => '', 'fecha_hasta' => '',
                        'captador' => '0', 'cerrador' => '0']);
        }
/*
 * Manejo de las variables de la forma superior. $dato (fecha_desde, fecha_hasta,
 * asesor captador y asesor cerrador).
 * Cuando el arreglo $dato contiene un solo item, este es el número de página (page=n).
 * Si el arreglo $dato está vacio (count($arreglo) == 0, esta opcion fue manejada arriba),
 * es una ruta 'GET' con o sin $orden.
 * Si $dato tiene más de 1 item. Fue seleccionado una fecha y un asesor.
 */
        if (1 >= count($dato)) {
            $fecha_desde = session('fecha_desde', '');
            $fecha_hasta = session('fecha_hasta', '');
            $captador    = session('captador', '0');
            $cerrador    = session('cerrador', '0');
        } else {
            if (isset($dato['captador'])) $captador = $dato['captador'];
            else $captador = 0;
            if (isset($dato['cerrador'])) $cerrador = $dato['cerrador'];
            else $cerrador = 0;

            if ('' == $dato['fecha_desde'])
                $fecha_desde = (new Carbon(Propiedad::min('fecha_reserva')));
            else $fecha_desde = (new Carbon($dato['fecha_desde']));
            if ('' == $dato['fecha_hasta'])
                $fecha_hasta = (new Carbon(Propiedad::max('fecha_reserva')));
            else $fecha_hasta = (new Carbon($dato['fecha_hasta']));
/*            list ($fecha_desde, $fecha_hasta) = Fecha::periodo($dato, $fecha_min,
                                                                $fecha_max);*/
        }
//        dd($dato, $fecha_desde, $fecha_hasta);
        if ('' == $orden or null == $orden) {
            $orden = 'id';
        }
        if (1 == Auth::user()->is_admin) {
            $users   = User::get(['id', 'name']);     // Todos los usuarios (asesores).
            $users[0]['name'] = 'Asesor otra oficina';
            $propiedades = Propiedad::where('id', '>', 0);   // condición dummy, solo para continuar armando la consulta.
        } else {
            $user   = User::find(Auth::user()->id);
            $title .= ' de ' . $user->name;
            $propiedades = $user()->propiedades()  // OJO: Deberia buscar todos los captados y cerrados por este id.
                            ->whereNull('user_borro');
        }

        if (0 < $captador) {      // Se selecciono un asesor captador o esta conectado.
            $propiedades = $propiedades->where('asesor_captador_id', $captador);
        }
        if (0 < $cerrador) {      // Se selecciono un asesor cerrador o esta conectado.
            if ($cerrador == $captador)
                $propiedades = $propiedades->orWhere('asesor_cerrador_id', $cerrador);
            else $propiedades = $propiedades->where('asesor_cerrador_id', $cerrador);
        }
        if ('' != $fecha_desde and '' != $fecha_hasta) {    // Se seleccionaron fechas.
            $fecha_desde = substr($fecha_desde, 0, 10);
            $fecha_hasta = substr($fecha_hasta, 0, 10);
            $propiedades = $propiedades
                            ->whereBetween('fecha_reserva', [$fecha_desde, $fecha_hasta]);
        } else {
            $propiedades = $propiedades
                            ->where('fecha_reserva', '<=', now(Fecha::$ZONA));
        }
        if ((0 == $captador) and (0 == $cerrador)) {
            $propiedades = $propiedades->orWhereNull('fecha_reserva');
        }
        $propiedades = $propiedades->orderBy($orden);   // Ordenar los items de los propiedades.
        if ('user_id' == $orden) {              // Si se pidió ordenar por id de usuario,
            $propiedades = $propiedades->orderBy('fecha_reserva');   // ordenar por fecha_reserva en cada usuario.
        }
        list ($filas, $tPrecio, $tLados, $tCompartidoConIva, $tFranquiciaSinIva,
                $tFranquiciaConIva, $tFranquiciaPagarR, $tRegalia, $tSanaf5PorCiento,
                $tOficinaBrutoReal, $tBaseHonorariosSo, $tBaseParaHonorari,
                $tCaptadorPrbr, $tGerente, $tCerradorPrbr, $tBonificaciones,
                $tIngresoNetoOfici, $tComisionBancaria, $tCaptadorPrbrSel,
                $tCerradorPrbrSel, $tLadosCap, $tLadosCer) =
                $this->totales($propiedades, True, $captador, $cerrador);
        //$propiedades = $propiedades->paginate(10);      // Pagina la impresión de 10 en 10
        if ($paginar) $propiedades = $propiedades->paginate(10);      // Pagina la impresión de 10 en 10
        else $propiedades = $propiedades->get();                      // Mostrar todos los registros.
// Devolver las fechas sin la hora. Los diez primeros caracteres son: yyyy-mm-dd.
        session(['fecha_desde' => $fecha_desde,    // Asignar valores en sesión.
                    'fecha_hasta' => $fecha_hasta, 'captador' => $captador,
                    'cerrador' => $cerrador]);
        return view('propiedades.index', compact('title', 'users', 'propiedades',
                    'filas', 'tPrecio', 'tCompartidoConIva', 'tLados',
                    'tFranquiciaSinIva', 'tFranquiciaConIva', 'tFranquiciaPagarR',
                    'tRegalia', 'tSanaf5PorCiento', 'tOficinaBrutoReal',
                    'tBaseHonorariosSo', 'tBaseParaHonorari', 'tIngresoNetoOfici',
                    'tCaptadorPrbr', 'tGerente', 'tCerradorPrbr', 'tBonificaciones',
                    'tComisionBancaria', 'tCaptadorPrbrSel', 'tCerradorPrbrSel',
                    'tLadosCap', 'tLadosCer', 'ruta', 'fecha_desde', 'fecha_hasta',
                    'captador', 'cerrador', 'paginar'));
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
        $fecha = DB::select("SELECT DATE_FORMAT(fecha_reserva, '%Y') AS Agno,
                                    DATE_FORMAT(fecha_reserva, '%m') AS Mes
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
                    $props = $props->whereNull('fecha_reserva');
                } else {
                    $props = $props->whereYear('fecha_reserva', $agno)
                                    ->whereMonth('fecha_reserva', $mes);
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
                                        ->whereNull('fecha_reserva');
                    } else {
                        $props = $props->where(function ($Q) use ($user) {
                                            $Q->where('asesor_captador_id', $user->id)
                                                ->orWhere('asesor_cerrador_id', $user->id);
                                        })
                                        ->whereYear('fecha_reserva', $agno)
                                        ->whereMonth('fecha_reserva', $mes);
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
                                        ->whereNull('fecha_reserva');
                    } else {
                        $props = $props->where(function ($Q) use ($user) {
                                            $Q->where('asesor_captador_id', $user->id)
                                                ->orWhere('asesor_cerrador_id', $user->id);
                                        })
                                        ->whereYear('fecha_reserva', $agno)
                                        ->whereMonth('fecha_reserva', $mes);
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
                        $p->reservaSinIva(), $p->iva, $p->reservaConIva(),
                        $p->compartidoConIva(), $p->compartidoSinIva(),
                        $p->lados, $p->franquiciaReservadoSinIva(),
                        $p->franquiciaReservadoConIva(), $p->porc_franquicia,
                        $p->franquiciaPagarReportada(), $p->reportado_casa_nacional,
                        $p->porc_regalia, $p->regalia(), $p->sanaf5PorCiento(),
                        $p->oficinaBrutoReal(), $p->baseHonorariosSocios(),
                        $p->baseParaHonorarios(), $p->asesor_captador_id,
                        $p->asesor_captador, $p->porc_captador_prbr, $p->captadorPrbr(),
                        $p->porc_gerente, $p->gerente(), $p->asesor_cerrador_id,
                        $p->asesor_cerrador, $p->porc_cerrador_prbr, $p->cerradorPrbr(),
                        $p->porc_bonificacion, $p->bonificaciones(), nulo($p->comision_bancaria, 0),
                        $p->ingresoNetoOficina(), nulo($p->numero_recibo), nulo($p->pago_gerente),
                        nulo($p->factura_gerente), nulo($p->pago_asesores), nulo($p->factura_asesores),
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

        return view('propiedades.create', compact('title', 'users', 'cols', 'exito'));
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
            'estatus_sistema_c21' => 'required',
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
            'estatus_sistema_c21.required' => 'El campo <Estatus sistema C21> es obligatorio',
        ]);

        //$data['user_id'] = Auth::user()->id;
        //$data['user_id'] = intval($data['user_id']);

/*        if (null != $data['fecha_reserva'])
            $data['fecha_reserva'] = Carbon::createFromFormat('Y-m-d', $data['fecha_reserva']);
        if (null != $data['fecha_firma'])
            $data['fecha_firma'] = Carbon::createFromFormat('Y-m-d', $data['fecha_firma']);
        dd($data);*/
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
            'comision_bancaria' => $data['comision_bancaria'],
            'numero_recibo' => $data['numero_recibo'],
            'asesor_captador_id' => $data['asesor_captador_id'],
            'asesor_captador' => $data['asesor_captador'],
            'asesor_cerrador_id' => $data['asesor_cerrador_id'],
            'asesor_cerrador' => $data['asesor_cerrador'],
            'pago_gerente' => $data['pago_gerente'],
            'factura_gerente' => $data['factura_gerente'],
            'pago_asesores' => $data['pago_asesores'],
            'factura_asesores' => $data['factura_asesores'],
            'pago_otra_oficina' => $data['pago_otra_oficina'],
//            'pago_otra_oficina' => null,
            'pagado_casa_nacional' => $data['pagado_casa_nacional'],
            'estatus_sistema_c21' => $data['estatus_sistema_c21'],
            'reporte_casa_nacional' => $data['reporte_casa_nacional'],
            'comentarios' => $data['comentarios'],
            'factura_AyS' => $data['factura_AyS'],
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
        if (1 == Auth::user()->is_admin) {
            return view('propiedades.show', compact('propiedad', 'rutRetorno', 'col_id'));
        }
        if ($propiedad->user_borro != null) {
            return redirect()->back();
        }
        if ($propiedad->user->id == Auth::user()->id) {
            return view('propiedades.show', compact('propiedad', 'rutRetorno', 'col_id'));
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
        if ((1 == Auth::user()->is_admin) or ($propiedad->user->id == Auth::user()->id)) {
            return view('propiedades.edit', ['propiedad' => $propiedad, 'users' => $users,
                                                'cols' => $cols, 'title' => $title]);
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

        if (1 != Auth::user()->is_admin) {
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
