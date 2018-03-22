@extends('layouts.app')

@section('content')
    <div class="card">
        <h4 class="card-header">Editar asesor</h4>
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

            <form method="POST" action="{{ url("/usuarios/{$user->id}") }}">
                {{ method_field('PUT') }}
                {!! csrf_field() !!}
                <!-- input name="_method" type="hidden" value="PUT" -->

                <label for="name">Nombre:</label>
                <input type="text" name="name" id="name" placeholder="Pedro Perez" value="{{ old('name', $user->name) }}">
                <br>
                <label for="email">Correo electrónico:</label>
                <input type="email" name="email" id="email" placeholder="pedro@example.com" value="{{ old('email', $user->email) }}">
                <br>
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id=password placeholder="Mayor a 6 caracteres">
                <br>
                <button class="btn btn-primary">Actualizar Asesor</button>
                <!-- a href="{{ action('UserController@index') }}">Regresar al listado de usuarios</a -->
                <a href="{{ url('/usuarios') }}" class="btn btn-link">Regresar al listado de asesores</a>
            </form>
        </div>
    </div>
@endsection