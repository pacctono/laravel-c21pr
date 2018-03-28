@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Asesor: {{ $user->name }}</h4>
    <div class="card-body">
        <p>Telefono del asesor: <spam class="alert-info">0{{ substr($user->telefono, 0, 3) }}-{{ substr($user->telefono, 3) }}</spam></p>
        <p>Correo del asesor: <spam class="alert-info">{{ $user->email }}</spam></p>

        <p>
            <!-- a href="{{ action('UserController@index') }}">Regresar al listado de usuarios</a -->
            <a href="{{ url('/usuarios') }}" class="btn btn-link">Regresar al listado de asesores</a>
        </p>
    </div>
</div>
@endsection
