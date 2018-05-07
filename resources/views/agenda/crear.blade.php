@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Resultado de
        {{ $contacto->resultado->descripcion }}
            el <spam class="alert-info">
                {{ $diaSemana[$contacto->fecha_evento->dayOfWeek] }}
                {{ $contacto->fecha_evento->format('d/m/Y H:i') }}
            </spam>
        con {{ $contacto->name }}
    </h4>
    <div class="card-body">
        <p>Telefono de contacto: <spam class="alert-info">
            0{{ substr($contacto->telefono, 0, 3) }}-{{ substr($contacto->telefono, 3, 3) }}-{{ substr($contacto->telefono, 6) }}
        </spam>.
        </p>
        <p>Correo de contacto: <spam class="alert-info">{{ $contacto->email }}
        </spam>.
        </p>
        <p>Atendido por: <spam class="alert-info">[{{ $contacto->user_id }}] {{ $contacto->user->name }}
        </spam></p>
        <p>Dirección del contacto: <spam class="alert-info">{{ $contacto->direccion }}
        </spam></p>

        <form method="POST" class="form align-items-end-horizontal"
                action="{{ route('agenda.store') }}">
        {!! csrf_field() !!}

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label" for="fecha_cita">La cita fue realizada:</label>
                <input type="date" name="fecha_cita" id="fecha_cita"
                    value="{{ old('fecha_cita', $contacto->fecha_evento->format('Y-m-d')) }}">
                <input type="time" name="hora_cita" id="hora_cita"
                    value="{{ old('hora_cita', $contacto->fecha_evento->format('H:i')) }}">
                <input type="hidden" name="contacto_id" value="{{ $contacto->id }}">
            </div>
        </div>
        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label" for="comentarios">Comentarios de la cita realizada:</label>
                <textarea class="form-control col-sm-10" cols="5" rows="5" maxlength="190" 
                        name="comentarios" id="comentarios" 
                        placeholder="Coloque aqui las comentarios que tuvo de la conversación con el contacto inicial citado.">{{ old('comentarios') }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="form-group d-flex">
                <button type="submit" class="btn btn-success">Agregar cita realizada</button>
                <a href="{{ route('agenda') }}" class="btn btn-link">Regresar a la agenda</a>
            </div>
        </div>
    </div>
</div>
@endsection
