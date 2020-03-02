<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Venezueladdn;
use App\User;
use App\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;        // PC
use Carbon\Carbon;                          // PC
use Jenssegers\Agent\Agent;                 // PC
use App\MisClases\Fecha;                    // PC
use App\MisClases\General;                  // PC

class ClienteController extends Controller
{
    protected $tipo = 'Cliente';
    protected $tipoPlural = 'Clientes';
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
        $diaSemana = Fecha::$diaSemana;

        $title = 'Listado de ' . $this->tipoPlural;
        $ruta = request()->path();
        $dato = request()->all();
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

// En caso de volver luego de haber enviado un correo, ver el metodo 'emailcita', en AgendaController.
        $alertar = 0;
        if ('alert' == $orden) {
            $orden = '';
            $alertar = 1;
        }        
        $sentido = 'asc';
        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
            $sentido = 'desc';
        }

        if (Auth::user()->is_admin) {
            $clientes = Cliente::orderBy($orden, $sentido);
            $users   = User::where('activo', True)->get(['id', 'name']);     // Todos los usuarios (asesores), excepto los no activos.
            $users[0]['name'] = 'Administrador';
        } else {
            $clientes = User::find(Auth::user()->id)->clientes()->whereNull('user_borro');
            $users    = '';
        }
        if (0 < $asesor) {      // Se selecciono un asesor.
            $clientes = $clientes->where('user_id', $asesor);
        }
        $clientes = $clientes->orderBy($orden, $sentido);

        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        $paginar = ($paginar)?!($movil or ('html' != $accion)):$paginar;
        if ($paginar) $clientes = $clientes->paginate($this->lineasXPagina);      // Pagina la impresión de 10 en 10
        else $clientes = $clientes->get();                // Mostrar todos los registros.
        session(['orden' => $orden, 'asesor' => $asesor]);

        if ('html' == $accion)
            return view('clientes.index',
                        compact('title', 'clientes', 'ruta', 'diaSemana', 'alertar',
                            'users', 'asesor', 'paginar', 'orden', 'movil', 'accion'));
        $html = view('clientes.index',
                    compact('title', 'clientes', 'ruta', 'diaSemana', 'alertar',
                            'users', 'asesor', 'paginar', 'orden', 'movil', 'accion'))
                ->render();
        General::generarPdf($html, 'clientes', $accion);
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
        if (1 == Auth::user()->is_admin) {
            $clientes = Cliente::whereNull('user_borro')->orderBy($filtro)->paginate(10);
        } else {
            $clientes = User::find(Auth::user()->id)->clientes()->whereNull('user_borro')->orderBy($filtro)->paginate(10);
            //return redirect('/clientes/create');
        }
    
        //dd(Auth::user()->id);

        return view('clientes.index', compact('title', 'clientes', 'ruta', 'diaSemana'));
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

        $rutaPrevia = redirect()->getUrlGenerator()->previous();
        $title = 'Crear ' . $this->tipo;
        $orden = session('orden', 'id');
        if (0 < stripos($rutaPrevia, '?page'))
            $nroPagina = substr($rutaPrevia, stripos($rutaPrevia, '?page'));
        else $nroPagina ='';
        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();

        $exito = session('exito', '');
        session(['exito' => '']);
        $cols = General::columnas('clientes');
        $tipos = $cols['tipo']['opcion'];
        $tipoXDef = $cols['tipo']['xdef'];
        unset($cols);
        if (!Auth::user()->is_admin) unset($tipos['F']);
        return view('clientes.crear',
                compact('title', 'ddns', 'tipos', 'tipoXDef', 'exito', 'orden', 'nroPagina'));
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
            'cedula' => '',
            'rif' => '',
            'name' => 'required',
            'tipo' => '',
            'ddn' => '',
            'telefono' => '',
            'otro_telefono' => '',
            'email' => ['sometimes', 'nullable', 'email'],
            'fecha_nacimiento' => '',
            'direccion' => '',
            'observaciones' => '',
            'contacto_id' => '',
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'email.email' => 'Debe suministrar un correo elctrónico válido.',
        ]);

        //dd($data);
        if ('' != $data['ddn'] and '' != $data['telefono']) {
            $data['telefono'] = $data['ddn'] . $data['telefono'];
        } else {
            $data['telefono'] = '';
        }
        unset($data['ddn']);

        $cols = General::columnas('clientes');
        Cliente::create([
            'cedula' => $data['cedula'],
            'rif' => $data['rif']??null,
            'name' => $data['name'],
            'tipo' => $data['tipo']??$cols['tipo']['xdef'],
            'telefono' => $data['telefono'],
            'otro_telefono' => $data['otro_telefono']??null,
            'user_id' => Auth::user()->id,
            'email' => $data['email'],
            'fecha_nacimiento' => $data['fecha_nacimiento']??null,
            'direccion' => $data['direccion']??null,
            'observaciones' => $data['observaciones']??null,
            'contacto_id' => $data['contacto_id']??$cols['contacto_id']['xdef'],
        ]);

        //return redirect('usuarios');
        session(['exito' => "El cliente '" . $data['name'] .
                            "' fue agregado con exito."]);
        return redirect()->route('clientes.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        $diaSemana = Fecha::$diaSemana;
        $orden = session('orden', 'id');
        $rutaPrevia = redirect()->getUrlGenerator()->previous();
        if (0 < stripos($rutaPrevia, '?page'))
            $nroPagina = substr($rutaPrevia, stripos($rutaPrevia, '?page'));
        else $nroPagina ='';

        if ((Auth::user()->is_admin) or
            (is_null($cliente->user_borro) and ($cliente->user->id == Auth::user()->id))) {
            return view('clientes.show', compact('cliente', 'diaSemana', 'orden', 'nroPagina'));
        } else {
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(is_null($cliente->user_borro))) {     // Los registros borrados no se editan.
            return redirect('/clientes');
        }

        $title = 'Editar ' . $this->tipo;
        $orden = session('orden', 'id');
        $rutaPrevia = redirect()->getUrlGenerator()->previous();
        if (0 < stripos($rutaPrevia, '?page'))
            $nroPagina = substr($rutaPrevia, stripos($rutaPrevia, '?page'));
        else $nroPagina ='';

        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();
        $cols = General::columnas('clientes');
        $tipos = $cols['tipo']['opcion'];
        unset($cols);
        if (!Auth::user()->is_admin) unset($tipos['F']);

        //dd($cliente);
        if ((Auth::user()->is_admin) or
            (is_null($cliente->user_borro) and ($cliente->user->id == Auth::user()->id))) { // Ver arriba para filas borradas.
            return view('clientes.editar', ['cliente' => $cliente, 'title' => $title,
                            'ddns' => $ddns, 'tipos' => $tipos, 'orden' => $orden,
                            'nroPagina' => $nroPagina]);
        }
        return redirect('/clientes');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'cedula' => '',
            'rif' => '',
            'name' => 'required',
            'tipo' => '',
            'ddn' => '',
            'telefono' => '',
            'otro_telefono' => '',
            'email' => ['sometimes', 'nullable', 'email'],
            'fecha_nacimiento' => '',
            'direccion' => '',
            'observaciones' => '',
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'email.email' => 'Debe suministrar un correo elctrónico válido.',
        ]);

        //dd($data);
        if ('' != $data['ddn'] and '' != $data['telefono']) {
            $data['telefono'] = $data['ddn'] . $data['telefono'];
        } else {
            $data['telefono'] = null;
        }
        unset($data['ddn']);

        foreach (['cedula', 'rif', 'telefono', 'email', 'fecha_nacimiento', 'direccion',
                    'observaciones'] as $col) {
            if (isset($data[$col]) and ('' == $data[$col] or is_null($data[$col]))) {
                unset($data[$col]);
            }
        }
        $data['user_actualizo'] = Auth::user()->id;

        //dd($data);
        $cliente->update($data);

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Cliente',
            'tx_data' => implode(';', $data),
            'tx_tipo' => 'A',
	    'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        return redirect()->route('clientes.show', ['cliente' => $cliente]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        if (!Auth::user()->is_admin) {
            return redirect('/clientes');
        }
        if (!(is_null($cliente->user_borro))) {
            return redirect()->route('clientes.show', ['cliente' => $cliente]);
        }

        $data['user_borro'] = Auth::user()->id;
        $cliente->update($data);
        //$data['borrado_at'] = Carbon::now();
        //$data['borrado_at'] = new Carbon();

        $datos = 'id:'.$cliente->id.', cedula:'.$cliente->cedula.', nombre:'.$cliente->name;

        $cliente->delete();

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Cliente',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
	    'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        //return redirect()->route('clientes.index');
        return redirect()->back();
    }
}
