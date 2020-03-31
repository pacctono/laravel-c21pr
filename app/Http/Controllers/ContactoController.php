<?php

namespace App\Http\Controllers;

use App\Contacto;
use App\Propiedad;
use App\Deseo;
use App\Origen;
use App\Precio;
use App\Price;
use App\Tipo;
use App\Resultado;
use App\Zona;
use App\Venezueladdn;
use App\User;
use App\Bitacora;
use \App\Mail\OfertaServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;        // PC
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;                          // PC
use Jenssegers\Agent\Agent;                 // PC
use Mpdf\Mpdf;
use App\MisClases\Fecha;                    // PC
use App\MisClases\General;                  // PC

class ContactoController extends Controller
{
    protected $tipo = 'Contacto Inicial';
    protected $tipoPlural = 'Contactos Iniciales';
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

        $title = 'Listado de ' . $this->tipoPlural;
        $ruta = request()->path();
        $dato = request()->all();
        if (1 >= count($dato)) $paginar = True; // Inicialmente, el arreglo '$dato' esta vacio.
        else $paginar = False;
// Todo se inicializa, cuando se selecciona 'Contactos' desde el menu vertical.
        if (('GET' == request()->method()) and ('' == $orden) and (0 == count($dato))) {
            session(['fecha_desde' => '', 'fecha_hasta' => '', 'deseo' => '',
                        'asesor' => '0']);
        }
/*
 * Manejo de las variables de la forma lateral. $dato (fecha_desde, fecha_hasta, deseo,
 * asesor).
 * Cuando el arreglo $dato contiene un solo item, este es el número de página (page=n).
 * Si el arreglo $dato está vacio (count($arreglo) == 0, esta opcion fue manejada arriba),
 * es una ruta 'GET' con o sin $orden.
 * Si $dato tiene más de 1 item. Fue seleccionado una fecha y/o deseo y/o un asesor.
 */
        if (1 >= count($dato)) {    // Arriba, paginar es True.
            $fecha_desde = session('fecha_desde', '');
            $fecha_hasta = session('fecha_hasta', '');
            $deseo       = session('deseo', '');
            $asesor      = session('asesor', '0');
        } else {
            if (isset($dato['asesor'])) $asesor = $dato['asesor'];
            else $asesor = 0;

            if (isset($dato['deseo'])) $deseo = $dato['deseo'];
            else $deseo = '';

            if (!isset($dato['fecha_desde']) or ('' == $dato['fecha_desde']))
                $fecha_desde = (new Carbon(Propiedad::min('created_at')));
            else $fecha_desde = (new Carbon($dato['fecha_desde']));
            if (!isset($dato['fecha_hasta']) or ('' == $dato['fecha_hasta']))
                $fecha_hasta = (new Carbon(Propiedad::max('created_at')));
            else $fecha_hasta = (new Carbon($dato['fecha_hasta']));
/*            list ($fecha_desde, $fecha_hasta) = Fecha::periodo($dato, $fecha_min,
                                                                $fecha_max);*/
        }

// En caso de volver luego de haber enviado un correo 's', ver el metodo 'emailcita', en AgendaController.
// En caso de volver luego de haber enviado un correo 'S', 'N', ver el metodo 'self::correoReporteCierre'.
        $alertar = 0;
        if (isset($_GET['correo']) and ($correo = $_GET['correo'])) {
            if ('S' == $correo) {
                $alertar = 1;
            } elseif ('s' == $correo) {
                $alertar = 2;
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
            $contactos = Contacto::where('id', '>', 0);     // dummy
            $users   = User::where('activo', True)->get(['id', 'name']);     // Todos los usuarios (asesores), excepto los no activos.
            $users[0]['name'] = 'Administrador';
        } else {
            $contactos = User::find(Auth::user()->id)
                        ->contactos()->whereNull('user_borro');
            $users = '';
            //return redirect('/contactos/create');
        }
        //dd($asesor, $deseo, $fecha_desde, $fecha_hasta, $contactos);
        if (0 < $asesor) {      // Se selecciono un asesor.
            $contactos = $contactos->where('user_id', $asesor);
        }
        if ('' != $deseo) {       // Se selecciono una deseo.
            $contactos = $contactos->where('deseo_id', $deseo);
        }

        if ('' != $fecha_desde and '' != $fecha_hasta) {    // Se seleccionaron fechas.
            $fecha_desde = substr($fecha_desde, 0, 10);
            $fecha_hasta = substr($fecha_hasta, 0, 10);
            $contactos = $contactos->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
        } else $contactos = $contactos->where('created_at', '<=', now(Fecha::$ZONA));

        $contactos = $contactos->orderBy($orden, $sentido);
        $deseos = Deseo::get();    // Todos los deseos.

        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        $paginar = ($paginar)?!($movil or ('html' != $accion)):$paginar;
        if ($paginar) $contactos = $contactos->paginate($this->lineasXPagina);      // Pagina la impresión de 10 en 10
        else $contactos = $contactos->get();                // Mostrar todos los registros.
        session(['fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta,    // Asignar valores en sesión.
                    'deseo' => $deseo, 'asesor' => $asesor,
                    'orden' => $orden
                ]);
        //dd($asesor, $deseo, $fecha_desde, $fecha_hasta, $contactos);
        if ('html' == $accion)
            return view('contactos.index',
                    compact('title', 'contactos', 'ruta', 'users', 'asesor',
                            'deseos', 'deseo',
                            'alertar', 'orden', 'movil', 'accion', 'paginar'));
        $html = view('contactos.index',
                    compact('title', 'contactos', 'ruta', 'users', 'asesor',
                            'deseos', 'deseo',
                            'alertar', 'orden', 'movil', 'accion', 'paginar'))
                ->render();
        General::generarPdf($html, 'contactosIniciales', $accion);
    }

    public function filtro($filtro)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $title = 'Listado de ' . $this->tipo;
        $ruta = request()->path();

        if ('' == $filtro or is_null($filtro)) {
            $filtro = 'created_at';
        }
        if (Auth::user()->is_admin) {
            $contactos = Contacto::whereNull('user_borro')
                                    ->orderBy($filtro)
                                    ->paginate($this->lineasXPagina);
        } else {
            $contactos = User::find(Auth::user()->id)
                                ->contactos()
                                ->whereNull('user_borro')
                                ->orderBy($filtro)
                                ->paginate($this->lineasXPagina);
            //return redirect('/contactos/create');
        }
    
        //dd(Auth::user()->id);

        return view('contactos.index', compact('title', 'contactos', 'ruta'));
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

        $title = 'Crear ' . $this->tipo;
        $deseos = Deseo::all();
        $origenes = Origen::all();
        $precios = Price::all();
        $tipos = Tipo::all();
        $resultados = Resultado::all();
        $zonas = Zona::all();
        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();
        $users = User::all();
        $exito = session('exito', '');
        session(['exito' => '']);

        return view('contactos.crear', compact(
            'title', 'deseos', 'origenes', 'precios',
            'tipos', 'resultados', 'zonas', 'ddns', 'users', 'exito'));
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
        $data = $request->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'cedula' => ['sometimes', 'nullable', 'digits_between:6,8'],
            'name' => 'required',
            'ddn' => '',
            'telefono' => ['sometimes', 'nullable', 'digits:7'],
            'otro_telefono' => '',
            'email' => ['sometimes', 'nullable', 'email'],
            'direccion' => '',
            'deseo_id' => 'required',
            'tipo_id' => 'required',
            'zona_id' => 'required',
            'precio_id' => 'required',
            'origen_id' => 'required',
            'resultado_id' => 'required',
            'fecha_evento' => ['sometimes', 'nullable', 'required_if:resultado_id,4,5,6,7', 'date'],
// El tipo 'time' decuelve la hora militar (formato de 24 horas) por eso 'H:i' no incluye am o pm.
            'hora_evento' => ['sometimes', 'nullable', 'required_if:resultado_id,4,5,6,7', 'date_format:H:i'],
            'observaciones' => '',
        ], [
            'cedula.digits_between' => 'La cedula de ideintidad debe contener 7 u 8 digitos',
            'name.required' => 'El campo nombre es obligatorio.',
            'telefono.digits:7' => 'La parte del telefono, sin ddn, debe contener 7 dígitos',
            'email.email' => 'Debe suministrar un correo electrónico válido.',
            'deseo_id.required' => 'El deseo del contacto inicial es obligatorio suministrarlo.',
            'tipo_id.required' => 'El tipo de inmueble es obligatorio suministrarlo.',
            'zona_id.required' => 'La zona donde esta ubicado el inmueble es obligatorio suministrarla.',
            'precio_id.required' => 'El precio del inmueble es obligatorio suministrarlo.',
            'origen_id.required' => 'El origen de como conocio de nuestra oficina es obligatorio suministrarlo.',
            'resultado_id.required' => 'El resultado de la conversación con el contacto inicial es obligatorio suministrarlo.',
            'fecha_evento.required_if' => 'La fecha del evento es requerida, cuando el resultado es llamada o cita',
            'fecha_evento.date' => 'La fecha del evento debe ser una fecha valida.',
            'hora_evento.required_if' => 'La hora del evento es requerida, cuando el resultado es llamada o cita',
            'hora_evento.date_format' => 'La hora del evento debe ser una hora valida.',
        ]);

        //$data['user_id'] = Auth::user()->id;
        //$data['user_id'] = intval($data['user_id']);
        //dd($data);

        if (!(is_null($data['ddn'])) and '' != $data['ddn'] and !(is_null($data['telefono'])) and
                                                        '' != $data['telefono']) {
            $data['telefono'] = $data['ddn'] . $data['telefono'];
        } else {
            $data['telefono'] = '';
        }
        unset($data['ddn']);
        $data['veces_name'] = Contacto::ofVeces($data['name'], 'name') + 1;
        if ('' != $data['telefono'])
            $data['veces_telefono'] = Contacto::ofVeces($data['telefono'], 'telefono') + 1;
        else $data['veces_telefono'] = 1;
        if ((isset($data['email'])) and ('' != $data['email']) and (!is_null($data['email'])))
            $data['veces_email'] = Contacto::ofVeces($data['email'], 'email') + 1;
        else $data['veces_email'] = 1;
        if ((isset($data['fecha_evento'])) and ('' != $data['fecha_evento'])) { // Los campos desactivados no pasan la variable.
// El tipo 'time' decuelve la hora militar (formato de 24 horas) por eso 'H:i' no incluye am/pm.
            if (isset($data['hora_evento']))   // Los campos desactivados no pasan la variable.
                $data['fecha_evento'] = Carbon::createFromFormat('Y-m-d H:i', $data['fecha_evento'] . ' ' .
                                                            ($data['hora_evento']??'12:00'));
            else $data['hora_evento'] = null;
        } else $data['fecha_evento'] = null;

        $contacto = Contacto::create([          // Devuelve el modelo.
            'cedula' => $data['cedula'],
            'name' => $data['name'],
            'veces_name' => $data['veces_name'],
            'telefono' => $data['telefono'],
            'veces_telefono' => $data['veces_telefono'],
            'otro_telefono' => $data['otro_telefono']??null,
            'user_id' => Auth::user()->id,
            'email' => $data['email'],
            'veces_email' => $data['veces_email'],
            'direccion' => $data['direccion'],
            'deseo_id' => $data['deseo_id'],
            'tipo_id' => $data['tipo_id'],
            'zona_id' => $data['zona_id'],
            'precio_id' => $data['precio_id'],
            'origen_id' => $data['origen_id'],
            'resultado_id' => $data['resultado_id'],
            'fecha_evento' => isset($data['fecha_evento'])?$data['fecha_evento']:null,  // No es necesario 'isset', solo por seguridad.
            'observaciones' => $data['observaciones']
        ]);

        $exito = "El contacto inicial '" . $data['name'] . "' fue agregado con exito.";
        if (($data['email']) and            // Se suministró un email.
            ((2 == $data['deseo_id']) or (4 == $data['deseo_id']))) { // Vende o da en alquiler.
            $correo = self::correoOfertaServicio($contacto, 0);
            if ('S' == $correo) $exito .= " También, se le envió";
            else $exito .= " Pero, no pudo enviarsele";
            $exito .= " la 'Oferta de Servicio'.";
        }       

        session(['exito' => $exito]);
        return redirect()->route('contactos.show', $contacto);
    }

    /**
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function show(Contacto $contacto, $rutRetorno='contactos.index')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $alertar = 0;
        if (isset($_GET['correo']) and ($correo = $_GET['correo'])) {
            if ('S' == $correo) {
                $alertar = 1;
            } elseif ('s' == $correo) {
                $alertar = 2;
            } elseif ('N' == $correo) {
                $alertar = -1;
            }
        }
        $col_id = '';
        if (15 < strlen($rutRetorno)) {
            $col_id = strtolower(substr($rutRetorno, 17)) . '_id';
        }
        
        $cols = General::columnas('clientes');
        $tipos = $cols['tipo']['opcion'];   // Usado para convertir 'contacto' a 'cliente'.
        unset($cols);
        if ((Auth::user()->is_admin) or 
            (is_null($contacto->user_borro) and ($contacto->user_id == Auth::user()->id))) {
// Si no es el administrador elimine en tipo de cliente 'F' (Familiar).
            if (!Auth::user()->is_admin) unset($tipos['F']);
            if (1 == $contacto->deseo_id) $buscaPropiedad = 'V';    // El contacto desea comprar.
            elseif (3 == $contacto->deseo_id) $buscaPropiedad = 'A';// El contacto desea alquilar.
            else $buscaPropiedad = null;
            if (isset($buscaPropiedad)) {
                $propiedades = Propiedad::where('estatus', 'A')
                                        ->where('negociacion', $buscaPropiedad)
                ->whereBetween('precio', [$contacto->price->menor, $contacto->price->mayor])
                                        ->orderBy('precio')
                                        ->get();
                //->get(['codigo', 'nombre', 'precio', 'ciudad_id', 'asesor_captador_id', 'asesor_captador']);
            }
            else $propiedades = collect(array());
            return view('contactos.show', compact('contacto', 'propiedades', 'tipos',
                                                'rutRetorno', 'col_id', 'alertar'));
        } else {
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function edit(Contacto $contacto)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(is_null($contacto->user_borro))) {
            return redirect('/contactos');
        }

        $title = 'Editar ' . $this->tipo;

        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();

        if ((Auth::user()->is_admin) or ($contacto->user->id == Auth::user()->id)) {
            return view('contactos.editar', ['contacto' => $contacto, 'title' => $title,
                        'ddns' => $ddns]);
        }
        return redirect('/contactos');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contacto $contacto)
    {
        $data = $request->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'cedula' => ['sometimes', 'nullable', 'digits_between:7,8'],
            'name' => 'required',
            'ddn' => '',
            'telefono' => ['sometimes', 'nullable', 'digits:7'],
            'otro_telefono' => '',
            'email' => ['sometimes', 'nullable', 'email'],
            'direccion' => '',
            'observaciones' => '',
        ], [
            'cedula.digits_between' => 'La cedula de ideintidad debe contener 7 u 8 digitos',
            'name.required' => 'El campo nombre es obligatorio.',
            'telefono.digits:7' => 'La parte del telefono, sin ddn, debe contener 7 dígitos',
            'email.email' => 'Debe suministrar un correo elctrónico válido.',
        ]);
        //dd($data);

        if (!(is_null($data['ddn'])) and '' != $data['ddn'] and !(is_null($data['telefono'])) and
                                                        '' != $data['telefono']) {
            $data['telefono'] = $data['ddn'] . $data['telefono'];
        } else {
            $data['telefono'] = null;
        }
        unset($data['ddn']);

/*        foreach (['telefono', 'email', 'direccion', 'observaciones'] as $col) {
            if ('' == $data[$col] or is_null($data[$col])) {
                unset($data[$col]);
            }
        }*/
        if ('' == $data['telefono'] or is_null($data['telefono'])) {
            $data['veces_telefono'] = 0;
        }
        if ('' == $data['email'] or is_null($data['email'])) {
            $data['veces_email'] = 0;
        }
/*        if ('' == $data['direccion'] or is_null($data['direccion'])) {
            unset($data['direccion']);
        }
        if ('' == $data['observaciones'] or is_null($data['observaciones'])) {
            unset($data['observaciones']);
        }*/
        $data['user_actualizo'] = Auth::user()->id;

        //dd($data);

        $contacto->update($data);

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Contacto',
            'tx_data' => implode(';', $data),
            'tx_tipo' => 'A',
	    'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        return redirect()->route('contactos.show', ['contacto' => $contacto]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contacto $contacto)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        if (!Auth::user()->is_admin) {
            return redirect('/contactos');
        }
        if (!(is_null($contacto->user_borro))) {
            return redirect()->route('contactos.show', ['contacto' => $contacto]);
        }

        $data['user_borro'] = Auth::user()->id;
        $contacto->update($data);
        //$data['borrado_at'] = Carbon::now();
        //$data['borrado_at'] = new Carbon();

        $datos = 'id:'.$contacto->id.', cedula:'.$contacto->cedula.', nombre:'.$contacto->name;

        $contacto->delete();

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Contacto',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
	    'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        return redirect()->route('contactos.index');
    }

    public static function correoOfertaServicio(Contacto $contacto, $ruta=0)
    {
        $host = env('MAIL_HOST');
        if (!($ip = gethostbyname($host)) or ($ip == $host)) { // No hay conexon a Internet.
            //dd($id, $ruta, $host, $ip);
            $correo = 'N';
            if (1 == $ruta)
                return redirect()->route('contactos.index', ['correo' => $correo]);
            elseif (2 == $ruta)
                return redirect()->route('contactos.show',
                                ['contacto' => $contacto, 'correo' => $correo]);
            else return $correo;
        }

//        $correoCopiar = \App\User::CORREO_COPIAR;
        $correoCopiar = \App\User::CORREO_SOCIOS;

        $user = User::find(Auth::user()->id);
        //dd($contacto, $user);
        Mail::to($contacto->email, $contacto->name)
                ->cc($user->email, $user->name)
                ->bcc($correoCopiar)
                ->send(new OfertaServicio($contacto, $user));
        $correo = 'S';
        if (1 == $ruta)
            return redirect()->route('contactos.index', ['correo' => $correo]);
        elseif (2 == $ruta)
            return redirect()->route('contactos.show',
                                ['contacto' => $contacto, 'correo' => $correo]);
        else return $correo;
    }   // Final del metodo correoCumpleano.
}
