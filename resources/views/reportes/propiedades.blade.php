@extends('layouts.app')

@section('content')
    <div class="m-0 p-0">
    @includeIf('include.totalesPropiedad')
    </div>

    <div class="d-flex justify-content-between align-items-end m-0 p-0">
        <h3 class="m-auto p-auto">{{ $title }}</h3>
    </div>

    @if ($propiedades->isNotEmpty())
    <table class="table table-striped table-hover table-bordered m-0 p-0">
        <thead class="thead-dark">
        <tr class="m-0 p-0">
            <th class="m-0 p-0" scope="col" title="Codigo MLS">
                <a href="{{ route($rutRetorno, [$id, 'codigo']) }}"
                        class="btn btn-link m-0 p-0">
                    C&oacute;digo
                </a>
            </th>
        @if (!$movil)
            <th class="m-0 p-0" scope="col" title="Fecha de reserva">
                <a href="{{ route($rutRetorno, [$id, 'fecha_reserva']) }}"
                        class="btn btn-link m-0 p-0">
                    Reserva
                </a>
            </th>
            <th class="m-0 p-0" scope="col" title="Fecha de la firma">
                <a href="{{ route($rutRetorno, [$id, 'fecha_firma']) }}"
                        class="btn btn-link m-0 p-0">
                    Firma
                </a>
            </th>
            <th class="m-0 p-0" scope="col" title="Tipo de negociaci&oacute;n">
                <a href="{{ route($rutRetorno, [$id, 'negociacion']) }}"
                        class="btn btn-link m-0 p-0">
                    N
                </a>
            </th>
        @endif (!$movil)
            <th class="m-0 p-0" scope="col">
                <a href="{{ route($rutRetorno, [$id, 'nombre']) }}"
                        class="btn btn-link m-0 p-0">
                    Nombre
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a href="{{ route($rutRetorno, [$id, 'precio']) }}"
                        class="btn btn-link m-0 p-0">
                    Precio
                </a>
            </th>
            <th class="m-0 p-0" scope="col" title="Lados">
                <a href="{{ route($rutRetorno, [$id, 'lados']) }}"
                        class="btn btn-link m-0 p-0">
                    L
                </a>
            </th>
        @if (!$movil)
        @if (Auth::user()->is_admin)
            <th class="m-0 p-0" scope="col" title="Franquicia">
                Franquic
            </th>
            <th class="m-0 p-0" scope="col">
                SANAF-5%
            </th>
            <th class="m-0 p-0" scope="col">
                Neto Of.
            </th>
            <th class="m-0 p-0" scope="col" title="Pago asesor captador, gerente y cerrador.">
                Bonif
            </th>
        @else
            <th class="m-0 p-0" scope="col" title="Porcentaje de comision cobrado al inmueble">
                %Com
            </th>
            <th class="m-0 p-0" scope="col" title="Porcentaje de IVA cobrado al inmueble">
                %IVA
            </th>
            <th class="m-0 p-0" scope="col" title="Precio de venta real">
                PrVeRe
            </th>
            <th class="m-0 p-0" scope="col" title="Pago asesor captador y/o cerrador, y bonificaciones.">
                Comisi&oacute;n
            </th>
            <th class="m-0 p-0" scope="col" title="Puntos por esta propiedad">
                Puntos
            </th>
        @endif (Auth::user()->is_admin)
            <th class="m-0 p-0" scope="col">Acc</th>
        @endif (!$movil)
        </tr>
        </thead>
        <tbody>
        @foreach ($propiedades as $propiedad)
        <tr class="{{ $propiedad->colorEstatus($propiedad->estatus) }} m-0 p-0">
            @if ($movil)
            <td class="m-0 p-0">
                <a href="{{ route('propiedades.muestra', [$propiedad, $rutRetorno]) }}"
                        class="btn btn-link float-right m-0 p-0">
                    {{ $propiedad->codigo }}
                </a>
            @else ($movil)
            <td class="m-0 p-0" title="{{ $propiedad->id }}) {{ (($propiedad->user_borro || $propiedad->deleted_at)?'Borrado':$propiedad->estatus_alfa) }}
Reporte en casa nacional: {{ $propiedad->reporte_casa_nacional_ven }}
Estatus en sistema C21: {{ $propiedad->estatus_c21_alfa.(($propiedad->pagado_casa_nacional)?' y PAGADO A CASA NACIONAL':'') }}
{{ (($propiedad->factura_AyS)?'Factura A & S: '.$propiedad->factura_AyS.'.':'') }}">
                <span class="float-right"> {{ $propiedad->codigo }} </span>
            @endif ($movil)
            </td>
        @if ($movil)
            <td class="m-0 p-0">
                {{ $propiedad->nombre }}({{ $propiedad->negociacion }})
        @else ($movil)
            <td class="m-0 py-0 px-1">
                <span class="float-right m-0 p-0" title="Fecha de reserva">
                    {{ $propiedad->fec_res }}</span>
            </td>
            <td class="m-0 py-0 px-1">
                <span class="float-right m-0 p-0" title="Fecha de la firma">
                    {{ $propiedad->fec_fir }}</span>
            </td>

            <td class="m-0 p-0" title="{{ $propiedad->negociacion_alfa }}">
                <span class="float-center">{{ $propiedad->negociacion }}</span></td>

            @if (Auth::user()->is_admin)
            <td class="m-0 p-0" title="{{ $propiedad->comentarios }}">
            @else
            <td class="m-0 p-0">
            @endif (Auth::user()->is_admin)
                {{ $propiedad->nombre }}
        @endif ($movil)
            </td>

        <?php $propiedad->mMoZero = false;  // Si el monto es 0, mostrar 'espacio vacio'. ?>
        <?php $propiedad->espMonB = false;  // Eliminar espacio entre simbolo de la moneda y el monto. ?>

            @if ((Auth::user()->is_admin) and (!$movil))
            <td class="m-0 p-0" title="Comisi&oacute;n: {{ $propiedad->comision_p }}
 Reserva s/IVA(I):{{ $propiedad->reserva_sin_iva_ven }};
                  IVA:{{ $propiedad->iva_p }};
 Reserva c/IVA(K):{{ $propiedad->reserva_con_iva_ven }}">
            @else (Auth::user()->is_admin)
            <td class="m-0 p-0">
            @endif (Auth::user()->is_admin)
                <span class="float-right m-0 p-0"{{--title="Precio del inmueble"--}}>
                    {{ $propiedad->precio_ven }}
                </span>
            {{--@if (Auth::user()->is_admin)
                <br>
                <span title="Comisi&oacute;n((H)">
                    Com:{{ $propiedad->comision_p }}
                </span><br>
                <span class="float-right" title="IVA(J)">
                    IVA:{{ $propiedad->iva_p }}
                </span><br>
                <span class="float-right" title="Precio de venta real">
                    {{ $propiedad->precio_venta_real_ven }}
                </span>
            @endif (Auth::user()->is_admin)--}}
            </td>
            @if ((Auth::user()->is_admin) and (!$movil))
            <td class="m-0 p-0"
                title="Compartido con otra oficina
                  s/IVA(M):{{ $propiedad->compartido_sin_iva_ven }};
 Reserva s/IVA(I):{{ $propiedad->reserva_sin_iva_ven }}
 Reserva c/IVA(K):{{ $propiedad->reserva_con_iva_ven }}">
            @else
            <td class="m-0 p-0">
            @endif
                <span class="float-right"> {{ $propiedad->lados }}</span>
            </td>
            @if (!$movil)
            @if (!(Auth::user()->is_admin)){{-- No es administrador --}}
                <td class="m-0 p-0">
                    <span class="float-right">
                        {{ $propiedad->comision_p }}
                    </span>
                </td>
                <td class="m-0 p-0">
                    <span class="float-right">
                        {{ $propiedad->iva_p }}
                    </span>
                </td>
                <td class="m-0 p-0">
                    <span class="float-right" title="Precio de venta real">
                        {{ $propiedad->precio_venta_real_ven }}
                    </span>
                </td>
            @endif (!(Auth::user()->is_admin))
            @if (Auth::user()->is_admin)
            <td class="m-0 p-0" title="Franquicia de reserva sin IVA(O) ({{ $propiedad->porc_franquicia_p }}): {{ $propiedad->franquicia_reservado_sin_iva_ven }} 
Franquicia de reserva con IVA(P) ({{ $propiedad->porc_franquicia_p }}): {{ $propiedad->franquicia_reservado_con_iva_ven }}
Franquicia a pagar reportada(Q) ({{ $propiedad->reportado_casa_nacional_p }}): {{ $propiedad->franquicia_pagar_reportada_ven }}
Compartido con IVA(L): {{ $propiedad->compartido_con_iva_ven }}
Franquicia a pagar reportada(Q) ({{ $propiedad->reportado_casa_nacional_p }})">
                <span class="float-right">
                    {{ $propiedad->franquicia_pagar_reportada_ven }}
                </span>
                {{--<span class="float-right"
                        title="Franquicia de reserva sin IVA(O) ({{ $propiedad->porc_franquicia_p }})">
                    {{ $propiedad->franquicia_reservado_sin_iva_ven }} 
                </span>
                <br>
                <span class="float-right" title="Franquicia de reserva con IVA(P) ({{ $propiedad->porc_franquicia_p }})">
                    {{ $propiedad->franquicia_reservado_con_iva_ven }}
                </span>
                <br>
                <span class="float-right" title="Franquicia a pagar reportada(Q) ({{ $propiedad->reportado_casa_nacional_p }})">
                    {{ $propiedad->franquicia_pagar_reportada_ven }}
                </span>
                <br>
                <span class="float-right" title="Compartido con IVA(L)">
                    {{ $propiedad->compartido_con_iva_ven }}
                </span>--}}
            </td>

            <td class="m-0 p-0" title="Porcentaje de REGALIA(S):{{ $propiedad->porc_regalia_p }}: {{ $propiedad->regalia_ven }}
SANAF 5 Porciento(T): {{ $propiedad->sanaf_5_por_ciento_ven }}
Porcentaje reportado a casa nacional(R):{{ $propiedad->reportado_casa_nacional_p }}">
                <span class="float-right">
                    {{ $propiedad->sanaf_5_por_ciento_ven }}
                </span>
                {{--<span class="float-right" title="Porcentaje de REGALIA(S):{{ $propiedad->porc_regalia_p }}">
                    {{ $propiedad->regalia_ven }}
                </span><br>
                <span class="float-right" title="SANAF 5 Porciento(T)">
                    {{ $propiedad->sanaf_5_por_ciento_ven }}
                </span><br>
                <span title="Porcentaje reportado a casa nacional(R)">
                    RCN:{{ $propiedad->reportado_casa_nacional_p }}
                </span>--}}
            </td>

            <td class="m-0 p-0" title="Oficina bruto real(U): {{ $propiedad->oficina_bruto_real_ven }}
Base para honorarios socios(V): {{ $propiedad->base_honorarios_socios_ven }}
Base para honorarios(W): {{ $propiedad->base_para_honorarios_ven }}
Ingreso neto a oficina(AC): {{ ($propiedad->numero_recibo)?('Recibo No.: '.$propiedad->numero_recibo):'' }} {{ $propiedad->ingreso_neto_oficina_ven }}">
                {{--<span class="float-right" title="Oficina bruto real(U)">
                    {{ $propiedad->oficina_bruto_real_ven }}
                </span><br>
                <span class="float-right" title="Base para honorarios socios(V)">
                    {{ $propiedad->base_honorarios_socios_ven }}
                </span><br>
                <span class="float-right" title="Base para honorarios(W)">
                    {{ $propiedad->base_para_honorarios_ven }}
                </span><br>
                <span class="float-right" title="Ingreso neto a oficina(AC)
{{ ($propiedad->numero_recibo)?('Recibo No.: '.$propiedad->numero_recibo):'' }}">
                    {{ $propiedad->ingreso_neto_oficina_ven }}
                </span>--}}
                <span class="float-right">
                    {{ $propiedad->ingreso_neto_oficina_ven }}
                </span>
            </td>
            @endif (Auth::user()->is_admin)

            <td class="m-0 p-0" title="@if (Auth::user()->is_admin) Gerente(Y): {{ $propiedad->gerente_ven }} @endif
            @if ((Auth::user()->is_admin) or ($propiedad->asesor_captador_id == Auth::user()->id))
Captador PRBR(X) ({{ $propiedad->nombre_captador }}): {{ $propiedad->captador_prbr_ven }}
            @endif ((Auth::user()->is_admin) or ($propiedad->asesor_captador_id == Auth::user()->id))
            @if ((Auth::user()->is_admin) or ($propiedad->asesor_cerrador_id == Auth::user()->id))
Cerrador PRBR(Z) ({{ $propiedad->nombre_cerrador }}): {{ $propiedad->cerrador_prbr_ven }}
            @endif ((Auth::user()->is_admin) or ($propiedad->asesor_cerrador_id == Auth::user()->id))
            @if ((Auth::user()->is_admin) or
                 ($propiedad->asesor_captador_id == Auth::user()->id) or
                 ($propiedad->asesor_cerrador_id == Auth::user()->id))
                @if ($propiedad->bonificaciones_ven)
Bonificaciones: {{ $propiedad->bonificaciones_ven }}">
                @endif ($propiedad->bonificaciones_ven)
            @endif ((Auth::user()->is_admin) or ...)
                <span class="float-right">
                    {{ $propiedad->bonificaciones_ven }}
                </span>
            </td>
            @if (!Auth::user()->is_admin)
            <td class="m-0 p-0"><span class="float-right">puntos</span></td>
            @endif (!Auth::user()->is_admin)

            <td class="d-flex align-items-end m-0 p-0">
                <a href="{{ route('propiedades.muestra', [$propiedad, $rutRetorno]) }}"
                        class="btn btn-link m-0 p-0">
                    <span class="oi oi-eye"></span>
                </a>
            </td>
            @endif (!$movil)
        </tr>
        @endforeach
        </tbody>
    </table>
    <p>
        <a href="{{ route($tipo.(('user'==$tipo)?'s':'')) }}" class="btn btn-link">
            Volver
        </a>
    </p>
    {{ $propiedades->links() }}
    @else
        <p>No hay propiedades registradas.</p>
    @endif

@endsection
