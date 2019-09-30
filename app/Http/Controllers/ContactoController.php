<?php

namespace App\Http\Controllers;

use App\Contacto;
use App\Deseo;
use App\Origen;
use App\Precio;
use App\Tipo;
use App\Resultado;
use App\Zona;
use App\Venezueladdn;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;        // PC
use Carbon\Carbon;                          // PC
use Jenssegers\Agent\Agent;                 // PC

class ContactoController extends Controller
{
    protected $tipo = 'Contacto Inicial';
    protected $tipoPlural = 'Contactos Iniciales';
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

        $title = 'Listado de ' . $this->tipoPlural;
        $ruta = request()->path();

// En caso de volver luego de haber enviado un correo, ver el metodo 'emailcita', en AgendaController.
        $alertar = 0;
        if ('alert' == $orden) {
            $orden = '';
            $alertar = 1;
        }
        if ('' == $orden or $orden == null) {
            $orden = 'id';
        }

        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        if (Auth::user()->is_admin) {
            $contactos = Contacto::orderBy($orden);
        } else {
            $contactos = User::find(Auth::user()->id)->contactos()->whereNull('user_borro')->orderBy($orden);
            //return redirect('/contactos/create');
        }
        if ($movil) $contactos = $contactos->get();
        else $contactos = $contactos->paginate(10);
    
        //dd(Auth::user()->id);

        return view('contactos.index', compact('title', 'contactos', 'ruta', 'alertar', 'movil'));
    }

    public function filtro($filtro)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $title = 'Listado de ' . $this->tipo;
        $ruta = request()->path();

        if ('' == $filtro or $filtro == null) {
            $filtro = 'created_at';
        }
        if (1 == Auth::user()->is_admin) {
            $contactos = Contacto::whereNull('user_borro')->orderBy($filtro)->paginate(10);
        } else {
            $contactos = User::find(Auth::user()->id)->contactos()->whereNull('user_borro')->orderBy($filtro)->paginate(10);
            //return redirect('/contactos/create');
        }
    
        //dd(Auth::user()->id);

        return view('contactos.index', compact('title', 'contactos', 'ruta'));
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

        $title = 'Crear ' . $this->tipo;
        $deseos = Deseo::all();
        $origenes = Origen::all();
        $precios = Precio::all();
        $tipos = Tipo::all();
        $resultados = Resultado::all();
        $zonas = Zona::all();
        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();
        $exito = session('exito', '');
        session(['exito' => '']);

        return view('contactos.create', compact(
            'title', 'deseos', 'origenes', 'precios',
            'tipos', 'resultados', 'zonas', 'ddns', 'exito'));
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
            'cedula' => ['sometimes', 'nullable', 'digits_between:6,8'],
            'name' => 'required',
            'ddn' => '',
            'telefono' => ['sometimes', 'nullable', 'digits:7'],
            'email' => ['sometimes', 'nullable', 'email'],
            'direccion' => '',
            'deseo_id' => 'required',
            'tipo_id' => 'required',
            'zona_id' => 'required',
            'precio_id' => 'required',
            'origen_id' => 'required',
            'resultado_id' => 'required',
            'fecha_evento' => ['sometimes', 'nullable', 'required_if:resultado_id,4,5,6,7', 'date'],
// El tipo 'time' decuelve la hora militar (formato de 24 horas) por eso 'H:i' no incluye am o pm.
            'hora_evento' => ['sometimes', 'nullable', 'required_if:resultado_id,4,5,6,7', 'date_format:H:i'],
            'observaciones' => '',
        ], [
            'cedula.digits_between' => 'La cedula de ideintidad debe contener 7 u 8 digitos',
            'name.required' => 'El campo nombre es obligatorio.',
            'telefono.digits:7' => 'La parte del telefono, sin ddn, debe contener 7 dígitos',
            'email.email' => 'Debe suministrar un correo electrónico válido.',
            'deseo_id.required' => 'El deseo del contacto inicial es obligatorio suministrarlo.',
            'tipo_id.required' => 'El tipo de tipo es obligatorio suministrarlo.',
            'zona_id.required' => 'La zona de la tipo es obligatorio suministrarla.',
            'precio_id.required' => 'El precio de la tipo es obligatorio suministrarlo.',
            'origen_id.required' => 'El origen de como conocio de nuestra oficina es obligatorio suministrarlo.',
            'resultado_id.required' => 'El resultado de la conversación con el contacto inicial es obligatorio suministrarlo.',
            'fecha_evento.required_if' => 'La fecha del evento es requerida, cuando el resultado es llamada o cita',
            'fecha_evento.date' => 'La fecha del evento debe ser una fecha valida.',
            'hora_evento.required_if' => 'La hora del evento es requerida, cuando el resultado es llamada o cita',
            'hora_evento.date_format' => 'La hora del evento debe ser una hora valida.',
        ]);

        //$data['user_id'] = Auth::user()->id;
        //$data['user_id'] = intval($data['user_id']);
        //dd($data);

        if (null != $data['ddn'] and '' != $data['ddn'] and null != $data['telefono'] and
                                                        '' != $data['telefono']) {
            $data['telefono'] = $data['ddn'] . $data['telefono'];
        } else {
            $data['telefono'] = '';
        }
        unset($data['ddn']);
        $data['veces_name'] = Contacto::ofVeces($data['name'], 'name') + 1;
        $data['veces_telefono'] = Contacto::ofVeces($data['telefono'], 'telefono') + 1;
        $data['veces_email'] = Contacto::ofVeces($data['email'], 'email') + 1;
        if (null != $data['fecha_evento']) {
// El tipo 'time' decuelve la hora militar (formato de 24 horas) por eso 'H:i' no incluye am/pm.
            $data['fecha_evento'] = Carbon::createFromFormat('Y-m-d H:i', $data['fecha_evento'] . ' ' . $data['hora_evento']);
        }

        Contacto::create([
            'cedula' => $data['cedula'],
            'name' => $data['name'],
            'veces_name' => $data['veces_name'],
            'telefono' => $data['telefono'],
            'veces_telefono' => $data['veces_telefono'],
            'user_id' => Auth::user()->id,
            'email' => $data['email'],
            'veces_email' => $data['veces_email'],
            'direccion' => $data['direccion'],
            'deseo_id' => $data['deseo_id'],
            'tipo_id' => $data['tipo_id'],
            'zona_id' => $data['zona_id'],
            'precio_id' => $data['precio_id'],
            'origen_id' => $data['origen_id'],
            'resultado_id' => $data['resultado_id'],
            'fecha_evento' => $data['fecha_evento'],
            'observaciones' => $data['observaciones']
        ]);

        session(['exito' => "El contacto inicial '" . $data['name'] .
                            "' fue agregado con exito."]);
        return redirect()->route('contactos.create');
    }

    /**
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function show(Contacto $contacto, $rutRetorno='contactos.index')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $col_id = '';
        if (15 < strlen($rutRetorno)) {
            $col_id = strtolower(substr($rutRetorno, 17)) . '_id';
        }
        
        if (1 == Auth::user()->is_admin) {
            return view('contactos.show', compact('contacto', 'rutRetorno', 'col_id'));
        }
        if ($contacto->user_borro != null) {
            return redirect()->back();
        }
        if ($contacto->user->id == Auth::user()->id) {
            return view('contactos.show', compact('contacto', 'rutRetorno', 'col_id'));
        } else {
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function edit(Contacto $contacto)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if ($contacto->user_borro != null) {
            return redirect('/contactos');
        }

        $title = 'Editar ' . $this->tipo;

        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();

        if ((1 == Auth::user()->is_admin) or ($contacto->user->id == Auth::user()->id)) {
            return view('contactos.edit', ['contacto' => $contacto, 'title' => $title,
                        'ddns' => $ddns]);
        }
        return redirect('/contactos');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contacto $contacto)
    {
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'cedula' => ['sometimes', 'nullable', 'digits_between:7,8'],
            'name' => 'required',
            'ddn' => '',
            'telefono' => ['sometimes', 'nullable', 'digits:7'],
            'email' => ['sometimes', 'nullable', 'email'],
            'direccion' => '',
            'observaciones' => '',
        ], [
            'cedula.digits_between' => 'La cedula de ideintidad debe contener 7 u 8 digitos',
            'name.required' => 'El campo nombre es obligatorio.',
            'telefono.digits:7' => 'La parte del telefono, sin ddn, debe contener 7 dígitos',
            'email.email' => 'Debe suministrar un correo elctrónico válido.',
        ]);

        //dd($data);

        if (null != $data['ddn'] and '' != $data['ddn'] and null != $data['telefono'] and
                                                        '' != $data['telefono']) {
            $data['telefono'] = $data['ddn'] . $data['telefono'];
        } else {
            $data['telefono'] = null;
        }
        unset($data['ddn']);

/*        foreach (['telefono', 'email', 'direccion', 'observaciones'] as $col) {
            if ('' == $data[$col] or $data[$col] == null) {
                unset($data[$col]);
            }
        }*/
        if ('' == $data['telefono'] or $data['telefono'] == null) {
            $data['veces_telefono'] = 0;
        }
        if ('' == $data['email'] or $data['email'] == null) {
            $data['veces_email'] = 0;
        }
/*        if ('' == $data['direccion'] or $data['direccion'] == null) {
            unset($data['direccion']);
        }
        if ('' == $data['observaciones'] or $data['observaciones'] == null) {
            unset($data['observaciones']);
        }*/
        $data['user_actualizo'] = Auth::user()->id;

        //dd($data);

        $contacto->update($data);

        return redirect()->route('contactos.show', ['contacto' => $contacto]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contacto $contacto)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        if (1 != Auth::user()->is_admin) {
            return redirect('/contactos');
        }
        if ($contacto->user_borro != null) {
            return redirect()->route('contactos.show', ['contacto' => $contacto]);
        }

        $data['user_borro'] = Auth::user()->id;
        //$data['borrado_at'] = Carbon::now();
        //$data['borrado_at'] = new Carbon();

        $contacto->delete();

        return redirect()->route('contactos.index');
    }
}
