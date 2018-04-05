<?php

namespace App\Http\Controllers;

use App\Turno;
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
    protected $tipo = 'Agenda';

    public function index($orden = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $title = $this->tipo . ' Personal';
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
    
        return view('agenda.index', compact('title', 'turnos', 'ruta', 'diaSemana', 'semanas'));
    }
}
