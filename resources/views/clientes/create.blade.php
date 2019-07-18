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

    <form method="POST" class="form-horizontal" action="{{ url('clientes') }}">
        {!! csrf_field() !!}

        <div class="row">
            <div class="form-group d-flex">
                <label for="cedula">Cedula:</label>
                <input type="number" size="8" maxlength="8" minlength="7" name="name" id="name" placeholder="12345678" value="{{ old('cedula') }}">
                &nbsp;
                <label for="name">Nombre:</label>
                <input type="text" required size="30" maxlength="30" name="name" id="name" placeholder="Pedro Perez" value="{{ old('name') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label for="telefono">Teléfono:</label>
                <select name="ddn" id="ddn">
                  <option value="">ddn</option>
                @foreach ($ddns as $ddn)
                @if (old('ddn', '414') == $ddn->ddn)
                  <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                @else
                  <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                @endif
                @endforeach
                </select>
                <input type="text" size="7" maxlength="7" name="telefono" id="telefono" placeholder="1234567" value="{{ old('telefono') }}">
                &nbsp;
                <label for="email">Correo electrónico:</label>
                <input type="email" size="30" maxlength="30" name="email" id="email" placeholder="pedro@example.com" value="{{ old('email') }}">
            </div>
        </div>

        <div class="form-group d-flex">
            <label for="direccion">Dirección:</label>
            <textarea cols="5" rows="4" maxlength="190" class="form-control" name="direccion" id="direccion" placeholder="Calle, Casa, Apto, Edificio, Barrio, Ciudad">{{ old('direccion') }}</textarea>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label for="deseo">Desea:</label>
                <select name="deseo_id" id="deseo">
                  <option value="">Qué desea hacer?</option>
                @foreach ($deseos as $deseo)
                @if (old('deseo_id') == $deseo->id)
                  <option value="{{ $deseo->id }}" selected>{{ $deseo->descripcion }}</option>
                @else
                  <option value="{{ $deseo->id }}">{{ $deseo->descripcion }}</option>
                @endif
                @endforeach
                </select>
                &nbsp;
                <label for="tipo">Tipo:</label>
                <select name="tipo_id" id="tipo_id">
                  <option value="">Qué tipo?</option>
                @foreach ($tipos as $tipo)
                @if (old('tipo_id') == $tipo->id)
                  <option value="{{ $tipo->id }}" selected>{{ $tipo->descripcion }}</option>
                @else
                  <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                @endif
                @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
            <label for="zona">Zona:</label>
                <select name="zona_id" id="zona">
                  <option value="">Qué zona?</option>
                @foreach ($zonas as $zona)
                @if (old('zona_id') == $zona->id)
                  <option value="{{ $zona->id }}" selected>{{ $zona->descripcion }}</option>
                @else
                  <option value="{{ $zona->id }}">{{ $zona->descripcion }}</option>
                @endif
                @endforeach
                </select>
                &nbsp;
                <label for="precio">Precio:</label>
                <select name="precio_id" id="precio">
                  <option value="">Qué precio?</option>
                @foreach ($precios as $precio)
                @if (old('precio_id') == $precio->id)
                  <option value="{{ $precio->id }}" selected>{{ $precio->descripcion }}</option>
                @else
                  <option value="{{ $precio->id }}">{{ $precio->descripcion }}</option>
                @endif
                @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
            <label for="origen">Origen:</label>
                <select name="origen_id" id="origen">
                  <option value="">Cómo supo de nosotros?</option>
                @foreach ($origenes as $origen)
                @if (old('origen_id') == $origen->id)
                  <option value="{{ $origen->id }}" selected>{{ $origen->descripcion }}</option>
                @else
                  <option value="{{ $origen->id }}">{{ $origen->descripcion }}</option>
                @endif
                @endforeach
                </select>
                &nbsp;
                <label for="resultado">Resultado:</label>
                <select name="resultado_id" id="resultado">
                  <option value="">Cuál fue el resultado?</option>
                @foreach ($resultados as $resultado)
                @if (old('resultado_id') == $resultado->id)
                  <option value="{{ $resultado->id }}" selected>{{ $resultado->descripcion }}</option>
                @else
                  <option value="{{ $resultado->id }}">{{ $resultado->descripcion }}</option>
                @endif
                @endforeach
                </select>
                &nbsp; {{-- Javascript: La fecha es requerida, cuando el resultado es una cita o llamada --}}
                <input type="date" name="fecha_evento" id="fecha_evento"
                        min="{{ now()->format('d/m/Y') }}" max="{{ now()->addWeeks(4)->format('d/m/Y') }}"
                        value="{{ old('fecha_evento') }}">
                <input type="time" name="hora_evento" id="hora_evento" value="{{ old('hora_evento') }}">
            </div>
        </div>

        <div class="form-group d-flex">
            <label for="observaciones">Observaciones:</label>
            <textarea cols="5" rows="5" maxlength="190" class="form-control" name="observaciones" id="observaciones" placeholder="Coloque aqui las observaciones que tuvo de la conversación con el cliente.">{{ old('observaciones') }}</textarea>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <button type="submit" class="btn btn-success">Agregar cliente</button>
                <a href="{{ url('/clientes') }}" class="btn btn-link">Regresar al listado de contactos iniciales</a>
            </div>
        </div>
    </form>
    </div>
</div>
@endsection
