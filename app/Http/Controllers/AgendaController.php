<?php

namespace App\Http\Controllers;

use App\Agenda;
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
    protected $tipo = 'Agenda ';

    public function index($orden = null) {
        if (!(Auth::check())) {             // No esta conectado.
            return redirect('login');
        }
// Variables propias del metodo del controlador.
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
            $user_id = Auth::user()->id;    // id del usuario (asesor) conectado.
            $asesorConectado = User::find($user_id);    // Consigue la clase App\User del asesor conectado.
            $title  .= 'de ' . $asesorConectado->name;  // Titulo de la página de la Agenda.
            $agendas = Agenda::where('user_id', $user_id)   // Solo el asesor conectado.
                    ->select('fecha_evento', 'hora_evento', 'descripcion', 'name', 'telefono',
                                'email', 'direccion');           // Solo estas columnas.
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

    public function indice($orden = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $title = $this->tipo;
        $ruta = request()->path();
        $diaSemana = $this->diaSemana;

        if ('' == $orden or $orden == null) {
            $orden = 'id';
        }
        if (1 == Auth::user()->is_admin) {
            $turnos = Turno::where('turno_en','>=', 'now')
                            ->orderBy($orden)->paginate(10);
        } else {
            $user   = User::find(Auth::user()->id);
            $turnos = $user->turnos()
                        ->where('turno_en','>=', 'now')
                        ->orderBy($orden)->paginate(10);
            $title .= ' de ' . $user->name;
        }
    
        return view('agenda.index', compact('title', 'turnos', 'ruta', 'diaSemana'));
    }

    public function show(Contacto $contacto)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        $diaSemana = $this->diaSemana;

        if (1 == Auth::user()->is_admin) {
            return view('agenda.show', compact('contacto', 'diaSemana'));
        }
        if ($contacto->user->id == Auth::user()->id) {
            return view('agenda.show', compact('contacto', 'diaSemana'));
        } else {
            return redirect('/agenda');
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
    }
}