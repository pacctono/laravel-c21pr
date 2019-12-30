<?php

namespace App\Http\Controllers\Auth;

use App\Bitacora;
use App\Turno;
use App\Aviso;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Carbon\Carbon;
use App\MisClases\Fecha;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';        // ruta relativa.
//    protected $redirectAsesor = '/contactos/crear';
    protected $redirectAsesor = 'home';     // Usada como name route.

    protected function authenticated(Request $request, $user) {
        Bitacora::create([
            'user_id' => $user->id,
            'tx_modelo' => 'Login',
            'tx_tipo' => 'L',
            'tx_data' => $user->name,
            'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        if (Auth::check() and !$user->activo) {
            Auth::logout();
            $request->session()->invalidate();
//            return Redirect::to('login');     // Funciona. La clase tiene que estar arriba: 'use'.
            return view('errors.noActivo');
//            return redirect()->route('users.errors', ['error' => 'noActivo']);
        }

        if ($user->is_admin) {  // Los administradores no participan en los turnos.
            return redirect($this->redirectTo);
        }
        $alertar = 0;
        $hoy = Fecha::hoy();
        $manana = Fecha::manana();
        $turnos = Turno::where('user_id', $user->id)
                        ->whereBetween('turno', [$hoy, $manana])
//                        ->where('turno', '<', DATE_FORMAT(now(), 'Y-m-d 18:00'))
                        ->whereNull('llegada');
        $nroTurnosHoy = $turnos->count();
        //dd($nroTurnosHoy);
        if (0 < $nroTurnosHoy) {
            $ZONA = Fecha::$ZONA;
            $ahora = now($ZONA);
            $turnos = $turnos->orderBy('turno')->get();
            $horaTardeMananaDesde = new Carbon($ahora->format('Y-m-d 09:00'), $ZONA);
            $horaTarde = new Carbon($ahora->format('Y-m-d 12:00'), $ZONA);   // No se usa.
            $horaTardeTardeDesde  = new Carbon($ahora->format('Y-m-d 13:00'), $ZONA);
            $horaTardeTardeHasta  = new Carbon($ahora->format('Y-m-d 18:00'), $ZONA);   // No se usa.
// Crea $tarde ('tm': es tarde en la mañana, 'bm': dentro de la hora en la mañana y 'tt').            
            $tarde = 'bm';
            if ($horaTardeTardeDesde < $ahora) $tarde = 'tt';
            elseif ($horaTarde < $ahora) $tarde = 'bt';
            elseif (($horaTardeMananaDesde < $ahora) and ($horaTardeTardeDesde > $ahora))
                $tarde = 'tm';
            $llegada = $ahora->format('H:i');
            $data['llegada'] = $llegada;
            //dd($ahora, $horaTardeMananaDesde, $horaTardeTardeDesde, $nroTurnosHoy, $tarde);
            $loginTurno = True;
            if (1 == $nroTurnosHoy) {
                $turno = $turnos[0];    // Turno de la ma#ana o la tarde. También, $turnos->first();
                $horaTurno = $turno->hora_turno;
                if ('08' == $horaTurno) {
                    if ('tm' == $tarde) $alertar = -2;   // tm:tarde en la ma#ana
                    elseif ('bm' == $tarde) $alertar = 1;   // Cumplio con su turno.
                    else $loginTurno = False;   // Se conecto en la tarde. Despues de terminar el turno de la mañana.
                } elseif (('bm' == $tarde) or ('tm' == $tarde)) {   // Turno tarde y se conecta en la mañana.
                    $alertar = 0;       // No es necesario, pero, al diablo por si acaso.
                    $loginTurno = False;
                } elseif ('bt' == $tarde) {
                    $alertar = 1;   // Esta cumpliendo con su turno de la tarde >12 y <13
                } elseif ('tt' == $tarde) { // Llego a su turno de la tarde, despues de la 13:00
                    if ($ahora <= $horaTardeTardeHasta) $alertar = -3;  // y antes de las 18:00
                    else {
                        $alertar = 0;   // No es necesario, pero, al diablo por si acaso.
                        $loginTurno = False;
                    }
                }
                //dd($horaTurno, $tarde, $alertar, $data, $turno);
            } else {
// Por ahora tendremos problemas, cuando el mismo asesor cubra los dos (2) turnos.
                $turno = $turnos[0];    // Turno de la ma#ana. Se define por el orden del query.
                $turnoT = $turnos[1];   // Turno de la tarde. Se define por el orden del query.
                if ('bm' == $tarde) {
                    $alertar = 1;
                } else $alertar = -2;   // Es necesario que lo coloque tarde, para que pueda conectarse para el turno de la tarde.
/*                } elseif ('tm' == $tarde) {
                    $alertar = -2;
                } else $loginTurno = False;*/
            }
            if ($loginTurno) $turno->update($data);
            if (0 < $alertar) {
                $fecha = Fecha::$diaSemana[$turno->turno->dayOfWeek] . ', ' . $turno->turno_fecha;
                Aviso::create([
                    'user_id' => $user->id,
                    'turno_id' => $turno->id,
                    'tipo' => 'T',
                    'fecha' => $ahora,
                    'descripcion' => 'Llego tarde el ' . $fecha . ' en la ' . $turno->fec_tur,
                    'user_creo' => $user->user_creo
                ]);
            }
        }
        return redirect()->route($this->redirectAsesor, ['alertar' => $alertar]);
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
