<?php

namespace App\Http\Controllers;

use App\Turno;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TurnoController extends Controller
{
    protected $diaSemana = [
        'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'
    ];
    protected $tipo = 'Turnos';

    public function index($orden = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $title = 'Listado de ' . $this->tipo;
        $ruta = request()->path();
        $diaSemana = $this->diaSemana;

        for ($d = 0; $d < 11; $d++) {
            $semanas[$d] = (new Carbon('next monday'))->addWeeks($d);   // Proximos once lunes.
        }

        if ('' == $orden or $orden == null) {
            $orden = 'id';
        }
        if (Auth::user()->is_admin) {
            $turnos = Turno::where('turno','>=', 'now')
                            ->orderBy($orden)->paginate(10);
        } else {
            $user   = User::find(Auth::user()->id);
            $turnos = $user->turnos()
                        ->where('turno','>=', 'now')
                        ->orderBy($orden)->paginate(10);
            $title .= ' de ' . $user->name;
        }
    
        return view('turnos.index', compact('title', 'turnos', 'ruta', 'diaSemana', 'semanas'));
    }

    public function crear($semana = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (1 != Auth::user()->is_admin) {
            return redirect('home');
        }

        if ('' == $semana or $semana == null) {
            $semana = 0;
        }

        $fecha = (new Carbon('next monday'))->addWeeks($semana);    // Fecha del lunes de la semana a editar.
        $turnoExiste = Turno::where('turno', $fecha->format('Y-m-d') . ' 08:00:00')->get()->all();
        if ($turnoExiste) {
            return redirect()->route('turnos.editar', $semana);
        }

        $diaSemana = $this->diaSemana;
        array_shift($diaSemana);                // Desaparece el domingo y comienza el lunes.
        $title = 'Crear ' . $this->tipo . ' para la semana que comienza el lunes, ' . 
                    $fecha->format('d/m/Y');

        for ($d = 0; $d < 6; $d++) {
            $fecha = (new Carbon('next monday'))->addWeeks($semana);
            $dia[$d] = $fecha->addDays($d)->format('Y-m-d');    // Fecha de cada dia.
        }

        $users = User::get(['id', 'name']);     // Todos los usuarios (asesores).
        for ($d = 0; $d < 11; $d++) {
            $semanas[$d] = (new Carbon('next monday'))->addWeeks($d);   // Proximos diez lunes.
        }
        //dd($semanas);
        return view('turnos.crear', compact('title', 'diaSemana', 'dia', 'users', 'semanas', 'semana'));
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

        if ('' == $semana or $semana == null) {
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

        if ('' == $semana or $semana == null) {
            $semana = 0;
        }

        $fecha = (new Carbon('next monday'))->addWeeks($semana);    // Fecha del lunes de la semana a editar.
        $turnoExiste = Turno::where('turno', $fecha->format('Y-m-d') . ' 08:00:00')->get()->all();
        if (!$turnoExiste) {
            return redirect()->route('turnos.crear', $semana);
        }

        $diaSemana = $this->diaSemana;
        array_shift($diaSemana);                // Desaparece el domingo y comienza el lunes.
        $title = 'Editar ' . $this->tipo . ' para la semana que comienza el lunes, ' . 
                    $fecha->format('d/m/Y');

        for ($d = 0; $d < 6; $d++) {
            $fecha = (new Carbon('next monday'))->addWeeks($semana);
            $dia[$d] = $fecha->addDays($d)->format('Y-m-d');    // Fecha de cada dia.
        }

        $users = User::get(['id', 'name']);     // Todos los usuarios (asesores).
        for ($d = 0; $d < 11; $d++) {
            $semanas[$d] = (new Carbon('next monday'))->addWeeks($d);   // Proximos diez lunes.
        }

        $fecha1 = (new Carbon('next monday'))->addWeeks($semana);   // Lunes
        $fecha2 = (new Carbon('next monday'))->addWeeks($semana)->addDays(6);                              // Domingo
        $turnos = Turno::whereBetween('turno', [$fecha1->format('Y-m-d'), $fecha2->format('Y-m-d')])
                    ->orderBy('id')         // Puede estar demas, pero, me aseguro el orden correcto.
                    ->get()->all();
        //dd($turnos);
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
        if ('' == $semana or $semana == null) {
            return redirect()->route('turnos');
        } else {
            return redirect()->route('turnos.crear', $semana);
        }
    }

    public function destroy(User $turno)
    {
    }
}
