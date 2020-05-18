@extends('layouts.app')

@section('content')
<div class="row no-gutters">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header h4 m-1 p-0">
                {{ $title }}
            </div>
            <div class="card-body my-1 mx-0 p-0">
            @include('include.exitoCrear')
            @include('include.errorData')

            <form method="POST" class="form-horizontal" action="{{ url('/usuarios') }}">
                {!! csrf_field() !!}

                <div class="form-row bg-suave my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">{{-- margen(m) arriba y abajo(y) 0 y padding(p) arriba y abajo(y) 0(0) --}}
{{-- Otros valores para margen y padding: 't':tope, 'b':bottom, 'l':left, 'r':right y 'x':left y right --}}
                        <label class="control-label m-0 p-0" for="cedula">
                            Cedula de identidad
                        </label>
                        <input type="text" class="form-control m-0 py-0 px-1" size="8"
                                maxlength="8" minlength="7" name="cedula" id="cedula"
                                placeholder="numero de cedula" value="{{ old('cedula') }}">
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="name">
                            Nombre
                        </label>
                        <input type="text" class="form-control m-0 py-0 px-1" size="30"
                                maxlength="30" required name="name" id="name"
                                placeholder="Nombre del asesor" value="{{ old('name') }}">
                    </div>
                </div>

                <div class="form-row bg-suave my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">{{-- margen(m) arriba y abajo(y) 0 y padding(p) arriba y abajo(y) 0(0) --}}
                        <label class="control-label m-0 p-0" for="telefono">Teléfono</label>
                        0<select class="form-control m-0 py-0 px-1" name="ddn" id="ddn">
                            <option class="m-0 p-0" value="">ddn</option>
                        @foreach ($ddns as $ddn)
                        @if (old('ddn', '414') == $ddn->ddn)
                            <option class="m-0 p-0" value="{{ $ddn->ddn }}" selected>
                                {{ $ddn->ddn }}
                            </option>
                        @else
                            <option class="m-0 p-0" value="{{ $ddn->ddn }}">
                                {{ $ddn->ddn }}
                            </option>
                        @endif
                        @endforeach
                        </select>
                        <input type="text" class="form-control m-0 py-0 px-1" size="7"
                                maxlength="7" minlength="7" required name="telefono"
                                id="telefono" placeholder="telefono sin area"
                                value="{{ old('telefono') }}">
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="email">
                            Correo electrónico personal
                        </label>
                        <input type="email" class="form-control m-0 py-0 px-1" size="60"
                                maxlength="160" required name="email" id="email"
                                placeholder="correo electronico" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-row bg-suave my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="fecha_nacimiento">
                            Fecha de nacimiento
                        </label>
                        <input type="date" class="form-control m-0 py-0 px-1"
                                name="fecha_nacimiento" id="fecha_nacimiento"
                                max="{{ now('America/Caracas')->format('Y-m-d') }}"
                                value="{{ old('fecha_nacimiento') }}">
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="email_c21">
                            Correo electrónico C21
                        </label>
                        <input type="email" class="form-control m-0 py-0 px-1" size="60"
                                maxlength="160" name="email_c21"  id="email_c21"
                                placeholder="correo electronico de Century 21" 
                                value="{{ old('email_c21') }}">
                    </div>
                </div>

                <div class="form-row bg-suave my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="licencia_mls">
                            Licencia MLS
                        </label>
                        <input type="text" class="form-control m-0 py-0 px-1" size="7"
                                maxlength="7" minlength="5" name="licencia_mls"
                                id="licencia_mls" placeholder="numero licencia"
                                value="{{ old('licencia_mls') }}">
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="fecha_ingreso">
                            Fecha de ingreso
                        </label>
                        <input type="date" class="form-control m-0 py-0 px-1"
                                name="fecha_ingreso" id="fecha_ingreso" min="2015-06-01"
                                max="{{ now('America/Caracas')->format('Y-m-d') }}"
                                value="{{ old('fecha_ingreso') }}">
                    </div>
                </div>

                <div class="form-row bg-suave my-0 mx-0 p-0">
                    <div class="my-0 mx-5 py-0 px-0">
                        <div class="my-0 mx-3 p-0">
                            Sexo
                        </div>
                        <div class="form-check form-check-inline my-0 mx-3 p-0">
                            <input class="form-check-input" type="radio" name="sexo" id="fem"
                                    value="F"
                                    {{ (('F' == old('sexo', 'X'))?'checked':'') }}>
                            <label class="form-check-label" for="fem">Femenino</label>
                        </div>
                        <div class="form-check form-check-inline m-0 p-0">
                            <input class="form-check-input" type="radio" name="sexo" id="masc"
                                    value="M" {{ (('M' == old('sexo', 'X'))?'checked':'') }}>
                            <label class="form-check-label" for="masc">Masculino</label>
                        </div>
                    </div>
                    <div class="my-0 ml-5 mr-0 py-0 px-0">
                        <div class="my-0 mx-3 p-0">
                            Estado civil
                        </div>
                        <div class="form-check form-check-inline my-0 mx-3 p-0">
                            <input class="form-check-input" type="radio" name="estado_civil"
                                    id="casado" value="C"
                                    {{ (('C' == old('sexo', 'X'))?'checked':'') }}>
                            <label class="form-check-label" for="casado">Casado</label>
                        </div>
                        <div class="form-check form-check-inline m-0 p-0">
                            <input class="form-check-input" type="radio" name="estado_civil"
                                    id="soltero" value="S"
                                    {{ (('S' == old('sexo', 'X'))?'checked':'') }}>
                            <label class="form-check-label" for="soltero">Soltero</label>
                        </div>
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="profesion">
                            Profesi&oacute;n
                        </label>
                        <input type="text" class="form-control m-0 py-0 px-1" size="30"
                                maxlength="100" name="profesion" id="profesion"
                                placeholder="Profesión del asesor"
                                value="{{ old('profesion') }}">
                    </div>
                </div>

                <div class="form-row bg-suave my-0 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="direccion">
                            Direcci&oacute;n
                        </label>
                        <input type="text" class="form-control m-0 py-0 px-1" size="30"
                                maxlength="100" name="direccion" id="direccion"
                                placeholder="Dirección del asesor"
                                value="{{ old('direccion') }}">
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="password">
                            Contrase&ntilde;a
                        </label>
                        <input type="password" class="form-control m-0 py-0 px-1" required
                                name="password" id=password placeholder="Mayor a 6 cars">
                    </div>
                </div>

                <div class="form-row bg-suave my-0 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <button type="submit" class="btn btn-primary m-0 p-1">
                            Crear Usuario
                        </button>
                        <a href="{{ route('users') }}" class="btn btn-link m-0 p-1">
                            Regresar al listado de usuarios
                        </a>
                    </div>
                </div>
            </form>
            </div><!--div class="card-body my-1 mx-0 p-0"-->
        </div><!--div class="card"-->
    </div><!--div class="col-lg-8"-->
    <div class="col-lg-4 justify-content-center bg-light">
        {{--<a href="" class="m-0 p-0 mostrarTooltip cargarimagen"
                iduser="{{ $user->id }}" cedula="{{ $user->cedula }}" nombre="{{ $user->nombre }}"
                nombreBase="{{ substr($user->email, 0, strpos($user->email, '@')) }}"
                data-toggle="tooltip" data-html="true"
                title="Actualizar foto del asesor(a) (<u>{{ $user->cedula_f.', '.$user->nombre }}</u>)">--}}
        <img class="img-fluid d-block mx-auto" src="{{ asset('storage/fotos/fotoPorCrear.jpg') }}"
                alt="Foto del asesor(a)" style="height:285px">
        {{--</a>--}}
    </div><!--div class="col-lg-4 justify-content-center bg-light"-->
</div><!--div class="row no-gutters"-->
@endsection

{{--@if (!isset($accion) or ('html' == $accion))
@section('js')

<script>
    $(function () {
        $(".mostrarTooltip").tooltip('enable')
    });
    @includeIf('include.alertar')
    @includeIf('include.confirmar')
    @includeIf('include.botonesDialog')

    $(document).ready(function() {
    @includeIf("users.cargarFoto")
    })
</script>

@endsection
@endif (!isset($accion) or ('html' == $accion))--}}
