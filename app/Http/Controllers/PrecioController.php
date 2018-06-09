<?php

namespace App\Http\Controllers;

use App\Precio;
use App\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrecioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $tipo = 'Precios';
    protected $ruta = 'precio';
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
        $arreglo = Precio::orderBy($orden)->paginate(10);

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

        Precio::create([
            'descripcion' => $data['descripcion'],
        ]);

        return redirect()->route($this->ruta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Precio  $precio
     * @return \Illuminate\Http\Response
     */
    public function show(Precio $precio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Precio  $precio
     * @return \Illuminate\Http\Response
     */
    public function edit(Precio $precio)
    {
        $tipo = $this->tipo;
        $plural = strtolower($tipo);
        $singular = substr($tipo, 0, -1);
        $title = 'Editar ' . $singular;
        $ruta = $this->ruta;
        $objModelo = $precio;
        $rutActualizar = '/' . strtolower($tipo) . '/' . $precio->id;

        return view($this->vistaEditar, compact('objModelo', 'title', 'ruta', 'rutActualizar',
                                        'singular', 'plural'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Precio  $precio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Precio $precio)
    {
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'descripcion' => 'required',
        ], [
            'descripcion.required' => 'El campo descripcion es obligatorio',
        ]);
        //dd($data);
        $precio->update($data);

        return redirect()->route($this->ruta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Precio  $precio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Precio $precio)
    {
        if (0 < ($precio->contactos->count()-$precio->contactosBorrados($precio->id)->count())) {
            return redirect()->route($this->ruta);  // Existen contactos asignados a este usuario.
        }
        if (0 < $precio->contactosBorrados($precio->id)->count()) {    // Existen contactos borrados (logico).
            $contactos = $precio->contactos;         // Todos los contactos con este precio, estan borrados.
            foreach ($contactos as $contacto) {     // Ciclo para borrar fisicamente los contactos.
                $contacto->delete();
            }
        }
        $usuario = Auth::user()->id;
        $datos = 'id:'.$precio->id.', descripcion:'.$precio->descripcion;
        $precio->delete();

        Bitacora::create([
            'user_id' => $usuario,
            'tx_modelo' => 'Precio',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
        ]);

        return redirect()->route($this->ruta);
    }
}
