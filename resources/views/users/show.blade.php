@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Asesor: {{ $user->name }}</h4>
    <div class="card-body">
        <p>Cédula de identidad: <spam class="alert-info">{{ $user->cedula_f }}</spam></p>
        <p>Telefono del asesor: <spam class="alert-info">{{ $user->telefono_f }}</spam></p>
        <p>Correo personal del asesor: <spam class="alert-info">{{ $user->email }}</spam></p>
        <p>Fecha de nacimiento:
            @if ('' == $user->fecha_nacimiento or $user->fecha_nacimiento == null)
                &nbsp;
            @else
            <spam class="alert-info">
                {{ $user->fecha_nacimiento_en }}
                ({{ $user->edad }} años)
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
                ({{ $user->Tiempo_servicio }})
            </spam>
            @endif
        </p>
	    <p>
            Sexo: <spam class="alert-info">{{ $user->genero }}</spam>
            Estado civil: <spam class="alert-info">{{ $user->edocivil }}</spam>
        </p>
    	<p>
            Profesión: <spam class="alert-info">{{ $user->profesion }}</spam>
        </p>
    	<p>
            Dirección: <spam class="alert-info">{{ $user->direccion }}</spam>
        </p>
        @if (null != $fechaUltLogin)
        <p>Fecha del último login:
            <spam class="alert-info">
                {{ $fechaUltLogin }}
            </spam>
        </p>
        @endif

        <p>
            @if (auth()->user()->is_admin)
            {{-- <a href="{{ action('UserController@index') }}">Regresar al listado de usuarios</a> --}}
            <a href="{{ url('/usuarios') }}" class="btn btn-link">
                Regresar
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
