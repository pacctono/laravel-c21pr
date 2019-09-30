@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>

    </div>

    @if ($propiedades->isNotEmpty())
    <table class="table table-striped table-hover table-bordered">
        <thead class="thead-dark">
        <tr>
            <th scope="col" title="Codigo MLS">
                <a href="{{ route($rutRetorno, [$id, 'codigo']) }}" class="btn btn-link">
                    C&oacute;digo
                </a>
            </th>
        @if (!$movil)
            <th scope="col" title="Fecha de reserva">
                <a href="{{ route($rutRetorno, [$id, 'fecha_reserva']) }}" class="btn btn-link">
                    Reserva
                </a>
        @if (Auth::user()->is_admin)
                <br>
        @else
            </th>
            <th scope="col" title="Fecha de la firma">
        @endif (Auth::user()->is_admin)
                <a href="{{ route($rutRetorno, [$id, 'fecha_firma']) }}" class="btn btn-link">
                    Firma
                </a>
            </th>
            <th scope="col" title="Tipo de negociaci&oacute;n">
                <a href="{{ route($rutRetorno, [$id, 'negociacion']) }}" class="btn btn-link">
                    N
                </a>
            </th>
        @endif (!$movil)
            <th scope="col">
                <a href="{{ route($rutRetorno, [$id, 'nombre']) }}" class="btn btn-link">
                    Nombre
                </a>
            </th>
            <th scope="col">
                <a href="{{ route($rutRetorno, [$id, 'precio']) }}" class="btn btn-link">
                    Precio
                </a>
            </th>
            <th scope="col" title="Lados">
                <a href="{{ route($rutRetorno, [$id, 'lados']) }}" class="btn btn-link">
                    L
                </a>
            </th>
        @if (!$movil)
        @if (Auth::user()->is_admin)
            <th scope="col" title="Franquicia">
                Franquic
            </th>
            <th scope="col">
                Regalia<br>
                SANAF-5%
            </th>
            <th scope="col">
                Montos<br>
                Base
            </th>
            <th scope="col" title="Pago asesor captador, gerente y cerrador.">
                Comis
            </th>
        @else
            <th scope="col" title="Porcentaje de comision cobrado al inmueble">%Com</th>
            <th scope="col" title="Porcentaje de IVA cobrado al inmueble">%IVA</th>
            <th scope="col" title="Precio de venta real">PrVeRe</th>
            <th scope="col" title="Pago asesor captador y/o cerrador, y bonificaciones.">Comisi&oacute;n</th>
            <th scope="col" title="Puntos por esta propiedad">Puntos</th>
        @endif (Auth::user()->is_admin)
            <th scope="col">Acciones</th>
        @endif (!$movil)
        </tr>
        </thead>
        <tbody>
        @foreach ($propiedades as $propiedad)
        <tr class="
        @if ('I' == $propiedad->estatus)
            table-active
        @elseif ('P' == $propiedad->estatus)
            table-warning
        @elseif (('S' == $propiedad->estatus) || ($propiedad->user_borro || $propiedad->deleted_at))
            table-danger
        @elseif (0 == ($loop->index % 2))
            table-primary
        @else
            table-info
        @endif
        ">
            @if ($movil)
            <td>
                <a href="{{ route('propiedades.muestra', [$propiedad, $rutRetorno]) }}"
                        class="btn btn-link float-right">
                    {{ $propiedad->codigo }}
                </a>
            @else ($movil)
            <td title="{{ $propiedad->id }}) {{ (($propiedad->user_borro || $propiedad->deleted_at)?'Borrado':$propiedad->estatus_alfa) }}
Reporte en casa nacional: {{ $propiedad->reporte_casa_nacional_ven }}
Estatus en sistema C21: {{ $propiedad->estatus_c21_alfa.(($propiedad->pagado_casa_nacional)?' y PAGADO A CASA NACIONAL':'') }}
{{ (($propiedad->factura_AyS)?'Factura A & S: '.$propiedad->factura_AyS.'.':'') }}">
                <span class="float-right"> {{ $propiedad->codigo }} </span>
            @endif ($movil)
            </td>
        @if ($movil)
            <td>
                {{ $propiedad->nombre }}({{ $propiedad->negociacion }})
        @else ($movil)
            <td>
                <span title="Fecha de reserva">
                    {{ $propiedad->reserva_en }}</span>
        @if (Auth::user()->is_admin)
                <br>
        @else
            </td>
            <td>
        @endif (Auth::user()->is_admin)
                <span title="Fecha de la firma">
                    {{ $propiedad->firma_en }}</span>
            </td>

            <td title="{{ $propiedad->negociacion_alfa }}">
                <span class="float-center">{{ $propiedad->negociacion }}</span></td>

            @if (Auth::user()->is_admin)
            <td title="{{ $propiedad->comentarios }}">
            @else
            <td>
            @endif (Auth::user()->is_admin)
                {{ $propiedad->nombre }}
        @endif ($movil)
            </td>

        <?php $propiedad->mMoZero = false;  // Si el monto es 0, mostrar 'espacio vacio'. ?>
        <?php $propiedad->espMonB = false;  // Eliminar espacio entre simbolo de la moneda y el monto. ?>

            @if ((Auth::user()->is_admin) and (!$movil))
            <td title="Comisi&oacute;n: {{ $propiedad->comision_p }}
 Reserva s/IVA(I):{{ $propiedad->reserva_sin_iva_ven }};
                  IVA:{{ $propiedad->iva_p }};
 Reserva c/IVA(K):{{ $propiedad->reserva_con_iva_ven }}">
            @else (Auth::user()->is_admin)
            <td>
            @endif (Auth::user()->is_admin)
                <span class="float-right" title="Precio del inmueble">
                    {{ $propiedad->precio_ven }}
                </span>
            @if (Auth::user()->is_admin)
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
            @endif (Auth::user()->is_admin)
            </td>
            @if ((Auth::user()->is_admin) and (!$movil))
            <td
                title="Compartido con otra oficina
                  s/IVA(M):{{ $propiedad->compartido_sin_iva_ven }};
 Reserva s/IVA(I):{{ $propiedad->reserva_sin_iva_ven }}
 Reserva c/IVA(K):{{ $propiedad->reserva_con_iva_ven }}">
            @else
            <td>
            @endif
                <span class="float-right"> {{ $propiedad->lados }}</span>
            </td>
            @if (!$movil)
            @if (!(Auth::user()->is_admin))
                <td><span class="float-right">{{ $propiedad->comision_p }}</span></td>
                <td><span class="float-right">{{ $propiedad->iva_p }}</span></td>
                <td><span class="float-right" title="Precio de venta real">
                    {{ $propiedad->precio_venta_real_ven }}</span>
                </td>
            @endif (!(Auth::user()->is_admin))
            @if (Auth::user()->is_admin)
            <td title="Franquicia">
                <span class="float-right" title="Franquicia de reserva sin IVA(O) ({{ $propiedad->porc_franquicia_p }})">
                    {{ $propiedad->franquicia_reservado_sin_iva_ven }} 
                </span><br>
                <span class="float-right" title="Franquicia de reserva con IVA(P) ({{ $propiedad->porc_franquicia_p }})">
                    {{ $propiedad->franquicia_reservado_con_iva_ven }}
                </span><br>
                <span class="float-right" title="Franquicia a pagar reportada(Q) ({{ $propiedad->reportado_casa_nacional_p }})">
                    {{ $propiedad->franquicia_pagar_reportada_ven }}
                </span><br>
                <span class="float-right" title="Compartido con IVA(L)">
                    {{ $propiedad->compartido_con_iva_ven }}
                </span>
            </td>

            <td>
                <span class="float-right" title="Porcentaje de REGALIA(S):{{ $propiedad->porc_regalia_p }}">
                    {{ $propiedad->regalia_ven }}
                </span><br>
                <span class="float-right" title="SANAF 5 Porciento(T)">
                    {{ $propiedad->sanaf_5_por_ciento_ven }}
                </span><br>
                <span title="Porcentaje reportado a casa nacional(R)">
                    RCN:{{ $propiedad->reportado_casa_nacional_p }}
                </span>
            </td>

            <td>
                <span class="float-right" title="Oficina bruto real(U)">
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
                </span>
            </td>
            @endif (Auth::user()->is_admin)

            <td>
            @if (Auth::user()->is_admin)
                <span class="float-right" title="Gerente(Y)">
                    {{ $propiedad->gerente_ven }}
                </span>
                <br>
            @endif (Auth::user()->is_admin)
            @if ((Auth::user()->is_admin) or ($propiedad->asesor_captador_id == Auth::user()->id))
                <span class="float-right" title="Captador PRBR(X){{ $propiedad->nombre_captador }}">
                    {{ $propiedad->captador_prbr_ven }}
                </span>
            @endif ((Auth::user()->is_admin) or ($propiedad->asesor_captador_id == Auth::user()->id))
            @if ((Auth::user()->is_admin) or ($propiedad->asesor_cerrador_id == Auth::user()->id))
                <br>
                <span class="float-right" title="Cerrador PRBR(Z){{ $propiedad->nombre_cerrador }}">
                    {{ $propiedad->cerrador_prbr_ven }}
                </span>
            @endif ((Auth::user()->is_admin) or ($propiedad->asesor_cerrador_id == Auth::user()->id))
            @if ((Auth::user()->is_admin) or
                 ($propiedad->asesor_captador_id == Auth::user()->id) or
                 ($propiedad->asesor_cerrador_id == Auth::user()->id))
                @if ($propiedad->bonificaciones_ven)
                    <br>
                    <span class="float-right" title="Bonificaciones">
                        {{ $propiedad->bonificaciones_ven }}
                    </span>
                @endif ($propiedad->bonificaciones_ven)
            @endif ((Auth::user()->is_admin) or ...)
            </td>
            @if (!Auth::user()->is_admin)
            <td><span class="float-right">puntos</span></td-->
            @endif (!Auth::user()->is_admin)

            <td class="d-flex align-items-end">
                <a href="{{ route('propiedades.muestra', [$propiedad, $rutRetorno]) }}" class="btn btn-link">
                    <span class="oi oi-eye"></span>
                </a>
            </td>
            @endif (!$movil)
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
            @if ($movil)
                <td colspan="4">
            @else ($movil)
                <td colspan="6">
            @endif ($movil)
                    <a href="{{ route($tipo) }}" class="btn btn-link">
                        Volver
                    </a>
                </td>
            </tr>
        </tfoot>
    </table>
    {{ $propiedades->links() }}
    @else
        <p>No hay propiedades registradas.</p>
    @endif

@endsection
