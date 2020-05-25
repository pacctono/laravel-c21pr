<?php

namespace App\Http\Controllers;

use App\User;
use App\Propiedad;
use App\Texto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function welcome()
    {
        $users   = User::where('id', '>', 1)->where('activo', True)->get();
        //dd($users);
        $propiedades = Propiedad::misPropiedades();
        //dd($propiedades);
        $hayFotos = false;
        foreach ($users as $user) {
            if ($user->foto) {
                $hayFotos = true;
                break;
            }
        }

        return view('welcome', compact('users', 'hayFotos', 'propiedades'));
    }
}
