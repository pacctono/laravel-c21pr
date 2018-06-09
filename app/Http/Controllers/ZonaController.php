<?php

namespace App\Http\Controllers;

use App\Zona;
use App\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $tipo = 'Zonas';
    protected $ruta = 'zona';
    protected $vistaCrear  = 'tabla.crear';
    protected $vistaIndice = 'tabla.index';
    protected $vistaEditar = 'tabla.edit';

    public function index($orden = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!auth()->user()->is_admin) {
            return redirect()->back();
        }

        $tipo = $this->tipo;
        $elemento = $this->ruta;
        $title = 'Listado de ' . $tipo;
        $rutCrear = $elemento . '.crear';
        $rutMostrar = $elemento . '.show';
        $rutEditar = $elemento . '.edit';
        $rutBorrar = $elemento . '.destroy';

        if ('' == $orden or $orden == null) {
            $orden = 'id';
        }
        $arreglo = Zona::orderBy($orden)->paginate(10);

        return view($this->vistaIndice, compact('title', 'arreglo', 'tipo', 'elemento',
                                        'rutCrear', 'rutMostrar', 'rutEditar', 'rutBorrar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipo = $this->tipo;
        $elemento = $this->ruta;
        $title = 'Crear ' . substr($tipo, 0, -1);
        $url  = '/' . strtolower($tipo);

        return view($this->vistaCrear, compact('title', 'tipo', 'elemento', 'url'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'descripcion' => 'required',
        ], [
            'descripcion.required' => 'El campo descripcion es obligatorio',
        ]);

        Zona::create([
            'descripcion' => $data['descripcion'],
        ]);

        return redirect()->route($this->ruta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Zona  $zona
     * @return \Illuminate\Http\Response
     */
    public function show(Zona $zona)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Zona  $zona
     * @return \Illuminate\Http\Response
     */
    public function edit(Zona $zona)
    {
        $tipo = $this->tipo;
        $plural = strtolower($tipo);
        $singular = substr($tipo, 0, -1);
        $title = 'Editar ' . $singular;
        $ruta = $this->ruta;
        $objModelo = $zona;
        $rutActualizar = '/' . strtolower($tipo) . '/' . $zona->id;

        return view($this->vistaEditar, compact('objModelo', 'title', 'ruta', 'rutActualizar',
                                        'singular', 'plural'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Zona  $zona
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zona $zona)
    {
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'descripcion' => 'required',
        ], [
            'descripcion.required' => 'El campo descripcion es obligatorio',
        ]);
        //dd($data);
        $zona->update($data);

        return redirect()->route($this->ruta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Zona  $zona
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zona $zona)
    {
        if (0 < ($zona->contactos->count()-$zona->contactosBorrados($zona->id)->count())) {
            return redirect()->route($this->ruta);  // Existen contactos asignados a este usuario.
        }
        if (0 < $zona->contactosBorrados($zona->id)->count()) {    // Existen contactos borrados (logico).
            $contactos = $zona->contactos;         // Todos los contactos con este zona, estan borrados.
            foreach ($contactos as $contacto) {     // Ciclo para borrar fisicamente los contactos.
                $contacto->delete();
            }
        }
        $usuario = Auth::user()->id;
        $datos = 'id:'.$zona->id.', descripcion:'.$zona->descripcion;
        $zona->delete();

        Bitacora::create([
            'user_id' => $usuario,
            'tx_modelo' => 'Zona',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
        ]);

        return redirect()->route($this->ruta);
    }
}
