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
use App\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;        // PC
use Carbon\Carbon;                          // PC
use Jenssegers\Agent\Agent;                 // PC
use Mpdf\Mpdf;
use App\MisClases\General;                  // PC

class ContactoController extends Controller
{
    protected $tipo = 'Contacto Inicial';
    protected $tipoPlural = 'Contactos Iniciales';
    protected $lineasXPagina = General::LINEASXPAGINA;
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

        $accion = 'html';
// En caso de volver luego de haber enviado un correo, ver el metodo 'emailcita', en AgendaController.
        $alertar = 0;
        if ('alert' == $orden) {
            $orden = '';
            $alertar = 1;
        } elseif (('ver' == $orden) or ('descargar' == $orden)) {
            $accion = $orden;
            $orden = '';
        }
        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
        }

        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        if (Auth::user()->is_admin) {
            $contactos = Contacto::orderBy($orden);
        } else {
            $contactos = User::find(Auth::user()->id)
                                ->contactos()->whereNull('user_borro')->orderBy($orden);
            //return redirect('/contactos/create');
        }
        if ($movil or ('html' != $accion)) $contactos = $contactos->get();
        else $contactos = $contactos->paginate($this->lineasXPagina);
        //dd(Auth::user()->id);

        if ('html' == $accion)
            return view('contactos.index',
                    compact('title', 'contactos', 'ruta', 'alertar', 'movil', 'accion'));
        $html = view('contactos.index',
                    compact('title', 'contactos', 'ruta', 'alertar', 'movil', 'accion'))
                ->render();
        //dd($html);
        General::generarPdf($html, 'contactosIniciales', $accion);
/*
        $namefile = 'contactosIniciales_'.time().'.pdf';
 
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
 
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path() . '/fonts',
            ]),
            'fontdata' => $fontData + [
                'arial' => [
                    'R' => 'arial.ttf',
                    'B' => 'arialbd.ttf',
                ],
            ],
            'default_font' => 'arial',
            "format" => "letter",  // Carta. Otras opciones: A4, A3, A2, etc.
        ]);
        // $mpdf->SetTopMargin(5);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        // dd($mpdf);
        if($accion=='ver'){
            $mpdf->Output($namefile,"I");
        }elseif($accion=='descargar'){
            $mpdf->Output($namefile,"D");   // "D": Descargar el archivo. "F": Guardar el archivo.
        }
 */
    }

    public function filtro($filtro)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $title = 'Listado de ' . $this->tipo;
        $ruta = request()->path();

        if ('' == $filtro or is_null($filtro)) {
            $filtro = 'created_at';
        }
        if (1 == Auth::user()->is_admin) {
            $contactos = Contacto::whereNull('user_borro')
                                    ->orderBy($filtro)
                                    ->paginate($this->lineasXPagina);
        } else {
            $contactos = User::find(Auth::user()->id)
                                ->contactos()
                                ->whereNull('user_borro')
                                ->orderBy($filtro)
                                ->paginate($this->lineasXPagina);
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

        return view('contactos.crear', compact(
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

        if (!(is_null($data['ddn'])) and '' != $data['ddn'] and !(is_null($data['telefono'])) and
                                                        '' != $data['telefono']) {
            $data['telefono'] = $data['ddn'] . $data['telefono'];
        } else {
            $data['telefono'] = '';
        }
        unset($data['ddn']);
        $data['veces_name'] = Contacto::ofVeces($data['name'], 'name') + 1;
        $data['veces_telefono'] = Contacto::ofVeces($data['telefono'], 'telefono') + 1;
        $data['veces_email'] = Contacto::ofVeces($data['email'], 'email') + 1;
        if (!(is_null($data['fecha_evento']))) {
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
        if (!(is_null($contacto->user_borro))) {
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
        if (!(is_null($contacto->user_borro))) {
            return redirect('/contactos');
        }

        $title = 'Editar ' . $this->tipo;

        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();

        if ((Auth::user()->is_admin) or ($contacto->user->id == Auth::user()->id)) {
            return view('contactos.editar', ['contacto' => $contacto, 'title' => $title,
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

        if (!(is_null($data['ddn'])) and '' != $data['ddn'] and !(is_null($data['telefono'])) and
                                                        '' != $data['telefono']) {
            $data['telefono'] = $data['ddn'] . $data['telefono'];
        } else {
            $data['telefono'] = null;
        }
        unset($data['ddn']);

/*        foreach (['telefono', 'email', 'direccion', 'observaciones'] as $col) {
            if ('' == $data[$col] or is_null($data[$col])) {
                unset($data[$col]);
            }
        }*/
        if ('' == $data['telefono'] or is_null($data['telefono'])) {
            $data['veces_telefono'] = 0;
        }
        if ('' == $data['email'] or is_null($data['email'])) {
            $data['veces_email'] = 0;
        }
/*        if ('' == $data['direccion'] or is_null($data['direccion'])) {
            unset($data['direccion']);
        }
        if ('' == $data['observaciones'] or is_null($data['observaciones'])) {
            unset($data['observaciones']);
        }*/
        $data['user_actualizo'] = Auth::user()->id;

        //dd($data);

        $contacto->update($data);

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Contacto',
            'tx_data' => implode(';', $data),
            'tx_tipo' => 'A',
        ]);

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

        if (!Auth::user()->is_admin) {
            return redirect('/contactos');
        }
        if (!(is_null($contacto->user_borro))) {
            return redirect()->route('contactos.show', ['contacto' => $contacto]);
        }

        $data['user_borro'] = Auth::user()->id;
        $contacto->update($data);
        //$data['borrado_at'] = Carbon::now();
        //$data['borrado_at'] = new Carbon();

        $datos = 'id:'.$contacto->id.', cedula:'.$contacto->cedula.', nombre:'.$contacto->name;

        $contacto->delete();

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Contacto',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
        ]);

        return redirect()->route('contactos.index');
    }
}
