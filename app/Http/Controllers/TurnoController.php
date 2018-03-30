<?php

namespace App\Http\Controllers;

use App\Turno;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TurnoController extends Controller
{
    protected $diaSemana = [
        'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'
    ];

    public function index($orden = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $title = 'Listado de turnos';
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
    
        return view('turnos.index', compact('title', 'turnos', 'ruta', 'diaSemana'));
    }

    public function crear($semana = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (1 != Auth::user()->is_admin) {
            return redirect('home');
        }

        if ($semana == null) {
            $semana = 0;
        }
        $diaSemana = $this->diaSemana;
        array_shift($diaSemana);
        $title = 'Crear Turnos para la semana que comienza el lunes, ';

        for ($d = 0; $d < 6; $d++) {
            $fecha = (new Carbon('next monday'))->addWeeks($semana);
            $dia[$d] = $fecha->addDays($d)->format('Y-m-d');
        }
        $fecha = (new Carbon('next monday'))->addWeeks($semana);
        $title .= $fecha->format('d/m/Y');

        $users = User::get(['id', 'name']);
        for ($d = $semana+1; $d < 10; $d++) {
            $semanas[$d] = (new Carbon('next monday'))->addWeeks($d);
        }

        //dd($dia, $fecha);
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

    public function editar(User $turno)
    {
    }

    public function update(User $turno)
    {
    }

    public function destroy(User $turno)
    {
    }
}
