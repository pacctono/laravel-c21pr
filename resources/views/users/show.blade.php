@extends('layouts.app')

@section('content')
    <div class="card">
        <h4 class="card-header">Asesor: {{ $user->name }}</h4>
        <div class="card-body">
            <p>Correo del asesor: {{ $user->email }}</p>

            <p>
                <!-- a href="{{ action('UserController@index') }}">Regresar al listado de usuarios</a -->
                <a href="{{ url('/usuarios') }}" class="btn btn-link">Regresar al listado de asesores</a>
            </p>
        </div>
    </div>
@endsection