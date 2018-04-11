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
            $asesor      = session('asesor', '');
        } else {
            $rPeriodo = $periodo['periodo'];
            if (isset($periodo['asesor'])) $asesor = $periodo['asesor'];
            else $asesor = 0;

            if ('hoy' == $rPeriodo) {
                $fecha_desde = Carbon::today()->startOfDay();
                $fecha_hasta = Carbon::today()->endOfDay();
            }
            if ('ayer' == $rPeriodo) {
                $fecha_desde = Carbon::yesterday()->startOfDay();
                $fecha_hasta = Carbon::yesterday()->endOfDay();
            }
            if ('manana' == $rPeriodo) {
                $fecha_desde = Carbon::tomorrow()->startOfDay();
                $fecha_hasta = Carbon::tomorrow()->endOfDay();
            }
// Aqui debo revisar, si hoy es lunes va a dar la semana pasada.
            if ('esta_semana' == $rPeriodo) {
                if (1 != now()->dayOfWeek) {
                    $fecha_desde = (new Carbon('previous monday'))->startOfDay();
                    $fecha_hasta = (new Carbon('previous monday'))->addDays(6)->endOfDay(); // Domingo
                } else {
                    $fecha_desde = (new Carbon())->startOfDay();
                    $fecha_hasta = (new Carbon())->addDays(6)->endOfDay(); // Domingo
                }
            }
// Igual, aqui debo revisar, si hoy es lunes va a dar la semana antepasada.
            if ('semana_pasada' == $rPeriodo) {
                $fecha_desde = (new Carbon('previous monday'))->startOfDay();
                $fecha_hasta = (new Carbon('previous monday'))->addDays(6)->endOfDay();
                if (1 != now()->dayOfWeek) {
                    $fecha_desde = $fecha_desde->addWeeks(-1);
                    $fecha_hasta = $fecha_hasta->addWeeks(-1);
                }
            }
            if ('proxima_semana' == $rPeriodo) {
                $fecha_desde = (new Carbon('next monday'))->startOfDay();
                $fecha_hasta = (new Carbon('next monday'))->addDays(6)->endOfDay();
            }
            if ('este_mes' == $rPeriodo) {
                $fecha_desde = Carbon::now()->startOfMonth()->startOfDay();
                $fecha_hasta = (new Carbon('last day of this month'))->endOfDay();
            }
            if ('mes_pasado' == $rPeriodo) {
                $fecha_desde = (new Carbon('first day of last month'))->startOfDay();
                $fecha_hasta = (new Carbon('last day of last month'))->endOfDay();
            }
            if ('proximo_mes' == $rPeriodo) {
                $fecha_desde = (new Carbon('first day of next month'))->startOfDay();
                $fecha_hasta = (new Carbon('last day of next month'))->endOfDay();
            }
            if ('todo' == $rPeriodo) {
                $fecha_desde = '';
                $fecha_hasta = '';
            }
            if ('intervalo' == $rPeriodo) {
                $fecha_desde = (new Carbon($periodo['fecha_desde']))->startOfDay();
                $fecha_hasta = (new Carbon($periodo['fecha_hasta']))->endOfDay();
            }
            if ('' != $fecha_desde and '' != $fecha_hasta) {
                $fecha_desde = $fecha_desde->format('Y-m-d H:m:s');
                $fecha_hasta = $fecha_hasta->format('Y-m-d H:m:s');
            }
        }
        //dd($periodo, $fecha_desde, $fecha_hasta);

        if ('' == $orden or $orden == null) {
            $orden = 'fecha_evento';
        }

        if (Auth::user()->is_admin) {       // El usuario (asesor) es un administrador.
            $users   = User::all();         // Todos los usuarios.
            $agendas = Agenda::select('fecha_evento', 'hora_evento', 'descripcion', 'name', 'telefono', 
                                        'user_id', 'email', 'direccion');   // Solo estas columnas.
            if ('name' == $orden) {         // Si se ordena por nombre, no muestra turnos (name == '').
                $agendas = $agendas->where('name', '!=', '');
            }
        } else {                            // El usuario (asesor) no es un administrador.
            $user_id = Auth::user()->id;    // id del usuario (asesor) conectado.
            $asesorConectado = User::find($user_id);    // Consigue la clase App\User del asesor conectado.
            $title  .= 'de ' . $asesorConectado->name;  // Titulo de la página de la Agenda.
            $agendas = Agenda::where('user_id', $user_id)   // Solo el asesor conectado.
                    ->select('fecha_evento', 'hora_evento', 'descripcion', 'name', 'telefono', 'email', 
                                'direccion');           // Solo estas columnas.
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
}
