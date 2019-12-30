<?php

namespace App\Http\Controllers;

use App\AgendaPersonal;
use App\User;
use App\Contacto;
use App\Cliente;
use App\Bitacora;
use \App\Mail\CitaPersonalAsesor;
use App\Venezueladdn;
use \App\Mail\CitaAsesor;
use \App\Mail\CitasAsesor;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\MisClases\Fecha;
use Jenssegers\Agent\Agent;                 // PC

class AgendaPersonalController extends Controller
{
    protected $tipo = 'cita personal ';

    public function index($orden = null) {
        if (!(Auth::check())) {             // No esta conectado.
            return redirect('login');
        }
// Variables propias de la metodo de la controlador.
        $title = $this->tipo;
        $ruta = request()->path();
        $periodo = request()->all();
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
// Todo se inicializa, cuando se selecciona desde el menú horizontal principal.
        if (('GET' == request()->method()) and ('' == $orden) and (0 == count($periodo))) {
            session(['rPeriodo' => '', 'fecha_desde' => '', 'fecha_hasta' => '', 'asesor' => '0']);
        }
/*
 * Manejo de las variables de la forma superior. $periodo (radios, fecha_desde, fecha_hasta y asesor).
 * Cuando el arreglo $periodo contiene un solo item, este es el número de página (page=n).
 * Si el arreglo $periodo está vacio (count($arreglo) == 0), es una ruta 'GET' con o sin $orden.
 * Si $periodo tiene más de 1 item. Fue seleccionado un radio y/o las fechas y el asesor.
 */
        if (1 >= count($periodo)) {
            $rPeriodo    = session('rPeriodo', '');
            $fecha_desde = session('fecha_desde', '');
            $fecha_hasta = session('fecha_hasta', '');
            $asesor      = session('asesor', '0');
        } else {    // Se ha solicitado una fecha o asesor especifico. Forma al inicio de la pagina.
            if ($movil) $periodo['periodo'] = 'intervalo';
            $rPeriodo = $periodo['periodo'];            // Radio periodo.
            $fecha_min = (new Carbon(AgendaPersonal::min('fecha_cita')));
            $fecha_max = (new Carbon(AgendaPersonal::max('fecha_cita')));
            if (isset($periodo['asesor'])) {
                $asesor = $periodo['asesor'];
                if ((!isset($periodo['fecha_desde'])) or ('' == $periodo['fecha_desde']))
                    $periodo['fecha_desde'] = $fecha_min;
                if ((!isset($periodo['fecha_hasta'])) or ('' == $periodo['fecha_hasta']))
                    $periodo['fecha_hasta'] = $fecha_max;
            }
            else $asesor = 0;
            list ($fecha_desde, $fecha_hasta) = Fecha::periodo($periodo, $fecha_min, $fecha_max);
        }
        //dd($periodo, $fecha_desde, $fecha_hasta);
// En caso de volver luego de haber enviado un correo, ver el metodo 'emailcita', en AgendaPersonalController.
        $alertar = 0;
        if ('alert' == $orden) {
            $orden = '';
            $alertar = 1;
        }
        if ('' == $orden or $orden == null) {
            $orden = 'fecha_cita';
        }

        if (Auth::user()->is_admin) {       // El usuario (asesor) es un administrador.
//            $users   = User::all();         // Todos los usuarios. Incluye '1' porque en turnos hay feriado.
            $users   = User::where('activo', True)->get();
            $users[0]['name'] = 'Administrador';
            $agendas = AgendaPersonal::select('fecha_cita', 'hora_cita', 'descripcion', 'name',
                                        'telefono', 'user_id', 'email', 'direccion');   // Solo estas columnas.
            if ('name' == $orden) {         // Si se ordena por nombre, no muestra turnos (name == '').
                $agendas = $agendas->where('name', '!=', '');
            }
        } else {                            // El usuario (asesor) no es un administrador.
            $asesor = Auth::user()->id;    // id de la usuario (asesor) conectado.
            $asesorConectado = User::find($asesor);    // Consigue la clase App\User de la asesor conectado.
            $title  .= 'de ' . $asesorConectado->name;  // Titulo de la página de la AgendaPersonal.
            $agendas = AgendaPersonal::where('user_id', $asesor)   // Solo el asesor conectado.
                    ->select('fecha_cita', 'hora_cita', 'descripcion', 'name', 'telefono',
                                'email', 'direccion');           // Solo estas columnas.
        }

        if (0 < $asesor) {      // Se selecciono un asesor o el conectado no es administrador.
            $agendas = $agendas->where('user_id', $asesor);
        }
// Devolver las fechas sin la hora. Los diez primeros caracteres son: yyyy-mm-dd.
        if ('' != $fecha_desde and '' != $fecha_hasta) {    // No se seleccionaron fechas.
            $fecha_desde = substr($fecha_desde, 0, 10);
            $fecha_hasta = substr($fecha_hasta, 0, 10);
            $agendas = $agendas->whereBetween('fecha_cita', [$fecha_desde, $fecha_hasta]);
        }
        $agendas = $agendas->orderBy($orden);   // Ordenar los items de la agenda.
        if ('user_id' == $orden) {              // Si se pidió ordenar por id de usuario,
            $agendas = $agendas->orderBy('fecha_cita');   // ordenar por fecha_cita en cada usuario.
        }
        if ($movil) $agendas = $agendas->get();
        else $agendas = $agendas->paginate(10);               // Pagina la impresión de 10 en 10
        session(['rPeriodo' => $rPeriodo, 'fecha_desde' => $fecha_desde,    // Asignar valores en sesión.
                    'fecha_hasta' => $fecha_hasta, 'asesor' => $asesor]);
        //if ('este_mes' == $rPeriodo) dd($agendas, $users, $fecha_desde, $fecha_hasta, $asesor);
        return view('agenda.index', compact('title', 'users', 'ruta', 'agendas',
                    'rPeriodo', 'fecha_desde', 'fecha_hasta', 'asesor', 'alertar', 'movil'));
    }

    public function show(AgendaPersonal $cita)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        if ((!Auth::user()->is_admin) and ($cita->id != Auth::user()->id)) {
            return redirect('/agenda');
        }

        $rutaPrevia = url()->previous();
        if (((False === strpos($rutaPrevia, 'agenda/')) and
            (False === strpos($rutaPrevia, 'agenda?page')) and
            (False === strpos($rutaPrevia, 'agenda/orden'))) or
            (1 === preg_match('@agenda/([0-9]+)/@', $rutaPrevia)))
            $rutaPrevia = null;

        //dd($cita, $cita->cita_dia_semana);
        return view('agendaPersonal.show', compact('cita', 'rutaPrevia'));
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

//        if ((!Auth::user()->is_admin) and ($agenda->id != Auth::user()->id)) {
//            return redirect('/agenda');
//        }

        $title = 'Crear ' . $this->tipo;
        $exito = session('exito', '');
        session(['exito' => '']);
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        if (Auth::user()->is_admin) {
            $contactos = Contacto::all();
            $clientes  = Cliente::all();
        } else {
            $contactos = Contacto::where('user_id', Auth::user()->id)->get();
            $clientes  = Cliente::where('user_id', Auth::user()->id)->get();
        }
        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();
        return view('agendaPersonal.crear',
                compact('title', 'contactos', 'clientes', 'ddns', 'exito'));
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
            'fecha_cita' => ['date'],
            'hora_cita'  => ['date_format:H:i'],
            'descripcion' => 'required',
            'contacto_id' => '',
            'cliente_id' => '',
            'name' => '',
            'ddn' => '',
            'telefono' => '',
            'email' => ['sometimes', 'nullable', 'email'],
            'direccion' => '',
            'comentarios' => ''
        ], [
            'fecha_cita.date' => 'La fecha de la cita debe ser una fecha valida.',
            'hora_cita.date_format' => 'La hora de la cita debe ser una hora valida.',
            'descripcion.required' => 'La descripcion es requerida.',
            'email.email' => 'Debe suministrar un correo elctrónico válido.',
        ]);
        //dd($data);
        if (!(is_null($data['ddn'])) and ('' != $data['ddn']) and
            !(is_null($data['telefono'])) and ('' != $data['telefono'])) {
            $data['telefono'] = $data['ddn'] . $data['telefono'];
        } else {
            $data['telefono'] = null;
        }
        unset($data['ddn']);

        $extra = '';
        if ('0' == $data['contacto_id']) $data['contacto_id'] = null;
        else {
            $contacto = Contacto::findOrFail($data['contacto_id']); // Si falla produce 'ModelNotFoundException'.
            if ($contacto->telefono) {
                if (is_null($data['telefono'])) $data['telefono'] = $contacto->telefono;
                elseif ($data['telefono'] != $contacto->telefono)
                    $extra .= 'Telefono registrado:' . $contacto->telefono . '. ';
            }
            if ($contacto->otro_telefono) {
                $extra .= 'Otro telefono registrado:' . $contacto->otro_telefono . '. ';
            }
            if ($contacto->email) {
                if (is_null($data['email']) or ('' == trim($data['email'])))
                    $data['email'] = $contacto->email;
                elseif ($data['email'] != $contacto->email)
                    $extra .= 'Email registrado:' . $contacto->email . '. ';
            }
            if ($contacto->direccion) {
                if (is_null($data['direccion']) or ('' == trim($data['direccion'])))
                    $data['direccion'] = $contacto->direccion;
                elseif ($data['direccion'] != $contacto->direccion)
                    $extra .= 'Direccion registrada:' . $contacto->direccion . '. ';
            }
            $data['comentarios'] = $extra . trim($data['comentarios']??'');
        }

        $extra = '';
        if ('0' == $data['cliente_id']) $data['cliente_id'] = null;
        else {
            $cliente = Cliente::findOrFail($data['cliente_id']);    // Si falla produce 'ModelNotFoundException'.
            if ($cliente->telefono) {
                if (is_null($data['telefono'])) $data['telefono'] = $cliente->telefono;
                elseif ($data['telefono'] != $cliente->telefono)
                    $extra .= 'Telefono registrado:' . $cliente->telefono . '. ';
            }
            if ($cliente->otro_telefono) {
                $extra .= 'Otro telefono registrado:' . $cliente->otro_telefono . '. ';
            }
            if ($cliente->email) {
                if (is_null($data['email']) or ('' == trim($data['email'])))
                    $data['email'] = $cliente->email;
                elseif ($data['email'] != $cliente->email)
                    $extra .= 'Email registrado:' . $cliente->email . '. ';
            }
            if ($cliente->direccion) {
                if (is_null($data['direccion']) or ('' == trim($data['direccion'])))
                    $data['direccion'] = $cliente->direccion;
                elseif ($data['direccion'] != $cliente->direccion)
                    $extra .= 'Direccion registrada:' . $cliente->direccion . '. ';
            }
            $data['comentarios'] = $extra . trim($data['comentarios']??'');
        }
        if (500 < strlen($data['comentarios']))
            $data['comentarios'] = substr($data['comentarios'], 0, 500);

        $cita = AgendaPersonal::create([        // Devuelve el modelo.
            'user_id'     => Auth::user()->id,
            'fecha_cita'  => $data['fecha_cita'],
            'hora_cita'   => $data['hora_cita'],
            'descripcion' => $data['descripcion'],
            'contacto_id' => $data['contacto_id'],
            'cliente_id' => $data['cliente_id'],
            'name'        => $data['name'],
            'telefono'    => $data['telefono'],
            'email'       => $data['email'],
            'direccion'   => $data['direccion'],
            'fecha_evento' => $data['fecha_cita'],
            'hora_evento' => $data['hora_cita'],
            'comentarios' => $data['comentarios'],
        ]);

//        $cita = AgendaPersonal::find($id);
        session(['exito' => "La cita  del '" . $data['fecha_cita'] .
                        ' ' . $data['hora_cita'] . "' fue agregada con exito."]);
        return redirect()->route('agendaPersonal.crear');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function edit(AgendaPersonal $cita)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        if ((!Auth::user()->is_admin) and ($cita->id != Auth::user()->id)) {
            return redirect('/agenda');
        }

        $rutaPrevia = url()->previous();
        if (((False === strpos($rutaPrevia, 'agenda/')) and
            (False === strpos($rutaPrevia, 'agenda?page')) and
            (False === strpos($rutaPrevia, 'agenda/orden'))) or
            (1 === preg_match('@agenda/([0-9]+)/@', $rutaPrevia)))
            $rutaPrevia = null;

        $title = 'Editar ' . $this->tipo;
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        if (Auth::user()->is_admin) {
            $contactos = Contacto::all();
            $clientes  = Cliente::all();
        } else {
            $contactos = Contacto::where('user_id', Auth::user()->id)->get();
            $clientes  = Cliente::where('user_id', Auth::user()->id)->get();
        }
        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();
        return view('agendaPersonal.editar',
                    compact('title', 'cita', 'contactos', 'clientes', 'ddns', 'rutaPrevia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AgendaPersonal $cita)
    {
        //dd($request, $cita);
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'fecha_cita' => ['date'],
            'hora_cita'  => ['date_format:H:i:s'],
            'descripcion' => 'required',
            'contacto_id' => '',
            'cliente_id' => '',
            'name' => '',
            'ddn' => '',
            'telefono' => '',
            'email' => ['sometimes', 'nullable', 'email'],
            'direccion' => '',
            'fecha_evento' => ['date'],
            'hora_evento'  => ['date_format:H:i:s'],
            'comentarios' => ''
        ], [
            'fecha_cita.date' => 'La fecha de la cita debe ser una fecha valida.',
            'hora_cita.date_format' => 'La hora de la cita debe ser una hora valida.',
            'descripcion.required' => 'La descripcion es requerida.',
            'email.email' => 'Debe suministrar un correo elctrónico válido.',
            'fecha_evento.date' => 'La fecha del evento debe ser una fecha valida.',
            'hora_evento.date_format' => 'La hora del evento debe ser una hora valida.',
        ]);
        if (!(is_null($data['ddn'])) and '' != $data['ddn'] and
            !(is_null($data['telefono'])) and '' != $data['telefono']) {
            $data['telefono'] = $data['ddn'] . $data['telefono'];
        } else {
            $data['telefono'] = null;
        }
        unset($data['ddn']);

        $extra = '';
        if ('0' == $data['contacto_id']) $data['contacto_id'] = null;
        else {
            $contacto = Contacto::findOrFail($data['contacto_id']); // Si falla produce 'ModelNotFoundException'.
            if ($contacto->telefono) {
                if (is_null($data['telefono'])) $data['telefono'] = $contacto->telefono;
                elseif ($data['telefono'] != $contacto->telefono)
                    $extra .= 'Telefono registrado:' . $contacto->telefono . '. ';
            }
            if ($contacto->otro_telefono) {
                $extra .= 'Otro telefono registrado:' . $contacto->otro_telefono . '. ';
            }
            if ($contacto->email) {
                if (is_null($data['email']) or ('' == trim($data['email'])))
                    $data['email'] = $contacto->email;
                elseif ($data['email'] != $contacto->email)
                    $extra .= 'Email registrado:' . $contacto->email . '. ';
            }
            if ($contacto->direccion) {
                if (is_null($data['direccion']) or ('' == trim($data['direccion'])))
                    $data['direccion'] = $contacto->direccion;
                elseif ($data['direccion'] != $contacto->direccion)
                    $extra .= 'Direccion registrada:' . $contacto->direccion . '. ';
            }
            $data['comentarios'] = $extra . trim($data['comentarios']??'');
        }

        $extra = '';
        if ('0' == $data['cliente_id']) $data['cliente_id'] = null;
        else {
            $cliente = Cliente::findOrFail($data['cliente_id']);    // Si falla produce 'ModelNotFoundException'.
            if ($cliente->telefono) {
                if (is_null($data['telefono'])) $data['telefono'] = $cliente->telefono;
                elseif ($data['telefono'] != $cliente->telefono)
                    $extra .= 'Telefono registrado:' . $cliente->telefono . '. ';
            }
            if ($cliente->otro_telefono) {
                $extra .= 'Otro telefono registrado:' . $cliente->otro_telefono . '. ';
            }
            if ($cliente->email) {
                if (is_null($data['email']) or ('' == trim($data['email'])))
                    $data['email'] = $cliente->email;
                elseif ($data['email'] != $cliente->email)
                    $extra .= 'Email registrado:' . $cliente->email . '. ';
            }
            if ($cliente->direccion) {
                if (is_null($data['direccion']) or ('' == trim($data['direccion'])))
                    $data['direccion'] = $cliente->direccion;
                elseif ($data['direccion'] != $cliente->direccion)
                    $extra .= 'Direccion registrada:' . $cliente->direccion . '. ';
            }
            $data['comentarios'] = $extra . trim($data['comentarios']??'');
        }
        if (500 < strlen($data['comentarios']))
            $data['comentarios'] = substr($data['comentarios'], 0, 500);

        //$cita = AgendaPersonal::where('id', $id)->get()[0];   // No se necesita, si el parametro de la funcion, arriba, es el mismo del routes/web.php. En todo caso deberia usar findOrFail.
        //dd(gettype($id), $id, (int)$id, $cita, $data);
        $cita->update($data);
        //dd('despues de update', $data, $cita);

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'AgendaPersonal',
            'tx_data' => implode(';', $data),
            'tx_tipo' => 'A',
	    'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);
        //dd('despues de create bitacora', $data, $cita);

        return view('agendaPersonal.show',
                                    ['cita' => $cita, 'rutaPrevia' => null]);
    }

    public function correoCita(AgendaPersonal $cita, $ruta=1)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
/* Permite que el asesor se envie correo.        
        if (!Auth::user()->is_admin) {
            return redirect()->back();
        }
 */
        $host = env('MAIL_HOST');
        if (!($ip = gethostbyname($host)) or ($ip == $host)) { // No hay conexon a Internet.
            $correo = 'N';
            //dd($cita, $ruta, $ip);
            if (1 == $ruta)
                return redirect()->route('agenda', ['correo' => $correo]);
            elseif (2 == $ruta)
                return redirect()->route('agenda.show',
                                ['cita' => $cita, 'correo' => $correo]);
            else return $correo;
        }

        //return new CitaAsesor('AgendaPersonal', $cita->id);   // Vista preliminar del correo, en el navegador.
        Mail::to($cita->user->email, $cita->user->name)
                ->send(new CitaAsesor('AgendaPersonal', $cita->id));
        return redirect()->route('agenda', ['correo' => 's']);
    } // public function correoCita(Contacto $contacto)
}