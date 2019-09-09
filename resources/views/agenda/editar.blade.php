@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Resultado de
        {{ $contacto->resultado->descripcion }}
            el <span class="alert-info">
                {{ $contacto->evento_dia_semana }}
                {{ $contacto->evento_con_hora }}
            </span>
        con {{ $contacto->name }}
    </h4>
    <div class="card-body">
        <p>Telefono de contacto: <span class="alert-info">
            0{{ $contacto->telefono }}
        </span>.
        </p>
        <p>Correo de contacto: <span class="alert-info">{{ $contacto->email }}
        </span>.
        </p>
        <p>Atendido por: <span class="alert-info">[{{ $contacto->user_id }}] {{ $contacto->user->name }}
        </span></p>
        <p>Dirección del contacto: <span class="alert-info">{{ $contacto->direccion }}
        </span></p>

        <form method="POST" class="form align-items-end-horizontal"
                action="{{ route('agenda.update', $id) }}">
        {{ method_field('PUT') }}
        {!! csrf_field() !!}

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label" for="fecha_cita">La cita fue realizada:</label>
                <input type="date" name="fecha_cita" id="fecha_cita"
                    value="{{ old('fecha_cita', $fecha_cita->format('Y-m-d')) }}">
                <input type="time" name="hora_cita" id="hora_cita"
                    value="{{ old('hora_cita', $fecha_cita->format('H:i')) }}">
            </div>
        </div>
        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label" for="comentarios">Comentarios de la cita realizada:</label>
                <textarea class="form-control col-sm-10" cols="5" rows="5" maxlength="190" 
                        name="comentarios" id="comentarios" 
                        placeholder="Coloque aqui las comentarios que tuvo de la conversación con el contacto inicial citado.">{{ old('comentarios', $comentarios) }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="form-group d-flex">
                <button type="submit" class="btn btn-success">Actualizar cita realizada</button>
                <a href="{{ route('agenda') }}" class="btn btn-link">Regresar a la agenda</a>
            </div>
        </div>
    </div>
</div>
@endsection
