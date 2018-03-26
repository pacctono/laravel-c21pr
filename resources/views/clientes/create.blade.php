@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Datos Personales del Cliente</h2>
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

    <form method="POST" action="{{ url('clientes') }}">
        {!! csrf_field() !!}

        <div class="row">
            <div class="form-group col-md-4">
                <label for="name">Nombre:</label>
                <input type="text" required size="30" maxlength="30" name="name" id="name" placeholder="Pedro Perez" value="{{ old('nombre') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
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
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
                <label for="email">Correo electrónico:</label>
                <input type="email" size="30" maxlength="30" name="email" id="email" placeholder="pedro@example.com" value="{{ old('email') }}">
            </div>
        </div>

        <div class="form-group col-md-4">
            <label for="direccion">Dirección:</label>
            <textarea cols="5" rows="4" maxlength="190" class="form-control" name="direccion" id="direccion" placeholder="Calle, Casa, Apto, Edificio, Barrio, Ciudad" value="{{ old('direccion') }}"></textarea>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
            <label for="deseo">Desea:</label>
                <select name="deseo_id" id="deseo">
                  <option value="">Qué desea hacer?</option>
                @foreach ($deseos as $deseo)
                  <option value="{{ $deseo->id }}">{{ $deseo->descripcion }}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
            <label for="propiedad">Propiedad:</label>
                <select name="propiedad_id" id="propiedad">
                  <option value="">Qué propiedad?</option>
                @foreach ($propiedades as $propiedad)
                  <option value="{{ $propiedad->id }}">{{ $propiedad->descripcion }}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
            <label for="zona">Zona:</label>
                <select name="zona_id" id="zona">
                  <option value="">Qué zona?</option>
                @foreach ($zonas as $zona)
                  <option value="{{ $zona->id }}">{{ $zona->descripcion }}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
            <label for="precio">Precio:</label>
                <select name="precio_id" id="precio">
                  <option value="">Qué precio?</option>
                @foreach ($precios as $precio)
                  <option value="{{ $precio->id }}">{{ $precio->descripcion }}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
            <label for="origen">Origen:</label>
                <select name="origen_id" id="origen">
                  <option value="">Cómo supo de nosotros?</option>
                @foreach ($origenes as $origen)
                  <option value="{{ $origen->id }}">{{ $origen->descripcion }}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
            <label for="resultado">Resultado:</label>
                <select name="resultado_id" id="resultado">
                  <option value="">Cuál fue el resultado?</option>
                @foreach ($resultados as $resultado)
                  <option value="{{ $resultado->id }}">{{ $resultado->descripcion }}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="form-group col-md-4">
            <label for="observaciones">Observaciones:</label>
            <textarea cols="5" rows="5" maxlength="190" class="form-control" name="observaciones" id="observaciones" placeholder="Coloque aqui las observaciones que tuvo de la conversación con el cliente." value="{{ old('observaciones') }}"></textarea>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
                <button type="submit" class="btn btn-success">Agregar cliente</button>
                <!-- a href="{{ url('/clientes') }}" class="btn btn-link">Regresar al listado de clientes</a -->
            </div>
        </div>
    </form>
</div>
@endsection