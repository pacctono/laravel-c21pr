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

        <form method="POST" class="form-horizontal" action="{{ url('/usuarios') }}">
            {!! csrf_field() !!}

            <div class="form-group form-inline row bg-suave my-0 py-0">  {{-- margen(m) arriba y abajo(y) 0 y padding(p) arriba y abajo(y) 0(0) --}}
{{-- Otros valores para margen y padding: 't':tope, 'b':bottom, 'l':left, 'r':right y 'x':left y right --}}
                <label class="control-label" for="cedula">Cedula de identidad</label>
                <input type="text" class="form-control col-sm-2" size="8" maxlength="8" minlength="7" 
                        name="cedula" id="cedula" placeholder="numero de cedula" value="{{ old('cedula') }}">
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <label class="control-label" for="name">Nombre</label>
                <input type="text" class="form-control col-sm-5" size="30" maxlength="30" 
                        required name="name" id="name" placeholder="Nombre del asesor" value="{{ old('name') }}">
            </div>

            <div class="form-group form-inline row bg-suave my-0 py-0">
                <label class="control-label" for="telefono">Teléfono</label>
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
                        required name="telefono" id="telefono" placeholder="telefono sin area" 
                        value="{{ old('telefono') }}">
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <label class="control-label" for="email">Correo electrónico personal</label>
                <input type="email" class="form-control col-sm-5" size="100" maxlength="160" required 
                        name="email" id="email" placeholder="correo electronico" value="{{ old('email') }}">
            </div>

            <div class="form-group form-inline row bg-suave my-0 py-0">
                <label class="control-label" for="fecha_nacimiento">Fecha de nacimiento</label>
                <input type="date" class="form-control col-sm-2" name="fecha_nacimiento" 
                        id="fecha_nacimiento" max="{{ now()->format('Y-m-d') }}"
                        value="{{ old('fecha_nacimiento') }}">
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <label class="control-label" for="email_c21">Correo electrónico C21</label>
                <input type="email" class="form-control col-sm-5" size="100" maxlength="160" 
                        name="email_c21"  id="email_c21" placeholder="correo electronico de Century 21" 
                        value="{{ old('email_c21') }}">
            </div>

            <div class="form-group form-inline row bg-suave my-0 py-0">
                <label class="control-label" for="licencia_mls">Licencia MLS</label>
                <input type="text" class="form-control col-sm-2" size="7" maxlength="7"
                    minlength="5" name="licencia_mls" id="licencia_mls" placeholder="numero licencia"
                    value="{{ old('licencia_mls') }}">
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <label class="control-label" for="fecha_ingreso">Fecha de ingreso</label>
                <input type="date" class="form-control col-sm-3" name="fecha_ingreso" 
                        id="fecha_ingreso" min="2015-06-01" max="{{ now()->format('Y-m-d') }}"
                        value="{{ old('fecha_ingreso') }}">
            </div>

            <div class="form-row bg-suave my-0 py-0 form-inline">
                <div class="col-lg-1">
                    Sexo
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sexo" id="fem"
                            value="F" {{ (('F' == old('sexo', 'X'))?'checked':'') }}>
                    <label class="form-check-label" for="fem">Femenino</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sexo" id="masc"
                            value="M" {{ (('M' == old('sexo', 'X'))?'checked':'') }}>
                    <label class="form-check-label" for="masc">Masculino</label>
                </div>
                <div class="col-lg-2">
                    Estado civil
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="estado_civil" id="casado"
                            value="C" {{ (('C' == old('estado_civil', 'X'))?'checked':'') }}>
                    <label class="form-check-label" for="casado">Casado</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="estado_civil" id="soltero"
                            value="S" {{ (('S' == old('estado_civil', 'X'))?'checked':'') }}>
                    <label class="form-check-label" for="soltero">Soltero</label>
                </div>
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <label class="control-label" for="profesion">Profesi&oacute;n</label>
                <input type="text" class="form-control col-sm-6" size="30" maxlength="100" 
			            name="profesion" id="profesion"
                        placeholder="Profesión del asesor"
                        value="{{ old('profesion') }}">
            </div>

            <div class="form-group form-inline row bg-suave my-0 py-0">
                <label class="control-label" for="direccion">Direcci&oacute;n</label>
                <input type="text" class="form-control col-sm-6" size="30" maxlength="100" 
			            name="direccion" id="direccion"
                        placeholder="Dirección del asesor"
                        value="{{ old('direccion') }}">
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <label class="control-label" for="password">Contrase&ntilde;a</label>
                <input type="password" class="form-control col-sm-2" required name="password" 
                        id=password placeholder="Mayor a 6 cars">
            </div>

            <div class="form-group form-inline row my-0 py-0">
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
                <a href="{{ route('users') }}" class="btn btn-link">Regresar al listado de usuarios</a>
            </div>
        </form>
        </div>
    </div>

@endsection
