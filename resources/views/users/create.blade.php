@extends('layouts.app')

@section('content')
    <div class="card col-10">
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

        <form method="POST" class="form-horizontal" action="{{ url('/usuarios') }}">
            {!! csrf_field() !!}

            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="cedula">Cedula de identidad:</label>
                <input type="text" class="form-control col-sm-2" size="8" maxlength="8" minlength="7" 
                        name="cedula" id="cedula" placeholder="87654321" value="{{ old('cedula') }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="name">Nombre:</label>
                <input type="text" class="form-control col-sm-5" size="30" maxlength="30" 
                        required name="name" id="name" placeholder="Pedro Perez" value="{{ old('name') }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="telefono">Teléfono:</label>
                0<select class="form-control col-sm-1" name="ddn" id="ddn">
                  <option value="">ddn</option>
                @foreach ($ddns as $ddn)
                @if (old('ddn', '414') == $ddn->ddn)
                  <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                @else
                  <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                @endif
                @endforeach
                </select>
                <input type="text" class="form-control col-sm-2" size="7" maxlength="7" minlength="7" 
                        required name="telefono" id="telefono" placeholder="1234567" 
                        value="{{ old('telefono') }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="email">Correo electrónico personal:</label>
                <input type="email" class="form-control col-sm-5" size="30" maxlength="30" required 
                        name="email" id="email" placeholder="pedro@example.com" value="{{ old('email') }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="fecha_nacimiento">Fecha de nacimiento:</label>
                <input type="date" class="form-control col-sm-2" name="fecha_nacimiento" 
                        id="fecha_nacimiento" max="{{ now()->format('Y-m-d') }}"
                        value="{{ old('fecha_nacimiento') }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="email_c21">Correo electrónico C21:</label>
                <input type="email" class="form-control col-sm-5" size="30" maxlength="30" 
                        name="email_c21"  id="email_c21" placeholder="pedro@c21.com" 
                        value="{{ old('email_c21') }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="licencia_mls">Licencia MLS:</label>
                <input type="text" class="form-control col-sm-2" size="7" maxlength="7"
                    minlength="5" name="licencia_mls" id="licencia_mls" placeholder="111111"
                    value="{{ old('licencia_mls') }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="fecha_ingreso">Fecha de ingreso:</label>
                <input type="date" class="form-control col-sm-3" required name="fecha_ingreso" 
                        id="fecha_ingreso" min="2015-06-01" max="{{ now()->format('Y-m-d') }}"
                        value="{{ old('fecha_ingreso') }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-1" for="sexo">Sexo:</label>
                <input type="text" class="form-control col-sm-1" size="1" maxlength="1" 
                        name="sexo" id="sexo" placeholder="M" value="{{ old('sexo') }}">
                <div class="control-label col-sm-2" for="sexo">&nbsp;</div>
                <label class="control-label col-sm-2" for="estado_civil">Estado civil:</label>
                <input type="text" class="form-control col-sm-1" size="1" maxlength="1" 
			name="estado_civil" id="estado_civil" placeholder="C"
                        value="{{ old('estado_civil') }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="direccion">Direccion:</label>
                <input type="text" class="form-control col-sm-6" size="30" maxlength="100" 
			name="direccion" id="direccion" placeholder="Aqui la dirección del asesor"
                        value="{{ old('direccion') }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="password">Contraseña:</label>
                <input type="password" class="form-control col-sm-2" required name="password" 
                        id=password placeholder="Mayor a 6 cars">
            </div>
            <div class="form-group d-flex">
                <button type="submit" class="btn btn-primary col-sm-4">Crear Usuario</button>
                <!-- a href="{{ action('UserController@index') }}">Regresar al listado de usuarios</a -->
                <a href="{{ url('/usuarios') }}" class="btn btn-link col-sm-4">Regresar al listado de usuarios</a>
            </div>
        </form>
        </div>
    </div>

@endsection
