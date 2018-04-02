@extends('layouts.app')

@section('content')
<div class="card col-md-8">
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

        <form method="POST" action="{{ url("/contactos/{$contacto->id}") }}">
            {{ method_field('PUT') }}
            {!! csrf_field() !!}
            <!-- input name="_method" type="hidden" value="PUT" -->

            <div class="form-group d-flex align-items-end">
                <label for="name">Nombre:</label>
                <input type="text" maxlength="30" required name="name" id="name" placeholder="Pedro Perez" value="{{ old('name', $contacto->name) }}">
            </div>

            <div class="form-group d-flex align-items-end">
                <label for="telefono">Teléfono:</label>
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
                <input type="text" size="7" maxlength="7" minlength="7" name="telefono" id="telefono" placeholder="xxxyyyyyyy" value="{{ old('telefono', substr($contacto->telefono, 3)) }}">
            </div>

            <div class="form-group d-flex align-items-end">
                <label for="email">Correo electrónico:</label>
                <input type="email" maxlength="30" name="email" id="email" placeholder="pedro@example.com" value="{{ old('email', $contacto->email) }}">
            </div>

            <div class="form-group d-flex align-items-end">
                <label for="direccion">Dirección:</label>
                <textarea cols="5" rows="4" maxlength="190" class="form-control" name="direccion" id="direccion" placeholder="Calle, Casa, Apto, Edificio, Barrio, Ciudad">{{ old('direccion', $contacto->direccion) }}</textarea>
            </div>

            <div class="form-group d-flex align-items-end">
                <label for="observaciones">Observaciones:</label>
                <textarea cols="5" rows="5" maxlength="190" class="form-control" name="observaciones" id="observaciones" placeholder="Coloque aqui las observaciones que tuvo de la conversación con el contacto inicial.">{{ old('observaciones', $contacto->observaciones) }}</textarea>
            </div>

            <div class="form-group d-flex align-items-end">
                <button class="btn btn-primary">Actualizar Contacto Inicial</button>
                <!-- a href="{{ action('ContactoController@index') }}">Regresar al listado de usuarios</a -->
                <a href="{{ url('/contactos') }}" class="btn btn-link">Regresar al listado de contactos iniciales</a>
            </div>
        </form>
    </div>
</div>
@endsection
