@extends('layouts.app')

@section('content')

<div>
    @includeIf('include.totalesPropiedad')
</div>

@if (isset($accion) and ('html' != $accion))
    <div>
        <h4 style="text-align:center;margin:0.25px 0px 0.25px 0px;padding:0px">
            {{ $title }}
        </h4>
    </div>
@endif (isset($accion) and ('html' != $accion))

@includeWhen((!$movil and (!isset($accion) or ('html' == $accion))), 'propiedades.vmenu', ['nCol' => 2])

    @if ($propiedades->isNotEmpty())
{{--@if ($paginar)
    {{ $propiedades->links() }}
@endif ($paginar)--}}
    <table
    @if (!isset($accion) or ('html' == $accion))
        class="table table-striped table-hover table-bordered m-0 p-0"
        style="font-size:0.75rem"
    @else (!isset($accion) or ('html' == $accion))
        class="center"
    @endif (!isset($accion) or ('html' == $accion))
    >
        <thead class="thead-dark">
        <tr
        @if ((isset($accion) and ('html' != $accion)))
            class="encabezado"
        @else ((isset($accion) and ('html' != $accion)))
            class="m-0 p-0"
        @endif ((isset($accion) and ('html' != $accion)))
        >
            <th class="m-0 p-0" scope="col" style="width:7%;">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'codigo') }}"
                        class="btn btn-link m-0 p-0" style="font-size:0.75rem">
                    C&oacute;digo
                </a>
            @else (!isset($accion) or ('html' == $accion))
                C&oacute;digo
            @endif (!isset($accion) or ('html' == $accion))
            </th>
        @if (!$movil)
            <th class="m-0 p-0" scope="col" data-toggle="tooltip" title="Fecha de reserva">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'fecha_reserva') }}"
                        class="btn btn-link m-0 p-0" style="font-size:0.75rem">
                    Reserva
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Reserva
            @endif (!isset($accion) or ('html' == $accion))
        {{--@if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
                <br>
        @else ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))--}}
            </th>
            <th class="m-0 p-0" scope="col" data-toggle="tooltip" title="Fecha de la firma">
        {{--@endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))--}}
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'fecha_firma') }}"
                        class="btn btn-link m-0 p-0" style="font-size:0.75rem">
                    Firma
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Firma
            @endif (!isset($accion) or ('html' == $accion))
            </th>
            <th class="m-0 p-0" scope="col" data-toggle="tooltip" title="Tipo de negociaci&oacute;n">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'negociacion') }}"
                        class="btn btn-link m-0 p-0" style="font-size:0.75rem">
                    N
                </a>
            @else (!isset($accion) or ('html' == $accion))
                N
            @endif (!isset($accion) or ('html' == $accion))
            </th>
        @endif (!$movil)
            <th class="m-0 p-0" scope="col">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'nombre') }}"
                        class="btn btn-link m-0 p-0" style="font-size:0.75rem">
                    Nombre
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Nombre
            @endif (!isset($accion) or ('html' == $accion))
            </th>
            <th class="m-0 p-0" scope="col">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'precio') }}"
                        class="btn btn-link m-0 p-0" style="font-size:0.75rem">
                    Precio
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Precio
            @endif (!isset($accion) or ('html' == $accion))
            </th>
            <th class="m-0 p-0" scope="col" data-toggle="tooltip" title="Lados">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'lados') }}"
                        class="btn btn-link m-0 p-0" style="font-size:0.75rem">
                    L
                </a>
            @else (!isset($accion) or ('html' == $accion))
                L
            @endif (!isset($accion) or ('html' == $accion))
            </th>
        @if ((!$movil) and (!isset($accion) or ('html' == $accion)))
        @if (Auth::user()->is_admin)
            <th class="m-0 p-0" scope="col" data-toggle="tooltip" title="Franquicia">
                Franquic
            </th>
            <th class="m-0 p-0" scope="col">
                {{--Regalia<br>--}}
                SANAF-5%
            </th>
            <th class="m-0 p-0" scope="col" data-toggle="tooltip" title="Neto de la oficina">
                {{--Montos<br>
                Base--}}
                Neto Of
            </th>
            {{--<th class="m-0 p-0" scope="col" title="Pago asesor captador, gerente y cerrador.">
                Comisi&oacute;n
            </th>--}}
        @else (Auth::user()->is_admin)
            <th class="m-0 p-0" scope="col" data-toggle="tooltip" title="Porcentaje de comision cobrado al inmueble">%Com</th>
        @endif (Auth::user()->is_admin)
        @if (isset($estatus) and ('V' == $estatus))
        @if (!Auth::user()->is_admin)   {{-- No es administrador. --}}
            <th class="m-0 p-0" scope="col" title="Porcentaje de IVA cobrado al inmueble">%IVA</th>
            {{--<th class="m-0 p-0" scope="col" title="Precio de venta real">PrVeRe</th>--}}
        @endif (!Auth::user()->is_admin)   {{-- No es administrador. --}}
            <th class="m-0 p-0" scope="col" title="Pago asesor captador y/o cerrador, y bonificaciones.">Comisi&oacute;n</th>
        @if (!Auth::user()->socio)  {{-- El usuario, ahora, es cualquiera; pero, no socio. --}}
            <th class="m-0 p-0" scope="col" title="Puntos por esta propiedad">Puntos</th>
        @endif (!Auth::user()->socio)  {{-- El usuario, ahora, es cualquiera; pero, no socio. --}}
        @endif (isset($estatus) and ('V' == $estatus))
            <th class="m-0 p-0" scope="col">Acciones</th>
        @endif ((!$movil) and (!isset($accion) or ('html' == $accion)))
        </tr>
        </thead>
        <tbody>

        @foreach ($propiedades as $propiedad)
        <tr class="{{ $propiedad->colorEstatus($propiedad->estatus) }} m-0 p-0">
        @if ($movil)
            <td class="m-0 p-0">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.show', $propiedad) }}" class="btn btn-link m-0 p-0">
                    <span class="text-right m-0 p-0">{{ $propiedad->codigo }}</span>
                </a>
            @else (!isset($accion) or ('html' == $accion))
                {{ $propiedad->codigo }}
            @endif (!isset($accion) or ('html' == $accion))
            </td>
            <td class="m-0 p-0">
        @else ($movil)
        @if (!isset($accion) or ('html' == $accion))
            <td class="text-right m-0 p-0 codigo ratonAyuda" data-toggle="tooltip" data-html="true"
                    titulo="<u>{{ $propiedad->id }}</u>)
                            <b>{{ (($propiedad->user_borro || $propiedad->deleted_at)?'Borrado':$propiedad->estatus_alfa) }}</b>
       @if (Auth::user()->is_admin)
                        <br>Reporte en casa nacional: <b>{{ $propiedad->reporte_casa_nacional_ven }}</b>
                        <br>Estatus en sistema C21:
                            <b>{{ $propiedad->estatus_c21_alfa.(($propiedad->pagado_casa_nacional)?' y PAGADO A CASA NACIONAL':'') }}</b>
                        {{ (($propiedad->factura_AyS)?'<br><em>Factura A & S: '.$propiedad->factura_AyS.'.</em>':'') }}"
                    {{--data-trigger="click"--}}
            >
               <input type="text" class="form-control form-control-sm m-0 p-0 codigo"
                        disabled minlength="6" tabindex="-1" name="codigo"
                        id="{{ $propiedad->id }}-codigo"
                        value="{{ old('entcodigo', $propiedad->codigo) }}">
        @else (Auth::user()->is_admin)
            ">
                <span class="text-right m-0 p-0">{{ $propiedad->codigo }}</span>
        @endif (Auth::user()->is_admin)
        @else (!isset($accion) or ('html' == $accion))
            <td>
                {{ $propiedad->codigo }}
        @endif (!isset($accion) or ('html' == $accion))
            </td>

            <td class="text-right m-0 py-0 px-1">
                <span class="text-right m-0 p-0" id="{{ $propiedad->id }}-fecres">
                    {{ $propiedad->fec_res }}</span>
            </td>
            <td class="text-right m-0 py-0 px-1">
                <span class="text-right m-0 p-0" id="{{ $propiedad->id }}-fecfir">
                    {{ $propiedad->fec_fir }}</span>
            </td>

            <td class="text-center m-0 p-0">
                <span class="m-0 p-0" id="{{ $propiedad->id }}-nego">
                    {{ $propiedad->negociacion }}
                </span>
            </td>

            <td text-left class="m-0 p-0 nombre
        @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
                    ratonAyuda" id="nombre-{{ $propiedad->id }}"
                    {{--data-toggle="tooltip" title="{{ $propiedad->comentarios }}"--}}
        @else ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
                    ratonInicial"
        @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            >
        @endif ($movil)
            {{ $propiedad->nombre }}
            </td>

        <?php $propiedad->asesor = Auth::user()->id;  // Usuario conectado. No se para que es esto, pero lo agregua hace mucho tiempo. ?>
        <?php $propiedad->mMoZero = false;  // Si el monto es 0, mostrar 'espacio vacio'. ?>
        <?php $propiedad->espMonB = false;  // Eliminar espacio entre simbolo de la moneda y el monto. ?>

        @if ($movil)
            <td class="text-right m-0 p-0"><!-- columna de precio -->
        @else ($movil)
        @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            <td class="text-right m-0 p-0 precio ratonAyuda" data-toggle="tooltip" data-html="true"
                    titulo="Comisi&oacute;n: <b>{{ $propiedad->comision_p }}</b>
                        <br>Reserva s/IVA(I):<b>{{ $propiedad->reserva_sin_iva_ven }}</b>;
                        <br>IVA:<b>{{ $propiedad->iva_p }}</b>;
                        <br>Reserva c/IVA(K):<b>{{ $propiedad->reserva_con_iva_ven }}</b>;
                        <br>Precio de venta real: <b>{{ $propiedad->precio_venta_real_ven }}</b>">
        @else (Auth::user()->is_admin)
            <td class="text-right m-0 p-0 ratonInicial">
        @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
        @endif ($movil)
            @if (!isset($accion) or ('html' == $accion))
                <span class="text-right m-0 p-0" id="{{ $propiedad->id }}-precio">
                    {{ $propiedad->precio_ven }}
                </span>
            @else (!isset($accion) or ('html' == $accion))
                <span style="text-align:right;">
                    {{ $propiedad->precio_ven }}
                </span>
            @endif (!isset($accion) or ('html' == $accion))
            {{--@if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
                <br>
                <span title="Comisi&oacute;n((H)">
                    Com:{{ $propiedad->comision_p }}
                </span><br>
                <span class="text-right" title="IVA(J)">
                    IVA:{{ $propiedad->iva_p }}
                </span><br>
                <span class="text-right" title="Precio de venta real">
                    {{ $propiedad->precio_venta_real_ven }}
                </span>
            @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))--}}
            </td>
        @if ($movil)
            <td class="text-center m-0 p-0"><!-- columna de lados -->
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.edit', $propiedad) }}" class="btn btn-link m-0 p-0">
                    <span class="m-0 p-0" id="{{ $propiedad->id }}-lados">
                        {{ $propiedad->lados }}
                    </span>
                </a>
            @else (!isset($accion) or ('html' == $accion))
                {{ $propiedad->lados }}
            @endif (!isset($accion) or ('html' == $accion))
        @else ($movil)
            @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            <td class="text-center m-0 p-0 lados ratonAyuda" data-toggle="tooltip" data-html="true"
                titulo="<u>Compartido con otra oficina</u>
                    <br>s/IVA(M):<b>{{ $propiedad->compartido_sin_iva_ven }}</b>;
                    <br>Reserva s/IVA(I):<b>{{ $propiedad->reserva_sin_iva_ven }}</b>
                    <br>Reserva c/IVA(K):<b>{{ $propiedad->reserva_con_iva_ven }}</b>">
            @else ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            <td class="text-center m-0 p-0 ratonInicial">
            @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            @if (!isset($accion) or ('html' == $accion))
                <span class="text-center m-0 p-0" id="{{ $propiedad->id }}-lados">
                    {{ $propiedad->lados }}
                </span>
            @else (!isset($accion) or ('html' == $accion))
                {{ $propiedad->lados }}
            @endif (!isset($accion) or ('html' == $accion))
        @endif ($movil)
            </td>

        @if ((!$movil) and (!isset($accion) or ('html' == $accion)))
            @if (!(Auth::user()->is_admin)){{-- No es administrador --}}
                <td class="text-right m-0 p-0 ratonInicial">
                    <span class="text-right m-0 p-0 ratonInicial">
                        {{ $propiedad->comision_p }}</span>
                </td>
            @if (isset($estatus) and ('V' == $estatus))
                <td class="text-right m-0 p-0 ratonInicial">
                    <span class="text-right m-0 p-0 ratonInicial">
                        {{ $propiedad->iva_p }}</span>
                </td>
            @endif (isset($estatus) and ('V' == $estatus))
                {{--<td class="m-0 p-0"><span class="text-right m-0 p-0" title="Precio de venta real">
                    {{ $propiedad->precio_venta_real_ven }}</span>
                </td>--}}

            @else (!Auth::user()->is_admin){{-- A partir de aqui solo se imprimira a los administradores. --}}
            <td class="text-right m-0 p-0 franquicia ratonAyuda" data-toggle="tooltip"
                    data-html="true" titulo="Franquicia de reserva sin IVA(O) 
                            (<em>{{ $propiedad->porc_franquicia_p }}</em>):
                            <b>{{ $propiedad->franquicia_reservado_sin_iva_ven }}</b>;
                        <br>Franquicia de reserva con IVA(P) 
                            (<em>{{ $propiedad->porc_franquicia_p }}</em>):
                            <b>{{ $propiedad->franquicia_reservado_con_iva_ven }}</b>;
                        <br>Franquicia a pagar reportada(Q) 
                            (<em>{{ $propiedad->reportado_casa_nacional_p }}</em>):
                            <b>{{ $propiedad->franquicia_pagar_reportada_ven }}</b>;
                        <br>Compartido con IVA(L):<b>{{ $propiedad->compartido_con_iva_ven }}</b>">
                {{--<span class="text-right" title="Franquicia de reserva sin IVA(O) ({{ $propiedad->porc_franquicia_p }})">
                    {{ $propiedad->franquicia_reservado_sin_iva_ven }} 
                </span><br>
                <span class="text-right" title="Franquicia de reserva con IVA(P) ({{ $propiedad->porc_franquicia_p }})">
                    {{ $propiedad->franquicia_reservado_con_iva_ven }}
                </span><br>
                <span class="text-right" title="Franquicia a pagar reportada(Q) ({{ $propiedad->reportado_casa_nacional_p }})">
                    {{ $propiedad->franquicia_pagar_reportada_ven }}
                </span><br>
                <span class="text-right" title="Compartido con IVA(L)">
                    {{ $propiedad->compartido_con_iva_ven }}
                </span>--}}
                <span class="text-right m-0 p-0">
                    {{ $propiedad->franquicia_pagar_reportada_ven }}
                </span>
            </td>

            <td class="text-right m-0 p-0 sanaf ratonAyuda" data-toggle="tooltip"
                    data-html="true" titulo="Porcentaje de REGALIA(S) 
                            (<em>{{ $propiedad->porc_regalia_p }}</em>):
                            <b>{{ $propiedad->regalia_ven }}</b>;
                        <br>Porcentaje reportado a casa nacional(R):
                        <b>{{ $propiedad->reportado_casa_nacional_p }}</b>">
                {{--<span class="text-right" title="Porcentaje de REGALIA(S):{{ $propiedad->porc_regalia_p }}">
                    {{ $propiedad->regalia_ven }}
                </span><br>
                <span class="text-right" title="SANAF 5 Porciento(T)">
                    {{ $propiedad->sanaf_5_por_ciento_ven }}
                </span><br>
                <span title="Porcentaje reportado a casa nacional(R)">
                    RCN:{{ $propiedad->reportado_casa_nacional_p }}
                </span>--}}
                <span class="text-right m-0 p-0">
                    {{ $propiedad->sanaf_5_por_ciento_ven }}
                </span>
            </td>

            <td class="text-right m-0 p-0 neto ratonAyuda" data-toggle="tooltip"
                    data-html="true" titulo="Oficina bruto real(U):
                            <b>{{ $propiedad->oficina_bruto_real_ven }}</b>;
                        <br>Base para honorarios socios(V):
                            <b>{{ $propiedad->base_honorarios_socios_ven }}</b>;
                        <br>Base para honorarios(W):
                            <b>{{ $propiedad->base_para_honorarios_ven }}</b>
                            {{ ($propiedad->numero_recibo)?('<b>Recibo No.: '.$propiedad->numero_recibo.'</b>'):'' }}">
                {{--<span class="text-right" title="Oficina bruto real(U)">
                    {{ $propiedad->oficina_bruto_real_ven }}
                </span><br>
                <span class="text-right" title="Base para honorarios socios(V)">
                    {{ $propiedad->base_honorarios_socios_ven }}
                </span><br>
                <span class="text-right" title="Base para honorarios(W)">
                    {{ $propiedad->base_para_honorarios_ven }}
                </span><br>
                <span class="text-right" title="Ingreso neto a oficina(AC)
{{ ($propiedad->numero_recibo)?('Recibo No.: '.$propiedad->numero_recibo):'' }}">
                    {{ $propiedad->ingreso_neto_oficina_ven }}
                </span>--}}
                <span class="text-right m-0 p-0">
                    {{ $propiedad->ingreso_neto_oficina_ven }}
                </span>
            </td>
            @endif (!(Auth::user()->is_admin)){{-- Hasta aqui solo se imprimira a los administradores. Producto de un 'else' --}}

        @if (isset($estatus) and ('V' == $estatus)){{-- Solo cuando el estatus es 'Ventas': 'P' y 'C' --}}
            <td class="text-right m-0 p-0"><!-- Comision del asesor. Deberia ser como captador, cerrador o ambas. -->
        @if (!Auth::user()->is_admin)   {{-- No es administrador --}}
                <span class="text-right m-0 p-0"
            @if ($propiedad->asesor_captador_id == Auth::user()->id)
                title="Captador PRBR(X)
                @if ($propiedad->asesor_cerrador_id == Auth::user()->id)
                    y Cerrador PRBR(Z)">
                    {{ $propiedad->captador_prbr + $propiedad->cerrador_prbr }}
                @else
                    ">
                    {{ $propiedad->captador_prbr_ven }}
                @endif ($propiedad->asesor_cerrador_id == Auth::user()->id)
            @else   {{-- No es el asesor captador. --}}
                @if ($propiedad->asesor_cerrador_id == Auth::user()->id)
                    title="Cerrador PRBR(Z)">
                    {{ $propiedad->cerrador_prbr_ven }}
                @else   {{-- Tampoco es el asesor cerrador. Esto NUNCA deberia ocurrir. --}}
                    >&nbsp;
                @endif ($propiedad->asesor_cerrador_id == Auth::user()->id)
            @endif ($propiedad->asesor_captador_id == Auth::user()->id)
                </span>
        @else (!Auth::user()->is_admin)   {{-- A partir de aqui solo es administrador --}}
                <span class="text-right" title="Gerente(Y)">
                    {{ $propiedad->gerente_ven }}
                </span>
                <br>
                <span class="text-right" title="Captador PRBR(X){{ $propiedad->nombre_captador }}">{{-- En nombre_captador se agregan ':' --}}
                    {{ $propiedad->captador_prbr_ven }}
                </span>
                <br>
                <span class="text-right" title="Cerrador PRBR(Z){{ $propiedad->nombre_cerrador }}">{{-- En nombre_cerrador se agregan ':' --}}
                    {{ $propiedad->cerrador_prbr_ven }}
                </span>
        @endif (!Auth::user()->is_admin)   {{-- Hasta aqui solo es administrador --}}
        @if ((Auth::user()->is_admin) or
                ($propiedad->asesor_captador_id == Auth::user()->id) or
                ($propiedad->asesor_cerrador_id == Auth::user()->id))
            @if ($propiedad->bonificaciones_ven)
                <br>
                <span class="text-right" title="Bonificaciones">
                    {{ $propiedad->bonificaciones_ven }}
                </span>
            @endif
        @endif ((Auth::user()->is_admin) or ...)
            </td><!-- Final de la columna de comisiones -->
        @if (!Auth::user()->is_admin)       {{-- El usuario no es administrador --}}
            <td class="text-right m-0 p-0"><span class="text-right m-0 p-0">
                {{ (($propiedad->asesor_captador_id == Auth::user()->id)?$propiedad->puntos_captador:0.00) +
                   (($propiedad->asesor_cerrador_id == Auth::user()->id)?$propiedad->puntos_cerrador:0.00) }}
                </span>
            </td>
        @elseif (!Auth::user()->socio)  {{-- El usuario, ahora, es administrador; pero, no socio. --}}
            <td class="text-right m-0 p-0"><span class="text-right m-0 p-0">
                {{ $propiedad->puntos_captador }}</span>
                <br><span class="text-right m-0 p-0">{{ $propiedad->puntos_cerrador }}
                </span>
            </td>
        @endif (!Auth::user()->is_admin)    {{-- El usuario es administrador --}}
        @endif (isset($estatus) and ('V' == $estatus)){{-- Hasta aqui ventas de un asesor. --}}

        {{-- @if (!isset($accion) or ('html' == $accion)) --}}
            <td class="d-flex align-items-end m-0 p-0">
                <a href="{{ route('propiedades.show', $propiedad) }}" class="btn btn-link m-0 p-0 mostrarTooltip" 
                        data-toggle="tooltip" data-html="true" title="Mostrar los datos de esta propiedad (<u>{{ $propiedad->nombre }}</u>).">
                    <span class="oi oi-eye m-0 p-0"></span>
                </a>
                @if (!($propiedad->user_borro || $propiedad->deleted_at))
                @if (Auth::user()->is_admin)
                <a href="{{ route('propiedades.edit', $propiedad) }}" class="btn btn-link m-0 p-0 mostrarTooltip"
                        data-toggle="tooltip" data-html="true" title="Editar los datos de esta propiedad (<u>{{ $propiedad->nombre }}</u>).">
                    <span class="oi oi-pencil m-0 p-0"></span>
                </a>
                @elseif (!(('P' == $propiedad->estatus) || ('C' == $propiedad->estatus) || ('S' == $propiedad->estatus)) and
                        ($propiedad->user->id == Auth::user()->id))
                <a href="{{ route('propiedades.edit', $propiedad) }}" class="btn btn-link m-0 p-0 mostrarTooltip"
                        data-toggle="tooltip" data-html="true" title="Editar los datos de esta propiedad (<u>{{ $propiedad->nombre }}</u>).">
                    <span class="oi oi-pencil m-0 p-0"></span>
                </a>
                @endif (Auth::user()->is_admin)
                @endif (!($propiedad->user_borro || $propiedad->deleted_at))

                @if ((('P' == $propiedad->estatus) || ('C' == $propiedad->estatus)) &&
                     (isset($propiedad->fecha_reserva) && isset($propiedad->fecha_firma)))
                    <a href="{{ route('propiedad.correo', [$propiedad->id, 1]) }}" class="btn btn-link m-0 p-0 mostrarTooltip"
                            data-toggle="tooltip" data-html="true" title="Enviar correo de 'Reporte de Cierre' a '<b>{{ (1 == $propiedad->user->id)?'Administrador':$propiedad->user->name }}</b>' sobre esta propiedad (<u>{{ $propiedad->codigo }}, {{ $propiedad->nombre }}</u>).">
                        <span class="oi oi-envelope-closed m-0 p-0"></span>
                    </a>
                @endif (('P' == $propiedad->estatus) || ('C' == $propiedad->estatus))
                @if ((Auth::user()->is_admin) && !($propiedad->user_borro || $propiedad->deleted_at) &&
                     !(('P' == $propiedad->estatus) || ('C' == $propiedad->estatus)))
                <form action="{{ route('propiedades.destroy', $propiedad) }}" method="POST" 
                        class="form-inline mt-0 mt-md-0"
                        onSubmit="return confirm('Realmente, desea borrar (borrado lógico) los datos de esta propiedad de la base de datos?')">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-link m-0 p-0">
                        <span class="oi oi-trash m-0 p-0 mostrarTooltip" data-toggle="tooltip" data-html="true"
                                title="Borrar (lógico) <b>{{ $propiedad->nombre }}</b>">
                        </span>
                    </button>
                </form>
                @endif ((Auth::user()->is_admin) && !($propiedad->user_borro || $propiedad->deleted_at) && ...)
                @if (Auth::user()->is_admin)
                <a href="#" class="btn btn-link m-0 p-0 editarCodigo mostrarTooltip" id="{{ $propiedad->id }}"
                    data-toggle="tooltip" data-html="true"
                    title="Cambiar el codigo MLS <b>{{ $propiedad->codigo }}</b> de esta propiedad (<u>{{ $propiedad->nombre}}</u>).">
                <span class="oi oi-brush m-0 p-0"></span>
                </a>
                @endif (Auth::user()->is_admin)
            </td>
        {{-- @endif (!isset($accion) or ('html' == $accion)) --}}
        @endif ((!$movil) and (!isset($accion) or ('html' == $accion)))
        </tr>

        <?php $propiedad->espMonB = true; // Restablecer variable cambiada antes de <precio> ?>

        @endforeach
        </tbody>
    </table>
@if ($paginar)
    {{ $propiedades->links() }}
@endif ($paginar)

@include('include.botonesPdf', ['enlace' => 'propiedades'])

@else ($propiedades->isNotEmpty())
    @includeif('include.noRegistros', ['elemento' => 'propiedades'])
@endif ($propiedades->isNotEmpty())

@includeWhen((!$movil and (!isset($accion) or ('html' == $accion))),
                'propiedades.vmenuCierre')

@endsection

{{-- @includeWhen((!$movil and (!isset($accion) or ('html' == $accion))),
                'include.modalAlertar')
@includeWhen((!$movil and (!isset($accion) or ('html' == $accion))),
                'include.modalConfirmar')--}}

@section('js')

@includeIf("propiedades.jqmenu", ['vista' => 'index'])

@endsection
