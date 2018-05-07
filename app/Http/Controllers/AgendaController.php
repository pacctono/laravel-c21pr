<?php

namespace App\Http\Controllers;

use App\Agenda;
use App\Cita;
use App\Turno;
use App\Contacto;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\MisClases\Fecha;

class AgendaController extends Controller
{
    protected $diaSemana = [
        'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'
    ];
    protected $tipo = 'cita ';

    public function index($orden = null) {
        if (!(Auth::check())) {             // No esta conectado.
            return redirect('login');
        }
// Variables propias de la metodo de la controlador.
        $title = $this->tipo;
        $ruta = request()->path();
        $diaSemana = $this->diaSemana;
        $periodo = request()->all();
// Todo se inicia, cuando se selecciona 'Agenda' desde el menú horizontal.
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
        } else {
            $rPeriodo = $periodo['periodo'];
            if (isset($periodo['asesor'])) $asesor = $periodo['asesor'];
            else $asesor = 0;

            list ($fecha_desde, $fecha_hasta) = Fecha::periodo($periodo);

/*            if ('' != $fecha_desde and '' != $fecha_hasta) {
                $fecha_desde = $fecha_desde->format('Y-m-d H:m:s');
                $fecha_hasta = $fecha_hasta->format('Y-m-d H:m:s');        {{ $diaSemana[$turno->turno_en->dayOfWeek] }}
        {{ $turno->turno_en->format('d/m/Y') }}

            }*/
        }
        //dd($periodo, $fecha_desde, $fecha_hasta);
        if ('' == $orden or $orden == null) {
            $orden = 'fecha_evento';
        }

        if (Auth::user()->is_admin) {       // El usuario (asesor) es un administrador.
            $users   = User::all();         // Todos los usuarios.
            $agendas = Agenda::select('fecha_evento', 'hora_evento', 'descripcion', 'name',
                                        'telefono', 'user_id', 'contacto_id', 'email',
                                        'direccion');   // Solo estas columnas.
            if ('name' == $orden) {         // Si se ordena por nombre, no muestra turnos (name == '').
                $agendas = $agendas->where('name', '!=', '');
            }
        } else {                            // El usuario (asesor) no es un administrador.
            $user_id = Auth::user()->id;    // id de la usuario (asesor) conectado.
            $asesorConectado = User::find($user_id);    // Consigue la clase App\User de la asesor conectado.
            $title  .= 'de ' . $asesorConectado->name;  // Titulo de la página de la Agenda.
            $agendas = Agenda::where('user_id', $user_id)   // Solo el asesor conectado.
                    ->select('fecha_evento', 'hora_evento', 'descripcion', 'name', 'telefono',
                                'contacto_id', 'email', 'direccion');           // Solo estas columnas.
        }

        if (0 < $asesor) {                  // No se selecciono un asesor o el conectado no es administrador.
            $agendas = $agendas->where('user_id', $asesor);
        }
        if ('' != $fecha_desde and '' != $fecha_hasta) {    // No se seleccionaron fechas.
            $agendas = $agendas->whereBetween('fecha_evento', [$fecha_desde, $fecha_hasta]);
        }
        $agendas = $agendas->orderBy($orden);   // Ordenar los items de la agenda.
        if ('user_id' == $orden) {              // Si se pidió ordenar por id de usuario, ordenar por fecha_evento dentro de cada usuario.
            $agendas = $agendas->orderBy('fecha_evento');
        }
        $agendas = $agendas->paginate(10);      // Pagina la impresión de 10 en 10
// Devolver las fechas sin la hora. Los diez primeros caracteres son: yyyy-mm-dd.
        $fecha_desde = substr($fecha_desde, 0, 10);
        $fecha_hasta = substr($fecha_hasta, 0, 10);
        session(['rPeriodo' => $rPeriodo, 'fecha_desde' => $fecha_desde,    // Asignar valores en sesión.
                    'fecha_hasta' => $fecha_hasta, 'asesor' => $asesor]);
        return view('agenda.index', compact('title', 'users', 'ruta', 'diaSemana', 'agendas',
                    'rPeriodo', 'fecha_desde', 'fecha_hasta', 'asesor'));
    }

    public function show(Contacto $contacto)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        $diaSemana = $this->diaSemana;

        if ((!Auth::user()->is_admin) and ($contacto->user->id != Auth::user()->id)) {
            return redirect('/agenda');
        }

        $cita = Cita::where('contacto_id', $contacto->id)->get();
        if (0 >= $cita->count()) {
            $cita->contacto = $contacto;
            $cita->fecha_cita = NULL;
            $cita->comentarios = NULL;
        } else {
            $cita=$cita->all()[0];
        }
        //dd($cita);
        return view('agenda.show', compact('cita', 'diaSemana'));
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
        $diaSemana = $this->diaSemana;

        return view('agenda.crear', compact('title', 'contacto', 'diaSemana'));
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
            'comentarios' => '',
            'contacto_id' => '',
        ], [
            'fecha_cita.date' => 'La fecha de la cita debe ser una fecha valida.',
            'hora_cita.date' => 'La hora de la cita debe ser una hora valida.',
        ]);
        //dd($data);
        //$contacto = $data['contacto'];
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

        $cita = Cita::where('contacto_id', $contacto->id)->get();
        if (0 >= $cita->count()) {
            return redirect()->route('agenda.crear', ['contacto' => $contacto]);
        } else {
            $id  = $cita->all()[0]->id;
            $fecha_cita  = $cita->all()[0]->fecha_cita;
            $comentarios = $cita->all()[0]->comentarios;
        }

        $title = 'Editar ' . $this->tipo;
        $diaSemana = $this->diaSemana;

        return view('agenda.editar', compact('title', 'contacto',
                'id', 'fecha_cita', 'comentarios', 'diaSemana'));
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
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'fecha_cita' => ['date'],
            'hora_cita'  => ['date_format:H:i'],
            'comentarios' => '',
        ], [
            'fecha_cita.date' => 'La fecha de la cita debe ser una fecha valida.',
            'hora_cita.date' => 'La hora de la cita debe ser una hora valida.',
        ]);
        //dd($data);

        $data['fecha_cita'] = Carbon::createFromFormat('Y-m-d H:i', $data['fecha_cita']
                                                    . ' ' . $data['hora_cita']);
        $cita->update($data);

        $contacto = $cita->contacto;
        return redirect()->route('agenda.show', ['contacto' => $contacto]);
    }
}