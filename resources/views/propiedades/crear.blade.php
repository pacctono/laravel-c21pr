@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">{{ $title }}</h4>
    <div class="card-body">
    @include('include.exitoCrear')
    @include('include.errorData')

    <form method="POST" class="form align-items-end-horizontal" id="formulario"
        action="{{ url('propiedades') }}" onSubmit="return fnSometerForma()">
        {!! csrf_field() !!}

        <div class="form-row my-0 py-0 bg-suave">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="codigo">*C&oacute;digo</label>
                <input type="text" class="form-control form-control-sm" size="8" maxlength="8"
                        minlength="6" name="codigo" id="codigo" required
                        placeholder="Codigo MLS" value="{{ old('codigo') }}">
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="estatus">*Estatus</label>
                <select class="form-control form-control-sm" name="estatus" id="estatus">
                @foreach ($cols['estatus']['opcion'] as $opcion => $muestra)
                  <option value="{{$opcion}}"
                  @if (old('estatus', $cols['estatus']['xdef']) == $opcion)
                    selected
                  @endif
                    >{{$muestra}}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline mx-1 px-2">
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
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="exclusividad">
                    *Exclusividad</label>
                <input type="checkbox" class="form-control"
                    name="exclusividad" id="exclusividad"
                    {{ old('exclusividad',
                        $cols['exclusividad']['xdef']) ? "checked" : "" }}>
            </div>
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="fecha_reserva">Fecha de reserva</label>
                <input type="date" class="form-control form-control-sm" name="fecha_reserva"
                        id="fecha_reserva" min="{{ now()->format('d/m/Y') }}"
                        max="{{ now()->addWeeks(4)->format('d/m/Y') }}"
                        value="{{ old('fecha_reserva') }}">
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="fecha_firma">Fecha de la firma</label>
                <input type="date" class="form-control form-control-sm" name="fecha_firma"
                    id="fecha_firma" min="{{ now()->addWeeks(-4)->format('d/m/Y') }}"
                    max="{{ now()->addWeeks(4)->format('d/m/Y') }}"
                    value="{{ old('fecha_firma') }}">
            </div>
        </div>

        <div class="form-row my-0 py-0 bg-suave">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="nombre">*Nombre</label>
                <input type="text" class="form-control form-control-sm" size="80" maxlength="150"
                    name="nombre" id="nombre" required
                    placeholder="nombre de la propiedad"
                    value="{{ old('nombre') }}">
            </div>
        </div>

        @if (!Auth::user()->is_admin)
        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="asesor_captador_id">
                    *Asesor captador</label>
                <select name="asesor_captador_id" id="asesor_captador_id">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                    @if (old("asesor_captador_id", Auth::user()->id) == $user->id)
                        selected
                    @endif
                        >{{ $user->name }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="asesor_captador">
                    +Nombre asesor captador otra oficina</label>
                <input type="text" class="form-control" size="60" maxlength="100"
                    name="asesor_captador" id="asesor_captador"
                    placeholder="Nombre captador otra oficina"
                    value="{{ old('asesor_captador') }}">
            </div>
        </div>

        <div class="form-row my-0 py-0 bg-suave">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="asesor_cerrador_id">
                    *Asesor cerrador</label>
                <select name="asesor_cerrador_id" id="asesor_cerrador_id">
                    @foreach ($users as $user)
                    @if (old("asesor_cerrador_id") == $user->id)
                    <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                    @else
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="asesor_cerrador">
                    +Nombre asesor cerrador otra oficina</label>
                <input type="text" class="form-control" size="60" maxlength="100"
                    name="asesor_cerrador" id="asesor_cerrador"
                    placeholder="Nombre cerrador otra oficina"
                    value="{{ old('asesor_cerrador') }}">
            </div>
        </div>
        @endif (!Auth::user()->is_admin)

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
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
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="comision">*Comision</label>
                <input type="float" class="form-control form-control-sm" size="6" maxlength="6"
                    name="comision" id="comision" required min="0.000" max="50.000"
                    value="{{ old('comision', $cols['comision']['xdef']) }}">%
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="iva">*IVA</label>
                <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                    required name="iva" id="iva" min="0.000" max="99.99"
                    value="{{ old('iva', $cols['iva']['xdef']) }}">%
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="lados">Lados</label>
                <input type="number" class="form-control form-control-sm" size="1" maxlength="1"
                    required name="lados" id="lados" min="1" max="2"
                    value="{{ old('lados', $cols['lados']['xdef']) }}">
            </div>
        </div>

        <fieldset class="datosPropiedad" style="border:solid 2px #000000">
            <legend>
                <button id="datosPropiedad" title="Presione para mostrar/esconder los datos de la propiedad">
                    Datos de la propiedad
                </button>
            </legend>

        <div class="form-row py-0">
            <div class="form-group form-inline mx-1 px-2">
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
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="metraje">Metraje</label>
                <input type="float" class="form-control form-control-sm" size="8" maxlength="11"
                    name="metraje" id="metraje" min="0.00"
                    value="{{ old('metraje', $cols['metraje']['xdef']) }}">
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="habitaciones">Habitaciones</label>
                <input type="integer" class="form-control form-control-sm" size="2" maxlength="3"
                    name="habitaciones" id="habitaciones" min="0"
                    value="{{ old('habitaciones', $cols['habitaciones']['xdef']) }}">
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="banos">Ba&ntilde;os</label>
                <input type="integer" class="form-control form-control-sm" size="1" maxlength="2"
                    name="banos" id="banos" min="1"
                    value="{{ old('banos', $cols['banos']['xdef']) }}">
            </div>
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="niveles">Niveles</label>
                <input type="float" class="form-control form-control-sm" size="2" maxlength="3"
                    name="niveles" id="niveles" min="1"
                    value="{{ old('niveles', $cols['niveles']['xdef']) }}">
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="puestos">Puestos de estacionamiento</label>
                <input type="integer" class="form-control form-control-sm" size="2" maxlength="3"
                    name="puestos" id="puestos" min="1"
                    value="{{ old('puestos', $cols['puestos']['xdef']) }}">
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="anoc">A&ntilde;o de construccion</label>
                <input type="integer" class="form-control form-control-sm" size="4" maxlength="4"
                    name="anoc" id="anoc" min="1900" max="{{ now()->format('Y') }}"
                    value="{{ old('anoc', $cols['anoc']['xdef']) }}">
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="caracteristica">Caracteristicas</label>
                <select class="form-control" name="caracteristica_id" id="caracteristica_id">
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

        <div class="form-row my-0 py-0">
            <div class="form-group col-lg-12 d-flex mx-1 px-2">
                <label class="control-label" for="descripcion" id="etiqDescripcion">Descripci&oacute;n</label>
                <textarea class="form-control form-control-sm" rows="5" name="descripcion" id="descripcion"
                placeholder="Descripcion detallada de la propiedad">{{ old('descripcion') }}</textarea>
            </div>
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group col-lg-12 d-flex mx-1 px-2">
                <label class="control-label" for="direccion" id="etiqDireccion">Direcci&oacute;n</label>
                <textarea class="form-control form-control-sm" rows="3" name="direccion" id="direccion"
                placeholder="Calle, Casa, Apto, Edificio, Barrio, etc.">{{ old('direccion') }}</textarea>
            </div>
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
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
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="codigo_postal">Codigo postal</label>
                <input type="float" class="form-control form-control-sm" size="8" maxlength="11"
                    name="codigo_postal" id="codigo_postal"
                    value="{{ old('codigo_postal', $cols['codigo_postal']['xdef']) }}">
            </div>
            <div class="form-group form-inline mx-1 px-2">
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
            <div class="form-group form-inline mx-1 px-2">
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

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
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
            <div class="form-group form-inline mx-1 px-2 nuevo">
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

        <div class="form-row my-0 py-0 nuevo">
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
                        id="fecha_nacimiento" max="{{ now()->format('Y-m-d') }}"
                        value="{{ old('fecha_nacimiento') }}">
            </div>
        </div>

        <div class="form-row my-0 py-0 nuevo">
            <div class="form-group col-lg-12 d-flex mx-1 px-2 nuevo">
                <label class="control-label" for="dirCliente" id="etiqDirCliente">
                    Direcci&oacute;n del cliente</label>
                <textarea class="form-control form-control-sm" rows="2" name="dirCliente" id="dirCliente"
                placeholder="Calle, Casa, Apto, Edificio, Barrio, etc.">{{ old('dirCliente') }}</textarea>
            </div>
        </div>

        <div class="form-row my-0 py-0 nuevo">
            <div class="form-group col-lg-12 d-flex mx-1 px-2 nuevo">
                <label class="control-label" for="observaciones" id="etiqObservaciones">
                    Observaciones sobre este cliente</label>
                <textarea class="form-control form-control-sm" rows="2" name="observaciones" id="observaciones"
                placeholder="Observaciones que se puedan tener sobre este cliente, etc.">{{ old('observaciones') }}</textarea>
            </div>
        </div>
        </fieldset>

        @if (Auth::user()->is_admin)
        <fieldset class="datosOficina" style="border:solid 2px #000000">
            <legend>
                <button id="datosOficina" title="Presione para mostrar/esconder los datos de la oficina">
                    Datos administrativos
                </button>
            </legend>
        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="porc_franquicia">*Franquicia</label>
                <input type="float" class="form-control" size="5" maxlength="5" min="0.000" max="50.000"
                    name="porc_franquicia" id="porc_franquicia" placeholder="10" required
                    value="{{ old('porc_franquicia', $cols['porc_franquicia']['xdef']) }}">%
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="reportado_casa_nacional">
                    *Reportado casa nacional</label>
                <input type="float" class="form-control" size="5" maxlength="5"
                    name="reportado_casa_nacional" id="reportado_casa_nacional"
                    value="{{ old('reportado_casa_nacional',
                                $cols['reportado_casa_nacional']['xdef']) }}">%
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="porc_regalia">*Regalia</label>
                <input type="float" class="form-control" size="5" maxlength="5"
                    name="porc_regalia" id="porc_regalia" required min="0.000" max="50.000"
                    value="{{ old('porc_regalia', $cols['porc_regalia']['xdef']) }}">%
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="porc_gerente">
                    *Porc gerente</label>
                <input type="float" class="form-control" size="5" maxlength="5"
                    name="porc_gerente" id="porc_gerente" required min="0.000" max="20.000"
                    value="{{ old('porc_gerente', $cols['porc_gerente']['xdef']) }}">%
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="porc_compartido">
                    *Porc compartido</label>
                <input type="float" class="form-control" size="5" maxlength="5"
                    name="porc_compartido" id="porc_compartido" required min="0.000" max="99.999"
                    value="{{ old('porc_compartido', $cols['porc_compartido']['xdef']) }}">%
            </div>
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="porc_bonificacion">
                    *Porcentaje bonificacion</label>
                <input type="float" class="form-control" size="5" maxlength="5"
                    name="porc_bonificacion" id="porc_bonificacion" required min="0.000" max="50.000"
                    value="{{ old('porc_bonificacion',
                        $cols['porc_bonificacion']['xdef']) }}">%
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="comision_bancaria">
                    Comision bancaria</label>
                <input type="float" class="form-control" size="15" maxlength="15"
                    name="comision_bancaria" id="comision_bancaria" min="0.00"
                    placeholder="ddd.ddd,dd" value="{{ old('comision_bancaria') }}">
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="numero_recibo">
                    N&uacute;mero de recibo</label>
                <input type="text" class="form-control" size="30" maxlength="30"
                    name="numero_recibo" id="numero_recibo"
                    placeholder="Numero del recibo"
                    value="{{ old('numero_recibo') }}">
            </div>
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="porc_captador_prbr">
                    *Porcentaje captador PRBR</label>
                <input type="float" class="form-control" size="5" maxlength="5" min="0.000" max="50.000"
                    name="porc_captador_prbr" id="porc_captador_prbr" required
                    value="{{ old('porc_captador_prbr', $cols['porc_captador_prbr']['xdef']) }}">%
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="asesor_captador_id">
                    *Asesor captador</label>
                <select name="asesor_captador_id" id="asesor_captador_id">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                    @if (old("asesor_captador_id") == $user->id)
                        selected
                    @endif
                        >{{ $user->name }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="asesor_captador">
                    +Asesor</label>
                <input type="text" class="form-control" size="40" maxlength="100"
                    name="asesor_captador" id="asesor_captador"
                    placeholder="Nombre captador otra oficina"
                    value="{{ old('asesor_captador') }}">
            </div>
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="porc_cerrador_prbr">
                    *Porcentaje cerrador PRBR</label>
                <input type="float" class="form-control" size="5" maxlength="5"
                    name="porc_cerrador_prbr" id="porc_cerrador_prbr" required min="0.000" max="50.000"
                    value="{{ old('porc_cerrador_prbr', $cols['porc_cerrador_prbr']['xdef']) }}">%
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="asesor_cerrador_id">
                    *Asesor cerrador</label>
                <select name="asesor_cerrador_id" id="asesor_cerrador_id">
                    @foreach ($users as $user)
                    @if (old("asesor_cerrador_id") == $user->id)
                    <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                    @else
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="asesor_cerrador">
                    +Asesor</label>
                <input type="text" class="form-control" size="40" maxlength="100"
                    name="asesor_cerrador" id="asesor_cerrador"
                    placeholder="Nombre cerrador otra oficina"
                    value="{{ old('asesor_cerrador') }}">
            </div>
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="pago_gerente">
                    Pago gerente</label>
                <input type="text" class="form-control" size="40" maxlength="100"
                    name="pago_gerente" id="pago_gerente"
                    placeholder="Como se realizo pago a gerente" value="{{ old('pago_gerente') }}">
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="factura_gerente">
                    Factura gerente</label>
                <input type="text" class="form-control" size="40" maxlength="100"
                    name="factura_gerente" id="factura_gerente"
                    placeholder="Factura gerente" value="{{ old('factura_gerente') }}">
            </div>
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="pago_asesores">
                    Pago asesores</label>
                <input type="text" class="form-control" size="40" maxlength="100"
                    name="pago_asesores" id="pago_asesores"
                    placeholder="Como se realizo el pago a los asesores"
                    value="{{ old('pago_asesores') }}">
            </div>

            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="factura_asesores">
                    Factura asesores</label>
                <input type="text" class="form-control" size="40" maxlength="100"
                    name="factura_asesores" id="factura_asesores"
                    placeholder="Factura asesores" value="{{ old('factura_asesores') }}">
            </div>
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="pago_otra_oficina">
                    Pago otra oficina</label>
                <input type="text" class="form-control" size="100" maxlength="100"
                    name="pago_otra_oficina" id="pago_otra_oficina"
                    placeholder="Como se realizo el pago a otra oficina"
                    value="{{ old('pago_otra_oficina') }}">
            </div>
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="pagado_casa_nacional">
                    Pagado casa nacional</label>
                <input type="checkbox" class="form-control"
                    name="pagado_casa_nacional" id="pagado_casa_nacional"
                    {{ old('pagado_casa_nacional',
                        $cols['pagado_casa_nacional']['xdef']) ? "checked" : "" }}>
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="estatus_sistema_c21">
                    *Estatus sistema C21</label>
                <select class="form-control" name="estatus_sistema_c21" id="estatus_sistema_c21">
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
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="reporte_casa_nacional">
                    Reporte casa nacional</label>
                <input type="text" class="form-control"
                    name="reporte_casa_nacional" id="reporte_casa_nacional"
                    size="10" maxlength="10" placeholder="numero"
                    value="{{ old('reporte_casa_nacional') }}">
            </div>
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="comentarios">
                    Comentarios</label>
                <input type="text" class="form-control"
                    name="comentarios" id="comentarios"
                    size="100" maxlength="600" placeholder="comentarios........."
                    value="{{ old('comentarios') }}">
            </div>
            <div class="form-group form-inline mx-1 px-2">
                <label class="control-label" for="factura_AyS">
                    Factura A&S</label>
                <input type="text" class="form-control"
                    name="factura_AyS" id="factura_AyS"
                    size="100" maxlength="100" placeholder="numero de factura"
                    value="{{ old('factura_AyS') }}">
            </div>
        </div>
        </fieldset>
        @else
            <input type="hidden" name="porc_franquicia" value="{{ $cols['porc_franquicia']['xdef'] }}">
            <input type="hidden" name="reportado_casa_nacional" value="{{ $cols['reportado_casa_nacional']['xdef'] }}">
            <input type="hidden" name="porc_regalia" value="{{ $cols['porc_regalia']['xdef'] }}">
            <input type="hidden" name="porc_gerente" value="{{ $cols['porc_gerente']['xdef'] }}">
            <input type="hidden" name="porc_compartido" value="{{ $cols['porc_compartido']['xdef'] }}">
            <input type="hidden" name="porc_captador_prbr" value="{{ $cols['porc_captador_prbr']['xdef'] }}">
            <input type="hidden" name="porc_cerrador_prbr" value="{{ $cols['porc_cerrador_prbr']['xdef'] }}">
            <input type="hidden" name="porc_bonificacion" value="{{ $cols['porc_bonificacion']['xdef'] }}">
        @endif

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-1 px-2">
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
<script>
    $(document).ready(function(){
        $("fieldset.datosPropiedad").children("div").hide();  // Clase "datosPropiedad"
        if ('X' != $("#cliente_id").val()) {
            $("div.nuevo").hide();          // Clase "nuevo"
            $("#etiqDirCliente, #dirCliente, #etiqObservaciones, #observaciones").hide();     // Id's
        }

        $("#datosPropiedad").click(function(ev){         // Id "datosPropiedad"
            $("fieldset.datosPropiedad").children("div").toggle(1000);  // Clase "datosPropiedad"
            ev.preventDefault();
        })
        $("#cliente_id").change(function(ev){            // Id "cliente_id"
            if ('X' == $("#cliente_id").val()) {        // Indica que es nuevo cliente.
                $("div.nuevo, #etiqDirCliente, #dirCliente, #etiqObservaciones, #observaciones").show();
            } else {
                $("div.nuevo, #etiqDirCliente, #dirCliente, #etiqObservaciones, #observaciones").hide();
            }
        })
        $("#datosOficina").click(function(ev){           // Id "datosOficina"
            $("fieldset.datosOficina").children("div").toggle(1000);  // Clase "datosOficina"
            ev.preventDefault();
        })
        $("#asesor_captador_id").change(function(ev){            // Id "asesor_captador_id"
            if ('1' == $('#asesor_captador_id').val()) {
                alert('Proceda a incluir el nombre de la oficina CAPTADORA ' +
                        '(y el nombre del asesor CAPTADOR, si es posible)');
                $("#asesor_captador").focus();
            }
        })
        $("#asesor_cerrador_id").change(function(ev){            // Id "asesor_cerrador_id"
            if ('1' == $('#asesor_cerrador_id').val()) {
                alert('Proceda a incluir el nombre de la oficina CERRADORA ' +
                        '(y el nombre del asesor CERRADOR, si es posible)');
                $("#asesor_cerrador").focus();
            }
        })
        $("#fecha_reserva").change(function(ev) {
            var j;
            var opciones = $("#estatus option");
            for (j=0,l=opciones.length; j<l; j++) {
                if ('I' == opciones[j].value) break;
            }
            var pendiente = opciones[j].text;
            if ('' != $(this).val()) {
                alert('Al colocar la fecha de reserva, debemos comenzar una negociacion,' +
                        " por eso el <estatus> debe ser " + pendiente);
                $("#estatus").val('I');
                $("#estatus").focus();
            }
        })
        $("#fecha_firma").change(function(ev) {
            var j, k;
            var opciones = $("#estatus option");
            for (j=0,l=opciones.length; j<l; j++) {
                if ('P' == opciones[j].value) break;
            }
            var pagosPendientes = opciones[j].text;
            for (k=0,l=opciones.length; k<l; k++) {
                if ('C' == opciones[k].value) break;
            }
            if (('' == $("#fecha_reserva").val()) && ('' != $(this).val())) {
                alert("Una propiedad no deberia tener 'fecha de la firma':" +
                        $("#fecha_firma").val() + " y vacia la 'fecha de reserva'");
                var resp = confirm("Desea asignar la 'fecha de reserva' igual a la 'fecha de la firma'");
                if (resp) $("#fecha_reserva").val($(this).val());
                $("#fecha_reserva").focus();
                return
            }
            var cerrado = opciones[k].text;
            if ('' != $(this).val()) {
                alert('Al colocar la fecha de la firma, debemos comenzar el cierre de la negociacion,' +
                        " por eso el <estatus> debe ser " + pagosPendientes + ' o ' + cerrado);
                $("#estatus").val('P');
                $("#estatus").focus();
            }
        })
        $("#formulario").submit(function(ev){
//            alert('asesor_captador_id:'+$("#asesor_captador_id").val()+
//                    '|asesor_captador:'+$("#asesor_captador").val()+'|');
//            alert('asesor_cerrador_id:'+$("#asesor_cerrador_id").val()+
//                    '|asesor_cerrador:'+$("#asesor_cerrador").val()+'|');
//            alert('$("#cliente_id option:selected").text():'+$("#cliente_id option:selected").text()+'|$("#name").val():'+$("#name").val()+'|');
//            ev.preventDefault();
            if (('' == $("#fecha_reserva").val()) && ('' != $("#fecha_firma").val())) {
                ev.preventDefault();
                alert("Una propiedad no puede tener 'fecha de la firma':" +
                        $("#fecha_firma").val() + " y vacia la 'fecha de reserva'");
                return
            }
            if (('' != $("#fecha_reserva").val()) && (('A' == $("#estatus").val()) ||
                                                      ('S' == $("#estatus").val()))) {
                ev.preventDefault();
                alert("Una propiedad no puede tener 'fecha de reserva':" +
                        $("#fecha_reserva").val() + " y el 'estatus' [A]ctivo o [S]");
                return
            }
            if (('' != $("#fecha_firma").val()) && (('A' == $("#estatus").val()) ||
                        ('I' == $("#estatus").val()) || ('S' == $("#estatus").val()))) {
                ev.preventDefault();
                alert("Una propiedad no puede tener 'fecha de la firma':" + $("#fecha_firma").val()
                        + " y el 'estatus' [A]ctivo, [I]nmueble pendiente o [S]");
                return
            }
            if ('1' == $('#asesor_captador_id').val()) {
                if ('' == $('#asesor_captador').val()) {
                    ev.preventDefault();
                    alert('No puede crear esta propiedad sin agregar el nombre de la ' +
                        'oficina CAPTADORA (y, opcionalmente, el nombre del asesor CAPTADOR)');
                    $("#asesor_captador").focus();
                }
/*            } else if ('1' == $('#asesor_cerrador_id').val()) {
                if ('' == $('#asesor_cerrador').val()) {
                    ev.preventDefault();
                    alert('No puede crear esta propiedad sin agregar el nombre de la ' +
                        'oficina CERRADORA (y, opcionalmente, el nombre del asesor CERRADOR)');
                    $("#asesor_cerrador").focus();
                }*/
            } else if ('X' == $("#cliente_id").val()) {
                if ('' === $("#name").val()) {
                    ev.preventDefault();
                    alert("Usted seleccciono un 'Nuevo...' cliente; pero, no suministro su nombre!");
                    $("#name").focus();
                }
            } else {
                var name = $("#cliente_id option:selected").text();
                $("#name").val(name);   // Etiq 'Nombre' (Nombre del cliente). Evita errores de campo 'requerido'.
            }
        })
    })
</script>

@endsection