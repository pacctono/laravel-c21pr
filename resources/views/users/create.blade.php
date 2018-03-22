@extends('layouts.app')

@section('content')
    <div class="card">
        <h4 class="card-header">Crear asesor</h4>
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

            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Pedro Perez" value="{{ old('name') }}">
                <small id="nameHelp" class="form-text text-muted">Por favor, coloque el nombre y apellido.</small>

            </div>

            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="pedro@example.com" value="{{ old('email') }}">
                <small id="nameHelp" class="form-text text-muted">Nunca comparta su correo electrónico.</small>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" class="form-control" id=password placeholder="Mayor a 6 caracteres">
            </div>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
            <!-- a href="{{ action('UserController@index') }}">Regresar al listado de usuarios</a -->
            <a href="{{ url('/usuarios') }}" class="btn btn-link">Regresar al listado de usuarios</a>
        </form>
        </div>
    </div>

@endsection