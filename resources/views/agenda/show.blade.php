@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Resultado de
        {{ $cita->contacto->resultado->descripcion }}
            el <spam class="alert-info">
                {{ $diaSemana[$cita->contacto->fecha_evento->dayOfWeek] }}
                {{ $cita->contacto->fecha_evento->format('d/m/Y H:i') }}
            </spam>
        con {{ $cita->contacto->name }}
    </h4>
    <div class="card-body">
        <p>Telefono de contacto: <spam class="alert-info">
            0{{ substr($cita->contacto->telefono, 0, 3) }}-{{ substr($cita->contacto->telefono, 3, 3) }}-{{ substr($cita->contacto->telefono, 6) }}
        </spam>.
        </p>
        <p>Correo de contacto: <spam class="alert-info">{{ $cita->contacto->email }}
        </spam>.
        </p>
        <p>Atendido por: <spam class="alert-info">[{{ $cita->contacto->user_id }}] {{ $cita->contacto->user->name }}
        </spam></p>
        <p>Dirección del contacto: <spam class="alert-info">{{ $cita->contacto->direccion }}
        </spam></p>
        @if (NULL == $cita->fecha_cita)
        <p><spam class="alert-info">La cita, todavía no ha sido concretada.</spam></p>
        @else
        <p>La cita fue realizada: <spam class="alert-info">
            {{ $diaSemana[$cita->fecha_cita->dayOfWeek] }}
            {{ $cita->fecha_cita->format('d/m/Y H:i') }}
        </spam></p>
        <p>Comentarios de la cita realizada: <spam class="alert-info">
            {{ $cita->comentarios }}
        </spam></p>
        @endif
        <p>
            <a href="{{ route('agenda') }}" class="btn btn-link">Regresar a la agenda</a>
        </p>
    </div>
</div>
@endsection
