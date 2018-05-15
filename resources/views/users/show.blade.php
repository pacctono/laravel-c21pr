@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Asesor: {{ $user->name }}</h4>
    <div class="card-body">
        <p>Cédula de identidad: <spam class="alert-info">{{ $user->cedula }}</spam></p>
        <p>Telefono del asesor: <spam class="alert-info">0{{ substr($user->telefono, 0, 3) }}-{{ substr($user->telefono, 3) }}</spam></p>
        <p>Correo personal del asesor: <spam class="alert-info">{{ $user->email }}</spam></p>
        <p>Fecha de nacimiento:
            @if ('' == $user->fecha_nacimiento or $user->fecha_nacimiento == null)
                &nbsp;
            @else
            <spam class="alert-info">
                {{ $user->fecha_nacimiento_en }}
            </spam>
            @endif
        </p>
        <p>Correo century21 del asesor: <spam class="alert-info">{{ $user->email_c21 }}</spam></p>
        <p>Licencia MLS: <spam class="alert-info">{{ $user->licencia_mls }}</spam></p>
        <p>Fecha de ingreso:
            @if ('' == $user->fecha_ingreso or $user->fecha_ingreso == null)
                &nbsp;
            @else
            <spam class="alert-info">
                {{ $user->fecha_ingreso_en }}
            </spam>
            @endif
        </p>
        @if (null != $fechaUltLogin)
        <p>Fecha del último login:
            <spam class="alert-info">
                {{ $fechaUltLogin->timezone('America/Caracas')->format('d/m/Y H:i (h:i a)') }}
            </spam>
        </p>
        @endif

        <p>
            @if (auth()->user()->is_admin)
            <!-- a href="{{ action('UserController@index') }}">Regresar al listado de usuarios</a -->
            <a href="{{ url('/usuarios') }}" class="btn btn-link">
                Regresar al listado de asesores
            </a>
            @else
            <a href="{{ route('users.edit', auth()->user()->id) }}" class="btn btn-link">
                Editar asesor
            </a>
            @endif
        </p>
    </div>
</div>
@endsection
