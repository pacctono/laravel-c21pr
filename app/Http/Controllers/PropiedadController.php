<?php

namespace App\Http\Controllers;

use App\Propiedad;
use App\User;
use App\Price;
use App\Tipo;
use App\Ciudad;
use App\Caracteristica;
use App\Municipio;
use App\Estado;
use App\Cliente;
use App\Venezueladdn;
use \App\Mail\ReporteCierre;
use App\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;        // PC
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;          // PC
use Carbon\Carbon;                          // PC
use Jenssegers\Agent\Agent;                 // PC
use App\MisClases\Fecha;                    // PC
use App\MisClases\General;               // PC

class PropiedadController extends Controller
{
    protected $tipo = 'Propiedad';
    protected $tipoPlural = 'Propiedades';
    protected $lineasXPagina = General::LINEASXPAGINA;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($orden=null, $accion='html')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $title = $this->tipoPlural;
        $ruta = request()->path();
        $dato = request()->all();
        //dd($ruta, $dato);
        if (1 >= count($dato)) $paginar = True; // Inicialmente, el arreglo '$dato' esta vacio.
        else $paginar = False;
// Todo se inicializa, cuando se selecciona 'Propiedades' desde el menu horizontal principal.
        if (('GET' == request()->method()) and ('' == $orden) and (0 == count($dato))) {
            session(['fecha_desde' => '', 'fecha_hasta' => '', 'anoc' => '',
                    'negociacion' => '', 'codigo' => '', 'estatus' => '', 'desde' => '',
                    'hasta' => '', 'asesor' => '0', 'captador' => '0', 'cerrador' => '0']);
        }
/*
 * Manejo de las variables de la forma superior. $dato (fecha_desde, fecha_hasta, estatus,
 * asesor captador y asesor cerrador).
 * Cuando el arreglo $dato contiene un solo item, este es el número de página (page=n).
 * Si el arreglo $dato está vacio (count($arreglo) == 0, esta opcion fue manejada arriba),
 * es una ruta 'GET' con o sin $orden.
 * Si $dato tiene más de 1 item. Fue seleccionado una fecha y/o estatus y/o un asesor.
 */
        if (1 >= count($dato)) {    // Arriba, paginar es True.
            $fecha_desde = session('fecha_desde', '');
            $fecha_hasta = session('fecha_hasta', '');
            $anoc        = session('anoc', '');
            $codigo      = session('codigo', '');
            $negociacion = session('negociacion', '');
            $estatus     = session('estatus', '');
            $desde       = session('desde', '');
            $hasta       = session('hasta', '');
            $captador    = session('captador', '0');
            $cerrador    = session('cerrador', '0');
            $asesor      = session('asesor', '0');
        } else {
            if (isset($dato['captador'])) $captador = $dato['captador'];
            else $captador = 0;
            if (isset($dato['cerrador'])) $cerrador = $dato['cerrador'];
            else $cerrador = 0;
            if (isset($dato['asesor'])) $asesor   = $dato['asesor'];
            else $asesor = 0;
            $captador = $asesor;
            $cerrador = $asesor;

            if (isset($dato['anoc'])) $anoc = $dato['anoc'];
            else $anoc = '';
            if (isset($dato['codigo'])) $codigo = $dato['codigo'];
            else $codigo = '';
            if (isset($dato['negociacion'])) $negociacion = $dato['negociacion'];
            else $negociacion = '';
            if (isset($dato['estatus'])) $estatus = $dato['estatus'];
            else $estatus = '';
            if (isset($dato['desde'])) $desde = $dato['desde'];
            else $desde = '';
            if (isset($dato['hasta'])) $hasta = $dato['hasta'];
            else $hasta = '';

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
// En caso de volver luego de haber enviado un correo, ver el metodo 'self::correoReporteCierre'.
        $alertar = 0;
        if (isset($_GET['correo']) and ($correo = $_GET['correo'])) {
            if ('S' == $correo) {
                $alertar = 1;
            } elseif ('N' == $correo) {
                $alertar = -1;
            }
        }
        $sentido = 'asc';
        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
            $sentido = 'desc';
        }
        if (Auth::user()->is_admin) {
//            $users   = User::get(['id', 'name']);     // Todos los usuarios (asesores).
            $users   = User::where('activo', True)->get(['id', 'name']);     // Todos los usuarios (asesores), excepto los no activos.
            $users[0]['name'] = 'Asesor otra oficina';
            $propiedades = Propiedad::where('id', '>', 0);   // condición dummy, solo para continuar armando la consulta.
//            $asesor = 0;
            $title = 'Listado de ' . $title;
        } else {
            $user   = User::find(Auth::user()->id);
            $title .= ' de ' . $user->name . ' (incluye [A]ctivas)';
            $asesor = $user->id;
            $propiedades = Propiedad::where(
                                    function ($query) use ($asesor) {
                                $query->where('user_id', $asesor)
                                        ->orWhere('asesor_captador_id', $asesor)
                                        ->orWhere('asesor_cerrador_id', $asesor)
                                        ->orWhere('estatus', 'A');
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
        if ('' != $anoc) {       // Se selecciono un anoc.
            $propiedades = $propiedades->where(DB::raw("YEAR(created_at)"), $anoc);
        }
        if ('' != $codigo) {       // Se selecciono un codigo.
            $propiedades = $propiedades->where('codigo', 'like', $codigo.'%');
        }
        if ('' != $negociacion) {       // Se selecciono una negociacion.
            $propiedades = $propiedades->where('negociacion', $negociacion);
        }
        if ('' != $estatus) {       // Se selecciono un estatus.
            if ('V' == $estatus) $propiedades = $propiedades->whereIn('estatus', ['P', 'C']);
            elseif ('X' == $estatus) {
                $propiedades = $propiedades
                                ->where('created_at', '<', (new Carbon(now()))->subDays(90))
                                ->where('estatus', 'A');
            }
            else $propiedades = $propiedades->where('estatus', $estatus);
        }
        /*$precios = Price::get();    // Todos los precios, incluye 'descripcion' y 'descripcion_alquiler'.
        if ('' != $precio) {       // Se selecciono un precio.
            $menor = $precios->where('id', $precio)->first()->menor;
            $mayor = $precios->where('id', $precio)->first()->mayor;
            $propiedades = $propiedades->whereBetween('precio', [$menor, $mayor]);
        }*/
        if ('' != $desde) {       // Se selecciono un precio minimo.
            if ('' == $hasta) $propiedades = $propiedades->where('precio', '>=', $desde);
            else $propiedades = $propiedades->whereBetween('precio', [$desde, $hasta]);
        } elseif ('' != $hasta) $propiedades = $propiedades->where('precio', '<=', $hasta);
        if ('' != $fecha_desde and '' != $fecha_hasta) {    // Se seleccionaron fechas.
            $fecha_desde = substr($fecha_desde, 0, 10);
            $fecha_hasta = substr($fecha_hasta, 0, 10);
            $propiedades = $propiedades->where(function ($query) use ($fecha_desde, $fecha_hasta) {
                                $query->whereBetween('fecha_firma', [$fecha_desde, $fecha_hasta])
                                    ->orWhereNull('fecha_firma');
                            });
        } else {
            $propiedades = $propiedades->where(function ($query) use ($fecha_desde, $fecha_hasta) {
                                $query->where('fecha_firma', '<=', now(Fecha::$ZONA))
                                    ->orWhereNull('fecha_firma');
                            });
        }
        if (0 === strpos($orden, 'fecha')) $sentido = 'desc';
        $propiedades = $propiedades->orderBy($orden, $sentido);   // Ordenar los items de las propiedades.
        if ('user_id' == $orden) {              // Si se pidió ordenar por id de usuario. NUNCA SUCEDERA.
            $propiedades = $propiedades->orderBy('fecha_firma', 'desc');   // ordenar por fecha_firma en cada usuario.
        }
        list ($filas, $tPrecio, $tLados, $tCompartidoConIva, $tFranquiciaSinIva,
                $tFranquiciaConIva, $tFranquiciaPagarR, $tRegalia, $tSanaf5PorCiento,
                $tOficinaBrutoReal, $tBaseHonorariosSo, $tBaseParaHonorari,
                $tCaptadorPrbr, $tGerente, $tCerradorPrbr, $tBonificaciones,
                $tComisionBancaria, $tIngresoNetoOfici, $tPrecioVentaReal, $tPuntos,
                $tCaptadorPrbrSel, $tCerradorPrbrSel, $tLadosCap, $tLadosCer,
                $tPvrCaptadorPrbrSel, $tPvrCerradorPrbrSel,
                $tPuntosCaptador, $tPuntosCerrador) =
                Propiedad::totales($propiedades, True, (($captador)?$captador:(($asesor)?$asesor:0)),
                                (($cerrador)?$cerrador:(($asesor)?$asesor:0)));
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        $paginar = ($paginar)?!($movil or ('html' != $accion)):$paginar;
        if ($paginar) $propiedades = $propiedades->paginate($this->lineasXPagina);      // Pagina la impresión de 10 en 10
        else $propiedades = $propiedades->get();                      // Mostrar todos los registros.
// Devolver las fechas sin la hora. Los diez primeros caracteres son: yyyy-mm-dd.
        $cols = General::columnas('propiedads');
        $arrEstatus = $cols['estatus']['opcion'];
        $negociaciones = $cols['negociacion']['opcion'];
        unset($cols);
        $anosc = Propiedad::select(DB::raw("distinct YEAR(created_at) AS anoc"))->get();
        session(['fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta,    // Asignar valores en sesión.
                    'anoc' => $anoc, 'codigo' => $codigo, 'negociacion' => $negociacion,
                    'estatus' => $estatus, 'desde' => $desde, 'hasta' => $hasta,
                    'asesor' => $asesor, 'captador' => $captador, 'cerrador' => $cerrador,
                    'orden' => $orden
                ]);
        /*dd($tCaptadorPrbr, $tCerradorPrbr, $tPrecioVentaReal, $tCaptadorPrbrSel,
                $tCerradorPrbrSel, $tLadosCap, $tLadosCer,
                $tPvrCaptadorPrbrSel + $tPvrCerradorPrbrSel);*/
//        return view((($movil)?'celular.indexPropiedades':'propiedades.index'),
        if ('html' == $accion)
            return view('propiedades.index',
                    compact('title', 'users', 'propiedades', 'movil',
                    'filas', 'tPrecio', 'tCompartidoConIva', 'tLados',
                    'tFranquiciaSinIva', 'tFranquiciaConIva', 'tFranquiciaPagarR',
                    'tRegalia', 'tSanaf5PorCiento', 'tOficinaBrutoReal',
                    'tBaseHonorariosSo', 'tBaseParaHonorari', 'tIngresoNetoOfici',
                    'tCaptadorPrbr', 'tGerente', 'tCerradorPrbr', 'tBonificaciones',
                    'tComisionBancaria', 'tPrecioVentaReal', 'tPuntos',
                    'tCaptadorPrbrSel', 'tCerradorPrbrSel', 'tLadosCap', 'tLadosCer',
                    'tPvrCaptadorPrbrSel', 'tPvrCerradorPrbrSel',
                    'ruta', 'fecha_desde', 'fecha_hasta', 'arrEstatus', 'negociaciones',
                    'anosc', 'anoc', 'codigo', 'desde', 'hasta',
                    'asesor', 'captador', 'cerrador', 'tPuntosCaptador', 'tPuntosCerrador',
                    'estatus', 'negociacion', 'orden', 'paginar', 'alertar', 'accion'));
        $html = view('propiedades.index',
                    compact('title', 'users', 'propiedades', 'movil',
                    'filas', 'tPrecio', 'tCompartidoConIva', 'tLados',
                    'tFranquiciaSinIva', 'tFranquiciaConIva', 'tFranquiciaPagarR',
                    'tRegalia', 'tSanaf5PorCiento', 'tOficinaBrutoReal',
                    'tBaseHonorariosSo', 'tBaseParaHonorari', 'tIngresoNetoOfici',
                    'tCaptadorPrbr', 'tGerente', 'tCerradorPrbr', 'tBonificaciones',
                    'tComisionBancaria', 'tPrecioVentaReal', 'tPuntos',
                    'tCaptadorPrbrSel', 'tCerradorPrbrSel', 'tLadosCap', 'tLadosCer',
                    'tPvrCaptadorPrbrSel', 'tPvrCerradorPrbrSel',
                    'ruta', 'fecha_desde', 'fecha_hasta', 'arrEstatus', 'negociaciones',
                    'anosc', 'anoc', 'codigo', 'desde', 'hasta',
                    'asesor', 'captador', 'cerrador', 'tPuntosCaptador', 'tPuntosCerrador',
                    'estatus', 'negociacion', 'orden', 'paginar', 'alertar', 'accion'))
                ->render();
        General::generarPdf($html, 'propiedades', $accion);
    }       // Final del metodo index.

    public function grabarArchivo()
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        if (!Auth::user()->is_admin) {
            return redirect()->back();
        }

        General::grabarArchivo();

        //return redirect('/propiedades');
        return redirect()->back();
    }

    function compararXNombre($cliente1, $cliente2) {
        return strcmp($cliente1->name, $cliente2->name);
    }

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

        $title = 'Crear ' . $this->tipo;
        $exito = session('exito', '');
        session(['exito' => '']);
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.

        $cols = General::columnas('propiedads');
        $colsC = General::columnas('clientes');
        $tiposC = $colsC['tipo']['opcion'];
        $tipoCXDef = $colsC['tipo']['xdef'];
        unset($colsC);
        if (!Auth::user()->is_admin) unset($tiposC['F']);
        $tipos = Tipo::all();
        $ciudades = Ciudad::all();
        $caracteristicas = Caracteristica::all();
        $municipios = Municipio::all();
        $estados = Estado::all();
        $clientes = Cliente::all()->all();
        //dd(self::compararXNombre($clientes[0], $clientes[1]));
        $otroCliente = array_shift($clientes);          // Primer id de cliente 'Otro', es sacado del arreglo.
        usort($clientes, "self::compararXNombre");      // Ordena clientes sin 'Otro'.
        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();
        array_unshift($clientes, $otroCliente);         // Agrega al inicio el cliente 'Otro'. Sacado del arreglo, anteriormente.
//        return view((($movil)?'celular.createPropiedades':'propiedades.crear'),
        return view('propiedades.crear',
                    compact('title', 'users', 'tipos', 'ciudades', 'caracteristicas',
                    'municipios', 'estados', 'clientes', 'ddns', 'cols', 'tiposC',
                    'tipoCXDef', 'exito'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->path(), $request->fullUrl(), $request->url(), $request->root());   //"propiedades", "http://c21pr.vb/propiedades", "http://c21pr.vb/propiedades", "http://c21pr.vb"
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'codigo' => ['required', 'digits_between:6,8'],
            'fecha_reserva' => ['sometimes', 'nullable', 'date'],
            'forma_pago_reserva' => '',
            'factura_reserva' => '',
            'fecha_firma' => ['sometimes', 'nullable', 'date'],
            'forma_pago_firma' => '',
            'factura_firma' => '',
            'negociacion' => 'required',
            'nombre' => 'required',
            'exclusividad' => '',
            'tipo_id' => '',
            'metraje' => '',
            'habitaciones' => '',
            'banos' => '',
            'niveles' => '',
            'puestos' => '',
            'anoc' => '',
            'caracteristica_id' => '',
            'descripcion' => '',
            'direccion' => '',
            'ciudad_id' => '',
            'codigo_postal' => '',
            'municipio_id' => '',
            'estado_id' => '',
            'cliente_id' => '',
            'estatus' => 'required',
            'moneda' => 'required',
            'precio' => 'required',
            'comision' => ['required', 'min:0.00', 'max:50.00'],
            'iva' => ['required', 'min:0.00', 'max:50.00'],
            'lados' => ['in:1,2'],
            'porc_franquicia' => ['required', 'min:0.00', 'max:50.00'],
            'reportado_casa_nacional' => 'required',
            'porc_regalia' => ['required', 'min:0.00', 'max:50.00'],
            'porc_compartido' => ['required', 'min:0.00', 'max:90.00'],
            'porc_captador_prbr' => ['required', 'min:0.00', 'max:50.00'],
            'porc_gerente' => ['required', 'min:0.00', 'max:50.00'],
            'porc_cerrador_prbr' => ['required', 'min:0.00', 'max:50.00'],
            'porc_bonificacion' => ['required', 'min:0.00', 'max:50.00'],
            'comision_bancaria' => '',
            'numero_recibo' => '',
            'asesor_captador_id' => 'required',
            'asesor_captador' => '',
            'asesor_cerrador_id' => 'required',
            'asesor_cerrador' => '',
            'pago_gerente' => '',
            'forma_pago_gerente_id' => '',
            'fecha_pago_gerente' => '',
            'factura_gerente' => '',
            'pago_asesores' => '',
            'forma_pago_captador_id' => '',
            'fecha_pago_captador' => '',
            'factura_captador' => '',
            'forma_pago_cerrador_id' => '',
            'fecha_pago_cerrador' => '',
            'factura_cerrador' => '',
            'factura_asesores' => '',
            'pago_otra_oficina' => '',
            'forma_pago_otra_oficina_id' => '',
            'fecha_pago_otra_oficina' => '',
            'factura_otra_oficina' => '',
            'pagado_casa_nacional' => '',
            'estatus_sistema_c21' => '',
            'reporte_casa_nacional' => '',
            'factura_AyS' => '',
            'comentarios' => '',
            'cedula' => '',
            'rif' => '',
            'name' => 'required',
            'tipo' => '',
            'ddn' => '',
            'telefono' => '',
            'email' => '',
            'fecha_nacimiento' => '',
            'dirCliente' => '',
            'observaciones' => '',
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
            'comision.min' => '<comision> no puede ser menor de 0.00',
            'comision.max' => '<comision> no puede ser tan alto',
            'iva.required' => 'El campo <iva> es obligatorio',
            'iva.min' => '<iva> no puede ser menor de 0.00',
            'iva.max' => '<iva> no puede ser tan alto',
            'lados.in' => 'El campo <lados> solo puede contener el valor 1 o 2',
            'porc_franquicia.required' => 'El campo <% franquicia> es obligatorio',
            'porc_franquicia.min' => '<% franquicia> no puede ser menor de 0.00',
            'porc_franquicia.max' => '<porc franquicia> no puede ser tan alto',
            'reportado_casa_nacional.required' => 'El campo <Reportado casa nacional> es obligatorio',
            'porc_regalia.required' => 'El campo <% Regalia> es obligatorio',
            'porc_regalia.min' => '<% regalia> no puede ser menor de 0.00',
            'porc_regalia.max' => '<porc regalia> no puede ser tan alto',
            'porc_compartido.required' => 'El campo <% compartido> es obligatorio',
            'porc_compartido.min' => '<% compartido> no puede ser menor de 0.00',
            'porc_compartido.max' => '<porc compartido> no puede ser tan alto',
            'porc_captador_prbr.required' => 'El campo <Porcentaje captador PRBR> es obligatorio',
            'porc_captador_prbr.min' => '<% captador> no puede ser menor de 0.00',
            'porc_captador_prbr.max' => '<porc captador> no puede ser tan alto',
            'porc_gerente.required' => 'El campo <Porcentaje gerente> es obligatorio',
            'porc_gerente.min' => '<% gerente> no puede ser menor de 0.00',
            'porc_gerente.max' => '<porc gerente> no puede ser tan alto',
            'porc_cerrador_prbr.required' => 'El campo <Porcentaje cerrador PRBR> es obligatorio',
            'porc_cerrador_prbr.min' => '<% cerrador> no puede ser menor de 0.00',
            'porc_cerrador_prbr.max' => '<porc cerrador> no puede ser tan alto',
            'porc_bonificacion.required' => 'El campo <Porcentaje bonificacion> es obligatorio',
            'porc_bonificacion.min' => '<% bocificacion> no puede ser menor de 0.00',
            'porc_bonificacion.max' => '<porc bonificacion> no puede ser tan alto',
            'asesor_captador_id.required' => 'El campo <Asesor captador> es obligatorio',
            'asesor_cerrador_id.required' => 'El campo <Asesor cerrador> es obligatorio',
            'name.required' => 'El campo <nombre> DEL CLIENTE (Nuevo) es obligatorio',
        ]);

        //dd($data);
        if ('X' == $data['cliente_id']) {
            if (!(is_null($data['ddn'])) and ('' != $data['ddn']) and
                !(is_null($data['telefono'])) and ('' != $data['telefono'])) {
                $data['telefono'] = $data['ddn'] . $data['telefono'];
            } else {
                $data['telefono'] = null;
            }
            unset($data['ddn']);

            $cols = General::columnas('clientes');
            $cliente = Cliente::create([
                'cedula' => $data['cedula']??null,
                'rif' => $data['rif']??null,
                'name' => $data['name'],
                'tipo' => $data['tipo']??$cols['tipo']['xdef'],
                'telefono' => $data['telefono']??null,
                'user_id' => Auth::user()->id,
                'email' => $data['email']??null,
                'fecha_nacimiento' => $data['fecha_nacimiento']??null,
                'dirCliente' => $data['dirCliente']??null,
                'observaciones' => $data['observaciones']??null,
                'contacto_id' => $data['contacto_id']??null,
            ]);
            $data['cliente_id'] = $cliente->id;
            unset($cols);
        }

        $cols = General::columnas('propiedads');
        Propiedad::create([
            'codigo' => $data['codigo'],
            'fecha_reserva' => $data['fecha_reserva'],
            'forma_pago_reserva' => (isset($data['forma_pago_reserva'])?$data['forma_pago_reserva']:null),
            'factura_reserva' => (isset($data['factura_reserva'])?$data['factura_reserva']:null),
            'fecha_firma' => $data['fecha_firma'],
            'forma_pago_firma' => (isset($data['forma_pago_firma'])?$data['forma_pago_firma']:null),
            'factura_firma' => (isset($data['factura_firma'])?$data['factura_firma']:null),
            'negociacion' => $data['negociacion'],
            'nombre' => $data['nombre'],
            'exclusividad' => (isset($data['exclusividad']) and
                                        ('on' == $data['exclusividad'])),
            'tipo_id' => (isset($data['tipo_id'])?$data['tipo_id']:$cols['tipo_id']['xdef']),
            'metraje' => (isset($data['metraje'])?$data['metraje']:null),
            'habitaciones' => (isset($data['habitaciones'])?$data['habitaciones']:null),
            'banos' => (isset($data['banos'])?$data['banos']:null),
            'niveles' => (isset($data['niveles'])?$data['niveles']:null),
            'puestos' => (isset($data['puestos'])?$data['puestos']:null),
            'anoc' => (isset($data['anoc'])?$data['anoc']:null),
            'caracteristica_id' =>
                (isset($data['caracteristica_id'])?$data['caracteristica_id']:$cols['caracteristica_id']['xdef']),
            'descripcion' => (isset($data['descripcion'])?$data['descripcion']:null),
            'direccion' => (isset($data['direccion'])?$data['direccion']:null),
            'ciudad_id' => (isset($data['ciudad_id'])?$data['ciudad_id']:$cols['ciudad_id']['xdef']),
            'codigo_postal' => (isset($data['codigo_postal'])?$data['codigo_postal']:null),
            'municipio_id' => (isset($data['municipio_id'])?$data['municipio_id']:$cols['municipio_id']['xdef']),
            'estado_id' => (isset($data['estado_id'])?$data['estado_id']:$cols['estado_id']['xdef']),
            'cliente_id' => (isset($data['cliente_id'])?$data['cliente_id']:$cols['cliente_id']['xdef']),
            'estatus' => (isset($data['estatus'])?$data['estatus']:$cols['estatus']['xdef']),
            'user_id' => Auth::user()->id,
            'moneda' => $data['moneda'],
            'precio' => $data['precio'],
            'comision' => $data['comision'],
            'iva' => $data['iva'],
            'lados' => $data['lados'],
            'porc_franquicia' => $data['porc_franquicia'],
            'reportado_casa_nacional' => $data['reportado_casa_nacional'],
            'porc_regalia' => $data['porc_regalia'],
            'porc_compartido' => $data['porc_compartido'],
            'porc_captador_prbr' => $data['porc_captador_prbr'],
            'porc_gerente' => $data['porc_gerente'],
            'porc_cerrador_prbr' => $data['porc_cerrador_prbr'],
            'porc_bonificacion' => $data['porc_bonificacion'],
            'comision_bancaria' => (isset($data['comision_bancaria'])?$data['comision_bancaria']:null),
            'numero_recibo' => (isset($data['numero_recibo'])?$data['numero_recibo']:null),
            'asesor_captador_id' => $data['asesor_captador_id'],
            'asesor_captador' => (isset($data['asesor_captador'])?$data['asesor_captador']:null),
            'asesor_cerrador_id' => $data['asesor_cerrador_id'],
            'asesor_cerrador' => (isset($data['asesor_cerrador'])?$data['asesor_cerrador']:null),
            'pago_gerente' => (isset($data['pago_gerente'])?$data['pago_gerente']:null),
            'forma_pago_gerente_id' => (isset($data['forma_pago_gerente_id'])?$data['forma_pago_gerente_id']:null),
            'fecha_pago_gerente' => (isset($data['fecha_pago_gerente'])?$data['fecha_pago_gerente']:null),
            'factura_gerente' => (isset($data['factura_gerente'])?$data['factura_gerente']:null),
            'pago_asesores' => (isset($data['pago_asesores'])?$data['pago_asesores']:null),
            'forma_pago_captador_id' => (isset($data['forma_pago_captador_id'])?$data['forma_pago_captador_id']:null),
            'fecha_pago_captador' => (isset($data['fecha_pago_captador'])?$data['fecha_pago_captador']:null),
            'factura_captador' => (isset($data['factura_captador'])?$data['factura_captador']:null),
            'forma_pago_cerrador_id' => (isset($data['forma_pago_cerrador_id'])?$data['forma_pago_cerrador_id']:null),
            'fecha_pago_cerrador' => (isset($data['fecha_pago_cerrador'])?$data['fecha_pago_cerrador']:null),
            'factura_cerrador' => (isset($data['factura_cerrador'])?$data['factura_cerrador']:null),
            'factura_asesores' => (isset($data['factura_asesores'])?$data['factura_asesores']:null),
            'pago_otra_oficina' => (isset($data['pago_otra_oficina'])?$data['pago_otra_oficina']:null),
            'forma_pago_otra_oficina_id' => (isset($data['forma_pago_otra_oficina_id'])?$data['forma_pago_otra_oficina_id']:null),
            'fecha_pago_otra_oficina' => (isset($data['fecha_pago_otra_oficina'])?$data['fecha_pago_otra_oficina']:null),
            'factura_otra_oficina' => (isset($data['factura_otra_oficina'])?$data['factura_otra_oficina']:null),
            'pagado_casa_nacional' => (isset($data['pagado_casa_nacional']) and
                                        ('on' == $data['pagado_casa_nacional'])),
            'estatus_sistema_c21' =>
            (isset($data['estatus_sistema_c21'])?$data['estatus_sistema_c21']:$cols['estatus_sistema_c21']['xdef']),
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
    public function show(Propiedad $propiedad, $rutRetorno='propiedades.orden')
    {
        //dd(redirect()->getUrlGenerator());    // arreglo , un poco complejo.
        if (!(Auth::check())) {
            return redirect('login');
        }

        $alertar = 0;
        if (isset($_GET['correo']) and ($correo = $_GET['correo'])) {
            if ('S' == $correo) {
                $alertar = 1;
            } elseif ('N' == $correo) {
                $alertar = -1;
            }
        }
        $col_id = '';
        $orden = '';
        $nroPagina = '';
        if ((0 == stripos($rutRetorno, 'propiedades')) or
            (0 == stripos($rutRetorno, 'reporte'))) {
            $rutaPrevia = redirect()->getUrlGenerator()->previous();
            if (17 < strlen($rutRetorno)) {     // 17 = len(propiedades/orden)
                $col_id = strtolower(substr($rutRetorno, 19)) . '_id';  // 19 = len(reporte.propiedades)
// $col_id sera 'caracteristica_id', 'ciudad_id', 'municipio_id', 'estado_id', 'tipo_id', etc.
                $long = (strrpos($rutaPrevia, '?')?strrpos($rutaPrevia, '?'):strlen($rutaPrevia)) - 1;
                $long -= strrpos($rutaPrevia, '/');
                $orden = substr($rutaPrevia, strrpos($rutaPrevia, '/')+1, $long);
            } else {
                $orden = session('orden', 'id');
            }
// Las proximas dos lineas consiguen si fue llamada desde una pagina especifica.
            if (0 < stripos($rutaPrevia, '?page'))
// $rutaPrevia: 'propiedades' o 'propiedades/orden/nombre' o 'propiedades/orden/nombre?page=5' o
//          'reportes/propiedadesCaracteristica/1/id' o 'reportes/propiedadesCaracteristica/1/codigo?page=3' o etc.
                $nroPagina = substr($rutaPrevia, stripos($rutaPrevia, '?page'));
        }
        
        //dd($alertar, $rutRetorno, $rutaPrevia, redirect()->getUrlGenerator());    // No consegui nada que pueda ayudar.
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        $tipos = Tipo::all();
        $ciudades = Ciudad::all();
        $caracteristicas = Caracteristica::all();
        $municipios = Municipio::all();
        $estados = Estado::all();
        $clientes = Cliente::all();
        if ((Auth::user()->is_admin) or
            (is_null($propiedad->user_borro) and (($propiedad->user_id == Auth::user()->id) or
                ($propiedad->asesor_captador_id == Auth::user()->id) or
                ($propiedad->asesor_cerrador_id == Auth::user()->id))) or
            ('A' == $propiedad->estatus)) {
//            return view((($movil)?'celular.showPropiedad':'propiedades.show'),
            return view('propiedades.show',
                        compact('propiedad', 'rutRetorno', 'nroPagina', 'col_id', 'movil',
                                'orden', 'tipos', 'ciudades', 'caracteristicas', 'municipios',
                                'estados', 'clientes', 'alertar'));
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
        if (!(is_null($propiedad->user_borro))) {
            return redirect('/propiedades');
        }

        $rutaPrevia = redirect()->getUrlGenerator()->previous();
// Las proximas dos lineas consiguen si fue llamada desde una pagina especifica.
        if (0 < stripos($rutaPrevia, '?page'))
            $nroPagina = substr($rutaPrevia, stripos($rutaPrevia, '?page'));
        else $nroPagina = '';
        $orden = session('orden', 'id');

        $cols = General::columnas('propiedads');
        $title = 'Editar ' . $this->tipo;
        $users = User::get(['id', 'name']);     // Todos los usuarios (asesores).
        $users[0]['name'] = 'Asesor otra oficina';
        $tipos = Tipo::all();
        $caracteristicas = Caracteristica::all();
        $ciudades = Ciudad::all();
        $municipios = Municipio::all();
        $estados = Estado::all();
        $clientes = Cliente::all();
//        dd($propiedad);
        if ((Auth::user()->is_admin) or
            (('P' != $propiedad->estatus) and ('C' != $propiedad->estatus) and
             (($propiedad->user_id == Auth::user()->id) or
              ($propiedad->asesor_captador_id == Auth::user()->id) or
              ($propiedad->asesor_cerrador_id == Auth::user()->id)))) {
            $agente = new Agent();
            $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
//            return view((($movil)?'celular.editPropiedades':'propiedades.editar'),
            return view('propiedades.editar',
//                        ['propiedad' => $propiedad, 'users' => $users, 'cols' => $cols, 'title' => $title]);
                    compact('propiedad', 'title', 'users', 'tipos', 'ciudades', 'caracteristicas',
                            'municipios', 'estados', 'clientes', 'cols', 'orden', 'nroPagina'));
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
            'forma_pago_reserva' => '',
            'factura_reserva' => '',
            'fecha_firma' => ['sometimes', 'nullable', 'date'],
            'forma_pago_firma' => '',
            'factura_firma' => '',
            'fecha_firma_ant' => '',
            'negociacion' => 'required',
            'nombre' => 'required',
            'exclusividad' => '',
            'tipo_id' => '',
            'metraje' => '',
            'habitaciones' => '',
            'banos' => '',
            'niveles' => '',
            'puestos' => '',
            'anoc' => '',
            'caracteristica_id' => '',
            'descripcion' => '',
            'direccion' => '',
            'ciudad_id' => '',
            'codigo_postal' => '',
            'municipio_id' => '',
            'estado_id' => '',
            'cliente_id' => '',
            'estatus' => 'required',
            'moneda' => 'required',
            'precio' => 'required',
            'comision' => ['required', 'min:0.00', 'max:50.00'],
            'iva' => ['required', 'min:0.00', 'max:50.00'],
            'lados' => ['in:1,2'],
            'porc_franquicia' => ['required', 'min:0.00', 'max:50.00'],
            'reportado_casa_nacional' => 'required',
            'porc_regalia' => ['required', 'min:0.00', 'max:50.00'],
            'porc_compartido' => ['required', 'min:0.00', 'max:90.00'],
            'porc_captador_prbr' => ['required', 'min:0.00', 'max:50.00'],
            'porc_gerente' => ['required', 'min:0.00', 'max:50.00'],
            'porc_cerrador_prbr' => ['required', 'min:0.00', 'max:50.00'],
            'porc_bonificacion' => ['required', 'min:0.00', 'max:50.00'],
            'comision_bancaria' => '',
            'numero_recibo' => '',
            'asesor_captador_id' => 'required',
            'asesor_captador' => '',
            'asesor_cerrador_id' => 'required',
            'asesor_cerrador' => '',
            'pago_gerente' => '',
            'forma_pago_gerente_id' => '',
            'fecha_pago_gerente' => '',
            'factura_gerente' => '',
            'pago_asesores' => '',
            'forma_pago_captador_id' => '',
            'fecha_pago_captador' => '',
            'factura_captador' => '',
            'forma_pago_cerrador_id' => '',
            'fecha_pago_cerrador' => '',
            'factura_cerrador' => '',
            'factura_asesores' => '',
            'pago_otra_oficina' => '',
            'forma_pago_otra_oficina_id' => '',
            'fecha_pago_otra_oficina' => '',
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
            'comision.min' => '<comision> no puede ser menor de 0.00',
            'comision.max' => '<comision> no puede ser tan alto',
            'iva.required' => 'El campo <iva> es obligatorio',
            'iva.min' => '<iva> no puede ser menor de 0.00',
            'iva.max' => '<iva> no puede ser tan alto',
            'porc_franquicia.required' => 'El campo <% franquicia> es obligatorio',
            'porc_franquicia.min' => '<% franquicia> no puede ser menor de 0.00',
            'porc_franquicia.max' => '<porc franquicia> no puede ser tan alto',
            'reportado_casa_nacional.required' => 'El campo <Reportado casa nacional> es obligatorio',
            'porc_regalia.required' => 'El campo <% Regalia> es obligatorio',
            'porc_regalia.min' => '<% regalia> no puede ser menor de 0.00',
            'porc_regalia.max' => '<porc regalia> no puede ser tan alto',
            'porc_compartido' => 'El campo <% compartido> es obligatorio',
            'porc_compartido.min' => '<% compartido> no puede ser menor de 0.00',
            'porc_compartido.max' => '<porc compartido> no puede ser tan alto',
            'porc_captador_prbr.required' => 'El campo <Porcentaje captador PRBR> es obligatorio',
            'porc_captador_prbr.min' => '<% captador> no puede ser menor de 0.00',
            'porc_captador_prbr.max' => '<porc captador> no puede ser tan alto',
            'porc_gerente.required' => 'El campo <Porcentaje gerente> es obligatorio',
            'porc_gerente.min' => '<% gerente> no puede ser menor de 0.00',
            'porc_gerente.max' => '<porc gerente> no puede ser tan alto',
            'porc_cerrador_prbr.required' => 'El campo <Porcentaje cerrador PRBR> es obligatorio',
            'porc_cerrador_prbr.min' => '<% cerrador> no puede ser menor de 0.00',
            'porc_cerrador_prbr.max' => '<porc cerrador> no puede ser tan alto',
            'porc_bonificacion.required' => 'El campo <Porcentaje bonificacion> es obligatorio',
            'porc_bonificacion.min' => '<% bocificacion> no puede ser menor de 0.00',
            'porc_bonificacion.max' => '<porc bonificacion> no puede ser tan alto',
            'asesor_captador_id.required' => 'El campo <Asesor captador> es obligatorio',
            'asesor_cerrador_id.required' => 'El campo <Asesor cerrador> es obligatorio',
            'estatus_sistema_c21.required' => 'El campo <Estatus sistema C21> es obligatorio',
        ]);

        //print_r($data);
        if (!array_key_exists('estatus', $data)) $data['estatus'] = 'A';
        if (!array_key_exists('exclusividad', $data))
            $data['exclusividad'] = false;
        elseif ('on' == $data['exclusividad'])
            $data['exclusividad'] = true;
        if (!array_key_exists('pagado_casa_nacional', $data))
            $data['pagado_casa_nacional'] = false;
        elseif ('on' == $data['pagado_casa_nacional'])
            $data['pagado_casa_nacional'] = true;

        $data['user_actualizo'] = Auth::user()->id;
        //dd($data);
        $propiedad->update($data);

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Propiedad',
            'tx_data' => implode(';', $data),
            'tx_tipo' => 'A',
	        'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        $correo = '';
        if (is_null($data['fecha_firma_ant']) and isset($data['fecha_firma'])) {
            $correo = self::correoReporteCierre($propiedad->id, 0);
        }
        return redirect()->route('propiedades.show',
                                ['propiedad' => $propiedad, 'correo' => $correo]);
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
        if (!(is_null($propiedad->user_borro))) {
            return redirect()->route('propiedades.show', ['propiedad' => $propiedad]);
        }

        $data['user_borro'] = Auth::user()->id;
        //$data['borrado_at'] = Carbon::now();
        //$data['borrado_at'] = new Carbon();

        //dd($data);
        $datos = 'id:'.$propiedad->id.', codigo:'.$propiedad->codigo.', nombre:'.$propiedad->nombre;
        $propiedad->delete();

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Propiedad',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
	    'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        return redirect()->route('propiedades.index');
    }

    public static function correoReporteCierre(Propiedad $propiedad, $ruta=0)
    {
//        $propiedad = Propiedad::findOrFail($id);  // Si falla produce 'ModelNotFoundException'.
        $host = env('MAIL_HOST');
        if (!($ip = gethostbyname($host)) or ($ip == $host)) { // No hay conexon a Internet.
            //dd($id, $ruta, $host, $ip);
            $correo = 'N';
            if (1 == $ruta)
                return redirect()->route('propiedades.index', ['correo' => $correo]);
            elseif (2 == $ruta)
                return redirect()->route('propiedades.show',
                                ['propiedad' => $propiedad, 'correo' => $correo]);
            else return $correo;
        }

        $correoSocios = \App\User::CORREO_SOCIOS;
        //dd($id, $ruta, $host, $ip, $correoSocios);

        $user = User::find(Auth::user()->id);
        Mail::to($user->email, $user->name)
                ->cc($correoSocios)
                ->send(new ReporteCierre($propiedad, $user));
        $correo = 'S';
        if (1 == $ruta)
            return redirect()->route('propiedades.index', ['correo' => $correo]);
        elseif (2 == $ruta)
            return redirect()->route('propiedades.show',
                                ['propiedad' => $propiedad, 'correo' => $correo]);
        else return $correo;
    }   // Final del metodo correoReporteCierre.

    public function ajPropiedades()
    {
        $arrTmp = Propiedad::where('estatus', '!=', 'S')
                        ->get(['id', 'codigo', 'negociacion', 'nombre', 'user_id', 'estatus', 'created_at'])
                        ->all();
        $users  = User::get(['id', 'name']);
        $cols = General::columnas('propiedads');
        $estatus = $cols['estatus']['opcion'];
        $negociaciones = $cols['negociacion']['opcion'];
        unset($cols);

        $asesores = [];
        foreach ($users as $v) {
            if (1 == $v->id) $asesores[1] = 'Administrador';
            else $asesores[$v->id] = $v->name;
        }

        $aPropiedades = [];
        $aCodigos = [];
        foreach ($arrTmp as $v) {
            if ('' != $v->id) $aPropiedades[$v->id] = [
                    'nb' => $v->nombre,
                    'ng' => $v->negociacion,
                    'uid' => $v->user_id,
                    'st' => $v->estatus,
                    'fc' => $v->created_at->format('d/m/Y'),
                    'ho' => $v->created_at->format('h:i a')
                ];
            if ('' != $v->codigo) $aCodigos[$v->codigo] = [
                    'id' => $v->id
                ];
        }

        return array(json_encode($asesores), json_encode($aPropiedades),
                    json_encode($estatus), json_encode($negociaciones),
                    json_encode($aCodigos));
    }
}
