@extends('layouts.app')

@section('content')
<div class="card m-0 p-0">
    <h4 class="card-header m-0 p-1">{{ $title }}</h4>
    <div class="card-body m-0 p-0">
    @include('include.exitoCrear')
    @include('include.errorData')

    <form method="POST" class="form align-items-end-horizontal" id="formulario"
        action="{{ url('propiedades') }}">
        {!! csrf_field() !!}

        <div class="form-row my-1 mx-0 p-0 bg-suave">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="codigo">*C&oacute;digo</label>
                <input type="text" class="form-control form-control-sm" size="8" maxlength="8"
                        minlength="6" name="codigo" id="codigo" required
                        placeholder="Codigo MLS" value="{{ old('codigo') }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="estatus">*Estatus</label>
                <select class="form-control form-control-sm" name="estatus" id="estatus">
                @foreach ($cols['estatus']['opcion'] as $opcion => $muestra)
                  <option value="{{$opcion}}"
                  @if (old('estatus', $cols['estatus']['xdef']) == $opcion)
                    selected
                  @endif
                  @if (isset($colores))
                    class="{{ $colores[$opcion] }}"
                  @endif (isset($colores))
                    >{{$muestra}}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
              <label class="control-label" for="negociacion">
                *Negociaci&oacute;n
              </label>
                <select class="form-control form-control-sm" name="negociacion" id="negociacion">
                @foreach ($cols['negociacion']['opcion'] as $opcion => $muestra)
                  <option value="{{$opcion}}"
                  @if (old('negociacion', $cols['negociacion']['xdef']) == $opcion)
                    selected
                  @endif
                    >{{$muestra}}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="exclusividad">
                    *Exclusividad</label>
                <input type="checkbox" class="form-control"
                    name="exclusividad" id="exclusividad"
                    {{ old('exclusividad',
                        $cols['exclusividad']['xdef']) ? "checked" : "" }}>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="fecha_inicial">*Fecha inicial</label>
                <input type="date" class="form-control form-control-sm" name="fecha_inicial"
                    @if (!Auth::user()->is_admin)   {{-- No es administrador --}}
                        min="{{ now('America/Caracas')->subWeeks(4)->format('Y-m-d') }}"
                        max="{{ now('America/Caracas')->addWeeks(4)->format('Y-m-d') }}"
                    @endif (!Auth::user()->is_admin)   {{-- No es administrador --}}
                        id="fecha_inicial"
                        value="{{ old('fecha_inicial', now('America/Caracas')->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="form-row my-1 mx-0 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="fecha_reserva">Fecha de reserva</label>
                <input type="date" class="form-control form-control-sm" name="fecha_reserva"
                    @if (!Auth::user()->is_admin)   {{-- No es administrador --}}
                        min="{{ now('America/Caracas')->format('Y-m-d') }}"
                        max="{{ now('America/Caracas')->addWeeks(4)->format('Y-m-d') }}"
                    @endif (!Auth::user()->is_admin)   {{-- No es administrador --}}
                        {{--data-toggle="tooltip" title="Fecha de la reserva"--}}
                        id="fecha_reserva" value="{{ old('fecha_reserva') }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="forma_pago_reserva_id">Forma de pago</label>
                <select class="form-control form-control-sm" name="forma_pago_reserva_id" id="forma_pago_reserva_id"
                        data-toggle="tooltip" title="Forma de pago de la reserva">
                    <option value="">Forma de pago?</option>
                @foreach ($forma_pagos as $forma_pago)
                    <option value="{{ $forma_pago->id }}"
                  @if (old('forma_pago_reserva_id') == $forma_pago->id)
                    selected
                  @endif
                    >{{ $forma_pago->descripcion }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="factura_reserva">Factura</label>
                <input type="text" class="form-control form-control-sm"
                    size="60" maxlength="100" name="factura_reserva" id="factura_reserva"
                    placeholder="factura de pago de la reserva"
                    {{--data-toggle="tooltip" title="Factura de pago de la reserva"--}}
                    value="{{ old('factura_reserva') }}">
            </div>
        </div>

        <div class="form-row my-1 mx-0 p-0 bg-suave">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="fecha_firma">Fecha de la firma</label>
                <input type="date" class="form-control form-control-sm" name="fecha_firma"
                    @if (!Auth::user()->is_admin)   {{-- No es administrador --}}
                        min="{{ now('America/Caracas')->format('Y-m-d') }}"
                        max="{{ now('America/Caracas')->addWeeks(4)->format('Y-m-d') }}"
                    @endif (!Auth::user()->is_admin)   {{-- No es administrador --}}
                        {{--data-toggle="tooltip" title="Fecha de la firma"--}}
                        id="fecha_firma" value="{{ old('fecha_firma') }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="forma_pago_firma">Forma de pago</label>
                <select class="form-control form-control-sm" name="forma_pago_firma_id" id="forma_pago_firma_id"
                        data-toggle="tooltip" title="Forma de pago de la firma">
                    <option value="">Forma de pago?</option>
                @foreach ($forma_pagos as $forma_pago)
                    <option value="{{ $forma_pago->id }}"
                  @if (old('forma_pago_firma_id') == $forma_pago->id)
                    selected
                  @endif
                    >{{ $forma_pago->descripcion }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="factura_firma">Factura</label>
                <input type="text" class="form-control form-control-sm"
                    size="60" maxlength="100" name="factura_firma" id="factura_firma"
                    placeholder="factura de pago a la firma"
                    {{--data-toggle="tooltip" title="Factura de pago a la firma"--}}
                    value="{{ old('factura_firma') }}">
            </div>
        </div>

        <div class="form-row my-1 mx-0 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="nombre">*Nombre</label>
                <input type="text" class="form-control form-control-sm" size="80" maxlength="150"
                    name="nombre" id="nombre" required
                    placeholder="nombre de la propiedad"
                    value="{{ old('nombre') }}">
            </div>
        </div>

        @if (!Auth::user()->is_admin)   {{-- Si no es un administrador (un asesor normal) --}}
        <div class="form-row my-1 mx-0 p-0 bg-suave">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="asesor_captador_id">
                    *Asesor captador</label>
                <select class="form-control form-control-sm" name="asesor_captador_id" id="asesor_captador_id">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                    @if (old("asesor_captador_id", Auth::user()->id) == $user->id)
                        selected
                    @endif
                        >{{ $user->name }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="asesor_captador">
                    +Nombre asesor captador otra oficina</label>
                <input type="text" class="form-control form-control-sm nombreAsesor"
                    size="60" maxlength="100" name="asesor_captador" id="asesor_captador"
                    placeholder="Nombre captador otra oficina"
                    value="{{ old('asesor_captador') }}">
            </div>
        </div>

        <div class="form-row my-1 mx-0 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="asesor_cerrador_id">
                    *Asesor cerrador</label>
                <select class="form-control form-control-sm" name="asesor_cerrador_id" id="asesor_cerrador_id">
                    @foreach ($users as $user)
                    @if (old("asesor_cerrador_id") == $user->id)
                    <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                    @else
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="asesor_cerrador">
                    +Nombre asesor cerrador otra oficina</label>
                <input type="text" class="form-control form-control-sm nombreAsesor"
                    size="60" maxlength="100" name="asesor_cerrador" id="asesor_cerrador"
                    placeholder="Nombre cerrador otra oficina"
                    value="{{ old('asesor_cerrador') }}">
            </div>
        </div>
        @endif (!Auth::user()->is_admin)

        <div class="form-row my-1 mx-0 p-0 bg-suave">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="precio">*Precio</label>
                <input class="form-control form-control-sm" size="2" maxlength="2" required
                    name="moneda" id="moneda"
                    value="{{ old('moneda', $cols['moneda']['xdef']) }}" list="monedas">
                <datalist id="monedas">
                @foreach ($cols['moneda']['opcion'] as $opcion => $muestra)
                    <option value="{{ $opcion }}">
                @endforeach
                </datalist>
                <input type="float" class="form-control form-control-sm" size="20" maxlength="20"
                    name="precio" id="precio" required placeholder="Precio del inmueble"
                    min="0.00" value="{{ old('precio') }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="comision">*Comision</label>
                <input type="float" class="form-control form-control-sm" size="6" maxlength="6"
                    name="comision" id="comision" required min="0.000" max="50.000"
                    value="{{ old('comision', $cols['comision']['xdef']) }}">%
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="iva">*IVA</label>
                <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                    required name="iva" id="iva" min="0.000" max="50.000"
                    value="{{ old('iva', $cols['iva']['xdef']) }}">%
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="lados">Lados</label>
                <input type="number" class="form-control form-control-sm" size="3"
                    required name="lados" id="lados" min="1" max="2"
                    value="{{ old('lados', $cols['lados']['xdef']) }}">
            </div>
        </div>

        <fieldset class="datosPropiedad m-0 p-0" style="border:solid 1px #000000">
            <legend class="my-0 mx-1 p-0">
                <button class="my-0 mx-1 p-0" id="datosPropiedad" title="Presione para mostrar/esconder los datos de la propiedad">
                    Datos de la propiedad
                </button>
            </legend>

        <div class="form-row m-0 p-0 bg-suave">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="tipo_id">Tipo</label>
                <select class="form-control form-control-sm" name="tipo_id" id="tipo_id">
                    <option value="">Qué tipo?</option>
                @foreach ($tipos as $tipo)
                @if (old('tipo_id', $cols['tipo_id']['xdef']) == $tipo->id)
                    <option value="{{ $tipo->id }}" selected>{{ $tipo->descripcion }}</option>
                @else
                    <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                @endif
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="metraje">Metraje</label>
                <input type="float" class="form-control form-control-sm" size="8" maxlength="11"
                    name="metraje" id="metraje" min="0.00"
                    value="{{ old('metraje', $cols['metraje']['xdef']) }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="habitaciones">Habitaciones</label>
                <input type="integer" class="form-control form-control-sm" size="2" maxlength="3"
                    name="habitaciones" id="habitaciones" min="0"
                    value="{{ old('habitaciones', $cols['habitaciones']['xdef']) }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="banos">Ba&ntilde;os</label>
                <input type="integer" class="form-control form-control-sm" size="1" maxlength="2"
                    name="banos" id="banos" min="1"
                    value="{{ old('banos', $cols['banos']['xdef']) }}">
            </div>
        </div>

        <div class="form-row m-0 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="niveles">Niveles</label>
                <input type="float" class="form-control form-control-sm" size="2" maxlength="3"
                    name="niveles" id="niveles" min="1"
                    value="{{ old('niveles', $cols['niveles']['xdef']) }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="puestos">Puestos de estacionamiento</label>
                <input type="integer" class="form-control form-control-sm" size="2" maxlength="3"
                    name="puestos" id="puestos" min="1"
                    value="{{ old('puestos', $cols['puestos']['xdef']) }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="anoc">A&ntilde;o de construccion</label>
                <input type="integer" class="form-control form-control-sm" size="4" maxlength="4"
                    name="anoc" id="anoc" min="1900" max="{{ now('America/Caracas')->format('Y') }}"
                    value="{{ old('anoc', $cols['anoc']['xdef']) }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="caracteristica">Caracteristicas</label>
                <select class="form-control form-control-sm" name="caracteristica_id" id="caracteristica_id">
                    <option value="">Qué caracteristica?</option>
                @foreach ($caracteristicas as $caracteristica)
                @if (old('caracteristica_id', $cols['caracteristica_id']['xdef']) == $caracteristica->id)
                    <option value="{{ $caracteristica->id }}" selected>{{ $caracteristica->descripcion }}
                    </option>
                @else
                    <option value="{{ $caracteristica->id }}">{{ $caracteristica->descripcion }}
                    </option>
                @endif
                @endforeach
                </select>
            </div>
        </div>

        <div class="form-row m-0 p-0 bg-suave">
            <div class="form-group col-lg-12 d-flex m-0 py-0 px-1">
                <label class="control-label" for="descripcion" id="etiqDescripcion">Descripci&oacute;n</label>
                <textarea class="form-control form-control-sm" rows="5" name="descripcion" id="descripcion"
                placeholder="Descripcion detallada de la propiedad">{{ old('descripcion') }}</textarea>
            </div>
        </div>

        <div class="form-row m-0 p-0">
            <div class="form-group col-lg-12 d-flex m-0 py-0 px-1">
                <label class="control-label" for="direccion" id="etiqDireccion">Direcci&oacute;n</label>
                <textarea class="form-control form-control-sm" rows="3" name="direccion" id="direccion"
                placeholder="Calle, Casa, Apto, Edificio, Barrio, etc.">{{ old('direccion') }}</textarea>
            </div>
        </div>

        <div class="form-row m-0 p-0 bg-suave">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="ciudad_id">Ciudad</label>
                <select class="form-control form-control-sm" name="ciudad_id" id="ciudad_id">
                    <option value="">Qué ciudad?</option>
                @foreach ($ciudades as $ciudad)
                @if (old('ciudad_id', $cols['ciudad_id']['xdef']) == $ciudad->id)
                    <option value="{{ $ciudad->id }}" selected>{{ $ciudad->descripcion }}</option>
                @else
                    <option value="{{ $ciudad->id }}">{{ $ciudad->descripcion }}</option>
                @endif
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="codigo_postal">Codigo postal</label>
                <input type="float" class="form-control form-control-sm" size="8" maxlength="11"
                    name="codigo_postal" id="codigo_postal"
                    value="{{ old('codigo_postal', $cols['codigo_postal']['xdef']) }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="municipio_id">Municipio</label>
                <select class="form-control form-control-sm" name="municipio_id" id="municipio_id">
                    <option value="">Qué municipio?</option>
                @foreach ($municipios as $municipio)
                @if (old('municipio_id', $cols['municipio_id']['xdef']) == $municipio->id)
                    <option value="{{ $municipio->id }}" selected>{{ $municipio->descripcion }}</option>
                @else
                    <option value="{{ $municipio->id }}">{{ $municipio->descripcion }}</option>
                @endif
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="estado_id">Estado</label>
                <select class="form-control form-control-sm" name="estado_id" id="estado_id">
                    <option value="">Qué estado?</option>
                @foreach ($estados as $estado)
                @if (old('estado_id', $cols['estado_id']['xdef']) == $estado->id)
                    <option value="{{ $estado->id }}" selected>{{ $estado->descripcion }}</option>
                @else
                    <option value="{{ $estado->id }}">{{ $estado->descripcion }}</option>
                @endif
                @endforeach
                </select>
            </div>
        </div>

        <div class="form-row m-0 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="cliente_id">Cliente</label>
                <select class="form-control form-control-sm" name="cliente_id" id="cliente_id">
                    <option value="X">Nuevo...</option>
                @foreach ($clientes as $cliente)
                @if (old('cliente_id', $cols['cliente_id']['xdef']) == $cliente->id)
                    <option value="{{ $cliente->id }}" selected>{{ $cliente->name }}</option>
                @else
                    <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                @endif
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline mx-1 px-1 nuevo">
                <label class="control-label" for="cedula">C&eacute;dula Id.</label>
                <input type="text" class="form-control form-control-sm" size="8" maxlength="8" minlength="6" 
                        name="cedula" id="cedula" placeholder="# cedula"
                        value="{{ old('cedula') }}">
            </div>
            <div class="form-group form-inline mx-1 px-1 nuevo">
                <label class="control-label" for="rif">Rif</label>
                <input type="text" class="form-control form-control-sm" size="10" minlength="10" 
                        name="rif" id="rif" placeholder="# rif"
                        value="{{ old('rif') }}">
            </div>
            <div class="form-group form-inline mx-1 px-1 nuevo">
                <label class="control-label" for="name">Nombre</label>
                <input type="text" class="form-control form-control-sm" size="30" maxlength="150" 
                        name="name" id="name" placeholder="Nombre del cliente"
                        value="{{ old('name') }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1 nuevo">
                <label class="control-label" for="tipo">Tipo</label>
                <select class="form-control form-control-sm" name="tipo" id="tipo">
                @foreach ($tiposC as $opcion => $muestra)
                  <option value="{{$opcion}}"
                  @if (old('tipo', $tipoCXDef) == $opcion)
                    selected
                  @endif
                    >{{$muestra}}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="form-row m-0 p-0 bg-suave nuevo">
            <div class="form-group form-inline mx-1 px-1 nuevo">
                <label class="control-label" for="telefono">Tel&eacute;fono</label>
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
                <input type="text" class="form-control form-control-sm" size="7" minlength="7" 
                        name="telefono" id="telefono" placeholder="telefono sin area" 
                        value="{{ old('telefono') }}">
            </div>
            <div class="form-group form-inline mx-1 px-1 nuevo">
                <label class="control-label" for="email">Correo electr&oacute;nico</label>
                <input type="email" class="form-control form-control-sm" size="60" maxlength="160"
                        name="email" id="email" placeholder="correo electronico" value="{{ old('email') }}">
            </div>
            <div class="form-group form-inline mx-1 px-1 nuevo">
                <label class="control-label" for="fecha_nacimiento">Fec. nacimiento</label>
                <input type="date" class="form-control form-control-sm" name="fecha_nacimiento" 
                        id="fecha_nacimiento" max="{{ now('America/Caracas')->format('Y-m-d') }}"
                        value="{{ old('fecha_nacimiento') }}">
            </div>
        </div>

        <div class="form-row m-0 p-0 bg-suave nuevo">
            <div class="form-group col-lg-12 d-flex m-0 py-0 px-1 nuevo">
                <label class="control-label" for="dirCliente" id="etiqDirCliente">
                    Direcci&oacute;n del cliente</label>
                <textarea class="form-control form-control-sm" rows="2" name="dirCliente" id="dirCliente"
                placeholder="Calle, Casa, Apto, Edificio, Barrio, etc.">{{ old('dirCliente') }}</textarea>
            </div>
        </div>

        <div class="form-row m-0 p-0 nuevo">
            <div class="form-group col-lg-12 d-flex m-0 py-0 px-1 nuevo">
                <label class="control-label" for="observaciones" id="etiqObservaciones">
                    Observaciones sobre este cliente</label>
                <textarea class="form-control form-control-sm" rows="2" name="observaciones" id="observaciones"
                placeholder="Observaciones que se puedan tener sobre este cliente, etc.">{{ old('observaciones') }}</textarea>
            </div>
        </div>
        </fieldset>

        @if (Auth::user()->is_admin)
        <fieldset class="datosOficina m-0 p-0" style="border:solid 1px #000000">
            <legend class="my-0 mx-1 p-0">
                <button class="my-0 mx-1 p-0" id="datosOficina" title="Presione para mostrar/esconder los datos de la oficina">
                    Datos administrativos
                </button>
            </legend>
        <div class="form-row m-0 p-0 bg-suave">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="porc_franquicia">*Franquicia</label>
                <input type="float" class="form-control form-control-sm" size="5" maxlength="5" min="0.000" max="50.000"
                    name="porc_franquicia" id="porc_franquicia" placeholder="10" required
                    value="{{ old('porc_franquicia', $cols['porc_franquicia']['xdef']) }}">%
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="reportado_casa_nacional">
                    *Reportado casa nacional</label>
                <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                    name="reportado_casa_nacional" id="reportado_casa_nacional"
                    value="{{ old('reportado_casa_nacional',
                                $cols['reportado_casa_nacional']['xdef']) }}">%
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="porc_regalia">*Regalia</label>
                <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                    name="porc_regalia" id="porc_regalia" required min="0.000" max="50.000"
                    value="{{ old('porc_regalia', $cols['porc_regalia']['xdef']) }}">%
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="porc_gerente">
                    *Porc gerente</label>
                <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                    name="porc_gerente" id="porc_gerente" required min="0.000" max="20.000"
                    value="{{ old('porc_gerente', $cols['porc_gerente']['xdef']) }}">%
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="porc_compartido">
                    *Porc compartido</label>
                <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                    name="porc_compartido" id="porc_compartido" required min="0.000" max="99.999"
                    value="{{ old('porc_compartido', $cols['porc_compartido']['xdef']) }}">%
            </div>
        </div>

        <div class="form-row m-0 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="porc_bonificacion">
                    *Porcentaje bonificacion</label>
                <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                    name="porc_bonificacion" id="porc_bonificacion" required min="0.000" max="50.000"
                    value="{{ old('porc_bonificacion',
                        $cols['porc_bonificacion']['xdef']) }}">%
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="comision_bancaria">
                    Comision bancaria</label>
                <input type="float" class="form-control form-control-sm" size="15" maxlength="15"
                    name="comision_bancaria" id="comision_bancaria" min="0.00"
                    placeholder="ddd.ddd,dd" value="{{ old('comision_bancaria') }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="numero_recibo">
                    N&uacute;mero de recibo</label>
                <input type="text" class="form-control form-control-sm" size="30" maxlength="30"
                    name="numero_recibo" id="numero_recibo"
                    placeholder="Numero del recibo"
                    value="{{ old('numero_recibo') }}">
            </div>
        </div>

        <div class="form-row m-0 p-0 bg-suave">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="porc_captador_prbr">
                    *Porcentaje captador PRBR</label>
                <input type="float" class="form-control form-control-sm" size="5" maxlength="5" min="0.000" max="50.000"
                    name="porc_captador_prbr" id="porc_captador_prbr" required
                    value="{{ old('porc_captador_prbr', $cols['porc_captador_prbr']['xdef']) }}">%
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="asesor_captador_id">
                    *Asesor captador</label>
                <select class="form-control form-control-sm" name="asesor_captador_id" id="asesor_captador_id">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                    @if (old("asesor_captador_id") == $user->id)
                        selected
                    @endif
                        >{{ $user->name }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="asesor_captador">
                    +Asesor</label>
                <input type="text" class="form-control form-control-sm nombreAsesor"
                    size="40" maxlength="100" name="asesor_captador" id="asesor_captador"
                    placeholder="Nombre captador otra oficina"
                    value="{{ old('asesor_captador') }}">
            </div>
        </div>

        <div class="form-row my-1 mx-0 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="fecha_captador">Fecha de pago</label>
                <input type="date" class="form-control form-control-sm" name="fecha_captador"
                        {{--min="{{ now('America/Caracas')->format('Y-m-d') }}"
                        max="{{ now('America/Caracas')->addWeeks(4)->format('Y-m-d') }}"--}}
                        data-toggle="tooltip" title="Fecha de pago al Asesor captador"
                        id="fecha_captador" value="{{ old('fecha_captador') }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="forma_pago_captador_id">Forma de pago</label>
                <select class="form-control form-control-sm" name="forma_pago_captador_id" id="forma_pago_captador_id"
                    data-toggle="tooltip" title="Forma de pago al Asesor captador">
                    <option value="">Forma de pago?</option>
                @foreach ($forma_pagos as $forma_pago)
                    <option value="{{ $forma_pago->id }}"
                  @if (old('forma_pago_captador_id') == $forma_pago->id)
                    selected
                  @endif
                    >{{ $forma_pago->descripcion }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="factura_captador">Factura</label>
                <input type="text" class="form-control form-control-sm"
                    size="60" maxlength="100" name="factura_captador" id="factura_captador"
                    placeholder="factura de pago al asesor captador"
                    {{--data-toggle="tooltip" title="Factura de pago al asesor captador"--}}
                    value="{{ old('factura_captador') }}">
            </div>
        </div>

        <div class="form-row m-0 p-0 bg-suave">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="porc_cerrador_prbr">
                    *Porcentaje cerrador PRBR</label>
                <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                    name="porc_cerrador_prbr" id="porc_cerrador_prbr" required min="0.000" max="50.000"
                    value="{{ old('porc_cerrador_prbr', $cols['porc_cerrador_prbr']['xdef']) }}">%
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="asesor_cerrador_id">
                    *Asesor cerrador</label>
                <select class="form-control form-control-sm" name="asesor_cerrador_id" id="asesor_cerrador_id">
                    @foreach ($users as $user)
                    @if (old("asesor_cerrador_id") == $user->id)
                    <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                    @else
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="asesor_cerrador">
                    +Asesor</label>
                <input type="text" class="form-control form-control-sm nombreAsesor"
                    size="40" maxlength="100" name="asesor_cerrador" id="asesor_cerrador"
                    placeholder="Nombre cerrador otra oficina"
                    value="{{ old('asesor_cerrador') }}">
            </div>
        </div>

        <div class="form-row my-1 mx-0 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="fecha_cerrador">Fecha de pago</label>
                <input type="date" class="form-control form-control-sm" name="fecha_cerrador"
                        {{--min="{{ now('America/Caracas')->format('Y-m-d') }}"
                        max="{{ now('America/Caracas')->addWeeks(4)->format('Y-m-d') }}"--}}
                        data-toggle="tooltip" title="Fecha de pago al Asesor cerrador"
                        id="fecha_cerrador" value="{{ old('fecha_cerrador') }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="forma_pago_cerrador_id">Forma de pago</label>
                <select class="form-control form-control-sm" name="forma_pago_cerrador_id" id="forma_pago_cerrador_id"
                    data-toggle="tooltip" title="Forma de pago al Asesor cerrador">
                    <option value="">Forma de pago?</option>
                @foreach ($forma_pagos as $forma_pago)
                    <option value="{{ $forma_pago->id }}"
                  @if (old('forma_pago_cerrador_id') == $forma_pago->id)
                    selected
                  @endif
                    >{{ $forma_pago->descripcion }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="factura_cerrador">Factura</label>
                <input type="text" class="form-control form-control-sm"
                    size="60" maxlength="100" name="factura_cerrador" id="factura_cerrador"
                    placeholder="factura de pago al asesor cerrador"
                    {{--data-toggle="tooltip" title="Factura de pago al asesor cerrador"--}}
                    value="{{ old('factura_cerrador') }}">
            </div>
        </div>

        <div class="form-row m-0 p-0 bg-suave">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="pago_gerente">
                    Pago gerente</label>
                <input type="text" class="form-control form-control-sm" size="40" maxlength="100"
                    name="pago_gerente" id="pago_gerente"
                    placeholder="Como se realizo pago a gerente" value="{{ old('pago_gerente') }}">
            </div>
            {{--<div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="factura_gerente">
                    Factura gerente</label>
                <input type="text" class="form-control form-control-sm" size="40" maxlength="100"
                    name="factura_gerente" id="factura_gerente"
                    placeholder="Factura gerente" value="{{ old('factura_gerente') }}">
            </div>--}}
        </div>

        <div class="form-row my-1 mx-0 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="fecha_gerente">Fecha de pago</label>
                <input type="date" class="form-control form-control-sm" name="fecha_gerente"
                        {{--min="{{ now('America/Caracas')->format('Y-m-d') }}"
                        max="{{ now('America/Caracas')->addWeeks(4)->format('Y-m-d') }}"--}}
                        data-toggle="tooltip" title="Fecha de pago al gerente"
                        id="fecha_gerente" value="{{ old('fecha_gerente') }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="forma_pago_gerente_id">Forma de pago</label>
                <select class="form-control form-control-sm" name="forma_pago_gerente_id" id="forma_pago_gerente_id"
                    data-toggle="tooltip" title="Forma de pago al gerente">
                    <option value="">Forma de pago?</option>
                @foreach ($forma_pagos as $forma_pago)
                    <option value="{{ $forma_pago->id }}"
                  @if (old('forma_pago_gerente_id') == $forma_pago->id)
                    selected
                  @endif
                    >{{ $forma_pago->descripcion }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="factura_gerente">Factura</label>
                <input type="text" class="form-control form-control-sm"
                    size="60" maxlength="100" name="factura_gerente" id="factura_gerente"
                    placeholder="factura de pago al gerente"
                    {{--data-toggle="tooltip" title="Factura de pago al gerente"--}}
                    value="{{ old('factura_gerente') }}">
            </div>
        </div>

        <div class="form-row m-0 p-0 bg-danger">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="pago_asesores">
                    Pago asesores</label>
                <input type="text" class="form-control form-control-sm" size="40" maxlength="100"
                    name="pago_asesores" id="pago_asesores"
                    placeholder="Como se realizo el pago a los asesores"
                    value="{{ old('pago_asesores') }}">
            </div>

            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="factura_asesores">
                    Factura asesores</label>
                <input type="text" class="form-control form-control-sm" size="100" maxlength="100"
                    name="factura_asesores" id="factura_asesores"
                    placeholder="Factura asesores" value="{{ old('factura_asesores') }}">
            </div>
        </div>

        <div class="form-row m-0 p-0 bg-suave">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="pago_otra_oficina">
                    Pago otra oficina</label>
                <input type="text" class="form-control form-control-sm" size="100" maxlength="100"
                    name="pago_otra_oficina" id="pago_otra_oficina"
                    placeholder="Como se realizo el pago a otra oficina"
                    value="{{ old('pago_otra_oficina') }}">
            </div>
        </div>

        <div class="form-row my-1 mx-0 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="fecha_otra_oficina">Fecha de pago</label>
                <input type="date" class="form-control form-control-sm" name="fecha_otra_oficina"
                        {{--min="{{ now('America/Caracas')->format('Y-m-d') }}"
                        max="{{ now('America/Caracas')->addWeeks(4)->format('Y-m-d') }}"--}}
                        data-toggle="tooltip" title="Fecha de pago a la otra oficina"
                        id="fecha_otra_oficina" value="{{ old('fecha_otra_oficina') }}">
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="forma_pago_otra_oficina_id">Forma de pago</label>
                <select class="form-control form-control-sm" name="forma_pago_otra_oficina_id"
                    id="forma_pago_otra_oficina_id"
                    data-toggle="tooltip" title="Forma de pago a la otra oficina">
                    <option value="">Forma de pago?</option>
                @foreach ($forma_pagos as $forma_pago)
                    <option value="{{ $forma_pago->id }}"
                  @if (old('forma_pago_otra_oficina_id') == $forma_pago->id)
                    selected
                  @endif
                    >{{ $forma_pago->descripcion }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="factura_otra_oficina">Factura</label>
                <input type="text" class="form-control form-control-sm"
                    size="60" maxlength="100" name="factura_otra_oficina" id="factura_otra_oficina"
                    placeholder="factura de pago a la otra_oficina"
                    {{--data-toggle="tooltip" title="Factura del pago a la otra oficina"--}}
                    value="{{ old('factura_otra_oficina') }}">
            </div>
        </div>

        <div class="form-row m-0 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="pagado_casa_nacional">
                    Pagado casa nacional</label>
                <input type="checkbox" class="form-control form-control-sm"
                    name="pagado_casa_nacional" id="pagado_casa_nacional"
                    {{ old('pagado_casa_nacional',
                        $cols['pagado_casa_nacional']['xdef']) ? "checked" : "" }}>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="estatus_sistema_c21">
                    *Estatus sistema C21</label>
                <select class="form-control form-control-sm" name="estatus_sistema_c21" id="estatus_sistema_c21">
                @foreach ($cols['estatus_sistema_c21']['opcion'] as $opcion => $muestra)
                  <option value="{{$opcion}}"
                  @if (old('estatus_sistema_c21',
                        $cols['estatus_sistema_c21']['xdef']) == $opcion)
                    selected
                  @endif
                    >{{$muestra}}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="reporte_casa_nacional">
                    Reporte casa nacional</label>
                <input type="text" class="form-control form-control-sm"
                    name="reporte_casa_nacional" id="reporte_casa_nacional"
                    size="10" maxlength="10" placeholder="numero"
                    value="{{ old('reporte_casa_nacional') }}">
            </div>
        </div>

        <div class="form-row m-0 p-0 bg-suave">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="comentarios">
                    Comentarios</label>
                <input type="text" class="form-control form-control-sm"
                    name="comentarios" id="comentarios"
                    size="100" maxlength="600" placeholder="comentarios........."
                    value="{{ old('comentarios') }}">
            </div>
        </div>
        <div class="form-row m-0 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <label class="control-label" for="factura_AyS">
                    Factura A&S</label>
                <input type="text" class="form-control form-control-sm"
                    name="factura_AyS" id="factura_AyS"
                    size="100" maxlength="100" placeholder="numero de factura"
                    value="{{ old('factura_AyS') }}">
            </div>
        </div>
        </fieldset>
        @else (Auth::user()->is_admin)
            <input type="hidden" name="porc_franquicia" value="{{ $cols['porc_franquicia']['xdef'] }}">
            <input type="hidden" name="reportado_casa_nacional" value="{{ $cols['reportado_casa_nacional']['xdef'] }}">
            <input type="hidden" name="porc_regalia" value="{{ $cols['porc_regalia']['xdef'] }}">
            <input type="hidden" name="porc_gerente" value="{{ $cols['porc_gerente']['xdef'] }}">
            <input type="hidden" name="porc_compartido" value="{{ $cols['porc_compartido']['xdef'] }}">
            <input type="hidden" name="porc_captador_prbr" value="{{ $cols['porc_captador_prbr']['xdef'] }}">
            <input type="hidden" name="porc_cerrador_prbr" value="{{ $cols['porc_cerrador_prbr']['xdef'] }}">
            <input type="hidden" name="porc_bonificacion" value="{{ $cols['porc_bonificacion']['xdef'] }}">
        @endif (Auth::user()->is_admin)

        <div class="form-row m-0 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <button type="submit" class="btn btn-success">
                    Agregar propiedad
                </button>
                <a href="{{ url('/propiedades') }}" class="btn btn-link">
                    Regresar al listado de propiedades
                </a>
            </div>
        </div>
    </form>
    </div>
</div>
@endsection

@section('js')

@includeIf("propiedades.revisar")

@endsection