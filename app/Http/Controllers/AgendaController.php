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
        if (!(Auth::check())) {
            return redirect('login');
        }

        $title = $this->tipo;
        $ruta = request()->path();
        $diaSemana = $this->diaSemana;
        $periodo = request()->all();

        if (0 < count($periodo)) {
            $periodo = 'todo';
        }
        if ('' == $orden or $orden == null) {
            $orden = 'fecha_evento';
        }

        if (Auth::user()->is_admin) {
            $users   = User::all();
            $agendas = Agenda::select('fecha_evento', 'hora_evento', 'descripcion', 'name', 'telefono', 'user_id', 'email', 'direccion');
            if ('name' == $orden) {
                $agendas = $agendas->where('name', '!=', '');
            }
        } else {
            $user_id = Auth::user()->id;
            $asesor  = User::find($user_id);
            $title  .= 'de ' . $asesor->name;
            $agendas = Agenda::where('user_id', $user_id)
                    ->select('fecha_evento', 'hora_evento', 'descripcion', 'name', 'telefono', 'email', 'direccion');
        }
        $agendas = $agendas->orderBy($orden);
        if ('user_id' == $orden) {
            $agendas = $agendas->orderBy('fecha_evento');
        }
        $agendas = $agendas->paginate(10);
        //dd($agendas);
        return view('agenda.index', compact('title', 'users', 'ruta', 'diaSemana', 'agendas'));
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
