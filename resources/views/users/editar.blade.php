@extends('layouts.app')

@section('content')
<div class="row no-gutters">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header my-1 mx-1 p-0">
                <h4 class="my-0 mx-1 p-0">{{ $title }}</h4>
            </div>
            <div class="card-body my-1 mx-0 p-0">
            @if ($errors->any())
                <div class="alert alert-danger my-1 mx-0 p-0">
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

                <div class="form-row bg-suave my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">{{-- margen(m) arriba y abajo(y) 0 y padding(p) arriba y abajo(y) 0(0) --}}
{{-- Otros valores para margen y padding: 't':tope, 'b':bottom, 'l':left, 'r':right y 'x':left y right --}}
                        <label class="control-label m-0 p-0" for="cedula">Cedula de identidad</label>
                        <input class="form-control m-0 py-0 px-1" type="text" size="8"
                                maxlength="8" minlength="7" name="cedula" id="cedula"
                                placeholder="numero de cedula"
                                value="{{ old('cedula', $user->cedula) }}">
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="name">Nombre</label>
                        <input class="form-control m-0 py-0 px-1" type="text" maxlength="30"
                                required name="name" id="name" placeholder="Nombre del asesor"
                                value="{{ old('name', $user->name) }}">
                    </div>
                </div>

                <div class="form-row bg-suave my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="telefono">
                            Tel&eacute;fono:
                        </label>
                        0<select class="form-control m-0 py-0 py-1" name="ddn" id="ddn">
                        <option value="">ddn</option>
                        @foreach ($ddns as $ddn)
                        @if (old('ddn', substr($user->telefono, 0, 3)) == $ddn->ddn)
                        <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                        @else
                        <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                        @endif
                        @endforeach
                        </select>
                        <input class="form-control m-0 py-0 px-1" type="text" size="7"
                                maxlength="7" minlength="7" required name="telefono"
                                id="telefono" placeholder="# sin area" pattern="[0-9]{7}"
                                value="{{ old('telefono', substr($user->telefono, 3)) }}">
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="email">
                            Correo electr&oacute;nico personal
                        </label>
                        <input class="form-control m-0 py-0 px-1" type="email" size="60"
                                maxlength="160" required
                                name="email" id="email" placeholder="correo electronico"
                                value="{{ old('email', $user->email) }}">
                    </div>
                </div>

                <div class="form-row bg-suave my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="fecha_nacimiento">
                            Fecha de nacimiento
                        </label>
                        <input class="form-control m-0 py-0 px-1" type="date"
                                name="fecha_nacimiento" id="fecha_nacimiento"
                                max="{{ now('America/Caracas')->format('Y-m-d') }}"
                                value="{{ old('fecha_nacimiento', ($user->fecha_nacimiento)?$user->fecha_nacimiento_bd:'') }}">
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="email_c21">
                            Correo electrónico C21
                        </label>
                        <input class="form-control m-0 py-0 px-1" type="email" size="60"
                                    maxlength="160" name="email_c21" id="email_c21"
                                    placeholder="correo electronico de Century 21"
                                    value="{{ old('email_c21', $user->email_c21) }}">
                    </div>
                </div>

                <div class="form-row bg-suave my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="licencia_mls">
                            Licencia MLS
                        </label>
                        <input class="form-control m-0 py-0 px-1" type="text" size="7"
                                minlength="5" maxlength="7" name="licencia_mls"
                                id="licencia_mls" placeholder="licencia MLS"
                                value="{{ old('licencia_mls', $user->licencia_mls) }}">
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="fecha_ingreso">
                            Fecha de ingreso
                        </label>
                        <input class="form-control m-0 py-0 px-1" type="date" name="fecha_ingreso"
                                id="fecha_ingreso" min="2015-06-01"
                                max="{{ now('America/Caracas')->format('Y-m-d') }}"
                                value="{{ old('fecha_ingreso', ($user->fecha_ingreso)?$user->fecha_ingreso_bd:'') }}">
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
                                    {{ (('F' == old('sexo', $user->sexo))?'checked':'') }}>
                            <label class="form-check-label" for="fem">Femenino</label>
                        </div>
                        <div class="form-check form-check-inline m-0 p-0">
                            <input class="form-check-input" type="radio" name="sexo" id="masc"
                                    value="M" {{ (('M' == old('sexo', $user->sexo))?'checked':'') }}>
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
                                    {{ (('C' == old('sexo', $user->estado_civil))?'checked':'') }}>
                            <label class="form-check-label" for="casado">Casado</label>
                        </div>
                        <div class="form-check form-check-inline m-0 p-0">
                            <input class="form-check-input" type="radio" name="estado_civil"
                                    id="soltero" value="S"
                                    {{ (('S' == old('sexo', $user->estado_civil))?'checked':'') }}>
                            <label class="form-check-label" for="soltero">Soltero</label>
                        </div>
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="profesion">
                            Profesi&oacute;n
                        </label>
                        <input class="form-control m-0 py-0 px-1" type="text" size="30"
                                maxlength="100" name="profesion" id="profesion"
                                value="{{ old('profesion', $user->profesion) }}">
                    </div>
                </div>

                <div class="form-row bg-suave my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="direccion">
                            Direcci&oacute;n
                        </label>
                        <input class="form-control m-0 py-0 px-1" type="text" size="30"
                                maxlength="100" name="direccion" id="direccion"
                                value="{{ old('direccion', $user->direccion) }}">
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="wa">
                            Whatsapp
                        </label>
                        <div class="form-check ml-1 mr-3" title="Copiar el numero personal del asesor">
                            <input class="form-check-input ml-0 mr-1 my-0 p-0 chequeado"
                                    type="checkbox" name="cpwa" id="cpwa"
                                    @if (($user->telefono) and ($user->wa == $user->telefono)) checked @endif>
                            <label class="form-check-label m-0 p-0" for="cpwa" style="font-size:0.75em">
                                Copiar
                            </label>
                        </div>
                        0<select class="form-control m-0 py-0 py-1" name="ddnwa" id="ddnwa">
                        @foreach ($ddns as $ddn)
                        @if (old('ddnwa', (isset($user->wa))?substr($user->wa, 0, 3):'414') == $ddn->ddn)
                        <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                        @else
                        <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                        @endif
                        @endforeach
                        </select>
                        <input class="form-control m-0 py-0 px-1" type="text" size="7"
                                maxlength="7" minlength="7" name="wa" id="wa" pattern="[0-9]{7}"
                                placeholder="# sin area" value="{{ old('wa', substr($user->wa, 3)) }}">
                    </div>
                </div>

                <div class="form-row bg-suave my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="te">
                            Telegram
                        </label>
                        <div class="form-check ml-1 mr-3" title="Copiar el numero personal del asesor">
                            <input class="form-check-input ml-0 mr-1 my-0 p-0 chequeado"
                                    type="checkbox" name="cpte" id="cpte"
                                    @if (($user->telefono) and ($user->wa == $user->telefono)) checked @endif>
                            <label class="form-check-label m-0 p-0" for="cpte" style="font-size:0.75em">
                                Copiar
                            </label>
                        </div>
                        0<select class="form-control m-0 py-0 py-1" name="ddnte" id="ddnte">
                        @foreach ($ddns as $ddn)
                        @if (old('ddnte', (isset($user->te))?substr($user->te, 0, 3):'414') == $ddn->ddn)
                        <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                        @else
                        <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                        @endif
                        @endforeach
                        </select>
                        <input class="form-control m-0 py-0 px-1" type="text" size="7"
                                maxlength="7" minlength="7" name="te" id="te" pattern="[0-9]{7}"
                                placeholder="# sin area" value="{{ old('te', substr($user->te, 3)) }}">
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="ig">
                            Instagram
                        </label>
                        <input class="form-control m-0 py-0 px-1" type="text" size="60"
                                    autocomplete="off" maxlength="160" name="ig" id="ig"
                                    placeholder="Instagram del asesor"
                                    value="{{ old('ig', $user->ig) }}">
                    </div>
                </div>

                <div class="form-row bg-suave my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="tw">
                            Twitter
                        </label>
                        <input class="form-control m-0 py-0 px-1" type="text" size="60"
                                    autocomplete="off" maxlength="160" name="tw" id="tw"
                                    placeholder="Twitter del asesor"
                                    value="{{ old('tw', $user->tw) }}">
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="fb">
                            Facebook
                        </label>
                        <input class="form-control m-0 py-0 px-1" type="text" size="60"
                                    autocomplete="off" maxlength="160" name="fb" id="fb"
                                    placeholder="Facebook del asesor"
                                    value="{{ old('fb', $user->fb) }}">
                    </div>
                </div>

                <div class="form-row bg-suave my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="password">
                            Contrase&ntilde;a
                        </label>
                        <input class="form-control m-0 py-0 px-1" type="password"
                                autocomplete="off" name="password" id=password
                                placeholder="Mayor a 6 caracteres">
                    </div>
                </div>

                <div class="form-row my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <label class="control-label m-0 p-0" for="activo">Activo</label>
                        <input type="checkbox" class="form-control m0 py-0 px-1"
                                name="activo" id="activo"
                                {{ old('activo', $user->activo) ? "checked" : "" }}>
                    </div>
                </div>

                <div class="form-row bg-suave my-1 mx-0 p-0">
                    <div class="form-group form-inline m-0 py-0 px-1">
                        <button class="btn btn-primary m-0 p-1">Actualizar Asesor</button>
                        @if (auth()->user()->is_admin)
                        {{-- <a href="{{ action('UserController@index') }}">Regresar al listado de usuarios</a> --}}
                        <a href="{{ route('users') }}" class="btn btn-link m-0 p-1">
                            Regresar al listado de asesores
                        </a>
                        @else
                        <a href="{{ route('users.show', auth()->user()->id) }}"
                                class="btn btn-link m-0 p-1">
                            Mostrar asesor
                        </a>
                        @endif
                    </div>
                </div>
            </form>
            </div><!--div class="card-body my-1 mx-0 p-0"-->
        </div><!--div class="card"-->
    </div><!--div class="col-lg-8"-->
    <div class="col-lg-4 justify-content-center bg-light">
        <a href="" class="m-0 p-0 mostrarTooltip cargarimagen"
                iduser="{{ $user->id }}" cedula="{{ $user->cedula }}" nombre="{{ $user->nombre }}"
                nombreBase="{{ substr($user->email, 0, strpos($user->email, '@')) }}"
                data-toggle="tooltip" data-html="true"
                id="foto" title="Actualizar foto del asesor(a) (<u>{{ $user->cedula_f.', '.$user->nombre }}</u>)">
        @if ($user->foto)
            <img class="img-fluid d-block mx-auto" src="{{ asset($user::DIR_PUBIMG . $user->foto) }}"
                    alt="Foto del asesor(a)" style="height:285px">
        @else ($user->foto)
            <img class="img-fluid d-block mx-auto" src="{{ asset('storage/fotos/fotoPorCrear.png') }}"
                    alt="Foto del asesor(a)" style="height:285px">
        @endif ($user->foto)
        </a>
    </div><!--div class="col-lg-4 justify-content-center bg-light"-->
</div><!--div class="row no-gutters"-->
@endsection

@if (!isset($accion) or ('html' == $accion))
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
    @includeIf("users.copiarNumero")
    })
</script>

@endsection
@endif (!isset($accion) or ('html' == $accion))
