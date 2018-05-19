@extends('layouts.app')

@section('content')
<div class="card col-md-10">
    <h4 class="card-header">{{ $title }}</h4>
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <h5>Por favor corrige los errores debajo:</h5>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ url("/usuarios/{$user->id}") }}">
            {{ method_field('PUT') }}
            {!! csrf_field() !!}
            <!-- input name="_method" type="hidden" value="PUT" -->

            <div class="form-group d-flex align-items-end">
                <label for="cedula">Cedula de identidad:</label>
                <input type="text" size="8" maxlength="8" minlength="7" name="cedula" id="cedula" 
                        placeholder="87654321" value="{{ old('cedula', $user->cedula) }}">
            </div>
            <div class="form-group d-flex align-items-end">
                <label for="name">Nombre:</label>
                <input type="text" maxlength="30" required name="name" id="name" 
                        placeholder="Pedro Perez" value="{{ old('name', $user->name) }}">
            </div>
            <div class="form-group d-flex align-items-end">
                <label for="telefono">Teléfono:</label>
                0<select name="ddn" id="ddn">
                  <option value="">ddn</option>
                @foreach ($ddns as $ddn)
                @if (old('ddn', substr($user->telefono, 0, 3)) == $ddn->ddn)
                  <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                @else
                  <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                @endif
                @endforeach
                </select>
                <input type="text" size="7" maxlength="7" minlength="7" required name="telefono" 
                        id="telefono" placeholder="1234567" 
                        value="{{ old('telefono', substr($user->telefono, 3)) }}">
            </div>

            <div class="form-group d-flex align-items-end">
                <label for="email">Correo electrónico personal:</label>
                <input type="email" size="30" maxlength="30" required name="email" id="email" 
                        placeholder="pedro@example.com" value="{{ old('email', $user->email) }}">
            </div>
            <div class="form-group d-flex align-items-end">
                <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                        value="{{ old('fecha_nacimiento', ($user->fecha_nacimiento)?$user->fecha_nacimiento_bd:'') }}">
            </div>
            <div class="form-group d-flex align-items-end">
                <label for="email_c21">Correo electrónico C21:</label>
                <input type="email" size="30" maxlength="30" name="email_c21" id="email_c21" 
                        placeholder="pedro@c21.com" value="{{ old('email_c21', $user->email_c21) }}">
            </div>
            <div class="form-group d-flex align-items-end">
                <label for="licencia_mls">Licencia MLS:</label>
                <input type="text" size="30" maxlength="30" name="licencia_mls" id="licencia_mls" 
                        placeholder="111111" value="{{ old('licencia_mls', $user->licencia_mls) }}">
            </div>
            <div class="form-group d-flex align-items-end">
                <label for="fecha_ingreso">Fecha de ingreso:</label>
                <input type="date" name="fecha_ingreso" id="fecha_ingreso"
                        value="{{ old('fecha_ingreso', ($user->fecha_ingreso)?$user->fecha_ingreso_bd:'') }}">
            </div>
            <div class="form-group d-flex align-items-end">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id=password 
                        placeholder="Mayor a 6 caracteres">
            </div>
            <div class="form-group d-flex align-items-end">
                <button class="btn btn-primary">Actualizar Asesor</button>
                @if (auth()->user()->is_admin)
                <!-- a href="{{ action('UserController@index') }}">Regresar al listado de usuarios</a -->
                <a href="{{ url('/usuarios') }}" class="btn btn-link">Regresar al listado de asesores</a>
                @else
                <a href="{{ route('users.show', auth()->user()->id) }}" class="btn btn-link">Mostrar asesor</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection