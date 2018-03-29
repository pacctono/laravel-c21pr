@extends('layouts.app')

@section('content')
    <div class="card col-8">
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

        <form method="POST" action="{{ url('/usuarios') }}">
            {!! csrf_field() !!}

            <div class="form-group d-flex align-items-end">
                <label for="name">Nombre:</label>
                <input type="text" maxlength="30" required name="name" class="form-control" id="name" placeholder="Pedro Perez" value="{{ old('name') }}">
            </div>

            <div class="form-group d-flex align-items-end">
                <label for="telefono">Telefono:</label>
                0<input type="text" size="10" maxlength="10" minlength="10" required name="telefono" class="form-control" id="telefono" placeholder="xxxyyyyyyy" value="{{ old('telefono') }}">
            </div>

            <div class="form-group d-flex align-items-end">
                <label for="email">Correo electrónico:</label>
                <input type="email" maxlength="30" required name="email" class="form-control" id="email" placeholder="pedro@example.com" value="{{ old('email') }}">
            </div>

            <div class="form-group d-flex align-items-end">
                <label for="password">Contraseña:</label>
                <input type="password" required name="password" class="form-control" id=password placeholder="Mayor a 6 caracteres">
            </div>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
            <!-- a href="{{ action('UserController@index') }}">Regresar al listado de usuarios</a -->
            <a href="{{ url('/usuarios') }}" class="btn btn-link">Regresar al listado de usuarios</a>
        </form>
        </div>
    </div>

@endsection