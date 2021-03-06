<?php

namespace App\Http\Controllers;

use App\Agenda;
use App\Cita;
use App\Turno;
use App\Contacto;
use App\User;
use App\Bitacora;
use \App\Mail\CitaAsesor;
use \App\Mail\CitasAsesor;
use \App\Mail\TurnosAsesor;
use \App\Mail\Cumpleano;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\MisClases\Fecha;
use Jenssegers\Agent\Agent;                 // PC
use App\MisClases\General;               // PC

class AgendaController extends Controller
{
    protected $tipo = 'cita ';
    protected $lineasXPagina = 20/*General::LINEASXPAGINA*/;

    public function index($orden=null, $accion='html') {
        if (!(Auth::check())) {             // No esta conectado.
            return redirect('login');
        }
// Variables propias de la metodo de la controlador.
        $title = $this->tipo;
        $ruta = request()->path();
        $periodo = request()->all();
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
//        dd($periodo);
// Todo se inicializa, cuando se selecciona 'Agenda' desde el menú horizontal principal.
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
            $fecha_min = (new Carbon(Agenda::min('fecha_evento')));
            $fecha_max = (new Carbon(Agenda::max('fecha_evento')));
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
// En caso de volver luego de haber enviado un correo, ver el metodo 'correoCita', en Agenda(Personal)Controller.
        $alertar = 0;
        if (isset($_GET['correo']) and ($correo = $_GET['correo'])) {
            if ('s' == $correo) {
                $alertar = 2;
            } elseif ('N' == $correo) {
                $alertar = -1;
            }
        }
        if ('' == $orden or $orden == null) {
            $orden = 'fecha_evento';
        }

        if (Auth::user()->is_admin) {       // El usuario (asesor) es un administrador.
//            $users   = User::all();         // Todos los usuarios. Incluye '1' porque en turnos hay feriado.
            $users   = User::where('activo', True)->get();
            $users[0]['name'] = 'Administrador';
            $agendas = Agenda::select('fecha_evento', 'hora_evento', 'descripcion', 'name',
                                        'telefono', 'user_id', 'contacto_id', 'email',
                                        'tipo', 'direccion');   // Solo estas columnas.
            $agendas = $agendas->where('tipo', '!=', 'T');  // Elimina los turnos de la agenda para el administrador.
            if ('name' == $orden) {         // Si se ordena por nombre, no muestra turnos (name == '').
                $agendas = $agendas->where('name', '!=', '');
            }
        } else {                            // El usuario (asesor) no es un administrador.
            $asesor = Auth::user()->id;    // id de la usuario (asesor) conectado.
            $asesorConectado = User::find($asesor);    // Consigue la clase App\User de la asesor conectado.
            $title  .= 'de ' . $asesorConectado->name;  // Titulo de la página de la Agenda.
            $agendas = Agenda::where('user_id', $asesor)   // Solo el asesor conectado.
                    ->select('fecha_evento', 'hora_evento', 'descripcion', 'name',
                            'telefono', 'tipo', 'contacto_id', 'email', 'direccion');   // Solo estas columnas.
        }

        if ((Auth::user()->is_admin) and (0 < $asesor)) {   // Se selecciono un asesor y esta conectado administrador.
            $agendas = $agendas->where('user_id', $asesor); // Esta condicion esta arriba para un asesor.
        }
        $hoy = Fecha::hoy();
// Devolver las fechas sin la hora. Los diez primeros caracteres son: yyyy-mm-dd.
        if ('' != $fecha_desde and '' != $fecha_hasta) {    // No se seleccionaron fechas.
            $fecha_desde = substr($fecha_desde, 0, 10);
            $fecha_hasta = substr($fecha_hasta, 0, 10);
            $agendas = $agendas->whereBetween('fecha_evento', [$fecha_desde, $fecha_hasta]);
        } else {
            $agendas = $agendas->where('fecha_evento', '>=', $hoy);
        }
        $agendas = $agendas->orderBy($orden);   // Ordenar los items de la agenda.
        if ('user_id' == $orden) {              // Si se pidió ordenar por id de usuario,
            $agendas = $agendas->orderBy('fecha_evento');   // ordenar por fecha_evento en cada usuario.
        }
        if ($movil or ('html' != $accion)) $agendas = $agendas->get();
        else $agendas = $agendas->paginate($this->lineasXPagina);               // Pagina la impresión de 10 en 10
        session(['rPeriodo' => $rPeriodo, 'fecha_desde' => $fecha_desde,    // Asignar valores en sesión.
                    'fecha_hasta' => $fecha_hasta, 'asesor' => $asesor]);
        //if ('este_mes' == $rPeriodo) dd($agendas, $users, $fecha_desde, $fecha_hasta, $asesor);
        if ('html' == $accion)
            return view('agenda.index', compact('title', 'users', 'ruta', 'agendas',
                        'rPeriodo', 'fecha_desde', 'fecha_hasta', 'asesor',
                        'alertar', 'orden', 'movil', 'accion', 'hoy'));
        $html = view('agenda.index', compact('title', 'users', 'ruta', 'agendas',
                        'rPeriodo', 'fecha_desde', 'fecha_hasta', 'asesor',
                        'alertar', 'orden', 'movil', 'accion'))
                ->render();
        General::generarPdf($html, 'agendas', $accion);
    }

    public function show(Contacto $contacto)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        if ((!Auth::user()->is_admin) and ($contacto->user_id != Auth::user()->id)) {
            return redirect('/agenda');
        }

        $rutaPrevia = url()->previous();        // "http://c21pr.vb/agenda/90/editar"
        if (((False === strpos($rutaPrevia, 'agenda/')) and
            (False === strpos($rutaPrevia, 'agenda?page')) and
            (False === strpos($rutaPrevia, 'agenda/orden'))) or
            (1 === preg_match('@agenda/([0-9]+)/@', $rutaPrevia)))
            $rutaPrevia = null;

        //dd($rutaPrevia);
        $cita = Cita::where('contacto_id', $contacto->id);
        if (0 >= $cita->count()) {
            $cita->contacto = $contacto;
            $cita->fecha_cita = NULL;
            $cita->comentarios = NULL;
        } else {
            $cita=$cita->firstOrFail(); // Primera fila devuelta de 'Cita' del $contacto->id.
        }
        //dd($cita);
        return view('agenda.show', compact('cita', 'rutaPrevia'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Contacto $contacto)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $cita = Cita::where('contacto_id', $contacto->id)->get();
        if (0 < $cita->count()) {
            return redirect()->route('agenda.edit', ['contacto' => $contacto]);
        }

        $title = 'Crear ' . $this->tipo;

        return view('agenda.crear', compact('title', 'contacto'));
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
        $data = $request->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'fecha_cita' => ['date'],
            'hora_cita'  => ['date_format:H:i'],
            'comentarios' => '',
            'contacto_id' => '',
        ], [
            'fecha_cita.date' => 'La fecha de la cita debe ser una fecha valida.',
            'hora_cita.date' => 'La hora de la cita debe ser una hora valida.',
        ]);
        //dd($data);
        //$contacto = $data['contacto'];
/*        if (!isset($data['fecha_cita'])) {    // Esto fue un invento, al parecer, muy malo.
            return redirect()->route('agenda.post');
        }*/
        $fecha_cita = Carbon::createFromFormat('Y-m-d H:i', $data['fecha_cita'] . ' ' .
                                                            $data['hora_cita']);
        Cita::create([
            'contacto_id' => $data['contacto_id'],
            'fecha_cita'  => $fecha_cita,
            'comentarios' => $data['comentarios']
        ]);

        $contacto = Contacto::find($data['contacto_id']);
        return redirect()->route('agenda.show', compact('contacto'));
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

        $cita = Cita::where('contacto_id', $contacto->id)->get();   // Devuelve un arreglo de 1 item.
        if (0 >= $cita->count()) {
            return redirect()->route('agenda.crear', ['contacto' => $contacto]);
        } else {
//            $id  = $cita->all()[0]->id;
// Solo accedemos al primer item del arreglo '$cita', ver arriba.
            $id  = $cita[0]->id;
            $fecha_cita  = $cita[0]->fecha_cita;
            $comentarios = $cita[0]->comentarios;
        }

        $title = 'Editar ' . $this->tipo;

        $rutaPrevia = url()->previous();
        if (((False === strpos($rutaPrevia, 'agenda/')) and
            (False === strpos($rutaPrevia, 'agenda?page')) and
            (False === strpos($rutaPrevia, 'agenda/orden'))) or
            (1 === preg_match('@agenda/([0-9]+)/@', $rutaPrevia)))
            $rutaPrevia = null;

        return view('agenda.editar', compact('title', 'contacto',
                'id', 'fecha_cita', 'comentarios', 'rutaPrevia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cita $cita)
    {
        $data = $request->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'fecha_cita' => ['date'],
            'hora_cita'  => ['date_format:H:i'],
            'comentarios' => '',
        ], [
            'fecha_cita.date' => 'La fecha de la cita debe ser una fecha valida.',
            'hora_cita.date_format' => 'La hora de la cita debe ser una hora valida.',
        ]);
        //dd($cita, $data);

        $data['fecha_cita'] = Carbon::createFromFormat('Y-m-d H:i', $data['fecha_cita']
                                                    . ' ' . $data['hora_cita']);
        $cita->update($data);

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Cita',
            'tx_data' => implode(';', $data),
            'tx_tipo' => 'A',
	    'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        $contacto = $cita->contacto;
        return redirect()->route('agenda.show', ['contacto' => $contacto]);
    } // public function update(Request $request, Cita $cita)

    public function correoCita(Contacto $contacto, $ruta=1) // No puede usar el modelo 'Agenda' porque no esta atado a una 'tabla' de la BD.
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
            if (1 == $ruta)
                return redirect()->route('agenda', ['correo' => $correo]);
            elseif (2 == $ruta)
                return redirect()->route('agenda.show',
                                ['contacto' => $contacto, 'correo' => $correo]);
            else return $correo;
        }

        //return new CitaAsesor('Contacto', $contacto->id);   // Vista preliminar del correo, en el navegador.
        Mail::to($contacto->user->email, $contacto->user->name)
                ->send(new CitaAsesor('Contacto', $contacto->id));
        return redirect()->route('agenda', ['correo' => 's']);
    } // public function correoCita(Contacto $contacto)

    public function correoCitas(User $user) // Todas las citas de un asesor.
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!Auth::user()->is_admin) {
            return redirect()->back();
        }

        $host = env('MAIL_HOST');
        if (!($ip = gethostbyname($host)) or ($ip == $host)) { // No hay conexon a Internet.
            $correo = 'N';
            return redirect()->route('users', ['correo' => $correo]);
        }

        //return new CitasAsesor($user);  // Vista preliminar del correo, en el navegador.
        Mail::to($user->email, $user->name)
                ->send(new CitasAsesor($user));
        return redirect()->route('users', ['correo' => 's']);
    } // public function correoCitas(User $user)

    public static function correoTodasCitas($desde=null, $hasta=null, $comando=null) // Todas las citas de todos los asesores.
    {
        if (is_null($comando)) {
            if (!(Auth::check())) {
                return redirect('login');
            }
            if (!Auth::user()->is_admin) {
                return redirect()->back();
            }
        }

        $host = env('MAIL_HOST');
        $correo = 'N';
        if (!($ip = gethostbyname($host)) or ($ip == $host)) { // No hay conexon a Internet.
            if (is_null($comando)) return redirect()->route('agenda', ['correo' => $correo]);
            return $correo;
        }

        $correo = 'X';
        $desde = $desde??Fecha::hoy()->format('Y-m-d');  // La BdD funciona con 'YYYY-mm-dd'.
        //return new CitasAsesor(User::find(1));  // Vista preliminar del correo, en el navegador.
        $usersIds = Agenda::select(DB::raw("distinct user_id"));
        if ($hasta) $usersIds = $usersIds->whereBetween('fecha_evento', [$desde, $hasta]);
        else $usersIds = $usersIds->where('fecha_evento', '>=', $desde);
        $usersIds = $usersIds->get();
        if ($usersIds->isEmpty()) {
            if ('C' == $comando) return $correo;
            else return redirect()->route('agenda', ['correo' => $correo]);
        }
        //dd($usersIds);
        foreach ($usersIds as $userId) {
            $user = User::findOrFail($userId)->first();
            //dd($user);
            if ($user->citas($desde, $hasta)->isNotEmpty()) {
                Mail::to($user->email, $user->name)
                        ->send(new CitasAsesor($user, $desde, $hasta));
                $correo = 's';
            }
        }
        if ('C' == $comando) return $correo;
        else return redirect()->route('agenda', ['correo' => $correo]);
    } // public function correoTodasCitas($tipo='todas')

    public function cumpleano(User $user)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!Auth::user()->is_admin) {
            return redirect()->back();
        }

        //return new Cumpleano($user);  // Vista preliminar del correo, en el navegador.
        Mail::to($user->email, $user->name)
                ->send(new Cumpleano($user));
        return redirect()->route('reportes', 'Cumpleanos');
    } // public function cumpleano(User $user)
}
