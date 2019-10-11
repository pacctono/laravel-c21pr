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
    public function index($orden = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        $diaSemana = Fecha::$diaSemana;

        $title = 'Listado de ' . $this->tipoPlural;
        $ruta = request()->path();

// En caso de volver luego de haber enviado un correo, ver el metodo 'emailcita', en AgendaController.
        $alertar = 0;
        if ('alert' == $orden) {
            $orden = '';
            $alertar = 1;
        }        
        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
        }

        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.

        if (Auth::user()->is_admin) {
            $clientes = Cliente::orderBy($orden);
        } else {
            $clientes = Cliente::whereNull('user_borro')->orderBy($orden);
        }
        if ($movil) $clientes = $clientes->get();
        else $clientes = $clientes->paginate($this->lineasXPagina);

        session(['orden' => $orden]);
        return view('clientes.index',
                    compact('title', 'clientes', 'ruta', 'diaSemana', 'alertar', 'movil'));
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
        return view('clientes.create', compact('title', 'ddns', 'exito', 'orden', 'nroPagina'));
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
            'ddn' => '',
            'telefono' => '',
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
            $data['telefono'] = '';
        }
        unset($data['ddn']);

        Cliente::create([
            'cedula' => $data['cedula'],
            'rif' => $data['rif'],
            'name' => $data['name'],
            'telefono' => $data['telefono'],
            'user_id' => Auth::user()->id,
            'email' => $data['email'],
            'fecha_nacimiento' => $data['fecha_nacimiento'],
            'direccion' => $data['direccion'],
            'observaciones' => $data['observaciones']
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

        //dd($cliente);
        if ((Auth::user()->is_admin) or ($cliente->user->id == Auth::user()->id)) { // Ver arriba para filas borradas.
            return view('clientes.edit', ['cliente' => $cliente, 'title' => $title,
                        'ddns' => $ddns, 'orden' => $orden,'nroPagina' => $nroPagina]);
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
            'ddn' => '',
            'telefono' => '',
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
            if ('' == $data[$col] or is_null($data[$col])) {
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
        ]);

        //return redirect()->route('clientes.index');
        return redirect()->back();
    }
}
