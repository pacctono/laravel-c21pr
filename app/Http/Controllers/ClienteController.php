<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Deseo;
use App\Origen;
use App\Precio;
use App\Propiedad;
use App\Resultado;
use App\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;        // PC

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::all();

        //dd(request()->path());

        $title = 'Listado de clientes';
        $ruta = request()->path();

        if (!(Auth::check())) {
            return redirect('login');
        }
        if (1 != Auth::user()->is_admin) {
            return redirect('/clientes/create');
        }

        return view('clientes.index', compact('title', 'clientes', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $deseos = Deseo::all();
        $origenes = Origen::all();
        $precios = Precio::all();
        $propiedades = Propiedad::all();
        $resultados = Resultado::all();
        $zonas = Zona::all();

        return view('clientes.create', compact('deseos', 'origenes', 'precios', 'propiedades', 'resultados', 'zonas'));
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
            'telefono' => '',
            'user_id' => '',
            'email' => ['email'],
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

        Cliente::create([
            'name' => $data['name'],
            'telefono' => $data['telefono'],
            'user_id' => Auth::user()->id,
            'email' => $data['email'],
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        //
    }
}
