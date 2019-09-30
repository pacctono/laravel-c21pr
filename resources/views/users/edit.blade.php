@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">{{ $title }}</h4>
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <h5>Por favor corrige los errores debajo</h5>
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

            <div class="form-group form-inline row bg-suave my-0 py-0">  {{-- margen(m) arriba y abajo(y) 0 y padding(p) arriba y abajo(y) 0(0) --}}
{{-- Otros valores para margen y padding: 't':tope, 'b':bottom, 'l':left, 'r':right y 'x':left y right --}}
                <label class="control-label" for="cedula">Cedula de identidad</label>
                <input class="form-control col-lg-3" type="text" size="8" maxlength="8" minlength="7"
                        name="cedula" id="cedula" placeholder="numero de cedula"
                        value="{{ old('cedula', $user->cedula) }}">
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <label class="control-label" for="name">Nombre</label>
                <input class="form-control col-lg-8" type="text" maxlength="30" required name="name" id="name" 
                        placeholder="Nombre del asesor" value="{{ old('name', $user->name) }}">
            </div>

            <div class="form-group form-inline row bg-suave my-0 py-0">
                <label class="control-label" for="telefono">Teléfono</label>
                0<select class="form-control" name="ddn" id="ddn">
                  <option value="">ddn</option>
                @foreach ($ddns as $ddn)
                @if (old('ddn', substr($user->telefono, 0, 3)) == $ddn->ddn)
                  <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                @else
                  <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                @endif
                @endforeach
                </select>
                <input class="form-control" type="text" size="7" maxlength="7" minlength="7"
                        required name="telefono" id="telefono" placeholder="numero sin area" 
                        value="{{ old('telefono', substr($user->telefono, 3)) }}">
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <label class="control-label" for="email">Correo electrónico personal</label>
                <input class="form-control" type="email" size="30" maxlength="30" required
                        name="email" id="email" placeholder="correo electronico"
                        value="{{ old('email', $user->email) }}">
            </div>

            <div class="form-group form-inline row bg-suave my-0 py-0">
                <label class="control-label" for="fecha_nacimiento">Fecha de nacimiento</label>
                <input class="form-control" type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                        max="{{ now()->format('Y-m-d') }}"
                        value="{{ old('fecha_nacimiento', ($user->fecha_nacimiento)?$user->fecha_nacimiento_bd:'') }}">
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <label class="control-label" for="email_c21">Correo electrónico C21</label>
                <input class="form-control" type="email" size="30" maxlength="30" name="email_c21" id="email_c21" 
                        placeholder="correo electronico de Century 21" value="{{ old('email_c21', $user->email_c21) }}">
            </div>

            <div class="form-group form-inline row bg-suave my-0 py-0">
                <label class="control-label" for="licencia_mls">Licencia MLS</label>
                <input class="form-control" type="text" size="7" minlength="5" maxlength="7" name="licencia_mls"
                        id="licencia_mls" placeholder="licencia MLS"
                        value="{{ old('licencia_mls', $user->licencia_mls) }}">
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <label class="control-label" for="fecha_ingreso">Fecha de ingreso</label>
                <input class="form-control" type="date" name="fecha_ingreso" id="fecha_ingreso"
                        min="2015-06-01" max="{{ now()->format('Y-m-d') }}"
                        value="{{ old('fecha_ingreso', ($user->fecha_ingreso)?$user->fecha_ingreso_bd:'') }}">
            </div>

            <div class="form-row bg-suave my-0 py-0 d-flex">
                <div class="col-lg-1">
                    Sexo
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sexo" id="fem"
                            value="F" {{ (('F' == old('sexo', $user->sexo))?'checked':'') }}>
                    <label class="form-check-label" for="fem">Femenino</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sexo" id="masc"
                            value="M" {{ (('M' == old('sexo', $user->sexo))?'checked':'') }}>
                    <label class="form-check-label" for="masc">Masculino</label>
                </div>
                <div class="col-lg-2">
                    Estado civil
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="estado_civil" id="casado"
                            value="C" {{ (('C' == old('sexo', $user->estado_civil))?'checked':'') }}>
                    <label class="form-check-label" for="casado">Casado</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="estado_civil" id="soltero"
                            value="S" {{ (('S' == old('sexo', $user->estado_civil))?'checked':'') }}>
                    <label class="form-check-label" for="soltero">Soltero</label>
                </div>
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <label class="control-label" for="profesion">Profesi&oacute;n</label>
                <input class="form-control" type="text" size="30" maxlength="100" name="profesion" id="profesion"
                        value="{{ old('profesion', $user->profesion) }}">
            </div>

            <div class="form-group form-inline row bg-suave my-0 py-0">
                <label class="control-label" for="direccion">Direcci&oacute;n</label>
                <input class="form-control" type="text" size="30" maxlength="100" name="direccion" id="direccion"
                        value="{{ old('direccion', $user->direccion) }}">
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <label class="control-label" for="password">Contrase&ntilde;a</label>
                <input class="form-control" type="password" name="password" id=password 
                        placeholder="Mayor a 6 caracteres">
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <button class="btn btn-primary">Actualizar Asesor</button>
                @if (auth()->user()->is_admin)
                {{-- <a href="{{ action('UserController@index') }}">Regresar al listado de usuarios</a> --}}
                <a href="{{ route('users') }}" class="btn btn-link">Regresar al listado de asesores</a>
                @else
                <a href="{{ route('users.show', auth()->user()->id) }}" class="btn btn-link">Mostrar asesor</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
