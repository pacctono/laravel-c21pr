<?php

namespace App\Http\Controllers;

use App\User;
use App\Venezueladdn;
use App\Bitacora;
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
/*        $fechaUltLogin = Bitacora::all()
                                ->where('user_id', $user->id)
                                ->where('tx_tipo', 'L')
                                ->max('created_at');*/
/*        $fechaUltLogin = Bitacora::OfUltLogin($user->id);
        if (!($fechaUltLogin instanceof Carbon)) $fechaUltLogin = null;*/
        $fechaUltLogin = Bitacora::fechaUltLogin($user->id);
        return view('users.show', compact('user', 'fechaUltLogin'));
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
            'cedula' => ['sometimes', 'nullable', 'digits_between:6,8'],
            'name' => 'required',
            'ddn' => '',
            'telefono' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],   // 'required|email|...'
            'email_c21' => ['sometimes', 'nullable', 'email'],
            'licencia_mls' => ['sometimes', 'nullable', 'digits_between:5,7', 'unique:users,licencia_mls'],
            'fecha_ingreso' => ['sometimes', 'nullable', 'date'],
            'fecha_nacimiento' => ['sometimes', 'nullable', 'date'],
            'sexo' => ['sometimes', 'nullable'],
            'estado_civil' => ['sometimes', 'nullable'],
            'profesion' => ['sometimes', 'nullable'],
            'direccion' => ['sometimes', 'nullable'],
            'password' => ['required']
        ], [
            'cedula.digits_between' => 'La cedula de ideintidad debe ser entre 6 y 8 digitos',
            'name.required' => 'El campo nombre es obligatorio',
            'telefono.required' => 'El teléfono debe ser suministrado',
            'email.required' => 'El correo electrónico es obligatorio suministrarlo',
            'email.email' => 'Debe suministrar un correo elctrónico válido',
            'email.unique' => 'Ese correo electrónico está siendo usado por otro usuario',
            'email_c21.email' => 'El correo electrónico de trabajo no es válido',
            'licencia_mls.digits_between:5,7' => 'La licencia MLS debe contener entre 5 y 7 dígitos',
            'licencia_mls.unique' => 'La licencia MLS debe ser única, alguien más posee ese número',
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
            'cedula' => $data['cedula'],
            'name' => $data['name'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'email_c21' => $data['email_c21'],
            'licencia_mls' => $data['licencia_mls'],
            'fecha_ingreso' => $data['fecha_ingreso'],
            'fecha_nacimiento' => $data['fecha_nacimiento'],
            'sexo' => $data['sexo'],
            'estado_civil' => $data['estado_civil'],
            'direccion' => $data['direccion'],
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
            'licencia_mls' => ['sometimes', 'nullable', 'digits_between:5,7'],
//            'licencia_mls' => ['sometimes', 'nullable', 'digits_between:5,7', Rule::unique('users')->ignore($user->licencia_mls)],
            'fecha_ingreso' => ['sometimes', 'nullable', 'date'],
            'fecha_nacimiento' => ['sometimes', 'nullable', 'date'],
            'sexo' => ['sometimes', 'nullable'],
            'estado_civil' => ['sometimes', 'nullable'],
            'profesion' => ['sometimes', 'nullable'],
            'direccion' => ['sometimes', 'nullable'],
            'password' => 'nullable|min:7'
        ], [
            'cedula.digits_between' => 'La cedula de ideintidad debe contener 7 u 8 digitos',
            'name.required' => 'El campo nombre es obligatorio',
            'telefono.required' => 'El teléfono debe ser suministrado',
            'email.required' => 'El correo electrónico es obligatorio suministrarlo',
            'email.email' => 'Debe suministrar un correo elctrónico válido',
            'email.unique' => 'Ese correo electrónico está siendo usado por otro usuario',
            'email_c21.email' => 'El correo electrónico de trabajo no es válido',
            'licencia_mls.digits_between:5,7' => 'La licencia MLS debe contener entre 5 y 7 dígitos',
//            'licencia_mls.unique' => 'La licencia MLS debe ser única, alguien más posee ese número',
            'fecha_ingreso.date' => 'La fecha de ingreso debe corresponder con una fecha',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe corresponder con una fecha',
            'password.min' => 'La contraseña debe contener más de 6 caracteres'
        ]);
        //dd($data);
	if (0 < User::where('id', '!=', $user->id)->where('licencia_mls', $data['licencia_mls'])->count()) {
            redirect()->back();
        }
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
        if (0 < ($user->contactos->count()-$user->contactosBorrados->count())) {
            return redirect()->route('users');  // Existen contactos asignados a este usuario.
        }
        if (0 < $user->contactosBorrados->count()) {    // Existen contactos borrados (logico).
            $contactos = $user->contactos;
            foreach ($contactos as $contacto) {     // Ciclo para borrar fisicamente los contactos.
                $contacto->delete();
            }
        }
        $usuario = Auth::user()->id;
        $datos = 'id:'.$user->id.', cedula:'.$user->cedula.', nombre:'.$user->name;
        $user->delete();

        Bitacora::create([
            'user_id' => $usuario,
            'tx_modelo' => 'User',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
        ]);

        return redirect()->route('users');
    }
}
