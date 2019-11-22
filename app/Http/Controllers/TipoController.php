<?php

namespace App\Http\Controllers;

use App\Tipo;
use App\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;                 // PC
use App\MisClases\General;               // PC

class TipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $tipo = 'Tipos';
    protected $ruta = 'tipo';
    protected $enlace = 'contactos';
    protected $vistaCrear  = 'tabla.crear';
    protected $vistaIndice = 'tabla.index';
    protected $vistaEditar = 'tabla.editar';

    public function index($orden=null, $accion='html')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!auth()->user()->is_admin) {
            return redirect()->back();
        }

        $tipo = $this->tipo;
        $elemento = $this->ruta;
        $enlace   = $this->enlace;
        $metBorradas = $enlace . 'Borrados';
        $title = 'Listado de ' . $tipo;
        $rutCrear = $elemento . '.crear';
        $rutMostrar = $elemento . '.show';
        $rutEditar = $elemento . '.edit';
        $rutBorrar = $elemento . '.destroy';
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.

        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
        }
        if ($movil or ('html' != $accion)) $arreglo = Tipo::orderBy($orden)->get();
	else $arreglo = Tipo::orderBy($orden)->paginate(10);

        if ('html' == $accion)
            return view($this->vistaIndice,
                        compact('title', 'arreglo', 'elemento',     // Quite $elemento (=caracteristica), no entiendo que hace aqui.
                                'enlace', 'accion', 'movil', 'metBorradas',
                                'rutCrear', 'rutMostrar', 'rutEditar', 'rutBorrar'));
        $html = view($this->vistaIndice,
                        compact('title', 'arreglo', 'elemento',     // Quite $elemento (=caracteristica), no entiendo que hace aqui.
                                'enlace', 'accion', 'movil', 'metBorradas',
                                'rutCrear', 'rutMostrar', 'rutEditar', 'rutBorrar'))
                ->render();
        General::generarPdf($html, $elemento, $accion);
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

        Tipo::create([
            'descripcion' => $data['descripcion'],
        ]);

        return redirect()->route($this->ruta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function show(Tipo $tipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function edit(Tipo $tipo)
    {
        $plural = strtolower($this->tipo);
        $singular = substr($this->tipo, 0, -1);
        $title = 'Editar ' . $singular;
        $ruta = $this->ruta;
        $objModelo = $tipo;
        $rutActualizar = '/' . strtolower($this->tipo) . '/' . $tipo->id;

        return view($this->vistaEditar, compact('objModelo', 'title', 'ruta', 'rutActualizar',
                                        'singular', 'plural'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tipo $tipo)
    {
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'descripcion' => 'required',
        ], [
            'descripcion.required' => 'El campo descripcion es obligatorio',
        ]);
        //dd($data);
        $tipo->update($data);

        return redirect()->route($this->ruta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tipo $tipo)
    {
        if (0 < ($tipo->contactos->count() - $tipo->contactosBorrados($tipo->id)->count())) {
            return redirect()->route($this->ruta);  // Existen contactos asignados a este tipo.
        }
        if (0 < ($tipo->propiedades->count() - $tipo->propiedadesBorradas($tipo->id)->count())) {
            return redirect()->route($this->ruta);  // Existen propiedades asignados a este tipo.
        }
        if (0 < $tipo->contactosBorrados($tipo->id)->count()) {    // Existen contactos borrados (logico).
            $contactos = $tipo->contactos;         // Todos los contactos con este tipo, estan borrados.
            foreach ($contactos as $contacto) {     // Ciclo para borrar fisicamente los contactos.
                $contacto->delete();
            }
        }
        if (0 < $tipo->propiedadesBorradas($tipo->id)->count()) {    // Existen propiedades borrados (logico).
            $propiedades = $tipo->propiedades;         // Todos los propiedades con este tipo, estan borrados.
            foreach ($propiedades as $propiedad) {     // Ciclo para borrar fisicamente los propiedades.
                $propiedad->delete();
            }
        }
        $usuario = Auth::user()->id;
        $datos = 'id:'.$tipo->id.', descripcion:'.$tipo->descripcion;
        $tipo->delete();

        Bitacora::create([
            'user_id' => $usuario,
            'tx_modelo' => 'Tipo',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
        ]);

        return redirect()->route($this->ruta);
    }
}
