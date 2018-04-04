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
        $turnoExiste = Turno::where('turno_en', $fecha->format('Y-m-d') . ' 08:00:00')->get()->all();
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
        for ($d = $semana+1; $d < 11; $d++) {
            $semanas[$d] = (new Carbon('next monday'))->addWeeks($d);   // Proximos diez lunes.
        }
        //dd($semanas);
        return view('turnos.crear', compact('title', 'diaSemana', 'dia', 'users', 'semanas'));
    }

    public function store()
    {
        $fechas = request()->all();
        //dd($fechas);

        for ($i = 0; $i < 11; $i++) {
            $data['turno_en'] = new Carbon($fechas['f'.$i] . ':00:00');
            $data['user_id'] = $fechas['u'.$i];
            $data['user_creo'] = Auth::user()->id;            

            Turno::create([
                'turno_en' => $data['turno_en'],
                'user_id' => $data['user_id'],
                'user_creo' => $data['user_creo'],
            ]);
        }

        return redirect()->route('turnos');
    }

    public function editar($semana)
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
        $turnoExiste = Turno::where('turno_en', $fecha->format('Y-m-d') . ' 08:00:00')->get()->all();
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
        for ($d = $semana+1; $d < 11; $d++) {
            $semanas[$d] = (new Carbon('next monday'))->addWeeks($d);   // Proximos diez lunes.
        }

        $fecha1 = (new Carbon('next monday'))->addWeeks($semana);   // Lunes
        $fecha2 = (new Carbon('next monday'))->addWeeks($semana)->addDays(6);                              // Domingo
        $turnos = Turno::whereBetween('turno_en', [$fecha1->format('Y-m-d'), $fecha2->format('Y-m-d')])
                        ->get()->all();
        //dd($turnos);
        return view('turnos.editar', compact('title', 'diaSemana', 'dia', 'users', 'semanas', 'turnos'));
    }

    public function update(User $turno)
    {
    }

    public function destroy(User $turno)
    {
    }
}
