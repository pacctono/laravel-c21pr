<?php

namespace App\Http\Controllers;

use App\Price;
use App\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;                 // PC
use App\MisClases\General;               // PC

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $tipo = 'Prices';
    protected $ruta = 'price';
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
        if ($movil or ('html' != $accion)) $arreglo = Price::orderBy($orden)->get();
	else $arreglo = Price::orderBy($orden)->paginate(10);

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
            'menor' => 'required',
            'mayor' => 'required',
        ], [
            'menor.required' => "El campo 'menor' es obligatorio",
            'mayor.required' => "El campo 'mayor' es obligatorio",
        ]);

        Price::create([
            'menor' => $data['menor'],
            'mayor' => $data['mayor'],
        ]);

        return redirect()->route($this->ruta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Price  $precio
     * @return \Illuminate\Http\Response
     */
    public function show(Price $precio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Price  $precio
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $precio)
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
     * @param  \App\Price  $precio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Price $precio)
    {
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'menor' => 'required',
            'mayor' => 'required',
        ], [
            'menor.required' => "El campo 'menor' es obligatorio",
            'mayor.required' => "El campo 'mayor' es obligatorio",
        ]);
        //dd($data);
        $precio->update($data);

        return redirect()->route($this->ruta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Price  $precio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $precio)
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
        $datos = 'id:'.$precio->id.', descripcion:'.$precio->menor.', '.$precio->mayor;
        $precio->delete();

        Bitacora::create([
            'user_id' => $usuario,
            'tx_modelo' => 'Price',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
	    'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        return redirect()->route($this->ruta);
    }
}
