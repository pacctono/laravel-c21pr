<?php

namespace App\Http\Controllers;

use App\Feriado;
use App\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;                 // PC
use App\MisClases\General;               // PC

class FeriadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $tipo = 'Feriados';
    protected $ruta = 'feriado';
    protected $enlace = False;
    protected $vistaCrear  = 'tabla.crear';
    protected $vistaIndice = 'tabla.index';
    protected $vistaEditar = 'tabla.editar';
    protected $lineasXPagina = General::LINEASXPAGINA;

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
        $metBorradas = $enlace . 'Borradas';
        $title = 'Listado de ' . $tipo;
        $rutCrear = $elemento . '.crear';
        $rutMostrar = $elemento . '.show';
        $rutEditar = $elemento . '.edit';
        $rutBorrar = $elemento . '.destroy';
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.

        if ('' == $orden or is_null($orden)) {
            $orden = 'fecha';
        }
        if ($movil or ('html' != $accion)) $arreglo = Feriado::orderBy($orden)->get();
	    else $arreglo = Feriado::orderBy($orden)->paginate($this->lineasXPagina);
        //$enlace .= '()';	// Aqui tiene que ser una funcion, no un atributo. La linea tiene que estar aqui, no antes.

        if ('html' == $accion)
            return view($this->vistaIndice,
                        compact('title', 'arreglo', 'elemento',     // Quite $elemento (=feriado), no entiendo que hace aqui.
                                'enlace', 'accion', 'movil', 'metBorradas',
                                'rutCrear', 'rutMostrar', 'rutEditar', 'rutBorrar'));
        $html = view($this->vistaIndice,
                        compact('title', 'arreglo', 'elemento',     // Quite $elemento (=feriado), no entiendo que hace aqui.
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
        $tipo = $this->tipo;				// Esta variable 'tipo' es comun en todas las tablas.
        $elemento = $this->ruta;
        $singular = substr($tipo, 0, -1);
        $title = 'Crear ' . substr($tipo, 0, -1);
        $url  = '/' . strtolower($tipo);

        $cols = General::columnas('feriados');
        $tipos = $cols['tipo']['opcion'];	// Esta variable 'tipos' es solo de la tabla 'feriados'.
        return view($this->vistaCrear, compact('title', 'tipos', 'tipo', 'elemento', 'singular', 'url'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'fecha' => ['required', 'date'],
            'tipo'  => 'required',
            'descripcion' => 'required',
        ], [
            'fecha.required' => 'La <fecha> es obligatoria.',
            'fecha.date' => 'La <fecha> debe ser valida.',
            'fecha.required' => 'La <fecha> es obligatoria.',
            'descripcion.required' => 'El campo descripcion es obligatorio',
        ]);

        $cols = General::columnas('feriados');
        Feriado::create([
            'fecha' => $data['fecha'],
            'tipo'  => (isset($data['tipo'])?$data['tipo']:$cols['tipo']['xdef']),
            'descripcion' => $data['descripcion'],
        ]);

        return redirect()->route($this->ruta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Feriado  $feriado
     * @return \Illuminate\Http\Response
     */
    public function show(Feriado $feriado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Feriado  $feriado
     * @return \Illuminate\Http\Response
     */
    public function edit(Feriado $feriado)
    {
        $tipo = $this->tipo;
        $plural = strtolower($tipo);
        $singular = substr($tipo, 0, -1);
        $title = 'Editar ' . $singular;
        $ruta = $this->ruta;
        $objModelo = $feriado;
        $rutActualizar = '/' . strtolower($tipo) . '/' . $feriado->id;

        $cols = General::columnas('feriados');
        $tipos = $cols['tipo']['opcion'];	// Esta variable 'tipos' es solo de la tabla 'feriados'.
        return view($this->vistaEditar, compact('objModelo', 'title', 'ruta', 'tipos',
                                            'rutActualizar', 'singular', 'plural'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Feriado  $feriado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feriado $feriado)
    {
        $data = $request->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'fecha' => ['required', 'date'],
            'tipo'  => 'required',
            'descripcion' => 'required',
        ], [
            'fecha.required' => 'La <fecha> es obligatoria.',
            'fecha.date' => 'La <fecha> debe ser valida.',
            'fecha.required' => 'La <fecha> es obligatoria.',
            'descripcion.required' => 'El campo descripcion es obligatorio',
        ]);
        //dd($data);
        $feriado->update($data);

        return redirect()->route($this->ruta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Feriado  $feriado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feriado $feriado)
    {
        $usuario = Auth::user()->id;
        $datos = 'id:'.$feriado->id.', descripcion:'.$feriado->descripcion;
        $feriado->delete();

        Bitacora::create([
            'user_id' => $usuario,
            'tx_modelo' => 'Feriado',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
	    'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        return redirect()->route($this->ruta);
    }
}
