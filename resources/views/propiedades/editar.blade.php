@extends('layouts.app')

@section('content')
<div class="card m-0 p-0">
    <h4 class="card-header {{ $propiedad->estatus_color }} m-0 p-1">
        {{ $title }} [{{ $propiedad->id}}] {{ $propiedad->codigo }} 
    </h4>
    <div class="card-body m-0 p-0">
    @include('include.errorData')

        <form class="form align-items-end-horizontal" method="POST" id="formulario"
                action="{{ url("/propiedades/{$propiedad->id}") }}">
            {{ method_field('PUT') }}
            {!! csrf_field() !!}
            <!-- input name="_method" type="hidden" value="PUT" -->
            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="nombre">*Nombre</label>
                    <input type="text" class="form-control form-control-sm" size="100" maxlength="150"
                        name="nombre" id="nombre" required
                        value="{{ old('nombre', $propiedad->nombre) }}">
                </div>
            @if (0 < count($propiedad->imagenes))
                <div class="ml-2 p-0">
                    <button type="button"
                            class="btn btn-{{ substr($propiedad->estatus_color, strpos($propiedad->estatus_color, '-')+1) }}
                                btn-sm my-0 mx-1 p-0"
                            nombreBase="{{ $propiedad->id }}_{{ $propiedad->codigo }}"
                            img="{{ json_encode($propiedad->imagenes, JSON_FORCE_OBJECT) }}"
                            id="mostrarfotos" title="Presione para mostrar/esconder las fotos de la propiedad">
                        Mostrar fotos
                    </button>
                </div>
            @endif (0 < count($propiedad->imagenes))
            </div>

            <div class="form-row my-1 mx-0 p-0 bg-suave">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="estatus">*Estatus</label>
                    <select class="form-control form-control-sm" name="estatus" id="estatus">
                @foreach ($cols['estatus']['opcion'] as $opcion => $muestra)
                    <option value="{{$opcion}}"
                    @if (old('estatus', $propiedad->estatus) == $opcion)
                        selected
                    @endif (old('estatus', $propiedad->estatus) == $opcion)
                    @if (isset($colores))
                        class="{{ $colores[$opcion] }}"
                    @endif (isset($colores))
                        >{{$muestra}}</option>
                @endforeach
                    </select>
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="negociacion">
                        *Negociaci&oacute;n</label>
                    <select class="form-control form-control-sm" name="negociacion" id="negociacion">
                    <option value="">Tipo de negociacion?</option>
                @foreach (array('V' => 'Venta', 'A' => 'Alquiler') as $opcion => $muestra)
                    <option value="{{$opcion}}"
                    @if (old('negociacion', $propiedad->negociacion) == $opcion)
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
                            $propiedad->exclusividad) ? "checked" : "" }}>
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="fecha_inicial">*Fecha inicial</label>
                    <input type="date" class="form-control form-control-sm" name="fecha_inicial"
                            id="fecha_inicial"
                        @if (!Auth::user()->is_admin)   {{-- No es administrador --}}
                            max="{{ now('America/Caracas')->addWeeks(1)->format('Y-m-d') }}"
                        @endif (!Auth::user()->is_admin)   {{-- No es administrador --}}
                            value="{{ old('fecha_inicial', $propiedad->fecha_inicial_bd) }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="fecha_reserva">Reserva</label>
                    <input type="date" class="form-control form-control-sm" name="fecha_reserva"
                            id="fecha_reserva"
                        @if (!Auth::user()->is_admin)   {{-- No es administrador --}}
                            max="{{ now('America/Caracas')->addWeeks(1)->format('Y-m-d') }}"
                        @endif (!Auth::user()->is_admin)   {{-- No es administrador --}}
                            value="{{ old('fecha_reserva', $propiedad->fecha_reserva_bd) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="forma_pago_reserva_id">Forma de pago</label>
                    <select class="form-control form-control-sm" name="forma_pago_reserva_id" id="forma_pago_reserva_id"
                        data-toggle="tooltip" title="Forma de pago de la reserva">
                        <option value="">Forma de pago?</option>
                    @foreach ($forma_pagos as $forma_pago)
                        <option value="{{ $forma_pago->id }}"
                    @if (old('forma_pago_reserva_id',
                            $propiedad->forma_pago_reserva_id??'') == $forma_pago->id)
                        selected
                    @endif
                        >{{ $forma_pago->descripcion }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="factura_reserva">Factura</label>
                    <input type="text" class="form-control form-control-sm"
                        size="80" maxlength="100" name="factura_reserva" id="factura_reserva"
                        placeholder="factura de pago de la reserva"
                        data-toggle="tooltip" title="Factura de pago de la reserva"
                        value="{{ old('factura_reserva',
                            $propiedad->factura_reserva??'') }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0 bg-suave">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="fecha_firma">Firma</label>
                    <input type="date" class="form-control form-control-sm" name="fecha_firma"
                            id="fecha_firma"
                        @if (!Auth::user()->is_admin)   {{-- No es administrador --}}
                            max="{{ now('America/Caracas')->addWeeks(1)->format('Y-m-d') }}"
                        @endif (!Auth::user()->is_admin)   {{-- No es administrador --}}
                            value="{{ old('fecha_firma', $propiedad->fecha_firma_bd) }}">
                    <input type="hidden" name="fecha_firma_ant" value="{{ $propiedad->fecha_firma }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="forma_pago_firma">Forma de pago</label>
                    <select class="form-control form-control-sm" name="forma_pago_firma_id" id="forma_pago_firma_id"
                        data-toggle="tooltip" title="Forma de pago a la firma">
                        <option value="">Forma de pago?</option>
                    @foreach ($forma_pagos as $forma_pago)
                        <option value="{{ $forma_pago->id }}"
                    @if (old('forma_pago_firma_id',
                            $propiedad->forma_pago_firma_id??'') == $forma_pago->id)
                        selected
                    @endif
                        >{{ $forma_pago->descripcion }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="factura_firma">Factura</label>
                    <input type="text" class="form-control form-control-sm"
                        size="80" maxlength="100" name="factura_firma" id="factura_firma"
                        placeholder="factura de pago a la firma"
                        data-toggle="tooltip" title="Factura de pago a la firma"
                        value="{{ old('factura_firma',
                            $propiedad->factura_firma??'') }}">
                </div>
            </div>

        @if (!Auth::user()->is_admin)       {{-- No es administrador --}}
            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="asesor_captador_id">
                        *Asesor captador</label>
                    <select name="asesor_captador_id" id="asesor_captador_id">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                        @if (old("asesor_captador_id",
                                $propiedad->asesor_captador_id) == $user->id)
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
                        value="{{ old('asesor_captador', $propiedad->asesor_captador) }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0 bg-suave">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="asesor_cerrador_id">
                        *Asesor cerrador</label>
                    <select name="asesor_cerrador_id" id="asesor_cerrador_id">
                @foreach ($users as $user)
                    @if (old("asesor_cerrador_id", $propiedad->asesor_cerrador_id) == $user->id)
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
                        value="{{ old('asesor_cerrador', $propiedad->asesor_cerrador) }}">
                </div>
            </div>
        @endif (!Auth::user()->is_admin)

            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="precio">*Precio</label>
                    <input class="form-control form-control-sm" size="2" maxlength="2"
                        required name="moneda" id="moneda"
                        value="{{ old('moneda', $propiedad->moneda) }}" list="monedas">
                    <datalist id="monedas">
                    @foreach ($cols['moneda']['opcion'] as $opcion => $muestra)
                        <option value="{{ $opcion }}">
                    @endforeach
                    </datalist>
                    <input type="float" class="form-control form-control-sm" required
                        size="20" maxlength="20" name="precio" id="precio"
                        placeholder="Precio del inmueble"
                        value="{{ old('precio', $propiedad->precio) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="comision">*Comision</label>
                    <input type="float" class="form-control form-control-sm" size="6"
                        maxlength="6" name="comision" id="comision" required
                        value="{{ old('comision', $propiedad->comision) }}">%
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="iva">*IVA</label>
                    <input type="float" class="form-control form-control-sm"
                        required size="5" maxlength="5" name="iva" id="iva"
                        value="{{ old('iva', $propiedad->iva) }}">%
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="lados">Lados</label>
                    <input type="number" class="form-control form-control-sm"
                        size="3" required name="lados" id="lados"
                        value="{{ old('lados', $propiedad->lados) }}">
                </div>
            </div>

        @if (0 < count($propiedad->imagenes))
            <div id="fotosestaticas">
            </div>
        @endif (0 < count($propiedad->imagenes))

            <fieldset class="datosPropiedad" style="border:solid 2px #000000">
                <legend>
                    <button id="datosPropiedad" title="Presione para mostrar/esconder los datos de la propiedad">
                        Datos de la propiedad
                    </button>
                </legend>

            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline my-0 mx-1 py-0 px-2">
                    <label class="control-label" for="tipo_id">Tipo</label>
                    <select class="form-control form-control-sm" name="tipo_id" id="tipo_id">
                        <option value="">Qué tipo?</option>
                @foreach ($tipos as $tipo)
                    @if (old('tipo_id', $propiedad->tipo_id) == $tipo->id)
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
                        name="metraje" id="metraje"
                        value="{{ old('metraje', $propiedad->metraje) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="habitaciones">Habitaciones</label>
                    <input type="integer" class="form-control form-control-sm" size="2" maxlength="3"
                        name="habitaciones" id="habitaciones"
                        value="{{ old('habitaciones', $propiedad->habitaciones) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="banos">Ba&ntilde;os</label>
                    <input type="integer" class="form-control form-control-sm" size="1" maxlength="2"
                        name="banos" id="banos"
                        value="{{ old('banos', $propiedad->banos) }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0 bg-suave">
                <div class="form-group form-inline my-0 mx-1 py-0 px-2">
                    <label class="control-label" for="niveles">Niveles</label>
                    <input type="float" class="form-control form-control-sm" size="2" maxlength="3"
                        name="niveles" id="niveles"
                        value="{{ old('niveles', $propiedad->niveles) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="puestos">Puestos de estacionamiento</label>
                    <input type="integer" class="form-control form-control-sm" size="2" maxlength="3"
                        name="puestos" id="puestos"
                        value="{{ old('puestos', $propiedad->puestos) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="anoc">A&ntilde;o de construccion</label>
                    <input type="integer" class="form-control form-control-sm" size="4" maxlength="4"
                        name="anoc" id="anoc"
                        value="{{ old('anoc', $propiedad->anoc) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="caracteristica">Caracteristicas</label>
                    <select class="form-control" name="caracteristica_id" id="tipo_id">
                        <option value="">Qué caracteristica?</option>
                    @foreach ($caracteristicas as $caracteristica)
                    @if (old('caracteristica_id', $propiedad->caracteristica_id) == $caracteristica->id)
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

            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group col-lg-12 d-flex mx-1 px-2">
                    <label class="control-label" for="descripcion" id="etiqDescripcion">Descripci&oacute;n</label>
                    <textarea class="form-control form-control-sm" rows="5" name="descripcion" id="descripcion"
                    placeholder="Descripcion detallada de la propiedad">{{ $propiedad->descripcion }}</textarea>
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0 bg-suave">
                <div class="form-group col-lg-12 d-flex mx-1 px-2">
                    <label class="control-label" for="direccion" id="etiqDireccion">Direcci&oacute;n</label>
                    <textarea class="form-control form-control-sm" rows="3" name="direccion" id="direccion"
                    placeholder="Calle, Casa, Apto, Edificio, Barrio, etc.">{{ $propiedad->direccion }}</textarea>
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline my-0 mx-1 py-0 px-2">
                    <label class="control-label" for="ciudad">Ciudad</label>
                    <select class="form-control form-control-sm" name="ciudad_id" id="ciudad_id">
                        <option value="">Qué ciudad?</option>
                @foreach ($ciudades as $ciudad)
                    @if (old('ciudad_id', $propiedad->ciudad_id) == $ciudad->id)
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
                        value="{{ old('codigo_postal', $propiedad->codigo_postal) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="municipio_id">Municipio</label>
                    <select class="form-control form-control-sm" name="municipio_id" id="municipio_id">
                        <option value="">Qué municipio?</option>
                @foreach ($municipios as $municipio)
                    @if (old('municipio_id', $propiedad->municipio_id) == $municipio->id)
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
                    @if (old('estado_id', $propiedad->estado_id) == $estado->id)
                        <option value="{{ $estado->id }}" selected>{{ $estado->descripcion }}</option>
                    @else
                        <option value="{{ $estado->id }}">{{ $estado->descripcion }}</option>
                    @endif
                @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0 bg-suave">
                <div class="form-group form-inline my-0 mx-1 py-0 px-2">
                    <label class="control-label" for="cliente_id">Cliente</label>
                    <select class="form-control form-control-sm" name="cliente_id" id="cliente_id">
                        <option value="">Qué cliente?</option>
                @foreach ($clientes as $cliente)
                    @if (old('cliente_id', $propiedad->cliente_id) == $cliente->id)
                        <option value="{{ $cliente->id }}" selected>{{ $cliente->name }}</option>
                    @else
                        <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                    @endif
                @endforeach
                    </select>
                </div>
            </div>
            </fieldset>

        @if (Auth::user()->is_admin)
            <fieldset class="datosOficina m-0 p-0" style="border:solid 2px #000000">
                <legend class="my-0 mx-1 p-0">
                    <button class="my-0 mx-1 p-0" id="datosOficina" title="Presione para mostrar/esconder los datos de la oficina">
                        Datos administrativos
                    </button>
                </legend>
            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="porc_franquicia">
                        *Franquicia</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="porc_franquicia" id="porc_franquicia" required
                        value="{{ old('porc_franquicia', $propiedad->porc_franquicia) }}">%
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="reportado_casa_nacional">
                        *Reportado casa nacional</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="reportado_casa_nacional" id="reportado_casa_nacional"
                        value="{{ old('reportado_casa_nacional',
                            $propiedad->reportado_casa_nacional) }}">%
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="porc_regalia">*Regalia</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="porc_regalia" id="porc_regalia"
                        required value="{{ old('porc_regalia', $propiedad->porc_regalia) }}">%
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="porc_gerente">
                        *Porc gerente</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="porc_gerente" id="porc_gerente" required
                        value="{{ old('porc_gerente', $propiedad->porc_gerente) }}">%
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="porc_compartido">
                        *Porc compartido</label>
                    <input type="float" class="form-control" size="5" maxlength="5"
                        name="porc_compartido" id="porc_compartido" required
                        value="{{ old('porc_compartido', $cols['porc_compartido']['xdef']) }}">%
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0 bg-suave">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="porc_bonificacion">
                        *Porcentaje bonificacion</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="porc_bonificacion" id="porc_bonificacion" required
                        value="{{ old('porc_bonificacion', $propiedad->porc_bonificacion) }}">%
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="comision_bancaria">
                        Comision bancaria</label>
                    <input type="float" class="form-control form-control-sm" size="15" maxlength="15"
                        name="comision_bancaria" id="comision_bancaria"
                        value="{{ old('comision_bancaria', $propiedad->comision_bancaria) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="numero_recibo">
                        N&uacute;mero de recibo</label>
                    <input type="text" class="form-control form-control-sm" size="30" maxlength="30"
                        name="numero_recibo" id="numero_recibo"
                        value="{{ old('numero_recibo', $propiedad->numero_recibo) }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="porc_captador_prbr">
                        *Porcentaje captador PRBR</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="porc_captador_prbr" id="porc_captador_prbr" required
                        value="{{ old('porc_captador_prbr', $propiedad->porc_captador_prbr) }}">%
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="asesor_captador_id">
                        *Asesor captador</label>
                    <select name="asesor_captador_id" id="asesor_captador_id">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                        @if (old("asesor_captador_id",
                                $propiedad->asesor_captador_id) == $user->id)
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
                        value="{{ old('asesor_captador', $propiedad->asesor_captador) }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0 bg-suave">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="fecha_captador">Fecha de pago</label>
                    <input type="date" class="form-control form-control-sm" name="fecha_captador"
                        id="fecha_captador"
                        {{--max="{{ now('America/Caracas')->addWeeks(4)->format('Y-m-d') }}"--}}
                        data-toggle="tooltip" title="Fecha de pago al asesor captador"
                        value="{{ old('fecha_captador', $propiedad->fecha_captador_bd) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="forma_pago_captador">Forma de pago</label>
                    <select class="form-control form-control-sm" name="forma_pago_captador_id" id="forma_pago_captador_id"
                        data-toggle="tooltip" title="Forma de pago al asesor captador">
                        <option value="">Forma de pago?</option>
                    @foreach ($forma_pagos as $forma_pago)
                        <option value="{{ $forma_pago->id }}"
                    @if (old('forma_pago_captador_id',
                            $propiedad->forma_pago_captador_id??'') == $forma_pago->id)
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
                        data-toggle="tooltip" title="Factura de pago al asesor captador"
                        value="{{ old('factura_captador',
                            $propiedad->factura_captador??'') }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="porc_cerrador_prbr">
                        *Porcentaje cerrador PRBR</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="porc_cerrador_prbr" id="porc_cerrador_prbr" required
                        value="{{ old('porc_cerrador_prbr', $propiedad->porc_cerrador_prbr) }}">%
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="asesor_cerrador_id">
                        *Asesor cerrador</label>
                    <select name="asesor_cerrador_id" id="asesor_cerrador_id">
                @foreach ($users as $user)
                    @if (old("asesor_cerrador_id", $propiedad->asesor_cerrador_id) == $user->id)
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
                        value="{{ old('asesor_cerrador', $propiedad->asesor_cerrador) }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0 bg-suave">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="fecha_cerrador">Fecha de pago</label>
                    <input type="date" class="form-control form-control-sm" name="fecha_cerrador"
                        id="fecha_cerrador"
                        {{--max="{{ now('America/Caracas')->addWeeks(4)->format('Y-m-d') }}"--}}
                        data-toggle="tooltip" title="Fecha de pago al asesor cerrador"
                        value="{{ old('fecha_cerrador', $propiedad->fecha_cerrador_bd) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="forma_pago_cerrador">Forma de pago</label>
                    <select class="form-control form-control-sm" name="forma_pago_cerrador_id"
                        id="forma_pago_cerrador_id"
                        data-toggle="tooltip" title="Forma de pago al asesor cerrador">
                        <option value="">Forma de pago?</option>
                    @foreach ($forma_pagos as $forma_pago)
                        <option value="{{ $forma_pago->id }}"
                    @if (old('forma_pago_cerrador_id',
                            $propiedad->forma_pago_cerrador_id??'') == $forma_pago->id)
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
                        data-toggle="tooltip" title="Factura de pago al asesor cerrador"
                        value="{{ old('factura_cerrador',
                            $propiedad->factura_cerrador??'') }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="pago_gerente">
                        Pago gerente</label>
                    <input type="text" class="form-control form-control-sm" size="100" maxlength="100"
                        name="pago_gerente" id="pago_gerente"
                        value="{{ old('pago_gerente', $propiedad->pago_gerente) }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0 bg-suave">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="fecha_gerente">Fecha de pago</label>
                    <input type="date" class="form-control form-control-sm" name="fecha_gerente"
                        id="fecha_gerente"
                        {{--max="{{ now('America/Caracas')->addWeeks(4)->format('Y-m-d') }}"--}}
                        data-toggle="tooltip" title="Fecha de pago al gerente"
                        value="{{ old('fecha_gerente', $propiedad->fecha_gerente_bd) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="forma_pago_gerente">Forma de pago</label>
                    <select class="form-control form-control-sm" name="forma_pago_gerente_id"
                        id="forma_pago_gerente_id"
                        data-toggle="tooltip" title="Forma de pago al gerente">
                        <option value="">Forma de pago?</option>
                    @foreach ($forma_pagos as $forma_pago)
                        <option value="{{ $forma_pago->id }}"
                    @if (old('forma_pago_gerente_id',
                            $propiedad->forma_pago_gerente_id??'') == $forma_pago->id)
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
                        data-toggle="tooltip" title="Factura de pago al gerente"
                        value="{{ old('factura_gerente',
                            $propiedad->factura_gerente??'') }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0 bg-danger">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="pago_asesores">
                        Pago asesores</label>
                    <input type="text" class="form-control form-control-sm" size="40" maxlength="100"
                        name="pago_asesores" id="pago_asesores"
                        value="{{ old('pago_asesores', $propiedad->pago_asesores) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="factura_asesores">
                        Factura asesores</label>
                    <input type="text" class="form-control form-control-sm" size="40" maxlength="100"
                        name="factura_asesores" id="factura_asesores"
                        value="{{ old('factura_asesores', $propiedad->factura_asesores) }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="pago_otra_oficina">
                        Pago otra oficina</label>
                    <input type="text" class="form-control form-control-sm" size="100" maxlength="100"
                        name="pago_otra_oficina" id="pago_otra_oficina"
                        placeholder="Como se realizo el pago a otra oficina"
                        value="{{ old('pago_otra_oficina', $propiedad->pago_otra_oficina) }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0 bg-suave">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="fecha_otra_oficina">Fecha de pago</label>
                    <input type="date" class="form-control form-control-sm" name="fecha_otra_oficina"
                        id="fecha_otra_oficina"
                        {{--max="{{ now('America/Caracas')->addWeeks(4)->format('Y-m-d') }}"--}}
                        data-toggle="tooltip" title="Fecha de pago a la otra oficina"
                        value="{{ old('fecha_otra_oficina', $propiedad->fecha_otra_oficina_bd) }}">
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="forma_pago_otra_oficina">Forma de pago</label>
                    <select class="form-control form-control-sm" name="forma_pago_otra_oficina_id" id="forma_pago_otra_oficina_id"
                        data-toggle="tooltip" title="Forma de pago a la otra oficina">
                        <option value="">Forma de pago?</option>
                    @foreach ($forma_pagos as $forma_pago)
                        <option value="{{ $forma_pago->id }}"
                    @if (old('forma_pago_otra_oficina_id',
                            $propiedad->forma_pago_otra_oficina_id??'') == $forma_pago->id)
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
                        placeholder="factura de pago a la otra oficina"
                        data-toggle="tooltip" title="Factura de pago a la otra oficina"
                        value="{{ old('factura_otra_oficina',
                            $propiedad->factura_otra_oficina??'') }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="pagado_casa_nacional">
                        Pagado casa nacional</label>
                    <input type="checkbox" class="form-control form-control-sm"
                        name="pagado_casa_nacional" id="pagado_casa_nacional"
                        {{ old('pagado_casa_nacional',
                            $propiedad->pagado_casa_nacional) ? "checked" : "" }}>
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="estatus_sistema_c21">
                        *Estatus sistema C21</label>
                    <select name="estatus_sistema_c21" id="estatus_sistema_c21">
                        <option value="">Estatus?</option>
                @foreach (array('V' => 'Vendido', 'P' => 'Pendiente') as $opcion => $muestra)
                        <option value="{{$opcion}}"
                    @if (old('estatus_sistema_c21', $propiedad->estatus_sistema_c21) == $opcion)
                            selected
                    @endif
                        >{{$muestra}}</option>
                @endforeach
                    </select>
                </div>
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="reporte_casa_nacional">
                        Reporte casa nacional</label>
                    <input type="text" class="form-control form-control-sm" size="10" maxlength="10"
                        name="reporte_casa_nacional" id="reporte_casa_nacional"
                        value="{{ old('reporte_casa_nacional', $propiedad->reporte_casa_nacional) }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0 bg-suave">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="comentarios">
                        Comentarios</label>
                    <input type="text" class="form-control form-control-sm"
                        name="comentarios" id="comentarios" size="100" maxlength="600"
                        value="{{ old('comentarios', $propiedad->comentarios) }}">
                </div>
            </div>

            <div class="form-row my-1 mx-0 p-0">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <label class="control-label" for="factura_AyS">
                        Factura A&S</label>
                    <input type="text" class="form-control form-control-sm"
                        size="100" maxlength="100" name="factura_AyS" id="factura_AyS"
                        value="{{ old('factura_AyS', $propiedad->factura_AyS) }}">
                </div>
            </div>
            </fieldset>
        @else (Auth::user()->is_admin)
                <input type="hidden" name="porc_franquicia" value="{{ $propiedad->porc_franquicia }}">
                <input type="hidden" name="reportado_casa_nacional" value="{{ $propiedad->reportado_casa_nacional }}">
                <input type="hidden" name="porc_regalia" value="{{ $propiedad->porc_regalia }}">
                <input type="hidden" name="porc_gerente" value="{{ $propiedad->porc_gerente }}">
                {{--<input type="hidden" name="porc_compartido" value="{{ $cols['porc_compartido']['xdef'] }}">--}}
                <input type="hidden" name="porc_compartido" value="{{ $propiedad->porc_compartido }}">
                <input type="hidden" name="porc_captador_prbr" value="{{ $propiedad->porc_captador_prbr }}">
                <input type="hidden" name="porc_cerrador_prbr" value="{{ $propiedad->porc_cerrador_prbr }}">
                <input type="hidden" name="porc_bonificacion" value="{{ $propiedad->porc_bonificacion }}">
                {{--<input type="hidden" name="forma_pago_reserva_id" value="{{ $propiedad->forma_pago_reserva_id }}">
                <input type="hidden" name="factura_reserva" value="{{ $propiedad->factura_reserva }}">
                <input type="hidden" name="forma_pago_firma_id" value="{{ $propiedad->forma_pago_firma_id }}">
                <input type="hidden" name="factura_firma" value="{{ $propiedad->factura_firma }}">--}}
                <input type="hidden" name="fecha_captador" value="{{ $propiedad->fecha_captador }}">
                <input type="hidden" name="forma_pago_captador_id" value="{{ $propiedad->forma_pago_captador_id }}">
                <input type="hidden" name="factura_captador" value="{{ $propiedad->factura_captador }}">
                <input type="hidden" name="fecha_cerrador" value="{{ $propiedad->fecha_cerrador }}">
                <input type="hidden" name="forma_pago_cerrador_id" value="{{ $propiedad->forma_pago_cerrador_id }}">
                <input type="hidden" name="factura_cerrador" value="{{ $propiedad->factura_cerrador }}">
                <input type="hidden" name="fecha_cerrador" value="{{ $propiedad->fecha_cerrador }}">
                <input type="hidden" name="forma_pago_cerrador_id" value="{{ $propiedad->forma_pago_cerrador_id }}">
                <input type="hidden" name="factura_cerrador" value="{{ $propiedad->factura_cerrador }}">
                <input type="hidden" name="fecha_gerente" value="{{ $propiedad->fecha_gerente }}">
                <input type="hidden" name="forma_pago_gerente_id" value="{{ $propiedad->forma_pago_gerente_id }}">
                <input type="hidden" name="factura_gerente" value="{{ $propiedad->factura_gerente }}">
                <input type="hidden" name="fecha_otra_oficina" value="{{ $propiedad->fecha_otra_oficina }}">
                <input type="hidden" name="forma_pago_otra_oficina_id" value="{{ $propiedad->forma_pago_otra_oficina_id }}">
                <input type="hidden" name="factura_otra_oficina" value="{{ $propiedad->factura_otra_oficina }}">
                <input type="hidden" name="estatus_sistema_c21" value="{{ $propiedad->estatus_sistema_c21 }}">
        @endif (Auth::user()->is_admin)

            <div class="form-row my-1 py-1">
                <div class="form-group form-inline m-0 py-0 px-1">
                    <button type="submit" class="btn btn-primary">
                        Actualizar Propiedad
                    </button>
                    <!-- a href="{{ action('PropiedadController@index') }}">Regresar al listado de usuarios</a -->
                    <!--a href="{{ url('/propiedades/orden/'.$orden).$nroPagina }}" class="btn btn-link"-->
                    <a href="{{ route('propiedades.orden', $orden).$nroPagina }}" class="btn btn-link">
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