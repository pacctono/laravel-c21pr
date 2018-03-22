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

    <form method="POST" action="{{ url('/home') }}">
        {!! csrf_field() !!}

        <div class="row">
            <div class="form-group col-md-4">
                <label for="nombre">Nombre:</label>
                <input type="text" required size="30" maxlength="30" name="nombre" id="nombre" placeholder="Pedro Perez" value="{{ old('nombre') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
                <label for="telefono-1">Teléfono:</label>
                <input type="text" size="3" maxlength="3" name="telefono-1" id="telefono-1" placeholder="4xx" value="{{ old('telefono-1') }}">
                <input type="text" size="7" maxlength="7" name="telefono-2" id="telefono-2" placeholder="1234567" value="{{ old('telefono-2') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
                <label for="email">Correo electrónico:</label>
                <input type="email" size="30" maxlength="30" name="email" id="email" placeholder="pedro@example.com" value="{{ old('email') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-4">
                <label for="direccion-1">Dirección:</label>
                <input type="text" size="30" maxlength="30" name="direccion-1" id="direccion-1" placeholder="Edificio, apartamento o casa" value="{{ old('direccion-1') }}">
                <input type="text" size="30" maxlength="30" name="direccion-2" id="direccion-2" placeholder="Urbanización, Ciudad" value="{{ old('direccion-2') }}">
            </div>
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