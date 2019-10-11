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
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Cédula de identidad:
                <span class={{ $clase }}>{{ $user->cedula_f }}</span>
            </div>
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Telefono del asesor:
                <span class={{ $clase }}>{{ $user->telefono_f }}</span>
            </div>
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Correo personal del asesor:
                <span class={{ $clase }}>{{ $user->email }}</span>
            </div>
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Fecha de nacimiento:
            @if ('' == $user->fecha_nacimiento or is_null($user->fecha_nacimiento))
                &nbsp;
            @else
            <span class={{ $clase }}>
                {{ $user->fecha_nacimiento_en }}
                ({{ $user->edad }} años)
            </span>
            @endif
            </div>
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Correo century21 del asesor:
                <span class={{ $clase }}>{{ $user->email_c21 }}</span>
            </div>
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Licencia MLS:
                <span class={{ $clase }}>{{ $user->licencia_mls }}</span>
            </div>
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Fecha de ingreso:
            @if ('' == $user->fecha_ingreso or is_null($user->fecha_ingreso))
                &nbsp;
            @else
            <span class={{ $clase }}>
                {{ $user->fecha_ingreso_en }}
                ({{ $user->Tiempo_servicio }})
            </span>
            @endif
            </div>
        </div>
	    <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Sexo:
                <span class={{ $clase }}>{{ $user->genero }}</span>
                Estado civil:
                <span class={{ $clase }}>{{ $user->edocivil }}</span>
            </div>
        </div>
    	<div class="row my-1 py-1">
            <div class="mx-1 px-2">
                            
        @if ($user->profesion)
                Profesión:
                <span class={{ $clase }}>{{ $user->profesion }}</span>
        @endif
            </div>
        </div>
    	<div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Dirección:
                <span class={{ $clase }}>{{ $user->direccion }}</span>
            </div>
        </div>
        @if (null != $fechaUltLogin)
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Fecha del último login:
                <span class={{ $clase }}>{{ $fechaUltLogin }}</span>
            </div>
        </div>
        @endif

        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                            
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
            </div>
        </div>
    </div>
</div>
@endsection
