<?php

namespace App\Http\Controllers;

use App\User;
use App\Texto;
use Illuminate\Http\Request;
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
    public function index()
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
        //dd($cumpleaneros[0]->fecha_cumpleanos);
        return view('home', compact('cumpleaneros', 'hoy', 'manana',
                    'texto1', 'texto2', 'texto3', 'texto4', 'texto5'));
    }
}
