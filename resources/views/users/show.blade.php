@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">
        @if ($user->activo)
            <?php $clase = $clase="alert-info"; ?>
        @else
            <?php $clase = $clase="table-danger"; ?>
        @endif
        Asesor: {{ $user->name }}
        @if (!$user->activo)
            @if ('F' == $user->sexo)
                [Asesora Inactiva]
            @else
                [Asesor Inactivo]
            @endif
        @endif
    </h4>
    <div class="card-body">
        <p>Cédula de identidad: <span class={{ $clase }}>{{ $user->cedula_f }}</span></p>
        <p>Telefono del asesor: <span class={{ $clase }}>{{ $user->telefono_f }}</span></p>
        <p>Correo personal del asesor: <span class={{ $clase }}>{{ $user->email }}</span></p>
        <p>Fecha de nacimiento:
            @if ('' == $user->fecha_nacimiento or $user->fecha_nacimiento == null)
                &nbsp;
            @else
            <span class={{ $clase }}>
                {{ $user->fecha_nacimiento_en }}
                ({{ $user->edad }} años)
            </span>
            @endif
        </p>
        <p>Correo century21 del asesor: <span class={{ $clase }}>{{ $user->email_c21 }}</span></p>
        <p>Licencia MLS: <span class={{ $clase }}>{{ $user->licencia_mls }}</span></p>
        <p>Fecha de ingreso:
            @if ('' == $user->fecha_ingreso or $user->fecha_ingreso == null)
                &nbsp;
            @else
            <span class={{ $clase }}>
                {{ $user->fecha_ingreso_en }}
                ({{ $user->Tiempo_servicio }})
            </span>
            @endif
        </p>
	    <p>
            Sexo: <span class={{ $clase }}>{{ $user->genero }}</span>
            Estado civil: <span class={{ $clase }}>{{ $user->edocivil }}</span>
        </p>
    	<p>
        @if ($user->profesion)
            Profesión: <span class={{ $clase }}>{{ $user->profesion }}</span>
        @endif
        </p>
    	<p>
            Dirección: <span class={{ $clase }}>{{ $user->direccion }}</span>
        </p>
        @if (null != $fechaUltLogin)
        <p>Fecha del último login:
            <span class={{ $clase }}>
                {{ $fechaUltLogin }}
            </span>
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
