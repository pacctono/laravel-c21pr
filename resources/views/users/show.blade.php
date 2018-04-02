@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Asesor: {{ $user->name }}</h4>
    <div class="card-body">
        <p>Cédula de identidad: <spam class="alert-info">{{ $user->cedula }}</spam></p>
        <p>Telefono del asesor: <spam class="alert-info">0{{ substr($user->telefono, 0, 3) }}-{{ substr($user->telefono, 3) }}</spam></p>
        <p>Correo personal del asesor: <spam class="alert-info">{{ $user->email }}</spam></p>
        <p>Fecha de nacimiento:
            <spam class="alert-info">
            @if ('' == $user->fecha_nacimiento or $user->fecha_nacimiento == null)
                {{ $user->fecha_nacimiento }}
            @else
                {{ $user->fecha_nacimiento->format('d/m/Y') }}
            @endif
            </spam>
        </p>
        <p>Correo century21 del asesor: <spam class="alert-info">{{ $user->email_c21 }}</spam></p>
        <p>Licencia MLS: <spam class="alert-info">{{ $user->licencia_mls }}</spam></p>
        <p>Fecha de ingreso:
            <spam class="alert-info">
            @if ('' == $user->fecha_ingreso or $user->fecha_ingreso == null)
                {{ $user->fecha_ingreso }}
            @else
                {{ $user->fecha_ingreso->format('d/m/Y') }}
            @endif
            </spam>
        </p>

        <p>
            <!-- a href="{{ action('UserController@index') }}">Regresar al listado de usuarios</a -->
            <a href="{{ url('/usuarios') }}" class="btn btn-link">Regresar al listado de asesores</a>
        </p>
    </div>
</div>
@endsection
