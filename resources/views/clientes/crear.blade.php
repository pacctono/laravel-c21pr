@extends('layouts.app')

@section('content')
<div class="card m-0 p-0">
    <h4 class="card-header m-0 p-1">{{ $title }}</h4>
    <div class="card-body m-0 p-0">
    @include('include.exitoCrear')
    @include('include.errorData')
        
    <form method="POST" class="form align-items-end-horizontal"
        id="formulario" action="{{ url('clientes') }}">
        {!! csrf_field() !!}

        <div class="form-row my-1 mx-0 p-0">
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="cedula">C&eacute;dula</label>
                <input type="text" class="form-control form-control-sm" size="8"
                        maxlength="8" name="cedula" id="cedula"
                        placeholder="Cedula Id"
                        title="Cedula de identidad del cliente"
                        value="{{ old('cedula') }}">
            </div>
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="rif">Rif</label>
                <input class="form-control form-control-sm" type="text" size="10"
                        maxlength="10" name="rif" id="rif"
                        placeholder="10 car's rif"
                        title="Numero de rif del cliente, el primer caracter deberia ser una letra"
                        value="{{ old('rif') }}">
            </div>
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="name">*Nombre</label>
                <input type="text" class="form-control form-control-sm" size="70"
                        maxlength="150" name="name" id="name" required
                        placeholder="Nombre del cliente"
                        title="Nombre del cliente"
                        value="{{ old('name') }}">
            </div>
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="tipo">*Tipo</label>
                <select class="form-control form-control-sm" name="tipo" id="tipo">
                @foreach ($tipos as $opcion => $muestra)
                  <option value="{{$opcion}}"
                  @if (old('tipo', $tipoXDef) == $opcion)
                    selected
                  @endif
                    >{{$muestra}}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="form-row bg-suave mt-1 mb-0 mx-0 p-0">
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label for="telefono">Tel&eacute;fono</label>
                0<select class="form-control form-control-sm" name="ddn" id="ddn">
                    <option value="">ddn</option>
                @foreach ($ddns as $ddn)
                @if (old('ddn', '414') == $ddn->ddn)
                    <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                @else
                    <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                @endif
                @endforeach
                </select>
                <input type="text" class="form-control form-control-sm" size="7"
                        maxlength="7" minlength="7" name="telefono" id="telefono"
                        value="{{ old('telefono') }}">
            </div>
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="email">Correo electr&oacute;nico</label>
                <input class="form-control form-control-sm" type="email"
                        size="50" maxlength="150" name="email" id="email"
                        placeholder="correo_del_cliente@correo.com"
                        title="correo electronico del cliente"
                        value="{{ old('email') }}">
            </div>
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="fecha_nacimiento">Fecha nacimiento</label>
                <input type="date" class="form-control form-control-sm" name="fecha_nacimiento" 
                        id="fecha_nacimiento" max="{{ now('America/Caracas')->format('Y-m-d') }}"
                        value="{{ old('fecha_nacimiento') }}">
            </div>
        </div>
        <div class="form-row bg-suave mt-0 mb-1 mx-0 p-0">
          <div class="form-group form-inline my-0 mx-2 py-0 px-1">
            <label class="control-label" for="otro_telefono">Otro telefono</label>
            <input type="text" class="form-control form-control-sm"
                    size="20" maxlength="20"
                    name="otro_telefono" id="otro_telefono"
                    placeholder="Quizas internacional"
                    value="{{ old('otro_telefono') }}">
          </div>
        </div>

        <div class="form-row my-1 mx-0 p-0">
          <div class="form-group form-inline my-0 mx-2 py-0 px-1">
            <label class="control-label" for="direccion" id="etiqDirCliente">
                Direcci&oacute;n</label>
            <textarea class="form-control form-control-sm" rows="2"
                    cols="80" maxlength="160" name="direccion" id="direccion"
                    placeholder="Calle, Casa, Apto, Edificio, Barrio, etc.">{{ old('direccion') }}</textarea>
          </div>
        </div>

        <div class="form-row bg-suave my-1 mx-0 p-0">
          <div class="form-group form-inline my-0 mx-2 py-0 px-1">
            <label class="control-label" for="observaciones" id="etiqObservaciones">
                Observaciones</label>
            <textarea class="form-control form-control-sm" rows="2"
                    cols="95" maxlength="190" name="observaciones" id="observaciones"
                    placeholder="Observaciones que se puedan tener sobre este cliente, etc.">{{ old('observaciones') }}</textarea>
          </div>
        </div>

        <div class="form-row my-1 mx-1 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <button type="submit" class="btn btn-success m-0 py-0 px-1">
                    Agregar Cliente
                </button>
                <!--a href="{{ url('/clientes/orden/'.$orden).$nroPagina }}" class="btn btn-link"-->
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

@includeIf("clientes.revisar")

@endsection
