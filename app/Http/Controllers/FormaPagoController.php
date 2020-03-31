<?php

namespace App\Http\Controllers;

use App\FormaPago;
use App\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;                 // PC
use App\MisClases\General;               // PC

class FormaPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $tipo = 'Forma_Pagos';
    protected $ruta = 'forma_pago';
    protected $enlace = 'propiedades';
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
            $orden = 'id';
        }
        if ($movil or ('html' != $accion)) $arreglo = FormaPago::orderBy($orden)->get();
	    else $arreglo = FormaPago::orderBy($orden)->paginate($this->lineasXPagina);
        //$enlace .= '()';	// Aqui tiene que ser una funcion, no un atributo. La linea tiene que estar aqui, no antes.

        if ('html' == $accion)
            return view($this->vistaIndice,
                        compact('title', 'arreglo', 'elemento',     // Quite $elemento (=forma_pago), no entiendo que hace aqui.
                                'enlace', 'accion', 'movil', 'metBorradas',
                                'rutCrear', 'rutMostrar', 'rutEditar', 'rutBorrar'));
        $html = view($this->vistaIndice,
                        compact('title', 'arreglo', 'elemento',     // Quite $elemento (=forma_pago), no entiendo que hace aqui.
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

        return view($this->vistaCrear, compact('title', 'tipo', 'elemento', 'url'));     // Quite $elemento (=forma_pago), no entiendo que hace aqui.
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
            'descripcion' => 'required',
        ], [
            'descripcion.required' => 'El campo descripcion es obligatorio',
        ]);

        FormaPago::create([
            'descripcion' => $data['descripcion'],
        ]);

        return redirect()->route($this->ruta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FormaPago  $forma_pago
     * @return \Illuminate\Http\Response
     */
    public function show(FormaPago $forma_pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormaPago  $forma_pago
     * @return \Illuminate\Http\Response
     */
    public function edit(FormaPago $forma_pago)
    {
        $tipo = $this->tipo;
        $plural = strtolower($tipo);
        $singular = substr($tipo, 0, -1);
        $title = 'Editar ' . $singular;
        $ruta = $this->ruta;
        $objModelo = $forma_pago;
        $rutActualizar = '/' . strtolower($tipo) . '/' . $forma_pago->id;

        return view($this->vistaEditar, compact('objModelo', 'title', 'ruta',
                                            'rutActualizar', 'singular', 'plural'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormaPago  $forma_pago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormaPago $forma_pago)
    {
        $data = $request->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'descripcion' => 'required',
        ], [
            'descripcion.required' => 'El campo descripcion es obligatorio',
        ]);
        //dd($data);
        $forma_pago->update($data);

        return redirect()->route($this->ruta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormaPago  $forma_pago
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormaPago $forma_pago)
    {
        if (0 < ($forma_pago->propiedades->count()-$forma_pago->propiedadesBorrados($forma_pago->id)->count())) {
            return redirect()->route($this->ruta);  // Existen propiedades asignados a este usuario.
        }
        if (0 < $forma_pago->propiedadesBorrados($forma_pago->id)->count()) {    // Existen propiedades borrados (logico).
            $propiedades = $forma_pago->propiedades;         // Todos los propiedades con este forma_pago, estan borrados.
            foreach ($propiedades as $propiedad) {     // Ciclo para borrar fisicamente los propiedades.
                $propiedad->delete();
            }
        }
        $usuario = Auth::user()->id;
        $datos = 'id:'.$forma_pago->id.', descripcion:'.$forma_pago->descripcion;
        $forma_pago->delete();

        Bitacora::create([
            'user_id' => $usuario,
            'tx_modelo' => 'FormaPago',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
	    'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        return redirect()->route($this->ruta);
    }
}
