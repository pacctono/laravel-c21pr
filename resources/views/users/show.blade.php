@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header my-1 mx-1 p-0">
        <h4 class="my-0 mx-1 p-0">
            @if ($user->activo)
                <?php $clase = "alert-info"; ?>
            @else
                <?php $clase = "table-danger"; ?>
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
    </div>
    <div class="card-body m-0 p-0">
        <div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                Cédula de identidad:
                <span class="{{ $clase }} m-0 p-0">{{ $user->cedula_f }}</span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                Telefono del asesor:
                <span class="{{ $clase }} m-0 p-0">{{ $user->telefono_f }}</span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                Correo personal del asesor:
                <span class="{{ $clase }} m-0 p-0">{{ $user->email }}</span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                Fecha de nacimiento:
            @if ('' == $user->fecha_nacimiento or is_null($user->fecha_nacimiento))
                &nbsp;
            @else
            <span class="{{ $clase }} m-0 p-0">
                {{ $user->fec_nac }}
                ({{ $user->edad }} años)
            </span>
            @endif
            </div>
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                Correo century21 del asesor:
                <span class="{{ $clase }} m-0 p-0">{{ $user->email_c21 }}</span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                Licencia MLS:
                <span class="{{ $clase }} m-0 p-0">{{ $user->licencia_mls }}</span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                Fecha de ingreso:
            @if ('' == $user->fecha_ingreso or is_null($user->fecha_ingreso))
                &nbsp;
            @else
            <span class="{{ $clase }} m-0 p-0">
                {{ $user->fec_ing }}
                ({{ $user->Tiempo_servicio }})
            </span>
            @endif
            </div>
        </div>
	    <div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                Sexo:
                <span class="{{ $clase }} m-0 p-0">{{ $user->genero }}</span>
                Estado civil:
                <span class="{{ $clase }} m-0 p-0">{{ $user->edocivil }}</span>
            </div>
        </div>
        @if ($user->profesion)
    	<div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                Profesión:
                <span class="{{ $clase }} m-0 p-0">{{ $user->profesion }}</span>
            </div>
        </div>
        @endif
    	<div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                Dirección:
                <span class="{{ $clase }} m-0 p-0">{{ $user->direccion }}</span>
            </div>
        </div>
    	<div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                Lados:
                <span class="{{ $clase }} m-0 p-0">{{ $user->lados }}</span>
            </div>
        </div>
    	<div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                comisi&oacute;n:
                <span class="{{ $clase }} m-0 p-0">{{ $user->comision }}</span>
            </div>
        </div>
    	<div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                Puntos:
                <span class="{{ $clase }} m-0 p-0">{{ $user->puntos }}</span>
            </div>
        </div>
        @if (null != $fechaUltLogin)
        <div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                Fecha del último login:
                <span class="{{ $clase }} m-0 p-0">{{ $fechaUltLogin }}</span>
            </div>
        </div>
        @endif

        <div class="row my-1 mx-0 p-0">
            <div class="my-0 mx-1 py-0 px-2">
                            
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
