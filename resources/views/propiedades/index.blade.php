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

@if (isset($alertar))
@if (0 < $alertar)
    <script>alert("Le fue enviado el correo con el 'Reporte de Cierre' de la propiedad.");</script>
@elseif (0 > $alertar)
    <script>alert("No fue enviado el correo con el 'Reporte de Cierre' de la propiedad. Probablemente, problemas con Internet! Revise su conexión");</script>
@endif (0 < $alertar)
@endif (isset($alertar))

@includeWhen((!$movil and (!isset($accion) or ('html' == $accion))), 'propiedades.vmenu')

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
            <th class="m-0 p-0" scope="col">
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
            <th class="m-0 p-0" scope="col" title="Fecha de reserva">
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
            <th class="m-0 p-0" scope="col" title="Fecha de la firma">
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
            <th class="m-0 p-0" scope="col" title="Tipo de negociaci&oacute;n">
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
            <th class="m-0 p-0" scope="col" title="Lados">
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
            <th class="m-0 p-0" scope="col" title="Franquicia">
                Franquic
            </th>
            <th class="m-0 p-0" scope="col">
                {{--Regalia<br>--}}
                SANAF-5%
            </th>
            <th class="m-0 p-0" scope="col">
                {{--Montos<br>
                Base--}}
                Neto Of
            </th>
            {{--<th class="m-0 p-0" scope="col" title="Pago asesor captador, gerente y cerrador.">
                Comis
            </th>--}}
        @else (Auth::user()->is_admin)
            <th class="m-0 p-0" scope="col" title="Porcentaje de comision cobrado al inmueble">%Com</th>
            {{--<th class="m-0 p-0" scope="col" title="Porcentaje de IVA cobrado al inmueble">%IVA</th> Cuatro columnas eliminadas a solicitud de Alirio.
            <th class="m-0 p-0" scope="col" title="Precio de venta real">PrVeRe</th>
            <th class="m-0 p-0" scope="col" title="Pago asesor captador y/o cerrador, y bonificaciones.">Comisi&oacute;n</th>
            <th class="m-0 p-0" scope="col" title="Puntos por esta propiedad">Puntos</th>--}}
        @endif (Auth::user()->is_admin)
            <th class="m-0 p-0" scope="col">Acciones</th>
        @endif ((!$movil) and (!isset($accion) or ('html' == $accion)))
        </tr>
        </thead>
        <tbody>

        @foreach ($propiedades as $propiedad)
        <tr class="
        @if ('A' == $propiedad->estatus)
            table-success
        @elseif ('I' == $propiedad->estatus)
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
        m-0 p-0">
        @if ($movil)
            <td class="m-0 p-0">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.show', $propiedad) }}" class="btn btn-link m-0 p-0">
                    <span class="float-right m-0 p-0">{{ $propiedad->codigo }}</span>
                </a>
            @else (!isset($accion) or ('html' == $accion))
                {{ $propiedad->codigo }}
            @endif (!isset($accion) or ('html' == $accion))
            </td>
            <td class="m-0 p-0">
        @else ($movil)
        @if (!isset($accion) or ('html' == $accion))
            <td class="m-0 p-0" title="{{ $propiedad->id }}) {{ (($propiedad->user_borro || $propiedad->deleted_at)?'Borrado':$propiedad->estatus_alfa) }}
Reporte en casa nacional: {{ $propiedad->reporte_casa_nacional_ven }}
Estatus en sistema C21: {{ $propiedad->estatus_c21_alfa.(($propiedad->pagado_casa_nacional)?' y PAGADO A CASA NACIONAL':'') }}
{{ (($propiedad->factura_AyS)?'Factura A & S: '.$propiedad->factura_AyS.'.':'') }}">
                <span class="float-right m-0 p-0">{{ $propiedad->codigo }}</span>
        @else (!isset($accion) or ('html' == $accion))
            <td>
                {{ $propiedad->codigo }}
        @endif (!isset($accion) or ('html' == $accion))
            </td>

            <td class="m-0 py-0 px-1">
                <span class="float-right m-0 p-0" title="Fecha de reserva">
                    {{ $propiedad->fec_res }}</span>
        {{--@if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
                <br>
        @else (Auth::user()->is_admin)--}}
            </td>
            <td class="m-0 py-0 px-1">
        {{--@endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))--}}
                <span class="float-right m-0 p-0" title="Fecha de la firma">
                    {{ $propiedad->fec_fir }}</span>
            </td>

            <td class="m-0 p-0" title="{{ $propiedad->negociacion_alfa }}">
                <span class="m-0 p-0">{{ $propiedad->negociacion }}</span></td>

        @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            <td class="m-0 p-0" title="{{ $propiedad->comentarios }}">
        @else (Auth::user()->is_admin)
            <td class="m-0 p-0">
        @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
        @endif ($movil)
            {{ $propiedad->nombre }}</td>

        <?php $propiedad->asesor = Auth::user()->id;  // Usuario conectado. ?>
        <?php $propiedad->mMoZero = false;  // Si el monto es 0, mostrar 'espacio vacio'. ?>
        <?php $propiedad->espMonB = false;  // Eliminar espacio entre simbolo de la moneda y el monto. ?>

        @if ($movil)
            <td class="m-0 p-0">
        @else ($movil)
        @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            <td class="m-0 p-0" title="Comisi&oacute;n: {{ $propiedad->comision_p }}
 Reserva s/IVA(I):{{ $propiedad->reserva_sin_iva_ven }};
                  IVA:{{ $propiedad->iva_p }};
 Reserva c/IVA(K):{{ $propiedad->reserva_con_iva_ven }};
 Precio de venta real: {{ $propiedad->precio_venta_real_ven }}">
        @else (Auth::user()->is_admin)
            <td class="m-0 p-0">
        @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
        @endif ($movil)
            @if (!isset($accion) or ('html' == $accion))
                <span class="float-right m-0 p-0">
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
                <span class="float-right" title="IVA(J)">
                    IVA:{{ $propiedad->iva_p }}
                </span><br>
                <span class="float-right" title="Precio de venta real">
                    {{ $propiedad->precio_venta_real_ven }}
                </span>
            @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))--}}
            </td>
        @if ($movil)
            <td class="m-0 p-0">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.edit', $propiedad) }}" class="btn btn-link m-0 p-0">
                    <span class="float-right m-0 p-0">{{ $propiedad->lados }}</span>
                </a>
            @else (!isset($accion) or ('html' == $accion))
                {{ $propiedad->lados }}
            @endif (!isset($accion) or ('html' == $accion))
        @else ($movil)
            @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            <td class="m-0 p-0"
                title="Compartido con otra oficina
                  s/IVA(M):{{ $propiedad->compartido_sin_iva_ven }};
 Reserva s/IVA(I):{{ $propiedad->reserva_sin_iva_ven }}
 Reserva c/IVA(K):{{ $propiedad->reserva_con_iva_ven }}">
            @else ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            <td class="m-0 p-0">
            @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            @if (!isset($accion) or ('html' == $accion))
                <span class="float-right m-0 p-0"> {{ $propiedad->lados }}</span>
            @else (!isset($accion) or ('html' == $accion))
                {{ $propiedad->lados }}
            @endif (!isset($accion) or ('html' == $accion))
        @endif ($movil)
            </td>

        @if ((!$movil) and (!isset($accion) or ('html' == $accion)))
            @if (!(Auth::user()->is_admin)){{-- No es administrador --}}
                <td class="m-0 p-0"><span class="float-right m-0 p-0">
                    {{ $propiedad->comision_p }}</span>
                </td>
                {{--<td class="m-0 p-0"><span class="float-right m-0 p-0"> Dos columnas eliminadas a solicitud de Alirio.
                    {{ $propiedad->iva_p }}</span>
                </td>
                <td class="m-0 p-0"><span class="float-right m-0 p-0" title="Precio de venta real">
                    {{ $propiedad->precio_venta_real_ven }}</span>
                </td>--}}

            @else (Auth::user()->is_admin)
            <td class="m-0 p-0" title="Franquicia de reserva sin IVA(O) ({{ $propiedad->porc_franquicia_p }}):{{ $propiedad->franquicia_reservado_sin_iva_ven }};
Franquicia de reserva con IVA(P) ({{ $propiedad->porc_franquicia_p }}):{{ $propiedad->franquicia_reservado_con_iva_ven }};
Franquicia a pagar reportada(Q) ({{ $propiedad->reportado_casa_nacional_p }}):{{ $propiedad->franquicia_pagar_reportada_ven }};
Compartido con IVA(L):{{ $propiedad->compartido_con_iva_ven }}">
                {{--<span class="float-right" title="Franquicia de reserva sin IVA(O) ({{ $propiedad->porc_franquicia_p }})">
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
                </span>--}}
                <span class="float-right m-0 p-0">
                    {{ $propiedad->franquicia_pagar_reportada_ven }}
                </span>
            </td>

            <td class="m-0 p-0" title="Porcentaje de REGALIA(S) ({{ $propiedad->porc_regalia_p }}):{{ $propiedad->regalia_ven }};
Porcentaje reportado a casa nacional(R):{{ $propiedad->reportado_casa_nacional_p }}">
                {{--<span class="float-right" title="Porcentaje de REGALIA(S):{{ $propiedad->porc_regalia_p }}">
                    {{ $propiedad->regalia_ven }}
                </span><br>
                <span class="float-right" title="SANAF 5 Porciento(T)">
                    {{ $propiedad->sanaf_5_por_ciento_ven }}
                </span><br>
                <span title="Porcentaje reportado a casa nacional(R)">
                    RCN:{{ $propiedad->reportado_casa_nacional_p }}
                </span>--}}
                <span class="float-right m-0 p-0">
                    {{ $propiedad->sanaf_5_por_ciento_ven }}
                </span>
            </td>

            <td class="m-0 p-0" title="Oficina bruto real(U):{{ $propiedad->oficina_bruto_real_ven }};
                Base para honorarios socios(V):{{ $propiedad->base_honorarios_socios_ven }};
                Base para honorarios(W):{{ $propiedad->base_para_honorarios_ven }};
{{ ($propiedad->numero_recibo)?('Recibo No.: '.$propiedad->numero_recibo):'' }}">
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
                <span class="float-right m-0 p-0">
                    {{ $propiedad->ingreso_neto_oficina_ven }}
                </span>
            </td>
            @endif (!(Auth::user()->is_admin))

            {{--@if (!Auth::user()->is_admin)   // No es administrador }}
            <td class="m-0 p-0"><!-- Comision del asesor. Deberia ser como captador, cerrador o ambas. -->
                <span class="float-right m-0 p-0"
            @if ($propiedad->asesor_captador_id == Auth::user()->id)
                title="Captador PRBR(X)
                @if ($propiedad->asesor_cerrador_id == Auth::user()->id)
                    Cerrador PRBR(Z)">
                    {{ $propiedad->captador_prbr + $propiedad->cerrador_prbr }}
                @else
                    ">
                    {{ $propiedad->captador_prbr_ven }}
                @endif ($propiedad->asesor_cerrador_id == Auth::user()->id)
            @else
                @if ($propiedad->asesor_cerrador_id == Auth::user()->id)
                    title="Cerrador PRBR(Z)">
                    {{ $propiedad->cerrador_prbr_ven }}
                @else
                    >&nbsp;
                @endif ($propiedad->asesor_cerrador_id == Auth::user()->id)
            @endif ($propiedad->asesor_captador_id == Auth::user()->id)
                </span>--}}
            {{--@if (Auth::user()->is_admin)
                <span class="float-right" title="Gerente(Y)">
                    {{ $propiedad->gerente_ven }}
                </span>
                <br>
            @endif--}}
            {{--@if ((Auth::user()->is_admin) or ($propiedad->asesor_captador_id == Auth::user()->id))
                <span class="float-right" title="Captador PRBR(X){{ $propiedad->nombre_captador }}">
                    {{ $propiedad->captador_prbr_ven }}
                </span>
            @endif
            @if ((Auth::user()->is_admin) or ($propiedad->asesor_cerrador_id == Auth::user()->id))
                <br>
                <span class="float-right" title="Cerrador PRBR(Z){{ $propiedad->nombre_cerrador }}">
                    {{ $propiedad->cerrador_prbr_ven }}
                </span>
            @endif ((Auth::user()->is_admin) or ($propiedad->asesor_cerrador_id == Auth::user()->id))
            {{--@if ((Auth::user()->is_admin) or
                 ($propiedad->asesor_captador_id == Auth::user()->id) or
                 ($propiedad->asesor_cerrador_id == Auth::user()->id))
                @if ($propiedad->bonificaciones_ven)
                    <br>
                    <span class="float-right" title="Bonificaciones">
                        {{ $propiedad->bonificaciones_ven }}
                    </span>
                @endif
            @endif--}}
            {{--</td>
            @endif (!Auth::user()->is_admin)    // No es administrador }}
            @if (!Auth::user()->is_admin)       // El usuario no es administrador }}
            <td class="m-0 p-0"><span class="float-right m-0 p-0">
                {{ (($propiedad->asesor_captador_id == Auth::user()->id)?$propiedad->puntos_captador:0.00) +
                   (($propiedad->asesor_cerrador_id == Auth::user()->id)?$propiedad->puntos_cerrador:0.00) }}
                </span>
            </td>
            @endif (!Auth::user()->is_admin)    // El usuario no es administrador --}}

        {{-- @if (!isset($accion) or ('html' == $accion)) --}}
            <td class="d-flex align-items-end m-0 p-0">
                <a href="{{ route('propiedades.show', $propiedad) }}" class="btn btn-link m-0 p-0" 
                        title="Mostrar los datos de esta propiedad ({{ $propiedad->nombre }}).">
                    <span class="oi oi-eye m-0 p-0"></span>
                </a>
                @if (!($propiedad->user_borro || $propiedad->deleted_at))
                @if (Auth::user()->is_admin)
                <a href="{{ route('propiedades.edit', $propiedad) }}" class="btn btn-link m-0 p-0"
                        title="Editar los datos de esta propiedad ({{ $propiedad->nombre }}).">
                    <span class="oi oi-pencil m-0 p-0"></span>
                </a>
                @elseif (!(('P' == $propiedad->estatus) || ('C' == $propiedad->estatus) || ('S' == $propiedad->estatus)))
                <a href="{{ route('propiedades.edit', $propiedad) }}" class="btn btn-link m-0 p-0"
                        title="Editar los datos de esta propiedad ({{ $propiedad->nombre }}).">
                    <span class="oi oi-pencil m-0 p-0"></span>
                </a>
                @endif (Auth::user()->is_admin)
                @endif (!($propiedad->user_borro || $propiedad->deleted_at))

                @if ((('P' == $propiedad->estatus) || ('C' == $propiedad->estatus)) &&
                     (isset($propiedad->fecha_reserva) && isset($propiedad->fecha_firma)))
                    <a href="{{ route('propiedad.correo', [$propiedad->id, 1]) }}" class="btn btn-link m-0 p-0"
                            title="Enviar correo de 'Reporte de Cierre' a '{{ (1 == $propiedad->user->id)?'Administrador':$propiedad->user->name }}' sobre esta propiedad ({{ $propiedad->codigo }}, {{ $propiedad->nombre }}).">
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
                    <button class="btn btn-link m-0 p-0" title="Borrar (lógico) propiedad.">
                        <span class="oi oi-trash m-0 p-0" title="Borrar {{ $propiedad->nombre }}">
                        </span>
                    </button>
                </form>
                @endif ((Auth::user()->is_admin) && !($propiedad->user_borro || $propiedad->deleted_at) && ...)
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

@section('js')

<script>
function alertaCampoRequerido() {
  var fecha_desde = document.getElementById('fecha_desde').value;
  var fecha_hasta = document.getElementById('fecha_hasta').value;
  var estatus = document.getElementById('estatus').value;
  var asesor  = document.getElementById('asesor').value;
  var captador = document.getElementById('captador').value;
  var cerrador = document.getElementById('cerrador').value;

  if (('' == fecha_desde) && ('' == estatus) && (0 == asesor) &&
        (0 == captador) && (0 == cerrador)) {
    alert("Usted debe suministrar la fecha de reserva 'Desde' o el 'estatus' o " +
            "el 'asesor' o el 'captador' o el 'cerrador'");
    return false;
  }
  return true;
}
</script>

@endsection
