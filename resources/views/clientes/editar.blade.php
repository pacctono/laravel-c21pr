@extends('layouts.app')

@section('content')
<div class="card m-0 p-0">
    <h4 class="card-header m-0 p-1">{{ $title }}</h4>
    <div class="card-body m-0 p-0">
    @include('include.errorData')

        <form method="POST" class="form align-items-end-horizontal"
            id="formulario" action="{{ url("/clientes/{$cliente->id}") }}">
            {{ method_field('PUT') }}
            {!! csrf_field() !!}
            <!-- input name="_method" type="hidden" value="PUT" -->

            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                    <label class="control-label" for="cedula">C&eacute;dula</label>
                    <input type="text" class="form-control form-control-sm" size="8"
                            maxlength="8" name="cedula" id="cedula"
                            value="{{ old('cedula', $cliente->cedula) }}">
                </div>
                <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                    <label class="control-label" for="rif">Rif</label>
                    <input class="form-control form-control-sm" type="text" size="10"
                            maxlength="10" name="rif" id="rif"
                            value="{{ old('rif', $cliente->rif) }}">
                </div>
                <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                    <label class="control-label" for="name">*Nombre</label>
                    <input type="text" class="form-control form-control-sm" size="70"
                            maxlength="150" name="name" id="name" required
                            value="{{ old('name', $cliente->name) }}">
                </div>
                <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                    <label class="control-label" for="tipo">*Tipo</label>
                    <select class="form-control form-control-sm" name="tipo" id="tipo">
                    @foreach ($tipos as $opcion => $muestra)
                    <option value="{{$opcion}}"
                    @if (old('tipo', $cliente->tipo) == $opcion)
                        selected
                    @endif
                        >{{$muestra}}</option>
                    @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row mt-1 mb-0 mx-0 p-0 bg-suave">
                <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                    <label for="telefono">Tel&eacute;fono</label>
                    0<select class="form-control form-control-sm" name="ddn" id="ddn">
                        <option value="">ddn</option>
                    @foreach ($ddns as $ddn)
                    @if (old('ddn', substr($cliente->telefono, 0, 3)) == $ddn->ddn)
                        <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                    @else
                        <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                    @endif
                    @endforeach
                    </select>
                    <input type="text" class="form-control form-control-sm" size="7"
                            maxlength="7" minlength="7" name="telefono" id="telefono"
                            value="{{ old('telefono', substr($cliente->telefono, 3)) }}">
                </div>
                <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                    <label class="control-label" for="email">Correo electr&oacute;nico</label>
                    <input class="form-control form-control-sm" type="email"
                            size="50" maxlength="150" name="email" id="email"
                            placeholder="correo_del_cliente@correo.com"
                            value="{{ old('email', $cliente->email) }}">
                </div>
                <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                    <label class="control-label" for="fecha_nacimiento">Fecha nacimiento</label>
                    <input type="date" class="form-control form-control-sm" name="fecha_nacimiento" 
                            id="fecha_nacimiento" max="{{ now('America/Caracas')->format('Y-m-d') }}"
            value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento_bd) }}">
                </div>
            </div>
            <div class="form-row bg-suave mt-0 mb-1 mx-0 p-0">
                <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                    <label class="control-label" for="otro_telefono">Otro telefono</label>
                    <input type="text" class="form-control form-control-sm"
                            size="20" maxlength="20"
                            name="otro_telefono" id="otro_telefono"
                            placeholder="Quizas internacional"
                            value="{{ old('otro_telefono', $cliente->otro_telefono) }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0">
              <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="direccion" id="etiqDirCliente">
                    Direcci&oacute;n</label>
                <textarea class="form-control form-control-sm" rows="2"
                        cols="80" maxlength="160" name="direccion" id="direccion"
                        placeholder="Calle, Casa, Apto, Edificio, Barrio, etc.">{{ old('direccion', $cliente->direccion) }}</textarea>
              </div>
            </div>

            <div class="form-row bg-suave my-1 mx-0 p-0">
              <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="observaciones" id="etiqObservaciones">
                    Observaciones</label>
                <textarea class="form-control form-control-sm" rows="2"
                        cols="95" maxlength="190" name="observaciones" id="observaciones"
                        placeholder="Observaciones que se puedan tener sobre este cliente, etc.">{{ old('observaciones', $cliente->observaciones) }}</textarea>
              </div>
            </div>

            <div class="form-row my-1 mx-1 p-0">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <button type="submit" class="btn btn-primary m-0 py-0 px-1">
                        Actualizar Cliente
                    </button>
                    <!-- a href="{{ action('ClienteController@index') }}">Regresar al listado de usuarios</a -->
                    <!-- a href="{{ url('/clientes/orden/'.$orden).$nroPagina }}" class="btn btn-link"-->
                    <a href="{{ route('clientes.orden', $orden).$nroPagina }}" class="btn btn-link">
                        Regresar al listado de clientes
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')

@includeIf("contactos.revisar")

@endsection
