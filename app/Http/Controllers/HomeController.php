<?php

namespace App\Http\Controllers;

use App\User;
use App\Texto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;
use App\MisClases\Fecha;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($alert=0)
    {
        $fecha_desde = Fecha::hoy();
        $fecha_hasta = Fecha::hoy()->addDays(3)->endOfDay();
        $cumpleaneros = User::cumpleanos($fecha_desde, $fecha_hasta)->get();
        $textos = Texto::all();
        $hoy = Fecha::hoy()->format('d-m');
        $manana = Fecha::manana()->format('d-m');
        $texto1 = $textos->find(1);
        $texto2 = $textos->find(2);
        $texto3 = $textos->find(3);
        $texto4 = $textos->find(4);
        $texto5 = $textos->find(5);
        //dd($cumpleaneros);
        //dd($cumpleaneros->first()->fecha_cumpleanos);
        //dd($alert);
        $nombre = Auth::user()->name;
        if (0 == $alert) {
            $alertar = '';
        } elseif (1 == $alert) {
            $alertar = 'Bienvenido a su turno, ' . $nombre . ', ha sido puntual!';
        } else {
            if (-1 == $alert) {
                $alertar = $nombre . '! Usted no tiene permitido acceder a esa página.';
            } else {
                $alertar = '! Usted llego tarde. Su turno comienza a las ';
                if (-2 == $alert) $alertar = $nombre . $alertar . '8:30am';
                elseif (-3 == $alert) $alertar = $nombre . $alertar . '12:30am';
                else $alertar = 'Disculpe! Esta notificación no debería existir.';
            }
        }
        $foto = substr(Auth::user()->email, 0, strpos(Auth::user()->email, '@')) . '-0.jpg';
        $foto = 'fotos/' . $foto;
/* Usando 'system'.
        $nLineas = 0;
        $fortuna = '';
        $ciclo = 0;
        while (1 != $nLineas) {
            $ciclo++;
            system("fortune >storage/fortuna.txt", $resultado);
            if (0 == $resultado) {
                $fortunas = file("storage/fortuna.txt");
                $nLineas = count($fortunas);
                if (1 == $nLineas) $fortuna = trim($fortunas[0]);
            } else break;
            if (100 < $ciclo) break;
        }
        $fortuna = exec("/usr/games/fortune");
        dd($resultado, $nLineas, $ciclo, $fortuna);*/
/* Usando "Symfony\Component\Process\Process"
        $proceso = new Process('fortune');
        $proceso->run();
        if ($proceso->isSuccessful()) $fortuna = $proceso->getOutput();
        else $fortuna = 'Fallo el proceso fortuna';
        dd($fortuna);*/
        $rutaPrevia = redirect()->getUrlGenerator()->previous();
        $login = stripos($rutaPrevia, 'login');
        return view('home', compact('cumpleaneros', 'hoy', 'manana', 'foto',
                    'rutaPrevia', 'login',
                    'texto1', 'texto2', 'texto3', 'texto4', 'texto5', 'alertar'));
    }
}
