@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Resultado de
        {{ $cita->contacto->resultado->descripcion }}
            el <span class="alert-info">
                {{ $cita->contacto->evento_dia_semana }}
                {{ $cita->contacto->evento_con_hora }}
            </span>
        con {{ $cita->contacto->name }}
    </h4>
    <div class="card-body">
        <p>Telefono de contacto: <span class="alert-info">
            {{ $cita->contacto->telefono_f }}
        </span>.
        </p>
        <p>Correo de contacto: <span class="alert-info">{{ $cita->contacto->email }}
        </span>.
        </p>
        <p>Atendido por: <span class="alert-info">
            [{{ $cita->contacto->user_id }}]
            {{ $cita->contacto->user->name }}
        </span></p>
        <p>Dirección del contacto: <span class="alert-info">{{ $cita->contacto->direccion }}
        </span></p>
        @if (NULL == $cita->fecha_cita)
        <p><span class="alert-info">La cita, todavía no ha sido concretada.</span></p>
        @else
        <p>La cita fue realizada: <span class="alert-info">
            {{ $cita->cita_dia_semana }}
            {{ $cita->cita_con_hora }}
        </span></p>
        <p>Comentarios de la cita realizada: <span class="alert-info">
            {{ $cita->comentarios }}
        </span></p>
        @endif
        <p>
            <a href="{{ $rutaPrevia??route('agenda') }}" class="btn btn-link">
                Regresar a la agenda
            </a>
        </p>
    </div>
</div>
@endsection
