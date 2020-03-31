<?php

namespace App\Http\Controllers;

use App\Aviso;
use App\User;
use App\Turno;
use App\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;        // PC
use Carbon\Carbon;                          // PC
use Jenssegers\Agent\Agent;                 // PC
use App\MisClases\Fecha;                    // PC
use App\MisClases\General;                  // PC

class AvisoController extends Controller
{
    protected $tipo = 'Aviso';
    protected $tipoPlural = 'Avisos';
    protected $lineasXPagina = 20 /*General::LINEASXPAGINA*/;
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
        if (!(Auth::user()->is_admin)) return redirect()->back();

        $title = 'Listado de ' . $this->tipoPlural;
        $ruta = request()->path();
        $dato = request()->all();
        //dd($dato);
        if (1 >= count($dato)) $paginar = True; // Inicialmente, el arreglo '$dato' esta vacio.
        else $paginar = False;
// Todo se inicializa, cuando se selecciona 'Contactos' desde el menu vertical.
        if (('GET' == request()->method()) and ('' == $orden) and (0 == count($dato))) {
            session(['fecha_desde' => '', 'fecha_hasta' => '', 'asesor' => '0']);
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
            $asesor      = session('asesor', '0');
        } else {
            if (isset($dato['asesor'])) $asesor = $dato['asesor'];
            else $asesor = 0;
        }

        $sentido = 'asc';
        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
            //$sentido = 'desc';
        }

        if (Auth::user()->is_admin) {
            $avisos = Aviso::orderBy($orden, $sentido);
            $users   = User::where('activo', True)->get(['id', 'name']);     // Todos los usuarios (asesores), excepto los no activos.
            $users[0]['name'] = 'Administrador';
        } else {
            $avisos = User::find(Auth::user()->id)->avisos()->whereNull('user_borro');
            $users    = '';
        }
        if (0 < $asesor) {      // Se selecciono un asesor.
            $avisos = $avisos->where('user_id', $asesor);
        }
        $avisos = $avisos->orderBy($orden, $sentido);

        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        $paginar = ($paginar)?!($movil or ('html' != $accion)):$paginar;
        if ($paginar) $avisos = $avisos->paginate($this->lineasXPagina);      // Pagina la impresión de 10 en 10
        else $avisos = $avisos->get();                // Mostrar todos los registros.
        session(['orden' => $orden, 'asesor' => $asesor]);

        if ('html' == $accion)
            return view('avisos.index',
                        compact('title', 'avisos', 'ruta', 'users', 'asesor',
                                'paginar', 'orden', 'movil', 'accion'));
        $html = view('avisos.index',
                    compact('title', 'avisos', 'ruta', 'users', 'asesor',
                            'paginar', 'orden', 'movil', 'accion'))
                ->render();
        General::generarPdf($html, 'avisos', $accion);
    }

    public function filtro($filtro)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) return redirect()->back();

        $title = 'Listado de ' . $this->tipo;
        $ruta = request()->path();

        if ('' == $filtro or is_null($filtro)) {
            $filtro = 'created_at';
        }
        if (1 == Auth::user()->is_admin) {
            $avisos = Aviso::whereNull('user_borro')->orderBy($filtro)->paginate(10);
        } else {
            $avisos = User::find(Auth::user()->id)->avisos()->whereNull('user_borro')->orderBy($filtro)->paginate(10);
            //return redirect('/avisos/create');
        }
    
        //dd(Auth::user()->id);

        return view('avisos.index', compact('title', 'avisos', 'ruta', 'diaSemana'));
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
        if (!(Auth::user()->is_admin)) return redirect()->back();

        $rutaPrevia = redirect()->getUrlGenerator()->previous();
        $title = 'Crear ' . $this->tipo;
        $orden = session('orden', 'id');
        if (0 < stripos($rutaPrevia, '?page'))
            $nroPagina = substr($rutaPrevia, stripos($rutaPrevia, '?page'));
        else $nroPagina ='';

        $exito = session('exito', '');
        session(['exito' => '']);
        $cols = General::columnas('avisos');
        $tipos = $cols['tipo']['opcion'];
        $tipoXDef = $cols['tipo']['xdef'];
        unset($cols, $tipos['C'], $tipos['T']);

        $users   = User::where('is_admin', False)->where('activo', True)->get(['id', 'name']);  // Usuarios, excepto administradores ni los no activos.
        //$asesor = 0;				// Se asigna al incluir el select del asesor.
        return view('avisos.crear',
                compact('title', 'asesor', 'users', 'tipos', 'tipoXDef', 'exito', 'orden', 'nroPagina'));
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
            'asesor' => ['required', 'numeric', 'min:1'],
            'tipo' => 'required',       // Inicialmente, siempre será 'A' (Amonestación). Campo escondido.
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'descripcion' => 'required'
        ], [
            'asesor.required' => 'El campo asesor es obligatorio.',
            'asesor.min' => 'Tiene que seleccionar un asesor valido.',
            'tipo.required' => 'El campo tipo es obligatorio.',
            'fecha.required' => 'El campo fecha es obligatorio.',
            'fecha.date' => 'El campo fecha tiene que tener el formato correcto.',
            'hora.required' => 'El campo hora es obligatorio.',
            'hora.date_format' => 'El campo hora tiene que tener el formato correcto.',
            'descripcion.required' => 'Debe suministrar una descripcion válida.',
        ]);
        //dd($data);

        $data['fecha'] = Carbon::createFromFormat('Y-m-d H:i', $data['fecha'] .
                                                    ' ' . $data['hora']);
        $cols = General::columnas('avisos');
        Aviso::create([
            'user_id' => $data['asesor'],
            'turno_id' => isset($data['turno_id'])?$data['turno_id']:null,      // Siempre deberia ser nulo.
            'tipo' => isset($data['tipo'])?$data['tipo']:$cols['tipo']['xdef'], // Una amonestación.
            'fecha' => $data['fecha'],
            'descripcion' => $data['descripcion'],
            'user_creo' => Auth::user()->id,
        ]);
        //return redirect('usuarios');

        session(['exito' => "El aviso '" . $data['descripcion'] .
                            "' fue agregado con exito."]);
        return redirect()->route('avisos.crear');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Aviso  $aviso
     * @return \Illuminate\Http\Response
     */
    public function show(Aviso $aviso)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) return redirect()->back();

        $rutaPrevia = redirect()->getUrlGenerator()->previous();
        $orden = session('orden', 'id');
        if (0 < stripos($rutaPrevia, '?page'))
            $nroPagina = substr($rutaPrevia, stripos($rutaPrevia, '?page'));
        else $nroPagina ='';

        return view('avisos.show', compact('aviso', 'orden', 'nroPagina'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Aviso  $aviso
     * @return \Illuminate\Http\Response
     */
    public function edit(Aviso $aviso)
    {
        if (!(Auth::check())) return redirect('login');

        if ((!(Auth::user()->is_admin)) or
            ('C' == $aviso->tipo) or ('T' == $aviso->tipo) or               // Los turnos no se editan.
            (!(is_null($aviso->user_borro)))) return redirect()->back();    // Los registros borrados no se editan.

        $title = 'Editar ' . $this->tipo;
        $orden = session('orden', 'id');
        $rutaPrevia = redirect()->getUrlGenerator()->previous();
        if (0 < stripos($rutaPrevia, '?page'))
            $nroPagina = substr($rutaPrevia, stripos($rutaPrevia, '?page'));
        else $nroPagina ='';

        $cols = General::columnas('avisos');
        $tipos = $cols['tipo']['opcion'];
        unset($cols);
        //dd($aviso);

        $asesor = $aviso->user_id;
        $users   = User::where('is_admin', False)
                        ->where('activo', True)
                        ->where('id', $asesor)          // Se podra cambiar el asesor?????
                        ->get(['id', 'name']);
        if (Auth::user()->is_admin)
            return view('avisos.editar', ['aviso' => $aviso, 'title' => $title,
                            'users' => $users, 'asesor' => $asesor, 'tipos' => $tipos,
                            'orden' => $orden, 'rutaPrevia' => $rutaPrevia, 'nroPagina' => $nroPagina]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Aviso  $aviso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aviso $aviso, $rutaRetorno=null)
    {
        //print_r($request->all());
        $data = $request->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'asesor' => ['required', 'numeric', 'min:1'],
            'tipo' => 'required',       // Inicialmente, siempre será 'A' (Amonestación). Campo escondido.
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'descripcion' => 'required'
        ], [
            'asesor.required' => 'El campo asesor es obligatorio.',
            'asesor.min' => 'Tiene que seleccionar un asesor valido.',
            'tipo.required' => 'El campo tipo es obligatorio.',
            'fecha.required' => 'El campo fecha es obligatorio.',
            'fecha.date' => 'El campo fecha tiene que tener el formato correcto.',
            'hora.required' => 'El campo hora es obligatorio.',
            'hora.date_format' => 'El campo hora tiene que tener el formato correcto.',
            'descripcion.required' => 'Debe suministrar una descripcion válida.',
        ]);
        //dd($data);

        $data['fecha'] = Carbon::createFromFormat('Y-m-d H:i', "{$data['fecha']} {$data['hora']}");
        $data['user_actualizo'] = Auth::user()->id;
        $aviso->update($data);

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Aviso',
            'tx_data' => implode(';', $data),
            'tx_tipo' => 'A',
	        'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        if (!isset($rutaRetorno)) {
            if (isset($_GET['rutaRetorno'])) $rutaRetorno = $_GET['rutaRetorno'];
        }
        return redirect($rutaRetorno??'/avisos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Aviso  $aviso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aviso $aviso)
    {
        if (!(Auth::check())) return redirect('login');

        if ((!(Auth::user()->is_admin)) or
            ('C' == $aviso->tipo) or ('T' == $aviso->tipo) or               // Los turnos no se borran aqui.
            (!(is_null($aviso->user_borro)))) return redirect()->back();    // Los registros borrados no se borran.

        $data['user_borro'] = Auth::user()->id;
        $aviso->update($data);      // Actualizar 'user_borro', antes de borrar, abajo.

        $datos = "id:{$aviso->id}, tipo:{$aviso->tipo}, fecha:{$aviso->fecha}, descripcion:{$aviso->descripcion}";

        $aviso->delete();

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Aviso',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
	        'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        //return redirect()->route('avisos.index');
        return redirect()->back();
    }
}
