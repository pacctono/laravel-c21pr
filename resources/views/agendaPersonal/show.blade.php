@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">
            Cita el <span class="alert-info">
                {{ $cita->cita_dia_semana }}
                {{ $cita->cita_con_hora }}
            </span>
        con {{ $cita->name??'<Nombre no suministrado>' }}
    </h4>
    <div class="card-body">
        <p>Telefono de la cita: <span class="alert-info">
            {{ $cita->telefono_f??'No suministrado.' }}
        </span>.
        </p>
        <p>Correo de la cita: <span class="alert-info">
            {{ $cita->email??'No suministrado.' }}
        </span>.
        </p>
        <p>Atendido por: <span class="alert-info">
            [{{ $cita->user_id }}]
            {{ $cita->user->name }}
        </span></p>
        <p>Dirección de la cita: <span class="alert-info">{{ $cita->direccion??'No suministrado.' }}
        </span></p>
        @if (now() < $cita->fecha_cita)
        <p><span class="alert-info">La cita, todavía no ha podido ser concretada.</span></p>
        @else (now() > $cita->fecha_cita)
        <p>La cita fue realizada: <span class="alert-info">
            {{ $cita->cita_dia_semana }}
            {{ $cita->cita_con_hora }}
        </span></p>
        <p>Comentarios de la cita realizada: <span class="alert-info">
            {{ $cita->comentarios }}
        </span></p>
        @endif (now() > $cita->fecha_cita)
        <p>
            <a href="{{ $rutaPrevia??route('agenda') }}" class="btn btn-link">
                Regresar a la agenda
            </a>
        </p>
    </div>
</div>
@endsection
