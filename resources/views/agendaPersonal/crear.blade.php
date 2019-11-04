@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">{{ $title }}</h4>
    <div class="card-body">
    @include('include.exitoCrear')
    @include('include.errorData')

        <form method="POST" class="form align-items-end-horizontal"
                action="{{ route('agendaPersonal.store') }}">
        {!! csrf_field() !!}

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="fecha_cita">*Fecha</label>
                <input class="form-control" type="date" name="fecha_cita" id="fecha_cita"
                        required min="{{ now()->format('Y-m-d') }}"
                        max="{{ now()->addWeeks(4)->format('Y-m-d') }}"
                        title="Fecha en la cual se concreto la cita, mayor o igual a hoy"
                        value="{{ old('fecha_cita') }}">
                <label class="control-label" for="hora_cita">*Hora</label>
                <input class="form-control" type="time" name="hora_cita" id="hora_cita"
                        title="Hora en la cual se concreto la cita"
                        required value="{{ old('hora_cita') }}">
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="name">Nombre</label>
                <input type="text" class="form-control form-control-sm" size="60"
                        maxlength="150" name="name" id="name"
                        placeholder="Nombre de la persona, si existe o la conoce"
                        title="Nombre de la persona de la cita"
                        value="{{ old('name') }}">
            </div>
        </div>

        <div class="form-group d-flex align-items-end mx-1 px-2">
            <label class="control-label" for="descripcion" id="etiqDescripcion">
                *Descripci&oacute;n</label>
            <textarea class="form-control form-control-sm" rows="2"
                        required name="descripcion" id="descripcion"
                        placeholder="Descripcion que se puedan sobre esta cita, etc.">{{ old('descripcion') }}</textarea>
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label for="telefono">Tel&eacute;fono</label>
                0<select class="form-control form-control-sm" name="ddn" id="ddn">
                    <option value="">ddn</option>
                @foreach ($ddns as $ddn)
                @if (old('ddn', '414') == $ddn->ddn)
                    <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                @else
                    <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                @endif
                @endforeach
                </select>
                <input type="text" class="form-control form-control-sm" size="7"
                        maxlength="7" minlength="7" name="telefono" id="telefono"
                        value="{{ old('telefono') }}">
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="email">Correo electr&oacute;nico</label>
                <input class="form-control form-control-sm" type="email"
                        size="50" maxlength="160" name="email" id="email"
                        placeholder="correo_de_la_cita@correo.com"
                        title="correo electronico de la cita"
                        value="{{ old('email') }}">
            </div>
        </div>

        <div class="form-group d-flex align-items-end mx-1 px-2">
            <label class="control-label" for="direccion" id="etiqDireccion">
                Direcci&oacute;n</label>
            <textarea class="form-control form-control-sm" rows="2" name="direccion" id="direccion"
                        placeholder="Calle, Casa, Apto, Edificio, Barrio, etc.">{{ old('direccion') }}</textarea>
        </div>

        <div class="form-group d-flex align-items-end mx-1 px-2">
            <label class="control-label" for="comentarios" id="etiqComentarios">
                Comentarios</label>
            <textarea class="form-control form-control-sm" rows="2"
                        name="comentarios" id="comentarios"
                        placeholder="Comentarios sobre la cita, despues de haber sido realizada">{{ old('comentarios') }}</textarea>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <button type="submit" class="btn btn-success">Agregar cita personal</button>
                <a href="{{ route('agenda') }}" class="btn btn-link">Regresar a la agenda</a>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection
