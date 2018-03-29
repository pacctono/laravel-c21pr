<?php

namespace App\Http\Controllers;

use App\Turno;
use App\User;
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
        $title = 'Listado de turnos';
        $ruta = request()->path();
        $diaSemana = $this->diaSemana;

        if (!(Auth::check())) {
            return redirect('login');
        }

        if ('' == $orden or $orden == null) {
            $orden = 'id';
        }
        if (1 == Auth::user()->is_admin) {
            $turnos = Turno::where('turno_en','>=', 'now')
                            ->orderBy($orden)->paginate(10);
        } else {
            $turnos = User::find(Auth::user()->id)->turnos()
                            ->where('turno_en','>=', 'now')
                            ->orderBy($orden)->paginate(10);
        }
    
        return view('turnos.index', compact('title', 'turnos', 'ruta', 'diaSemana'));
    }

    public function crear($semana = null)
    {
        if ($semana == null) {
            $semana = 0;
        }
        $diaSemana = $this->diaSemana;
        array_shift($diaSemana);
        $title = 'Crear Turnos para la semana que comienza el lunes, ';

        $fecha = (new Carbon('next monday'))->addWeeks($semana);
        $title .= $fecha->format('d/m/Y');
        $users = User::all();
        //dd($diaSemana);
        return view('turnos.crear', compact('title', 'diaSemana', 'users'));
    }

    public function store()
    {
        Turno::create([
            'name' => $data['name'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

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
