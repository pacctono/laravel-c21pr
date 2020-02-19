<?php

namespace App\Http\Controllers;

use App\Texto;
use App\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;                 // PC
use App\MisClases\General;               // PC

class TextoController extends Controller
{
    protected $tipo = 'Textos';
    protected $ruta = 'texto';
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
        if ($movil or ('html' != $accion)) $arreglo = Texto::orderBy($orden)->get();
	else $arreglo = Texto::orderBy($orden)->paginate($this->lineasXPagina);

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

        Texto::create([
            'descripcion' => $data['descripcion'],
        ]);

        return redirect()->route($this->ruta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Texto  $texto
     * @return \Illuminate\Http\Response
     */
    public function show(Texto $texto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Texto  $texto
     * @return \Illuminate\Http\Response
     */
    public function edit(Texto $texto)
    {
        $tipo = $this->tipo;
        $plural = strtolower($tipo);
        $singular = substr($tipo, 0, -1);
        $title = 'Editar ' . $singular;
        $ruta = $this->ruta;
        $objModelo = $texto;
        $rutActualizar = '/' . strtolower($tipo) . '/' . $texto->id;

        return view($this->vistaEditar, compact('objModelo', 'title', 'ruta', 'rutActualizar',
                                        'singular', 'plural'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Texto  $texto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Texto $texto)
    {
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'descripcion' => 'required',
            'enlace'      => '',
            'textoEnlace' => '',
        ], [
            'descripcion.required' => 'El campo descripcion es obligatorio',
        ]);
        //dd($data);
        $texto->update($data);

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Texto',
            'tx_data' => implode(';', $data),
            'tx_tipo' => 'A',
	    'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        return redirect()->route($this->ruta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Texto  $texto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Texto $texto)
    {
        $usuario = Auth::user()->id;
        $datos = 'id:'.$texto->id.', descripcion:'.$texto->descripcion;
        $texto->delete();

        Bitacora::create([
            'user_id' => $usuario,
            'tx_modelo' => 'Texto',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
	    'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        return redirect()->route($this->ruta);
    }
}
