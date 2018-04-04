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

    protected $tipo = 'Asesor';

    public function index($orden = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!auth()->user()->is_admin) {
            $user = auth()->user();
            return redirect()->route('users.show', ['user' => $user]);
        }

        //$users = DB::table('users')->get();
        $title = 'Listado de ' . $this->tipo . 'es';

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
        $title = 'Crear ' . $this->tipo;

        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();

        return view('users.create', compact('title', 'ddns'));
    }

    public function store()
    {
//        $data = request()->all();   // all() ---> only(campos requeridos separados por ,)
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'cedula' => ['sometimes', 'nullable', 'digits_between:7,8'],
            'name' => 'required',
            'ddn' => '',
            'telefono' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],   // 'required|email|...'
            'email_c21' => ['sometimes', 'nullable', 'email'],
            'licencia_mls' => ['sometimes', 'nullable', 'digits_between:6,7'],
            'fecha_ingreso' => ['sometimes', 'nullable', 'date'],
            'fecha_nacimiento' => ['sometimes', 'nullable', 'date'],
            'password' => ['required']
        ], [
            'cedula.digits_between' => 'La cedula de ideintidad debe contener 7 u 8 digitos',
            'name.required' => 'El campo nombre es obligatorio',
            'telefono.required' => 'El teléfono debe ser suministrado',
            'email.required' => 'El correo electrónico es obligatorio suministrarlo',
            'email.email' => 'Debe suministrar un correo elctrónico válido',
            'email.unique' => 'Ese correo electrónico está siendo usado por otro usuario',
            'email_c21.email' => 'El correo electrónico de trabajo no es válido',
            'licencia_mls.digits_between:6,7' => 'La licencia MLS debe contener 6 o 7 dígitos',
            'fecha_ingreso.date' => 'La fecha de ingreso debe corresponder con una fecha',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe corresponder con una fecha',
            'password.required' => 'La contraseña es obligatorio suministrarla'
        ]);

        //dd($data);
        if (null != $data['ddn'] and '' != $data['ddn'] and null != $data['telefono'] and
                                                        '' != $data['telefono']) {
            $data['telefono'] = $data['ddn'] . $data['telefono'];
        } else {
            $data['telefono'] = '';
        }
        unset($data['ddn']);

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
        $title = 'Editar ' . $this->tipo;
        $ddns = Venezueladdn::distinct()->get(['ddn'])->all();

        return view('users.edit', ['user' => $user, 'title' => $title, 'ddns' => $ddns]);
    }

    public function update(User $user)
    {
        $data = request()->validate([   // Si ocurre error, laravel nos envia al url anterior.
            'cedula' => ['sometimes', 'nullable', 'digits_between:7,8'],
            'name' => 'required',
            'ddn' => '',
            'telefono' => 'required',
//            'email' => ['required', 'email', 'unique:users,email,'.$user->id],   // 'required|email|...'
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],    // 'email' 2do par no es necesario.
            'email_c21' => ['sometimes', 'nullable', 'email'],
            'licencia_mls' => ['sometimes', 'nullable', 'digits_between:6,7'],
            'fecha_ingreso' => ['sometimes', 'nullable', 'date'],
            'fecha_nacimiento' => ['sometimes', 'nullable', 'date'],
            'password' => 'nullable|min:7'
        ], [
            'cedula.digits_between' => 'La cedula de ideintidad debe contener 7 u 8 digitos',
            'name.required' => 'El campo nombre es obligatorio',
            'telefono.required' => 'El teléfono debe ser suministrado',
            'email.required' => 'El correo electrónico es obligatorio suministrarlo',
            'email.email' => 'Debe suministrar un correo elctrónico válido',
            'email.unique' => 'Ese correo electrónico está siendo usado por otro usuario',
            'email_c21.email' => 'El correo electrónico de trabajo no es válido',
            'licencia_mls.digits_between:6,7' => 'La licencia MLS debe contener 6 o 7 dígitos',
            'fecha_ingreso.date' => 'La fecha de ingreso debe corresponder con una fecha',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe corresponder con una fecha',
            'password.min' => 'La contraseña debe contener más de 6 caracteres'
        ]);
        //dd($data);
        if (null != $data['ddn'] and '' != $data['ddn'] and null != $data['telefono'] and
                                                        '' != $data['telefono']) {
            $data['telefono'] = $data['ddn'] . $data['telefono'];
        } else {
            $data['telefono'] = '';
        }
        unset($data['ddn']);

        if ($data['password'] != null) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        //dd($data);
        $user->update($data);

        return redirect()->route('users.show', ['user' => $user]);
    }

    public function destroy(User $user)
    {

        $user->delete();

        return redirect()->route('users');
    }
}