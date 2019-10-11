<?php

namespace App\Http\Controllers;

use App\Turno;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;                 // PC
use App\MisClases\Fecha;
use App\MisClases\General;               // PC

class TurnoController extends Controller
{
    protected $tipo = 'Turnos';
    protected $lineasXPagina = General::LINEASXPAGINA;

    public function index($orden = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $title = 'Listado de ' . $this->tipo;
        $ruta = request()->path();
        $periodo = request()->all();
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
//        dd($periodo);
// Todo se inicializa, cuando se selecciona 'turnos' desde el menú horizontal.
        if (('GET' == request()->method()) and ('' == $orden) and (0 == count($periodo))) {
            session(['rPeriodo' => '', 'fecha_desde' => '', 'fecha_hasta' => '',
                        'asesor' => '0']);
        }
        $diaSemana = Fecha::$diaSemana;

        for ($d = 0; $d < 11; $d++) {
            $semanas[$d] = Fecha::primerLunesDePrimeraSemana()
                                    ->addWeeks($d);            // Proximos once lunes.
        }
//        dd($semanas);
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
            $fecha_min = (new Carbon(Turno::min('turno')));
            $fecha_max = (new Carbon(Turno::max('turno')));
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
//        dd($periodo, $fecha_desde, $fecha_hasta);
// En caso de volver luego de haber enviado un correo, ver el metodo 'emailcita', en AgendaController.
        $alertar = 0;
        if ('alert' == $orden) {
            $orden = '';
            $alertar = 1;
        }
        if ('' == $orden or is_null($orden)) {
            $orden = 'turno';
        }
        if (Auth::user()->is_admin) {
            $users   = User::all();         // Todos los usuarios. Incluye '1' porque en turnos hay feriado.
            $turnos = Turno::where('id', '>', 0);   // condición dummy, solo para continuar armando la consulta.
        } else {
            $user   = User::find(Auth::user()->id);
            $title .= ' de ' . $user->name;
            $turnos = $user->turnos();
        }
    
        if (0 < $asesor) {      // Se selecciono un asesor o el conectado no es administrador.
            $turnos = $turnos->where('user_id', $asesor);
        }
        if ('' != $fecha_desde and '' != $fecha_hasta) {    // No se seleccionaron fechas.
            $fecha_desde = substr($fecha_desde, 0, 10);
            $fecha_hasta = substr($fecha_hasta, 0, 10);
            $turnos = $turnos->whereBetween('turno', [$fecha_desde, $fecha_hasta]);
        } else {
            $turnos = $turnos->where('turno', '>=', now(Fecha::$ZONA));
        }
        $turnos = $turnos->orderBy($orden);   // Ordenar los items de los turnos.
        if ('user_id' == $orden) {              // Si se pidió ordenar por id de usuario,
            $turnos = $turnos->orderBy('turno');   // ordenar por turno en cada usuario.
        }
        if ($movil) $turnos = $turnos->get();
        else $turnos = $turnos->paginate($this->lineasXPagina);               // Pagina la impresión de 10 en 10
// Devolver las fechas sin la hora. Los diez primeros caracteres son: yyyy-mm-dd.
        session(['rPeriodo' => $rPeriodo, 'fecha_desde' => $fecha_desde,    // Asignar valores en sesión.
                    'fecha_hasta' => $fecha_hasta, 'asesor' => $asesor]);
        return view('turnos.index', compact('title', 'users', 'turnos', 'ruta', 'diaSemana',
                'semanas', 'rPeriodo', 'fecha_desde', 'fecha_hasta', 'asesor', 'alertar', 'movil'));
    }

    public function crear($semana = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (1 != Auth::user()->is_admin) {
            return redirect('home');
        }

        if ('' == $semana or is_null($semana)) {
            $semana = 0;
        }

        $fecha = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana);
        $turnoExiste = Turno::where('turno', $fecha->format('Y-m-d') . ' 08:00:00')->get()->all();
        if ($turnoExiste) {
            return redirect()->route('turnos.editar', $semana);
        }

        $diaSemana = Fecha::$diaSemana;
        array_shift($diaSemana);                // Desaparece el domingo y comienza el lunes.
        $title = 'Crear ' . $this->tipo . ' para la semana que comienza el lunes, ' . 
                    $fecha->format('d/m/Y');

        for ($d = 0; $d < 6; $d++) {
            $fecha = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana);
            $dia[$d] = $fecha->addDays($d)->format('Y-m-d');    // Fecha de cada dia.
        }

        $users = User::get(['id', 'name']);     // Todos los usuarios (asesores).

        for ($d = 0; $d < 11; $d++) {
            $semanaInicial = Fecha::primerLunesDePrimeraSemana();
            $semanas[$d] = $semanaInicial->addWeeks($d);            // Proximos once lunes.
        }
        //dd($semanas);
        return view('turnos.crear', compact('title', 'diaSemana', 'dia', 'users',
                    'semanas', 'semana'));
    }

    public function store(Request $request)
    {
        $fechas = request()->validate([             // $fechas = request()->all();
            'u0' => ['required'],
            'f0' => '',
            'u1' => ['required'],
            'f1' => '',
            'u2' => ['required'],
            'f2' => '',
            'u3' => ['required'],
            'f3' => '',
            'u4' => ['required'],
            'f4' => '',
            'u5' => ['required'],
            'f5' => '',
            'u6' => ['required'],
            'f6' => '',
            'u7' => ['required'],
            'f7' => '',
            'u8' => ['required'],
            'f8' => '',
            'u9' => ['required'],
            'f9' => '',
            'u10' => ['required'],
            'f10' => '',
            'semana' => ''
        ], [
            'u0.required' => 'No seleccionó el asesor para el turno del lunes en la mañana',
            'u1.required' => 'No seleccionó el asesor para el turno del martes en la mañana',
            'u2.required' => 'No seleccionó el asesor para el turno del miercoles en la mañana',
            'u3.required' => 'No seleccionó el asesor para el turno del lunes en la tarde',
            'u4.required' => 'No seleccionó el asesor para el turno del martes en la tarde',
            'u5.required' => 'No seleccionó el asesor para el turno del miercoles en la tarde',
            'u6.required' => 'No seleccionó el asesor para el turno del jueves en la mañana',
            'u7.required' => 'No seleccionó el asesor para ipaspudo_2018-04-04-17.tgzel turno del viernes en la mañana',
            'u8.required' => 'No seleccionó el asesor para el turno del sábado en la mañana',
            'u9.required' => 'No seleccionó el asesor para el turno del viernes en la tarde',
            'u10.required' => 'No seleccionó el asesor para el turno del sábado en la tarde',
        ]);
        //dd($fechas);

        for ($i = 0; $i < 11; $i++) {
            $data['turno']  = new Carbon($fechas['f'.$i] . ':00:00');
            $data['user_id']   = $fechas['u'.$i];
            $data['user_creo'] = Auth::user()->id;            

            Turno::create([
                'turno'  => $data['turno'],
                'user_id'   => $data['user_id'],
                'user_creo' => $data['user_creo'],
            ]);
        }
        $semana = $fechas['semana'];

        if ('' == $semana or is_null($semana)) {
            return redirect()->route('turnos');
        } else {
            return redirect()->route('turnos.crear', $semana);
        }
    }

    public function editar($semana = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (1 != Auth::user()->is_admin) {
            return redirect('home');
        }

        if ('' == $semana or is_null($semana)) {
            $semana = 0;
        }

        $fecha = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana);
        $turnoExiste = Turno::where('turno', $fecha->format('Y-m-d') . ' 08:00:00')->get()->all();
        if (!$turnoExiste) {
            return redirect()->route('turnos.crear', $semana);
        }

        $diaSemana = Fecha::$diaSemana;
        array_shift($diaSemana);                // Desaparece el domingo y comienza el lunes.
        $title = 'Editar ' . $this->tipo . ' para la semana que comienza el lunes, ' . 
                    $fecha->format('d/m/Y');

        for ($d = 0; $d < 6; $d++) {
            $fecha = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana);
            $dia[$d] = $fecha->addDays($d)->format('Y-m-d');    // Fecha de cada dia.
        }

        $users = User::get(['id', 'name']);     // Todos los usuarios (asesores).

        for ($d = 0; $d < 11; $d++) {
            $semanaInicial = Fecha::primerLunesDePrimeraSemana();
            $semanas[$d] = $semanaInicial->addWeeks($d);            // Proximos once lunes.
        }

        $fecha1 = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana);   // Lunes
        $fecha2 = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana)->addDays(6);                              // Domingo
        $turnos = Turno::whereBetween('turno', [$fecha1->format('Y-m-d'), $fecha2->format('Y-m-d')])
                    ->orderBy('id')         // Puede estar demas, pero, me aseguro el orden correcto.
                    ->get()->all();
//        dd($turnos);
        $turno = $turnos[0];
        return view('turnos.editar', compact('title', 'diaSemana', 'dia', 'users', 'semanas', 'semana',
                    'turnos', 'turno'));
    }

    public function update(Request $request, Turno $turno)
    {
        $fechas = request()->validate([             // $fechas = request()->all();
            'u0' => ['required'],
            'f0' => '',
            'u1' => ['required'],
            'f1' => '',
            'u2' => ['required'],
            'f2' => '',
            'u3' => ['required'],
            'f3' => '',
            'u4' => ['required'],
            'f4' => '',
            'u5' => ['required'],
            'f5' => '',
            'u6' => ['required'],
            'f6' => '',
            'u7' => ['required'],
            'f7' => '',
            'u8' => ['required'],
            'f8' => '',
            'u9' => ['required'],
            'f9' => '',
            'u10' => ['required'],
            'f10' => '',
            'semana' => ''
        ], [
            'u0.required' => 'No seleccionó el asesor para el turno del lunes en la mañana',
            'u1.required' => 'No seleccionó el asesor para el turno del martes en la mañana',
            'u2.required' => 'No seleccionó el asesor para el turno del miercoles en la mañana',
            'u3.required' => 'No seleccionó el asesor para el turno del lunes en la tarde',
            'u4.required' => 'No seleccionó el asesor para el turno del martes en la tarde',
            'u5.required' => 'No seleccionó el asesor para el turno del miercoles en la tarde',
            'u6.required' => 'No seleccionó el asesor para el turno del jueves en la mañana',
            'u7.required' => 'No seleccionó el asesor para el turno del viernes en la mañana',
            'u8.required' => 'No seleccionó el asesor para el turno del sábado en la mañana',
            'u9.required' => 'No seleccionó el asesor para el turno del viernes en la tarde',
            'u10.required' => 'No seleccionó el asesor para el turno del sábado en la tarde',
        ]);
        //dd($fechas, $turno);
        
        $id = $turno->id;
        for ($i = 0; $i < 11; $i++) {
            $turno = Turno::find($id+$i);
//            $data['turno'] = new Carbon($fechas['f'.$i] . ':00:00');
            $data['user_id'] = $fechas['u'.$i];
            if ($data['user_id'] != $turno->user_id) {
                $data['user_actualizo'] = Auth::user()->id;
                //dd($turno, $data);
                $turno->update($data);
            }
        }
        //dd($fechas, $data, $turno);

        $semana = $fechas['semana'];
        if ('' == $semana or is_null($semana)) {
            return redirect()->route('turnos');
        } else {
            return redirect()->route('turnos.crear', $semana);
        }
    }

    public function destroy(User $turno)
    {
    }
}
