@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">{{ $title }}</h4>
    <div class="card-body">
    @include('include.exitoCrear')
    @include('include.errorData')

        <form method="POST" class="form align-items-end-horizontal" id="formulario"
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
                <label>Seleccione</label>
                <select class="form-control form-control-sm" id="seleccion"
                        title="Seleccione para definir el nombre de la persona de la cita">
                    <option value="C" title="El nombre de la cita se seleccionara de la lista de contactos registrados">Contacto</option>
                    <option value="L" title="El nombre de la cita se seleccionara de la lista de clientes registrados">Cliente</option>
                    <option value="N" title="El nombre de la cita sera suministrada">Nombre</option>
                </select>
            </div>
            <div class="form-group form-inline mx-1 px-2 nombres contactos">
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
            <div class="form-group form-inline mx-1 px-2 nombres clientes">
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
            <div class="form-group form-inline mx-1 px-2 nombres inputNombre">
                <label class="control-label" for="name">Nombre</label>
                <input type="text" class="form-control form-control-sm" size="50"
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

@section('js')
<script>
    $(document).ready(function(){
        $("div.nombres").hide();
        if (1 < $("#contacto_id option").length) {    // Se muestra el select si hay contactos.
            $("#seleccion").val('C');
            $("div.contactos").show();
        } else if (1 < $("#cliente_id option").length) {  // Se muestra el select si hay clientes.
            $("#seleccion").val('L');
            $("div.clientes").show();
        } else {
            $("#seleccion").val('N');   // Se muestra el input (texto) del name.
            $("div.inputNombre").show();
        }
        $('#seleccion').on('focusin', function(){
            console.log("Saving value " + $(this).val());
            $(this).data('val', $(this).val());
        });
        $("#seleccion").change(function(ev){
            var previo = $(this).data('val');
            var actual = $(this).val();
            if ('C' == $(this).val()) {
                if (1 >= $("#contacto_id option").length) {
                    alert("Usted no tiene contactos registrados que pueda seleccionar");
                    $(this).val(previo);
                    return;
                }
                $("div.nombres").hide();
                $("div.contactos").show();
                $("#cliente_id").val('0');
                $("#name").val('');
                alert('Seleccione el nombre de un contacto de la lista de ' +
                        'contactos iniciales registrados. Si son los mismos, no necesita ' +
                        'incluir: telefono, otro_telefono, email y direccion.');
                $("#contacto_id").focus();
            } else if ('L' == $(this).val()) {
                if (1 >= $("#cliente_id option").length) {
                    alert("Usted no tiene clientes registrados que pueda seleccionar");
                    $(this).val(previo);
                    return;
                }
                $("div.nombres").hide();
                $("div.clientes").show();
                $("#contacto_id").val('0');
                $("#name").val('');
                alert('Seleccione el nombre de un cliente de la lista de ' +
                        'clientes registrados. Si son los mismos, no necesita ' +
                        'incluir: telefono, otro_telefono, email y direccion.');
                $("#cliente_id").focus();
            } else {
                $("div.nombres").hide();
                $("div.inputNombre").show();
                $("#contacto_id").val('0');
                $("#cliente_id").val('0');
                alert('Suministre el nombre de la persona con quien realizara la cita');
                $("#name").focus();
            }
        })
        $("#formulario").submit(function(ev) {
            if (('0' == $("#contacto_id").val()) && ('0' == $("#cliente_id").val()) &&
                ('' == $("#name").val())) {
                ev.preventDefault();
                alert('Tiene que colocar un nombre, seleccionar un contacto o un cliente.');
                $("div.nombres").hide();
                $("div.inputNombre").show();
                $("#seleccion").val('N');
                $("#contacto_id").val('0');
                $("#cliente_id").val('0');
                $("#name").focus();
            }
        })
    })
</script>

@endsection