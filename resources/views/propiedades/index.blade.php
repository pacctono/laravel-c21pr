@extends('layouts.app')

@section('content')

<div>
@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
    <form method="POST" class="form-horizontal" action="{{ route('propiedades.post') }}"
            onSubmit="return alertaCampoRequerido()">
        {!! csrf_field() !!}

        <div class="form-row my-0 py-0 mx-1 px-1">
    @include('include.fechas', ['tipoFecha' => 'de la firma '])
    @include('include.estatus')
    @includeWhen(Auth::user()->is_admin, 'include.asesor', ['berater' => 'captador'])   {{-- Obligatorio pasar la variable 'berater' --}}
    @includeWhen(Auth::user()->is_admin, 'include.asesor', ['berater' => 'cerrador'])   {{-- berater (asesor en aleman) --}}
    @include('include.botonMostrar')
        </div>
        {{--<div class="form-group form-inline my-0 py-0 mx-0 px-0">
            <label class="control-label" for="fecha_desde" title="Fecha de la firma desde">
                Desde</label>
            <input class="form-control form-control-sm" type="date" name="fecha_desde"
                id="fecha_desde" max="{{ now() }}" title="Fecha de la firma desde"
                value="{{ old('fecha_desde', $fecha_desde) }}">
        </div>
        <div class="form-group form-inline my-0 py-0 mx-0 px-0">
            <label class="control-label" for="fecha_hasta" title="Fecha de la firma hasta">
                Hasta</label>
            <input class="form-control form-control-sm" type="date" name="fecha_hasta"
                id="fecha_hasta" max="{{ now() }}" title="Fecha de la firma hasta"
                value="{{ old('fecha_hasta', $fecha_hasta) }}">
        </div>--}}
        {{--<div class="form-group form-inline my-0 py-0 mx-0 px-0">
            <select class="form-control form-control-sm" name="estatus" id="estatus">
                <option value="">Estatus</option>
            @foreach ($arrEstatus as $opcion => $muestra)
                <option value="{{$opcion}}"
            @if (old('estatus', $estatus) == $opcion)
                selected
            @endif
                >{{ substr($muestra, 0, 35) }}</option>
            @endforeach
            </select>
        </div>--}}

    {{--@if (Auth::user()->is_admin)
        <div class="form-group form-inline my-0 py-0 mx-0 px-0">
            <select class="form-control form-control-sm" name="captador" id="captador">
                <option value="0">Captador</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}"
                @if (old("captador", $captador) == $user->id)
                selected
                @endif
                >
                {{ $user->name }}
                </option>
            @endforeach
            </select>
        </div>
        <div class="form-group form-inline my-0 py-0 mx-0 px-0">
            <select class="form-control form-control-sm" name="cerrador" id="cerrador">
                <option value="0">Cerrador</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}"
                @if (old("cerrador", $cerrador) == $user->id)
                    selected
                @endif
                >
                {{ $user->name }}
                </option>
            @endforeach
            </select>
        </div>
    @endif (Auth::user()->is_admin)
        <div class="form-group form-inline my-0 py-0 mx-0 px-0">
            <button type="submit" class="btn btn-success">Mostrar</button>
        </div>
    </div>--}}
    </form>
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

    @includeIf('include.totalesPropiedad')
    {{--<div class="row my-0 py-0 mx-1 px-1">
        TOTS: {{ $filas }} props
        <span class="alert-success mx-1 px-1" title="Precio">
            {{ Prop::numeroVen($tPrecio, 0) }}</span>{{-- 'Prop' es un alias definido en config/app.php --}}
    {{--@if (Auth::user()->is_admin)
        <span class="alert-info ml-1 mr-0 px-1">Compartido con IVA:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Compartido con otra oficina con IVA">
            {{ Prop::numeroVen($tCompartidoConIva, 2) }}</span>
        <span class="alert-info ml-1 mr-0 px-1">Lados:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Sumatoria de los lados">
            {{ $tLados }}</span>
        <span class="alert-info ml-1 mr-0 px-1">Franq a pagar rep:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Franquicia a pagar reportada">
            {{ Prop::numeroVen($tFranquiciaPagarR, 2) }}</span>
        <span class="alert-info ml-1 mr-0 px-1">Regalia:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Regalia">
            {{ Prop::numeroVen($tRegalia, 2) }}</span>
        <span class="alert-info ml-1 mr-0 px-1">Sanaf:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Sanaf - 5%">
            {{ Prop::numeroVen($tSanaf5PorCiento, 2) }}</span>
    </div>
    <div class="row my-0 py-0 mx-1 px-1">
        <span class="alert-info ml-1 mr-0 px-1">Captador:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Captador PRBR">
            {{ Prop::numeroVen($tCaptadorPrbr, 2) }}
            @if (0 < $tCaptadorPrbrSel)
            ({{ Prop::numeroVen($tCaptadorPrbrSel, 2) }} [{{ $tLadosCap }}])
            @endif
        </span>
        <span class="alert-info ml-1 mr-0 px-1">Cerrador:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Cerrador PRBR">
            {{ Prop::numeroVen($tCerradorPrbr, 2) }}
            @if (0 < $tCerradorPrbrSel)
            ({{ Prop::numeroVen($tCerradorPrbrSel, 2) }} [{{ $tLadosCer }}])
            @endif
        </span>
    @else (Auth::user()->is_admin)
        <span class="alert-info ml-1 mr-0 px-1">Lados:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Sumatoria de los lados">
            {{ $tLadosCap + $tLadosCer }}</span>
    @if (0 < $tCaptadorPrbrSel)
        <span class="alert-info ml-1 mr-0 px-1">Captador:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Captador PRBR">
            {{ Prop::numeroVen($tCaptadorPrbrSel, 2) }}
        </span>
    @endif (0 < $tCaptadorPrbrSel)
    @if (0 < $tCerradorPrbrSel)
        <span class="alert-info ml-1 mr-0 px-1">Cerrador:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Cerrador PRBR">
            {{ Prop::numeroVen($tCerradorPrbrSel, 2) }}
        </span>
    @endif (0 < $tCerradorPrbrSel)
    @endif (Auth::user()->is_admin)
    @if (0 < $tBonificaciones)
        <span class="alert-info ml-1 mr-0 px-1">Bons:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Bonificaciones">
            {{ Prop::numeroVen($tBonificaciones, 2) }}</span>
    @endif
    @if (Auth::user()->is_admin)
        <span class="alert-info ml-1 mr-0 px-1">Gerente:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Gerente">
            {{ Prop::numeroVen($tGerente, 2) }}</span>
        @if (0 < $tComisionBancaria)
            <span class="alert-info ml-1 mr-0 px-1">Coms:</span>
            <span class="alert-success ml-0 mr-1 px-1" title="Comision bancaria">
                {{ Prop::numeroVen($tComisionBancaria, 2) }}</span>
        @endif
        <span class="alert-info ml-1 mr-0 px-1">Neto:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Ingreso neto de la oficina">
            {{ Prop::numeroVen($tIngresoNetoOfici, 2) }}</span>
        <span class="alert-info ml-1 mr-0 px-1">PrVeRe:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Precio de venta real">
            {{ Prop::numeroVen($tPrecioVentaReal, 2) }}
            @if ((0 < $tPvrCaptadorPrbrSel) || (0 < $tPvrCerradorPrbrSel))
                ({{ Prop::numeroVen($tPvrCaptadorPrbrSel+$tPvrCerradorPrbrSel, 2) }})
            @endif
        </span>
    @if ((0 < $tCaptadorPrbrSel) || (0 < $tCerradorPrbrSel))
        {{-- $tCaptadorPrbrSel }}-{{ $tCerradorPrbr --}}
    {{--</div>
    <div class="row my-0 py-0 mx-1 px-1">
    @endif ((0 != $tCaptadorPrbrSel) || (0 != $tCerradorPrbr))
        <span class="alert-info ml-1 mr-0 px-1">Tot Comision:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Total de comisiones: Captado + Cerrado">
            {{ Prop::numeroVen($tCaptadorPrbr+$tCerradorPrbr, 2) }}
        @if ((0 < $tCaptadorPrbrSel) or (0 < $tCerradorPrbrSel))
            ({{ Prop::numeroVen($tCaptadorPrbrSel+$tCerradorPrbrSel, 2) }} [{{ $tLadosCap+$tLadosCer }}])
        @endif
        </span>
    @else (Auth::user()->is_admin)
    @if ((0 < $tPvrCaptadorPrbrSel) || (0 < $tPvrCerradorPrbrSel))
        <span class="alert-info ml-1 mr-0 px-1">PrVeRe:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Precio de venta real">
            {{ Prop::numeroVen($tPvrCaptadorPrbrSel+$tPvrCerradorPrbrSel, 2) }}
        </span>
    @endif ((0 < $tPvrCaptadorPrbrSel) || (0 < $tPvrCerradorPrbrSel))
    @if ((0 < $tCaptadorPrbrSel) or (0 < $tCerradorPrbrSel))
        <span class="alert-info ml-1 mr-0 px-1">Tot Comision:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Total de comisiones: Captado + Cerrado">
                {{ Prop::numeroVen($tCaptadorPrbrSel+$tCerradorPrbrSel, 2) }}
        </span>
    @endif ((0 < $tCaptadorPrbrSel) or (0 < $tCerradorPrbrSel))
    @endif (Auth::user()->is_admin)
    </div>--}}
</div>

    <div class="row">
    @if (!isset($accion) or ('html' == $accion))
        <div class="col-sm-8">
        @if ($movil)
            <h5 class="pb-1">{{ substr($title, 11) }}</h5>
        @else ($movil)
            <h1 class="pb-1">{{ $title }}</h1>
        @endif ($movil)
        </div>
        <div class="col-sm-4">
            <a href="{{ route('propiedades.create') }}" class="btn btn-primary float-right">
                Crear Propiedad</a>
        </div>
    @else (!isset($accion) or ('html' == $accion))
        <h1 style="text-align:center;">{{ $title }}</h1>
    @endif (!isset($accion) or ('html' == $accion))
    </div>
@if (isset($alertar))
@if (0 < $alertar)
    <script>alert("Le fue enviado el correo con el 'Reporte de Cierre' de la propiedad.");</script>
@elseif (0 > $alertar)
    <script>alert("No fue enviado el correo con el 'Reporte de Cierre' de la propiedad. Probablemente, problemas con Internet! Revise su conexión");</script>
@endif (0 < $alertar)
@endif (isset($alertar))
    @if ($propiedades->isNotEmpty())
@if ($paginar)
    {{ $propiedades->links() }}
@endif ($paginar)
    <table
    @if (!isset($accion) or ('html' == $accion))
        class="table table-striped table-hover table-bordered"
    @else (!isset($accion) or ('html' == $accion))
        class="center"
    @endif (!isset($accion) or ('html' == $accion))
    >
        <thead class="thead-dark my-0 py-0">
        <tr
        @if ((isset($accion) and ('html' != $accion)))
            class="encabezado"
        @else ((isset($accion) and ('html' != $accion)))
            class="my-0 py-0"
        @endif ((isset($accion) and ('html' != $accion)))
        >
            <th scope="col">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'codigo') }}" class="btn btn-link">
                    C&oacute;digo
                </a>
            @else (!isset($accion) or ('html' == $accion))
                C&oacute;digo
            @endif (!isset($accion) or ('html' == $accion))
            </th>
        @if (!$movil)
            <th scope="col" title="Fecha de reserva">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'fecha_reserva') }}" class="btn btn-link">
                    Reserva
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Reserva
            @endif (!isset($accion) or ('html' == $accion))
        @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
                <br>
        @else ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            </th>
            <th scope="col" title="Fecha de la firma">
        @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'fecha_firma') }}" class="btn btn-link">
                    Firma
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Firma
            @endif (!isset($accion) or ('html' == $accion))
            </th>
            <th scope="col" title="Tipo de negociaci&oacute;n">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'negociacion') }}" class="btn btn-link">
                    N
                </a>
            @else (!isset($accion) or ('html' == $accion))
                N
            @endif (!isset($accion) or ('html' == $accion))
            </th>
        @endif (!$movil)
            <th scope="col">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'nombre') }}" class="btn btn-link">
                    Nombre
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Nombre
            @endif (!isset($accion) or ('html' == $accion))
            </th>
            <th scope="col">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'precio') }}" class="btn btn-link">
                    Precio
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Precio
            @endif (!isset($accion) or ('html' == $accion))
            </th>
            <th scope="col" title="Lados">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.orden', 'lados') }}" class="btn btn-link">
                    L
                </a>
            @else (!isset($accion) or ('html' == $accion))
                L
            @endif (!isset($accion) or ('html' == $accion))
            </th>
        @if ((!$movil) and (!isset($accion) or ('html' == $accion)))
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
        @else (Auth::user()->is_admin)
            <th scope="col" title="Porcentaje de comision cobrado al inmueble">%Com</th>
            <th scope="col" title="Porcentaje de IVA cobrado al inmueble">%IVA</th>
            <th scope="col" title="Precio de venta real">PrVeRe</th>
            <th scope="col" title="Pago asesor captador y/o cerrador, y bonificaciones.">Comisi&oacute;n</th>
            <th scope="col" title="Puntos por esta propiedad">Puntos</th>
        @endif (Auth::user()->is_admin)
            <th scope="col">Acciones</th>
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
        ">
        @if ($movil)
            <td>
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.show', $propiedad) }}" class="btn btn-link">
                    <span class="float-right"> {{ $propiedad->codigo }} </span>
                </a>
            @else (!isset($accion) or ('html' == $accion))
                {{ $propiedad->codigo }}
            @endif (!isset($accion) or ('html' == $accion))
            </td>
            <td>
        @else ($movil)
        @if (!isset($accion) or ('html' == $accion))
            <td title="{{ $propiedad->id }}) {{ (($propiedad->user_borro || $propiedad->deleted_at)?'Borrado':$propiedad->estatus_alfa) }}
Reporte en casa nacional: {{ $propiedad->reporte_casa_nacional_ven }}
Estatus en sistema C21: {{ $propiedad->estatus_c21_alfa.(($propiedad->pagado_casa_nacional)?' y PAGADO A CASA NACIONAL':'') }}
{{ (($propiedad->factura_AyS)?'Factura A & S: '.$propiedad->factura_AyS.'.':'') }}">
                <span class="float-right"> {{ $propiedad->codigo }} </span>
        @else (!isset($accion) or ('html' == $accion))
            <td>
                {{ $propiedad->codigo }}
        @endif (!isset($accion) or ('html' == $accion))
            </td>

            <td>
                <span title="Fecha de reserva">
                    {{ $propiedad->fec_res }}</span>
        @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
                <br>
        @else (Auth::user()->is_admin)
            </td>
            <td>
        @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
                <span title="Fecha de la firma">
                    {{ $propiedad->fec_fir }}</span>
            </td>

            <td title="{{ $propiedad->negociacion_alfa }}">
                <span class="float-center">{{ $propiedad->negociacion }}</span></td>

        @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            <td title="{{ $propiedad->comentarios }}">
        @else (Auth::user()->is_admin)
            <td>
        @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
        @endif ($movil)
            {{ $propiedad->nombre }}</td>

        <?php $propiedad->asesor = Auth::user()->id;  // Usuario conectado. ?>
        <?php $propiedad->mMoZero = false;  // Si el monto es 0, mostrar 'espacio vacio'. ?>
        <?php $propiedad->espMonB = false;  // Eliminar espacio entre simbolo de la moneda y el monto. ?>

        @if ($movil)
            <td>
        @else ($movil)
        @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            <td title="Comisi&oacute;n: {{ $propiedad->comision_p }}
 Reserva s/IVA(I):{{ $propiedad->reserva_sin_iva_ven }};
                  IVA:{{ $propiedad->iva_p }};
 Reserva c/IVA(K):{{ $propiedad->reserva_con_iva_ven }}">
        @else (Auth::user()->is_admin)
            <td>
        @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
        @endif ($movil)
            @if (!isset($accion) or ('html' == $accion))
                <span class="float-right" title="Precio del inmueble">
                    {{ $propiedad->precio_ven }}
                </span>
            @else (!isset($accion) or ('html' == $accion))
                <span style="text-align:right;">
                    {{ $propiedad->precio_ven }}
                </span>
            @endif (!isset($accion) or ('html' == $accion))
            @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
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
            @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            </td>
        @if ($movil)
            <td>
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('propiedades.edit', $propiedad) }}" class="btn btn-link">
                    <span class="float-right"> {{ $propiedad->lados }}</span>
                </a>
            @else (!isset($accion) or ('html' == $accion))
                {{ $propiedad->lados }}
            @endif (!isset($accion) or ('html' == $accion))
        @else ($movil)
            @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            <td
                title="Compartido con otra oficina
                  s/IVA(M):{{ $propiedad->compartido_sin_iva_ven }};
 Reserva s/IVA(I):{{ $propiedad->reserva_sin_iva_ven }}
 Reserva c/IVA(K):{{ $propiedad->reserva_con_iva_ven }}">
            @else ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            <td>
            @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
            @if (!isset($accion) or ('html' == $accion))
                <span class="float-right"> {{ $propiedad->lados }}</span>
            @else (!isset($accion) or ('html' == $accion))
                {{ $propiedad->lados }}
            @endif (!isset($accion) or ('html' == $accion))
        @endif ($movil)
            </td>

        @if ((!$movil) and (!isset($accion) or ('html' == $accion)))
            @if (!(Auth::user()->is_admin))
                <td><span class="float-right">{{ $propiedad->comision_p }}</span></td>
                <td><span class="float-right">{{ $propiedad->iva_p }}</span></td>
                <td><span class="float-right" title="Precio de venta real">
                    {{ $propiedad->precio_venta_real_ven }}</span>
                </td>

            @else (Auth::user()->is_admin)
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
            @endif (!(Auth::user()->is_admin))

            <td>
            @if (Auth::user()->is_admin)
                <span class="float-right" title="Gerente(Y)">
                    {{ $propiedad->gerente_ven }}
                </span>
                <br>
            @endif
            @if ((Auth::user()->is_admin) or ($propiedad->asesor_captador_id == Auth::user()->id))
                <span class="float-right" title="Captador PRBR(X){{ $propiedad->nombre_captador }}">
                    {{ $propiedad->captador_prbr_ven }}
                </span>
            @endif
            @if ((Auth::user()->is_admin) or ($propiedad->asesor_cerrador_id == Auth::user()->id))
                <br>
                <span class="float-right" title="Cerrador PRBR(Z){{ $propiedad->nombre_cerrador }}">
                    {{ $propiedad->cerrador_prbr_ven }}
                </span>
            @endif
            @if ((Auth::user()->is_admin) or
                 ($propiedad->asesor_captador_id == Auth::user()->id) or
                 ($propiedad->asesor_cerrador_id == Auth::user()->id))
                @if ($propiedad->bonificaciones_ven)
                    <br>
                    <span class="float-right" title="Bonificaciones">
                        {{ $propiedad->bonificaciones_ven }}
                    </span>
                @endif
            @endif
            </td>
            @if (!Auth::user()->is_admin)
            <td><span class="float-right">
                {{ (($propiedad->asesor_captador_id == Auth::user()->id)?$propiedad->puntos_captador:0.00) +
                   (($propiedad->asesor_cerrador_id == Auth::user()->id)?$propiedad->puntos_cerrador:0.00) }}
                </span>
            </td>
            @endif

        {{-- @if (!isset($accion) or ('html' == $accion)) --}}
            <td class="d-flex align-items-end">
                <a href="{{ route('propiedades.show', $propiedad) }}" class="btn btn-link" 
                        title="Mostrar los datos de esta propiedad ({{ $propiedad->nombre }}).">
                    <span class="oi oi-eye"></span>
                </a>
                @if (!($propiedad->user_borro || $propiedad->deleted_at))
                @if (Auth::user()->is_admin)
                <a href="{{ route('propiedades.edit', $propiedad) }}" class="btn btn-link"
                        title="Editar los datos de esta propiedad ({{ $propiedad->nombre }}).">
                    <span class="oi oi-pencil"></span>
                </a>
                @elseif (!(('P' == $propiedad->estatus) || ('C' == $propiedad->estatus) || ('S' == $propiedad->estatus)))
                <a href="{{ route('propiedades.edit', $propiedad) }}" class="btn btn-link"
                        title="Editar los datos de esta propiedad ({{ $propiedad->nombre }}).">
                    <span class="oi oi-pencil"></span>
                </a>
                @endif (Auth::user()->is_admin)
                @endif (!($propiedad->user_borro || $propiedad->deleted_at))

                @if ((('P' == $propiedad->estatus) || ('C' == $propiedad->estatus)) &&
                     (isset($propiedad->fecha_reserva) && isset($propiedad->fecha_firma)))
                    <a href="{{ route('propiedad.correo', [$propiedad->id, 1]) }}" class="btn btn-link"
                            title="Enviar correo de 'Reporte de Cierre' a '{{ (1 == $propiedad->user->id)?'Administrador':$propiedad->user->name }}' sobre esta propiedad ({{ $propiedad->codigo }}, {{ $propiedad->nombre }}).">
                        <span class="oi oi-envelope-closed"></span>
                    </a>
                @endif (('P' == $propiedad->estatus) || ('C' == $propiedad->estatus))
                @if ((Auth::user()->is_admin) && !($propiedad->user_borro || $propiedad->deleted_at) &&
                     !(('P' == $propiedad->estatus) || ('C' == $propiedad->estatus)))
                <form action="{{ route('propiedades.destroy', $propiedad) }}" method="POST" 
                        class="form-inline mt-0 mt-md-0"
                        onSubmit="return confirm('Realmente, desea borrar (borrado lógico) los datos de esta propiedad de la base de datos?')">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-link" title="Borrar (lógico) propiedad.">
                        <span class="oi oi-trash" title="Borrar {{ $propiedad->nombre }}">
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
    <p>No hay propiedades registradas.</p>
@endif ($propiedades->isNotEmpty())

@endsection

@section('js')

<script>
function alertaCampoRequerido() {
  var fecha_desde = document.getElementById('fecha_desde').value;
  var fecha_hasta = document.getElementById('fecha_hasta').value;
  var estatus = document.getElementById('estatus').value;
  var captador = document.getElementById('captador').value;
  var cerrador = document.getElementById('cerrador').value;

  if (('' == fecha_desde) && ('' == estatus) && (0 == captador) && (0 == cerrador)) {
    alert("Usted debe suministrar la fecha de reserva 'Desde' o el 'estatus' o el 'captador' o el 'cerrador'");
    return false;
  }
  return true;
}
</script>

@endsection
