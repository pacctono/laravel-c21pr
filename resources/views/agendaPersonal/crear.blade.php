@extends('layouts.app')

@section('content')
<div class="card m-0 p-0">
    <h4 class="card-header m-0 p-1">{{ $title }}</h4>
    <div class="card-body m-0 p-0">
    @include('include.exitoCrear')
    @include('include.errorData')

    <form method="POST" class="form align-items-end-horizontal"
        id="formulario" action="{{ route('agendaPersonal.store') }}">
        {!! csrf_field() !!}

        <div class="form-row my-1 mx-0 p-0">
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="fecha_cita">*Fecha</label>
                <input class="form-control form-control-sm" type="date" name="fecha_cita" id="fecha_cita"
                        required min="{{ now('America/Caracas')->format('Y-m-d') }}"
                        max="{{ now('America/Caracas')->addWeeks(4)->format('Y-m-d') }}"
                        title="Fecha en la cual se concreto la cita, mayor o igual a hoy"
                        value="{{ old('fecha_cita') }}">
                <label class="control-label" for="hora_cita">*Hora</label>
                <input class="form-control form-control-sm" type="time" name="hora_cita" id="hora_cita"
                        title="Hora en la cual se concreto la cita"
                        required value="{{ old('hora_cita') }}">
            </div>
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label>Seleccione</label>
                <select class="form-control form-control-sm" id="seleccion"
                        title="Seleccione para definir el nombre de la persona de la cita">
                    <option value="C" title="El nombre de la cita se seleccionara de la lista de contactos registrados">
                        Contacto
                    </option>
                    <option value="L" title="El nombre de la cita se seleccionara de la lista de clientes registrados">
                        Cliente
                    </option>
                    <option value="N" title="El nombre de la cita sera suministrada">Nombre</option>
                </select>
            </div>
            <div class="form-group form-inline my-0 mx-2 py-0 px-1 nombres contactos">
                <label for="contacto_id">Contacto</label>
                <select class="form-control form-control-sm" name="contacto_id" id="contacto_id">
                    <option value="0">Lista de contactos</option>
                @foreach ($contactos as $contacto)
                @if (old('contacto_id') == $contacto->id)
                    <option value="{{ $contacto->id }}" selected>{{ $contacto->name }}</option>
                @else
                    <option value="{{ $contacto->id }}">{{ $contacto->name }}</option>
                @endif
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline my-0 mx-2 py-0 px-1 nombres clientes">
                <label for="cliente_id">Cliente</label>
                <select class="form-control form-control-sm" name="cliente_id" id="cliente_id">
                    <option value="0">Lista de clientes</option>
                @foreach ($clientes as $cliente)
                @if (old('cliente_id') == $cliente->id)
                    <option value="{{ $cliente->id }}" selected>{{ $cliente->name }}</option>
                @else
                    <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                @endif
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline my-0 mx-2 py-0 px-1 nombres inputNombre">
                <label class="control-label" for="name">Nombre</label>
                <input type="text" class="form-control form-control-sm" size="50"
                        maxlength="150" name="name" id="name"
                        placeholder="Nombre de la persona, si existe o la conoce"
                        title="Nombre de la persona de la cita"
                        value="{{ old('name') }}">
            </div>
        </div>

        <div class="form-row bg-suave my-1 mx-0 p-0">
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="descripcion" id="etiqDescripcion">
                    *Descripci&oacute;n</label>
                <textarea class="form-control form-control-sm" rows="4"
                        cols="125" required name="descripcion" id="descripcion"
                        placeholder="Descripcion que se puedan sobre esta cita, etc.">{{ old('descripcion') }}</textarea>
            </div>
        </div>

        <div class="form-row my-1 mx-0 p-0">
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label for="telefono">Tel&eacute;fono&nbsp;</label>
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
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="email">Correo electr&oacute;nico</label>
                <input class="form-control form-control-sm" type="email"
                        size="50" maxlength="160" name="email" id="email"
                        placeholder="correo_de_la_cita@correo.com"
                        title="correo electronico de la cita"
                        value="{{ old('email') }}">
            </div>
        </div>

        <div class="form-row bg-suave my-1 mx-0 p-0">
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="direccion" id="etiqDireccion">
                    Direcci&oacute;n</label>
                <textarea class="form-control form-control-sm" rows="2"
                        cols="100" name="direccion" id="direccion"
                        placeholder="Calle, Casa, Apto, Edificio, Barrio, etc.">{{ old('direccion') }}</textarea>
            </div>
        </div>

        <div class="form-row my-1 mx-0 p-0">
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="comentarios" id="etiqComentarios">
                    Comentarios</label>
                <textarea class="form-control form-control-sm" rows="4"
                            cols=125" name="comentarios" id="comentarios"
                            placeholder="Comentarios sobre la cita, despues de haber sido realizada">{{ old('comentarios') }}</textarea>
            </div>
        </div>

        <div class="form-row my-1 mx-1 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <button type="submit" class="btn btn-success m-0 py-0 px-1">
                    Agregar cita personal
                </button>
                <a href="{{ route('agenda') }}" class="btn btn-link">
                    Regresar a la agenda
                </a>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection

@section('js')

@includeIf("agendaPersonal.revisar", ['vista' => 'crear'])

@endsection