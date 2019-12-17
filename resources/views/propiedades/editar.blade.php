@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">{{ $title }}
        [{{ $propiedad->id}}] {{ $propiedad->codigo }} 
    </h4>
    <div class="card-body">
    @include('include.errorData')

        <form class="form align-items-end-horizontal" method="POST"  id="formulario"
                action="{{ url("/propiedades/{$propiedad->id}") }}">
            {{ method_field('PUT') }}
            {!! csrf_field() !!}
            <!-- input name="_method" type="hidden" value="PUT" -->
            <div class="form-row my-0 py-0">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="nombre">*Nombre</label>
                    <input type="text" class="form-control form-control-sm" size="100" maxlength="150"
                        name="nombre" id="nombre" required
                        value="{{ old('nombre', $propiedad->nombre) }}">
                </div>
            </div>

            <div class="form-row my-0 py-0 bg-suave">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="estatus">*Estatus</label>
                    <select class="form-control form-control-sm" name="estatus" id="estatus">
                @foreach ($cols['estatus']['opcion'] as $opcion => $muestra)
                    <option value="{{$opcion}}"
                    @if (old('estatus', $propiedad->estatus) == $opcion)
                        selected
                    @endif (old('estatus', $propiedad->estatus) == $opcion)
                        >{{$muestra}}</option>
                @endforeach
                    </select>
                </div>
                <div class="form-group form-inline mx-1 px-2">
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
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="exclusividad">
                        *Exclusividad</label>
                    <input type="checkbox" class="form-control"
                        name="exclusividad" id="exclusividad"
                        {{ old('exclusividad',
                            $propiedad->exclusividad) ? "checked" : "" }}>
                </div>
            </div>

            <div class="form-row my-0 py-0">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="fecha_reserva">Reserva</label>
                    <input type="date" class="form-control form-control-sm" name="fecha_reserva"
                        id="fecha_reserva" max="{{ now()->addWeeks(4)->format('d/m/Y') }}"
                        value="{{ old('fecha_reserva',
                            ($propiedad->fecha_reserva)?$propiedad->fecha_reserva_bd:'') }}">
                <div class="form-group form-inline mx-1 px-2">
                </div>
                    <label class="control-label" for="fecha_firma">Firma</label>
                    <input type="date" class="form-control form-control-sm" name="fecha_firma"
                        id="fecha_firma" max="{{ now()->addWeeks(4)->format('d/m/Y') }}"
                        value="{{ old('fecha_firma', $propiedad->fecha_firma_bd) }}">
                    <input type="hidden" name="fecha_firma_ant" value="{{ $propiedad->fecha_firma }}">
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
                        @if (old("asesor_captador_id",
                                $propiedad->asesor_captador_id) == $user->id)
                            selected
                        @endif
                            >{{ $user->name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="asesor_captador">
                        +Nombre asesor captador otra oficina</label>
                    <input type="text" class="form-control form-control-sm" size="60"
                        maxlength="100" name="asesor_captador" id="asesor_captador"
                        value="{{ old('asesor_captador', $propiedad->asesor_captador) }}">
                </div>
            </div>

            <div class="form-row my-0 py-0 bg-suave">
                <div class="form-group form-inline mx-1 px-2">
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
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="asesor_cerrador">
                        +Nombre asesor cerrador otra oficina</label>
                    <input type="text" class="form-control form-control-sm" size="60"
                        maxlength="100" name="asesor_cerrador" id="asesor_cerrador"
                        value="{{ old('asesor_cerrador', $propiedad->asesor_cerrador) }}">
                </div>
            </div>
        @endif (!Auth::user()->is_admin)

            <div class="form-row my-0 py-0 bg-suave">
                <div class="form-group form-inline mx-1 px-2">
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
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="comision">*Comision</label>
                    <input type="float" class="form-control form-control-sm" size="6"
                        maxlength="6" name="comision" id="comision" required
                        value="{{ old('comision', $propiedad->comision) }}">%
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="iva">*IVA</label>
                    <input type="float" class="form-control form-control-sm"
                        required size="5" maxlength="5" name="iva" id="iva"
                        value="{{ old('iva', $propiedad->iva) }}">%
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="lados">Lados</label>
                    <input type="number" class="form-control form-control-sm" size="1"
                        maxlength="1" required name="lados" id="lados"
                        value="{{ old('lados', $propiedad->lados) }}">
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
                    @if (old('tipo_id', $propiedad->tipo_id) == $tipo->id)
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
                        name="metraje" id="metraje"
                        value="{{ old('metraje', $propiedad->metraje) }}">
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="habitaciones">Habitaciones</label>
                    <input type="integer" class="form-control form-control-sm" size="2" maxlength="3"
                        name="habitaciones" id="habitaciones"
                        value="{{ old('habitaciones', $propiedad->habitaciones) }}">
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="banos">Ba&ntilde;os</label>
                    <input type="integer" class="form-control form-control-sm" size="1" maxlength="2"
                        name="banos" id="banos"
                        value="{{ old('banos', $propiedad->banos) }}">
                </div>
            </div>

            <div class="form-row py-0 bg-suave">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="niveles">Niveles</label>
                    <input type="float" class="form-control form-control-sm" size="2" maxlength="3"
                        name="niveles" id="niveles"
                        value="{{ old('niveles', $propiedad->niveles) }}">
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="puestos">Puestos de estacionamiento</label>
                    <input type="integer" class="form-control form-control-sm" size="2" maxlength="3"
                        name="puestos" id="puestos"
                        value="{{ old('puestos', $propiedad->puestos) }}">
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="anoc">A&ntilde;o de construccion</label>
                    <input type="integer" class="form-control form-control-sm" size="4" maxlength="4"
                        name="anoc" id="anoc"
                        value="{{ old('anoc', $propiedad->anoc) }}">
                </div>
                <div class="form-group form-inline mx-1 px-2">
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

            <div class="form-row py-0">
                <div class="form-group col-lg-12 d-flex mx-1 px-2">
                    <label class="control-label" for="descripcion" id="etiqDescripcion">Descripci&oacute;n</label>
                    <textarea class="form-control form-control-sm" rows="5" name="descripcion" id="descripcion"
                    placeholder="Descripcion detallada de la propiedad">{{ $propiedad->descripcion }}</textarea>
                </div>
            </div>

            <div class="form-row py-0 bg-suave">
                <div class="form-group col-lg-12 d-flex mx-1 px-2">
                    <label class="control-label" for="direccion" id="etiqDireccion">Direcci&oacute;n</label>
                    <textarea class="form-control form-control-sm" rows="3" name="direccion" id="direccion"
                    placeholder="Calle, Casa, Apto, Edificio, Barrio, etc.">{{ $propiedad->direccion }}</textarea>
                </div>
            </div>

            <div class="form-row py-0">
                <div class="form-group form-inline mx-1 px-2">
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
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="codigo_postal">Codigo postal</label>
                    <input type="float" class="form-control form-control-sm" size="8" maxlength="11"
                        name="codigo_postal" id="codigo_postal"
                        value="{{ old('codigo_postal', $propiedad->codigo_postal) }}">
                </div>
                <div class="form-group form-inline mx-1 px-2">
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
                <div class="form-group form-inline mx-1 px-2">
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

            <div class="form-row py-0 bg-suave">
                <div class="form-group form-inline mx-1 px-2">
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
            <fieldset class="datosOficina" style="border:solid 2px #000000">
                <legend>
                    <button id="datosOficina" title="Presione para mostrar/esconder los datos de la oficina">
                        Datos administrativos
                    </button>
                </legend>
            <div class="form-row my-0 py-0">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="porc_franquicia">
                        *Franquicia</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="porc_franquicia" id="porc_franquicia" required
                        value="{{ old('porc_franquicia', $propiedad->porc_franquicia) }}">%
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="reportado_casa_nacional">
                        *Reportado casa nacional</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="reportado_casa_nacional" id="reportado_casa_nacional"
                        value="{{ old('reportado_casa_nacional',
                            $propiedad->reportado_casa_nacional) }}">%
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="porc_regalia">*Regalia</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="porc_regalia" id="porc_regalia"
                        required value="{{ old('porc_regalia', $propiedad->porc_regalia) }}">%
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="porc_gerente">
                        *Porc gerente</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="porc_gerente" id="porc_gerente" required
                        value="{{ old('porc_gerente', $propiedad->porc_gerente) }}">%
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="porc_compartido">
                        *Porc compartido</label>
                    <input type="float" class="form-control" size="5" maxlength="5"
                        name="porc_compartido" id="porc_compartido" required
                        value="{{ old('porc_compartido', $cols['porc_compartido']['xdef']) }}">%
                </div>
            </div>

            <div class="form-row my-0 py-0 bg-suave">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="porc_bonificacion">
                        *Porcentaje bonificacion</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="porc_bonificacion" id="porc_bonificacion" required
                        value="{{ old('porc_bonificacion', $propiedad->porc_bonificacion) }}">%
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="comision_bancaria">
                        Comision bancaria</label>
                    <input type="float" class="form-control form-control-sm" size="15" maxlength="15"
                        name="comision_bancaria" id="comision_bancaria"
                        value="{{ old('comision_bancaria', $propiedad->comision_bancaria) }}">
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="numero_recibo">
                        N&uacute;mero de recibo</label>
                    <input type="text" class="form-control form-control-sm" size="30" maxlength="30"
                        name="numero_recibo" id="numero_recibo"
                        value="{{ old('numero_recibo', $propiedad->numero_recibo) }}">
                </div>
            </div>

            <div class="form-row my-0 py-0">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="porc_captador_prbr">
                        *Porcentaje captador PRBR</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="porc_captador_prbr" id="porc_captador_prbr" required
                        value="{{ old('porc_captador_prbr', $propiedad->porc_captador_prbr) }}">%
                </div>
                <div class="form-group form-inline mx-1 px-2">
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
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="asesor_captador">
                        +Asesor</label>
                    <input type="text" class="form-control form-control-sm" size="40" maxlength="100"
                        name="asesor_captador" id="asesor_captador"
                        value="{{ old('asesor_captador', $propiedad->asesor_captador) }}">
                </div>
            </div>

            <div class="form-row my-0 py-0 bg-suave">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="porc_cerrador_prbr">
                        *Porcentaje cerrador PRBR</label>
                    <input type="float" class="form-control form-control-sm" size="5" maxlength="5"
                        name="porc_cerrador_prbr" id="porc_cerrador_prbr" required
                        value="{{ old('porc_cerrador_prbr', $propiedad->porc_cerrador_prbr) }}">%
                </div>
                <div class="form-group form-inline mx-1 px-2">
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
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="asesor_cerrador">
                        +Asesor</label>
                    <input type="text" class="form-control form-control-sm" size="40" maxlength="100"
                        name="asesor_cerrador" id="asesor_cerrador"
                        value="{{ old('asesor_cerrador', $propiedad->asesor_cerrador) }}">
                </div>
            </div>

            <div class="form-row my-0 py-0">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="pago_gerente">
                        Pago gerente</label>
                    <input type="text" class="form-control form-control-sm" size="40" maxlength="100"
                        name="pago_gerente" id="pago_gerente"
                        value="{{ old('pago_gerente', $propiedad->pago_gerente) }}">
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="factura_gerente">
                        Factura gerente</label>
                    <input type="text" class="form-control form-control-sm" size="40" maxlength="100"
                        name="factura_gerente" id="factura_gerente"
                        value="{{ old('factura_gerente', $propiedad->factura_gerente) }}">
                </div>
            </div>

            <div class="form-row my-0 py-0 bg-suave">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="pago_asesores">
                        Pago asesores</label>
                    <input type="text" class="form-control form-control-sm" size="40" maxlength="100"
                        name="pago_asesores" id="pago_asesores"
                        value="{{ old('pago_asesores', $propiedad->pago_asesores) }}">
                </div>
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="factura_asesores">
                        Factura asesores</label>
                    <input type="text" class="form-control form-control-sm" size="40" maxlength="100"
                        name="factura_asesores" id="factura_asesores"
                        value="{{ old('factura_asesores', $propiedad->factura_asesores) }}">
                </div>
            </div>

            <div class="form-row my-0 py-0">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="pago_otra_oficina">
                        Pago otra oficina</label>
                    <input type="text" class="form-control form-control-sm" size="100" maxlength="100"
                        name="pago_otra_oficina" id="pago_otra_oficina"
                        placeholder="Como se realizo el pago a otra oficina"
                        value="{{ old('pago_otra_oficina', $propiedad->pago_otra_oficina) }}">
                </div>
            </div>

            <div class="form-row my-0 py-0 bg-suave">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="pagado_casa_nacional">
                        Pagado casa nacional</label>
                    <input type="checkbox" class="form-control form-control-sm"
                        name="pagado_casa_nacional" id="pagado_casa_nacional"
                        {{ old('pagado_casa_nacional',
                            $propiedad->pagado_casa_nacional) ? "checked" : "" }}>
                </div>
                <div class="form-group form-inline mx-1 px-2">
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
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="reporte_casa_nacional">
                        Reporte casa nacional</label>
                    <input type="text" class="form-control form-control-sm" size="10" maxlength="10"
                        name="reporte_casa_nacional" id="reporte_casa_nacional"
                        value="{{ old('reporte_casa_nacional', $propiedad->reporte_casa_nacional) }}">
                </div>
            </div>

            <div class="form-row my-0 py-0">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="comentarios">
                        Comentarios</label>
                    <input type="text" class="form-control form-control-sm"
                        name="comentarios" id="comentarios" size="100" maxlength="600"
                        value="{{ old('comentarios', $propiedad->comentarios) }}">
                </div>
            </div>

            <div class="form-row my-0 py-0 bg-suave">
                <div class="form-group form-inline mx-1 px-2">
                    <label class="control-label" for="factura_AyS">
                        Factura A&S</label>
                    <input type="text" class="form-control form-control-sm"
                        size="100" maxlength="100" name="factura_AyS" id="factura_AyS"
                        value="{{ old('factura_AyS', $propiedad->factura_AyS) }}">
                </div>
            </div>
            </fieldset>
        @else
                <input type="hidden" name="porc_franquicia" value="{{ $propiedad->porc_franquicia }}">
                <input type="hidden" name="reportado_casa_nacional" value="{{ $propiedad->reportado_casa_nacional }}">
                <input type="hidden" name="porc_regalia" value="{{ $propiedad->porc_regalia }}">
                <input type="hidden" name="porc_gerente" value="{{ $propiedad->porc_gerente }}">
                <input type="hidden" name="porc_compartido" value="{{ $cols['porc_compartido']['xdef'] }}">
                <input type="hidden" name="porc_captador_prbr" value="{{ $propiedad->porc_captador_prbr }}">
                <input type="hidden" name="porc_cerrador_prbr" value="{{ $propiedad->porc_cerrador_prbr }}">
                <input type="hidden" name="porc_bonificacion" value="{{ $propiedad->porc_bonificacion }}">
                <input type="hidden" name="estatus_sistema_c21" value="{{ $propiedad->estatus_sistema_c21 }}">
        @endif

            <div class="form-row my-1 py-1">
                <div class="form-group form-inline mx-1 px-2">
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
<script>
    $(document).ready(function(){
        $("fieldset.datosPropiedad").children("div").hide();  // Clase "datosPropiedad"
        $("#datosPropiedad").click(function(event){           // Id "datosPropiedad"
            $("fieldset.datosPropiedad").children("div").toggle(1000);  // Clase "datosPropiedad"
            event.preventDefault();
        })
        $("#datosOficina").click(function(event){           // Id "datosOficina"
            $("fieldset.datosOficina").children("div").toggle(1000);  // Clase "datosOficina"
            event.preventDefault();
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
            var l = opciones.length;
            if ('' != $(this).val()) {
                for (j=0; j<l; j++) {
                    if ('I' == opciones[j].value) break;
                }
                var pendiente = opciones[j].text;
                alert("Al colocar la 'fecha de reserva', debemos comenzar una negociacion," +
                        " por eso el 'estatus' debe ser " + pendiente);
                $("#estatus").val('I');
                if ('' != $("#fecha_firma").val()) {
                    for (j=0; j<l; j++) {
                        if ('P' == opciones[j].value) break;
                    }
                    var pagosPendientes = opciones[j].text;
                    for (j=0; j<l; j++) {
                        if ('C' == opciones[j].value) break;
                    }
                    var cerrado = opciones[j].text;
                    alert("Como tenemos 'fecha de la firma':" + $("#fecha_firma").val() +
                            ", también; el 'estatus'" + ' debe ser ' + pagosPendientes +
                            ' o ' + cerrado);
                    $("#estatus").val('P');
                }
                $("#estatus").focus();
            }
        })
        $("#fecha_firma").change(function(ev) {
            if (('' == $("#fecha_reserva").val()) && ('' != $(this).val())) {
                alert("Una propiedad no deberia tener 'fecha de la firma':" +
                        $("#fecha_firma").val() + " y vacia la 'fecha de reserva'");
                var resp = confirm("Desea asignar la 'fecha de reserva' igual a la " +
                                    "'fecha de la firma'");
                if (resp) {
                    alert("También, procederé a cambiar el 'estatus'.");
                    $("#fecha_reserva").val($(this).val());
                    $("#estatus").val('P');
                    $("#estatus").focus();
                } else
                    $("#fecha_reserva").focus();
                return
            }
            if ('' != $(this).val()) {
                var j;
                var opciones = $("#estatus option");
                var l = opciones.length;
                for (j=0; j<l; j++) {
                    if ('P' == opciones[j].value) break;
                }
                var pagosPendientes = opciones[j].text;
                for (j=0; j<l; j++) {
                    if ('C' == opciones[j].value) break;
                }
                var cerrado = opciones[j].text;
                alert('Al colocar la fecha de la firma, debemos comenzar el cierre de la negociacion,' +
                        " por eso el <estatus> debe ser " + pagosPendientes + ' o ' + cerrado);
                $("#estatus").val('P');
                $("#estatus").focus();
            }
        })
        $("#formulario").submit(function(ev) {
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
            }
        })
    })
</script>

@endsection