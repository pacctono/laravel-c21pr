<?php

namespace App\Http\Controllers;

use App\User;
use App\Propiedad;
use App\Deseo;
use App\Tipo;
use App\Price;
use App\Ciudad;
use App\Zona;
use App\Venezueladdn;
use App\Texto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\MisClases\General;              // PC
use Jenssegers\Agent\Agent;             // PC

class WelcomeController extends Controller
{
    public function welcome()
    {
        $users   = User::where('id', '>', 1)->where('activo', True)->get();
        //dd($users);
        $propiedades = Propiedad::misPropiedades();         // Propiedades con fotos. Arreglo personalizado convertido a 'collect'.
        //dd($propiedades);
        $hayFotos = false;
        foreach ($users as $user) {
            if ($user->foto) {
                $hayFotos = true;
                break;
            }
        }
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.

        return view('welcome', compact('users', 'hayFotos', 'propiedades', 'movil'));
    }   // public function welcome()
/*
 * Propiedades que un comprador desea comprar o alquilar.
 * La propiedad tiene que estar siendo negociada como Venta o Alquiler.
 */
    public function propiedades($deseo, $ciudad, $tipo)
    {
        $negociacion = ('3' == $deseo)?'A':'V';
        $ciudad = $ciudad;    // Aqui hay un problema grave. Ciudad != Zona.
        $propiedades = Propiedad::where('estatus', 'A')
                                    ->where('ciudad_id', $ciudad)
                                    ->where('negociacion', $negociacion)
                                    ->where('tipo_id', $tipo)->get();
// $propiedades = Propiedad::where('estatus', 'A')->where('ciudad_id', 3)->where('negociacion', 'V')->where('tipo_id', 2)->get();                                    
// foreach ($propiedades as $p) if (0 < count($p->imagenes)) foreach ($p->imagenes as $i=>$e) echo $p->id, " $i => $e\n"; else echo $p->id, " vacio\n";

        $agente = new Agent();
        $movil  = $agente->isMobile() and true;         // Fuerzo booleana. No funciona al usar el metodo directamente.
        return view('inicio.propiedades', compact('propiedades', 'movil'));
    }   // public function propiedades($zona, $deseo, $tipo)
    public function ajaxWelcome()
    {
        $deseos = [];
        foreach (Deseo::get(['id', 'descripcion']) as $v) {
            $deseos[$v->id] = $v->descripcion;
        }
        $deseosC = $deseosV = [];
        foreach (Deseo::get(['id', 'descripcion']) as $v) {
            if ((1 == $v->id) or (3 == $v->id)) {
                $deseosC[$v->id] = $v->descripcion;
            }
            if ((2 == $v->id) or (4 == $v->id)) {
                $deseosV[$v->id] = $v->descripcion;
            }
        }
        $tipos = [];
        foreach (Tipo::get(['id', 'descripcion']) as $v) {
            $tipos[$v->id] = $v->descripcion;
        }
        $preciosV = [];
        $preciosA = [];
        foreach (Price::all() as $v) {
            $preciosV[$v->id] = $v->descripcion;
            $preciosA[$v->id] = $v->descripcion_alquiler;
        }
        $ciudades = [];
        foreach (Ciudad::get(['id', 'descripcion']) as $v) {
            $ciudades[$v->id] = $v->descripcion;
        }
        $zonas = [];
        foreach (Zona::get(['id', 'descripcion']) as $v) {
            $zonas[$v->id] = $v->descripcion;
        }
        $j = 0;
        $ddns = [];
        foreach (Venezueladdn::distinct()->get(['ddn'])->all() as $v) {
            $ddns[++$j] = $v->ddn;
        }

        return array(json_encode($deseos), json_encode($deseosC), json_encode($deseosV),
                    json_encode($tipos), json_encode($preciosV), json_encode($preciosA),
                    json_encode($ciudades), json_encode($zonas), json_encode($ddns));
    }   // public function ajaxWelcome()
}
