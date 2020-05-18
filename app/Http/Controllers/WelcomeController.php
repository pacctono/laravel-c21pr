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

        return view('welcome', compact('users', 'propiedades'));
    }
}
