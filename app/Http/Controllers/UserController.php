<?php

namespace App\Http\Controllers;

use App\User;
use App\Venezueladdn;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{

    public function index($orden = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        //$users = DB::table('users')->get();
        $title = 'Listado de asesores';

        if ('' == $orden or $orden == null) {
            $orden = 'id';
        }
        $users = User::orderBy($orden)->paginate(10);
        //dd($users);

        return view('users.index', compact('title', 'users'));
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function create()
    {
        $title = 'Crear asesor';
        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();

        return view('users.create', compact('title', 'ddns'));
    }

    public function store()
    {
//        $data = request()->all();   // all() ---> only(campos requeridos separados por ,)
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'name' => 'required',
            'telefono' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],   // 'required|email|...'
            'password' => ['required']
        ], [
            'name.required' => 'El campo nombre es obligatorio',
            'telefono.required' => 'El teléfono debe ser suministrado',
            'email.required' => 'El correo electrónico es obligatorio suministrarlo',
            'email.email' => 'Debe suministrar un correo elctrónico válido',
            'email.unique' => 'Ese correo electrónico está siendo usado por otro usuario',
            'password.required' => 'La contraseña es obligatorio suministrarla'
        ]);

        //dd($data);

        User::create([
            'name' => $data['name'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        //return redirect('usuarios');
        return redirect()->route('users');
    }

    public function edit(User $user)
    {
        $title = 'Editar asesor';
        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();

        return view('users.edit', ['user' => $user, 'title' => $title, 'ddns' => $ddns]);
    }

    public function update(User $user)
    {
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'name' => 'required',
            'telefono' => 'required',
//            'email' => ['required', 'email', 'unique:users,email,'.$user->id],   // 'required|email|...'
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],    // 'email' 2do par no es necesario.
            'password' => 'nullable|min:7'
        ], [
            'name.required' => 'El campo nombre es obligatorio',
            'telefono.required' => 'El teléfono debe ser suministrado',
            'email.required' => 'El correo electrónico es obligatorio suministrarlo',
            'email.email' => 'Debe suministrar un correo elctrónico válido',
            'email.unique' => 'Ese correo electrónico está siendo usado por otro usuario',
            'password.min' => 'La contraseña debe contener más de 6 caracteres'
        ]);

        if ($data['password'] != null) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.show', ['user' => $user]);
    }

    public function destroy(User $user)
    {

        $user->delete();

        return redirect()->route('users');
    }
}