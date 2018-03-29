<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Deseo;
use App\Origen;
use App\Precio;
use App\Propiedad;
use App\Resultado;
use App\Zona;
use App\Venezueladdn;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;        // PC
use Carbon\Carbon;                          // PC

class ClienteController extends Controller
{
    protected $diaSemana = [
        'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($orden = null)
    {
        $title = 'Listado de clientes';
        $ruta = request()->path();
        $diaSemana = $this->diaSemana;

        if (!(Auth::check())) {
            return redirect('login');
        }

        if ('' == $orden or $orden == null) {
            $orden = 'id';
        }
        if (1 == Auth::user()->is_admin) {
            $clientes = Cliente::orderBy($orden)->paginate(10);
        } else {
            $clientes = User::find(Auth::user()->id)->clientes()->whereNull('user_borro')->orderBy($orden)->paginate(10);
            //return redirect('/clientes/create');
        }
    
        //dd(Auth::user()->id);

        return view('clientes.index', compact('title', 'clientes', 'ruta', 'diaSemana'));
    }

    public function filtro($filtro)
    {
        $title = 'Listado de clientes';
        $ruta = request()->path();
        $diaSemana = [
            'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'
        ];

        if (!(Auth::check())) {
            return redirect('login');
        }

        if ('' == $filtro or $filtro == null) {
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

        $title = 'Crear cliente';
        $deseos = Deseo::all();
        $origenes = Origen::all();
        $precios = Precio::all();
        $propiedades = Propiedad::all();
        $resultados = Resultado::all();
        $zonas = Zona::all();
        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();

        return view('clientes.create', compact(
            'title', 'deseos', 'origenes', 'precios',
            'propiedades', 'resultados', 'zonas', 'ddns'));
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
            'name' => 'required',
            'ddn' => '',
            'telefono' => '',
            'email' => ['sometimes', 'nullable', 'email'],
            'direccion' => '',
            'deseo_id' => 'required',
            'propiedad_id' => 'required',
            'zona_id' => 'required',
            'precio_id' => 'required',
            'origen_id' => 'required',
            'resultado_id' => 'required',
            'observaciones' => '',
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'email.email' => 'Debe suministrar un correo elctrónico válido.',
            'deseo_id.required' => 'El deseo del cliente es obligatorio suministrarlo.',
            'propiedad_id.required' => 'El tipo de propiedad es obligatorio suministrarlo.',
            'zona_id.required' => 'La zona de la propiedad es obligatorio suministrarla.',
            'precio_id.required' => 'El precio de la propiedad es obligatorio suministrarlo.',
            'origen_id.required' => 'El origen de como conocio de nuestra oficina es obligatorio suministrarlo.',
            'resultado_id.required' => 'El resultado de la conversación con el cliente es obligatorio suministrarlo.',
        ]);

        //$data['user_id'] = Auth::user()->id;
        //$data['user_id'] = intval($data['user_id']);
        //dd($data);

        if ('' != $data['ddn'] and '' != $data['telefono']) {
            $data['telefono'] = $data['ddn'] . $data['telefono'];
        } else {
            $data['telefono'] = '';
        }
        unset($data['ddn']);
        $data['veces_name'] = Cliente::ofVeces($data['name'], 'name') + 1;
        $data['veces_telefono'] = Cliente::ofVeces($data['telefono'], 'telefono') + 1;
        $data['veces_email'] = Cliente::ofVeces($data['email'], 'email') + 1;

        Cliente::create([
            'name' => $data['name'],
            'veces_name' => $data['veces_name'],
            'telefono' => $data['telefono'],
            'veces_telefono' => $data['veces_telefono'],
            'user_id' => Auth::user()->id,
            'email' => $data['email'],
            'veces_email' => $data['veces_email'],
            'direccion' => $data['direccion'],
            'deseo_id' => $data['deseo_id'],
            'propiedad_id' => $data['propiedad_id'],
            'zona_id' => $data['zona_id'],
            'precio_id' => $data['precio_id'],
            'origen_id' => $data['origen_id'],
            'resultado_id' => $data['resultado_id'],
            'observaciones' => $data['observaciones']
        ]);

        //return redirect('usuarios');
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
        $diaSemana = $this->diaSemana;

        if (!(Auth::check())) {
            return redirect('login');
        }
        if (1 == Auth::user()->is_admin) {
            return view('clientes.show', compact('cliente', 'diaSemana'));
        }
        if ($cliente->user_borro != null) {
            return redirect('/clientes');
        }
        if ($cliente->user->id == Auth::user()->id) {
            return view('clientes.show', compact('cliente', 'diaSemana'));
        } else {
            return redirect('/clientes');
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
        if ($cliente->user_borro != null) {
            return redirect('/clientes');
        }

        $title = 'Editar cliente';
        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();

        if ((1 == Auth::user()->is_admin) or ($cliente->user->id == Auth::user()->id)) {
            return view('clientes.edit', ['cliente' => $cliente, 'title' => $title, 'ddns' => $ddns]);
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
            'name' => 'required',
            'ddn' => '',
            'telefono' => '',
            'email' => ['sometimes', 'nullable', 'email'],
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

        foreach (['telefono', 'email', 'direccion', 'observaciones'] as $col) {
            if ('' == $data[$col] or $data[$col] == null) {
                unset($data[$col]);
            }
        }
/*        if ('' == $data['telefono'] or $data['telefono'] == null) {
            unset($data['telefono']);
        }
        if ('' == $data['email'] or $data['email'] == null) {
            unset($data['email']);
        }
        if ('' == $data['direccion'] or $data['direccion'] == null) {
            unset($data['direccion']);
        }
        if ('' == $data['observaciones'] or $data['observaciones'] == null) {
            unset($data['observaciones']);
        }*/
        $data['user_actualizo'] = Auth::user()->id;

        //dd($data);

        $cliente->update($data);

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

        if (1 != Auth::user()->is_admin) {
            return redirect('/clientes');
        }
        if ($cliente->user_borro != null) {
            return redirect()->route('clientes.show', ['cliente' => $cliente]);
        }

        $data['user_borro'] = Auth::user()->id;
        //$data['borrado_en'] = Carbon::now();
        $data['borrado_en'] = new Carbon();

        //dd($data);

        $cliente->update($data);

        return redirect()->route('clientes.index');
    }
}
