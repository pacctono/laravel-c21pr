@extends('layouts.app')

@section('content')
    <div class="card">
        <h4 class="card-header">Editar cliente</h4>
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

            <form method="POST" action="{{ url("/clientes/{$cliente->id}") }}">
                {{ method_field('PUT') }}
                {!! csrf_field() !!}
                <!-- input name="_method" type="hidden" value="PUT" -->

                <label for="name">Nombre:</label>
                <input type="text" maxlength="30" required name="name" id="name" placeholder="Pedro Perez" value="{{ old('name', $cliente->name) }}">
                <br>

                <label for="telefono">Teléfono:</label>
                <select name="ddn" id="ddn">
                  <option value="">ddn</option>
                @foreach ($ddns as $ddn)
                @if (old('ddn', substr($cliente->telefono, 0, 3)) == $ddn->ddn)
                  <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                @else
                  <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                @endif
                @endforeach
                </select>
                <input type="text" size="7" maxlength="7" minlength="10" name="telefono" id="telefono" placeholder="xxxyyyyyyy" value="{{ old('telefono', substr($cliente->telefono, 3)) }}">
                <br>

                <label for="email">Correo electrónico:</label>
                <input type="email" maxlength="30" name="email" id="email" placeholder="pedro@example.com" value="{{ old('email', $cliente->email) }}">
                <br>

                <label for="direccion">Dirección:</label>
                <textarea cols="5" rows="4" maxlength="190" class="form-control" name="direccion" id="direccion" placeholder="Calle, Casa, Apto, Edificio, Barrio, Ciudad">{{ old('direccion', $cliente->direccion) }}</textarea>
                <br>

                <label for="observaciones">Observaciones:</label>
                <textarea cols="5" rows="5" maxlength="190" class="form-control" name="observaciones" id="observaciones" placeholder="Coloque aqui las observaciones que tuvo de la conversación con el cliente.">{{ old('observaciones', $cliente->observaciones) }}</textarea>

                <button class="btn btn-primary">Actualizar Cliente</button>
                <!-- a href="{{ action('ClienteController@index') }}">Regresar al listado de usuarios</a -->
                <a href="{{ url('/clientes') }}" class="btn btn-link">Regresar al listado de clientes</a>
            </form>
        </div>
    </div>
@endsection