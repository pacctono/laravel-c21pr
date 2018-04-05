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

        <form class="form align-items-end-horizontal" method="POST" 
                action="{{ url("/contactos/{$contacto->id}") }}">
            {{ method_field('PUT') }}
            {!! csrf_field() !!}
            <!-- input name="_method" type="hidden" value="PUT" -->

            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="cedula">Cedula:</label>
                <input type="text" class="form-control col-sm-3" size="8" maxlength="8" minlength="7" 
                        name="cedula" id="cedula" placeholder="12345678" 
                        value="{{ old('cedula', $contacto->cedula) }}">
                &nbsp;
                <label class="control-label col-sm-2" for="name">Nombre:</label>
                <input type="text" class="form-control col-sm-5" maxlength="30" required name="name" 
                        id="name" placeholder="Pedro Perez" value="{{ old('name', $contacto->name) }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="telefono">Teléfono:</label>
                <div class="form-control col-sm-3">
                    <select name="ddn" id="ddn">
                        <option value="">ddn</option>
                    @foreach ($ddns as $ddn)
                    @if (old('ddn', substr($contacto->telefono, 0, 3)) == $ddn->ddn)
                        <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                    @else
                        <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                    @endif
                    @endforeach
                    </select>
                    <input type="text" size="7" maxlength="7" minlength="7" name="telefono" 
                            id="telefono" placeholder="yyyyyyy" 
                            value="{{ old('telefono', substr($contacto->telefono, 3)) }}">
                </div>
                <label class="control-label col-sm-2" for="email">Correo electrónico:</label>
                <input type="email" class="form-control col-sm-5" maxlength="30" name="email" 
                        id="email" placeholder="pedro@example.com"
                        value="{{ old('email', $contacto->email) }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="direccion">Dirección:</label>
                <textarea class="form-control col-sm-10" cols="5" rows="4" maxlength="190" 
                    name="direccion" id="direccion" 
                    placeholder="Calle, 87749470Casa, Apto, Edificio, Barrio, Ciudad">{{ old('direccion', $contacto->direccion) }}</textarea>
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="observaciones">Observaciones:</label>
                <textarea class="form-control col-sm-10" cols="5" rows="5" maxlength="190" 
                    name="observaciones" id="observaciones" 
                    placeholder="Coloque aqui las observaciones que tuvo de la conversación con el contacto inicial.">{{ old('observaciones', $contacto->observaciones) }}</textarea>
            </div>

            <div class="form-group d-flex">
                <button type="submit" class="btn btn-primary col-sm-5">Actualizar Contacto Inicial</button>
                <!-- a href="{{ action('ContactoController@index') }}">Regresar al listado de usuarios</a -->
                <a href="{{ url('/contactos') }}" class="btn btn-link">Regresar al listado de contactos iniciales</a>
            </div>
        </form>
    </div>
</div>
@endsection
