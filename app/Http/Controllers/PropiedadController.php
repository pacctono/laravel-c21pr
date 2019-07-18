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
                $tipos = explode(',',           // Crea arreglo de valores separados por ,
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
                'xdef' => $fila->COLUMN_DEFAULT,
                'tipo' => $tipo,
                'come' => $come,
            );
        }
        return $cols;
    }       // Final del metodo columnas.

    protected function totales($propiedades)
    {
        $filas = $propiedades->count();      // # propiedades para vista propiedades.index.
        $tPrecio = $propiedades->sum('precio'); // total precio para vista propiedades.index.
        $tFranquiciaSinIva = 0.00;          // 'O' de la hoja de calculo de Alirio.
        $tFranquiciaConIva = 0.00;          // 'P' de la hoja de calculo de Alirio.
        $tFranquiciaPagarR = 0.00;          // 'Q' de la hoja de calculo de Alirio.
        $tRegalia          = 0.00;          // 'S'.
        $tSanaf5PorCiento  = 0.00;          // 'T'.
        $tOficinaBrutoReal = 0.00;          // 'U'.
        $tBaseHonorariosSo = 0.00;          // 'V'.
        $tBaseParaHonorari = 0.00;          // 'W'.
        $tIngresoNetoOfici = 0.00;          // 'AC'.
        $tCaptadorPrbr     = 0.00;          // 'X'.
        $tGerente          = 0.00;          // 'Y'.
        $tCerradorPrbr     = 0.00;          // 'Z'.
        $tBonificaciones   = 0.00;          // 'AA'.
        $tComisionBancaria = $propiedades->sum('comision_bancaria');          // 'AB'.
        foreach ($propiedades->get() as $prop) {
            $tFranquiciaSinIva += $prop->franquiciaReservadoSinIva();
            $tFranquiciaConIva += $prop->franquiciaReservadoConIva();
            $tFranquiciaPagarR += $prop->franquiciaPagarReportada();
            $tRegalia          += $prop->regalia();
            $tSanaf5PorCiento  += $prop->sanaf5PorCiento();
            $tOficinaBrutoReal += $prop->oficinaBrutoReal();
            $tBaseHonorariosSo += $prop->baseHonorariosSocios();
            $tBaseParaHonorari += $prop->baseParaHonorarios();
            $tIngresoNetoOfici += $prop->ingresoNetoOficina();
            $tCaptadorPrbr     += $prop->captadorPrbr();
            $tGerente          += $prop->gerente();
            $tCerradorPrbr     += $prop->cerradorPrbr();
            $tBonificaciones   += $prop->bonificaciones();
        }
        $tPrecio           = number_format($tPrecio, 2, ',', '.');
        $tFranquiciaSinIva = number_format($tFranquiciaSinIva, 2, ',', '.');
        $tFranquiciaConIva = number_format($tFranquiciaConIva, 2, ',', '.');
        $tFranquiciaPagarR = number_format($tFranquiciaPagarR, 2, ',', '.');
        $tRegalia          = number_format($tRegalia, 2, ',', '.');
        $tSanaf5PorCiento  = number_format($tSanaf5PorCiento, 2, ',', '.');
        $tOficinaBrutoReal = number_format($tOficinaBrutoReal, 2, ',', '.');
        $tBaseHonorariosSo = number_format($tBaseHonorariosSo, 2, ',', '.');
        $tBaseParaHonorari = number_format($tBaseParaHonorari, 2, ',', '.');
        $tIngresoNetoOfici = number_format($tIngresoNetoOfici, 2, ',', '.');
        $tCaptadorPrbr     = number_format($tCaptadorPrbr, 2, ',', '.');
        $tGerente          = number_format($tGerente, 2, ',', '.');
        $tCerradorPrbr     = number_format($tCerradorPrbr, 2, ',', '.');
        $tBonificaciones   = number_format($tBonificaciones, 2, ',', '.');
        $tComisionBancaria = number_format($tComisionBancaria, 2, ',', '.');

        return array ($filas, $tPrecio, $tFranquiciaSinIva, $tFranquiciaConIva,
                    $tFranquiciaPagarR, $tRegalia, $tSanaf5PorCiento,
                    $tOficinaBrutoReal, $tBaseHonorariosSo, $tBaseParaHonorari,
                    $tIngresoNetoOfici, $tCaptadorPrbr, $tGerente, $tCerradorPrbr,
                    $tBonificaciones, $tComisionBancaria);
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
        $propiedades = $propiedades->orderBy($orden);   // Ordenar los items de los propiedades.
        if ('user_id' == $orden) {              // Si se pidió ordenar por id de usuario,
            $propiedades = $propiedades->orderBy('fecha_reserva');   // ordenar por fecha_reserva en cada usuario.
        }
        list ($filas, $tPrecio, $tFranquiciaSinIva, $tFranquiciaConIva, $tFranquiciaPagarR,
                $tRegalia, $tSanaf5PorCiento, $tOficinaBrutoReal, $tBaseHonorariosSo,
                $tBaseParaHonorari, $tIngresoNetoOfici, $tCaptadorPrbr, $tGerente,
                $tCerradorPrbr, $tBonificaciones, $tComisionBancaria) =
                $this->totales($propiedades);
        $propiedades = $propiedades->paginate(10);      // Pagina la impresión de 10 en 10
// Devolver las fechas sin la hora. Los diez primeros caracteres son: yyyy-mm-dd.
        session(['fecha_desde' => $fecha_desde,    // Asignar valores en sesión.
                    'fecha_hasta' => $fecha_hasta, 'captador' => $captador,
                    'cerrador' => $cerrador]);
        return view('propiedades.index', compact('title', 'users', 'propiedades',
                    'filas', 'tPrecio', 'tFranquiciaSinIva', 'tFranquiciaConIva',
                    'tFranquiciaPagarR', 'tRegalia', 'tSanaf5PorCiento',
                    'tOficinaBrutoReal', 'tBaseHonorariosSo', 'tBaseParaHonorari',
                    'tIngresoNetoOfici', 'tCaptadorPrbr', 'tGerente', 'tCerradorPrbr',
                    'tBonificaciones', 'tComisionBancaria',
                    'ruta', 'fecha_desde', 'fecha_hasta', 'captador', 'cerrador'));
    }       // Final del metodo index.

    public function grabarArchivo()
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        if (1 == Auth::user()->is_admin) {
            $users   = User::get();                     // Todos los usuarios (asesores).
            $users[0]['name'] = 'Asesor otra oficina';
            $propiedades = Propiedad::where('id', '>', 0);   // condición dummy, solo para continuar armando la consulta.
        } else {
            return redirect('/propiedades');
        }

        list ($filas, $tPrecio, $tFranquiciaSinIva, $tFranquiciaConIva, $tFranquiciaPagarR,
                $tRegalia, $tSanaf5PorCiento, $tOficinaBrutoReal, $tBaseHonorariosSo,
                $tBaseParaHonorari, $tIngresoNetoOfici, $tCaptadorPrbr, $tGerente,
                $tCerradorPrbr, $tBonificaciones, $tComisionBancaria) =
                $this->totales($propiedades);
        $propiedades = $propiedades->get();
        $contenido = '';
        foreach ($propiedades as $p) {
            $contenido .= $p->id . ';' .                            // #0  A
                        $p->codigo . ';' .                          // #1  B
                        $p->reserva_en . ';' .                      // #2  C
                        $p->firma_en . ';' .                        // #3  D
                        $p->negociacion . ';' .                     // #4  E
                        '"' . $p->nombre . '"' . ';' .              // #5  F
                        $p->estatus . ';' .				            // #6
                        $p->moneda . ';' .				            // #7
                        $p->precio . ';' .				            // #8  G
                        $p->comision . ';' .				        // #9  H
                        $p->reservaSinIva() . ';' .		            // #10 I
                        $p->iva . ';' .				                // #11 J
                        $p->reservaConIva() . ';' .		            // #12 K
                        $p->compartidoConIva() . ';' .		        // #13 L
                        $p->compartidoSinIva() . ';' .		        // #14 M
                        $p->lados . ';' .				            // #15 N
                        $p->franquiciaReservadoSinIva() . ';' .		// #16 O
                        $p->franquiciaReservadoConIva() . ';' .		// #17 P
                        $p->porc_franquicia . ';' .				    // #18
                        $p->franquiciaPagarReportada() . ';' .		// #19 Q
                        $p->reportado_casa_nacional . ';' .			// #20 R
                        $p->porc_regalia . ';' .				    // #21
                        $p->regalia() . ';' .				        // #22 S
                        $p->sanaf5PorCiento() . ';' .				// #23 T
                        $p->oficinaBrutoReal() . ';' .				// #24 U
                        $p->baseHonorariosSocios() . ';' .			// #25 V
                        $p->baseParaHonorarios() . ';' .			// #26 W
                        $p->porc_captador_prbr . ';' .				// #27
                        $p->captadorPrbr() . ';' .				    // #28 X
                        $p->porc_gerente . ';' .			    	// #29
                        $p->gerente() . ';' .				        // #30 Y
                        $p->porc_cerrador_prbr . ';' .				// #31
                        $p->cerradorPrbr() . ';' .				    // #32 Z
                        $p->porc_bonificacion . ';' .				// #33
                        $p->bonificaciones() . ';' .				// #34 AA
                        $p->comision_bancaria . ';' .				// #35 AB
                        $p->ingresoNetoOficina() . ';' .			// #36 AC
                        '"' . $p->numero_recibo . '"' . ';' .       // #37 AD
                        $p->asesor_captador_id . ';' .				// #38 AE
                        $p->asesor_captador . ';' .				    // #39 AE
                        $p->asesor_cerrador_id . ';' .				// #40 AF
                        $p->asesor_cerrador . ';' .				    // #41 AF
                        '"' . $p->pago_gerente . '"' . ';' .        // #42 AG
                        '"' . $p->factura_gerente . '"' . ';' .     // #43 AH
                        '"' . $p->pago_asesores . '"' . ';' .       // #44 AI
                        '"' . $p->factura_asesores . '"' . ';' .    // #45 AJ
                        '"' . $p->pago_otra_oficina . '"' . ';' .   // #46 AK
                        $p->pagado_casa_nacional . ';' .			// #47 AL
                        $p->estatus_sistema_c21 . ';' .				// #48 AM
                        $p->reporte_casa_nacional . ';' .			// #49 AN
                        $p->factura_AyS . ';' .				        // #50 AP
                        '"' . $p->comentarios . '"' . "\n";         // #51 AO
        }
        $contenido .= $filas . ';' . $tPrecio . ';' . $tFranquiciaSinIva . ';' .
		            $tFranquiciaConIva . ';' . $tFranquiciaPagarR . ';' .
	            	$tRegalia . ';' . $tSanaf5PorCiento . ';' . $tOficinaBrutoReal .
		            ';' . $tBaseHonorariosSo . ';' .  $tBaseParaHonorari . ';' .
                    $tIngresoNetoOfici . ';' . $tCaptadorPrbr . ';' . $tGerente .
                    ';' . $tCerradorPrbr . ';' . $tBonificaciones . ';' .
                    $tComisionBancaria . "\n";
        //dd($contenido);

        Storage::put('public/asesores.txt', $users);
        Storage::put('public/propiedades.txt', $contenido);

        return redirect('/propiedades');
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
            'bonificacion' => '',
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
            'bonificacion' => '',
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
