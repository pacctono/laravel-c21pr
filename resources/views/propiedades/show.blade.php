@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Propiedad:
        [{{ $propiedad->id}}]{{ $propiedad->codigo }} 
        {{ substr($propiedad->nombre, 0, 30) }}
        {{ $propiedad->negociacion_alfa }}.
        {{ $propiedad->precio_ven }}
        @if (1 < $propiedad->user_id)
        ([{{ $propiedad->user_id }}] {{ substr($propiedad->user->name, 0, 20) }})
        @endif
    </h4>
    <div class="card-body">
        <p>Estatus del Inmueble: <span class="alert-info">
            {{ $propiedad->estatus_alfa }}
        </span></p>
        <p>Fecha de Reserva: <span class="alert-info">
            {{ $propiedad->reserva_en }}
        </span>&nbsp;
        Fecha de la firma: <span class="alert-info">
            {{ $propiedad->firma_en }}
        </span></p>
	<p>Comisi&oacute;n: <span class="alert-info">
	    {{ $propiedad->comision_p }}
        </span>&nbsp;Reserva sin IVA:<span class="alert-info">
            {{ $propiedad->reserva_sin_iva_ven }}
        </span>&nbsp;Reserva con IVA:<span class="alert-info">
            {{ $propiedad->reserva_con_iva_ven }}
        </span>&nbsp;<span class="alert-info">(IVA:
            {{ $propiedad->iva_p }})
        </span></p>
	<p>Lados: <span class="alert-info">
        {{ $propiedad->lados }}
        </span>&nbsp;Compartido con otra oficina sin IVA:<span class="alert-info">
            {{ $propiedad->compartido_sin_iva_ven }}
        </span>&nbsp;Compartido con otra oficina con IVA:<span class="alert-info">
            {{ $propiedad->compartido_con_iva_ven }}
        </span></p>
	<p>Reportado a casa nacional: <span class="alert-info">
        {{ $propiedad->reportado_casa_nacional_p }}
        </span>&nbsp;Franquicia de reserva sin IVA:<span class="alert-info">
            {{ $propiedad->franquicia_reservado_sin_iva_ven }}
        </span>&nbsp;Franquicia de reserva con IVA:<span class="alert-info">
            {{ $propiedad->franquicia_reservado_con_iva_ven }}
        </span></p>
	<p>Franquicia a pagar reportada: <span class="alert-info">
            {{ $propiedad->franquicia_pagar_reportada_ven }}
        </span>&nbsp;Regalia: <span class="alert-info">
            {{ $propiedad->regalia_ven }}
            &nbsp;({{ $propiedad->porc_regalia_p }})
        </span>&nbsp;SANAF 5%: <span class="alert-info">
            {{ $propiedad->sanaf_5_por_ciento_ven }}
        </span></p>
    <p>Oficina bruto real: <span class="alert-info">
            {{ $propiedad->oficina_bruto_real_ven }}
        </span>&nbsp; Base para honorarios socios: <span class="alert-info">
            {{ $propiedad->base_honorarios_socios_ven }}
        </span>&nbsp; Base para honorarios: <span class="alert-info">
            {{ $propiedad->base_para_honorarios_ven }}
        </span></p>
    <p>Asesor captador: <span class="alert-info">
            {{ ('1' == $propiedad->asesor_captador_id)?
                    $propiedad->asesor_captador:
                    $propiedad->captador->name }}
        </span>
            [{{ (($propiedad->aplicar_porc_captador)?'*':'') .
                                            $propiedad->porc_captador_prbr_p }}]
        <span class="alert-info">
            {{ ($propiedad->captador_prbr_ven) }}
        </span>&nbsp;Asesor cerrador: <span class="alert-info">
            {{ ('1' == $propiedad->asesor_cerrador_id)?
                    $propiedad->asesor_cerrador:
                    $propiedad->cerrador->name }}
        </span>
            [{{ (($propiedad->aplicar_porc_cerrador)?'*':'') .
                                            $propiedad->porc_cerrador_prbr_p }}]
        <span class="alert-info">
            {{ ($propiedad->cerrador_prbr_ven) }}
        </span></p>
    <p>GERENTE:
        [{{ (($propiedad->aplicar_porc_gerente)?'*':'') .
                                            $propiedad->porc_gerente_p }}]
        <span class="alert-info">
            {{ $propiedad->gerente_ven }}
        </span>&nbsp;Bonificaciones: <span class="alert-info">
            {{ $propiedad->bonificaciones_ven }}
        </span>&nbsp;Comisi&oacute;n bancaria descontada: <span class="alert-info">
            {{ $propiedad->comision_bancaria_ven }}
        </span>&nbsp;Ingreso neto a oficina: <span class="alert-info">
            {{ $propiedad->ingreso_neto_oficina_ven }}
        </span></p>
    <p>Recibo No.: <span class="alert-info">
        {{ ($propiedad->numero_recibo)?$propiedad->numero_recibo:'?' }}
        </span>&nbsp;Pago Gerente: <span class="alert-info">
        {{ $propiedad->pago_gerente }}
        </span>&nbsp;Fact.: <span class="alert-info">
        {{ ($propiedad->factura_gerente)?$propiedad->factura_gerente:'?' }}
        </span></p>
    <p>Pago Asesores: <span class="alert-info">
        {{ $propiedad->pago_asesores }}
        </span>&nbsp;Fact.: <span class="alert-info">
        {{ ($propiedad->factura_asesores)?$propiedad->factura_asesores:'?' }}
        </span></p>
    @if (1 == $propiedad->lados)
    <p>Pago Otra oficina: <span class="alert-info">
        {{ $propiedad->pago_otra_oficina }}
        </span></p>
    @endif
    <p>Pagado Casa Nacional: <span class="alert-info">
        {{ ($propiedad->pagado_casa_nacional)?'Si':'No' }}
        </span>&nbsp;Estatus sistema C21: <span class="alert-info">
        {{ $propiedad->estatus_c21_alfa }}
        </span>&nbsp;Reporte casa nacional: <span class="alert-info">
        {{ $propiedad->reporte_casa_nacional }}
        </span></p>
    @if ($propiedad->factura_AyS)
    <p>Factura A & S: <span class="alert-info">
        {{ $propiedad->factura_AyS }}
        </span></p>
    @endif
    <p>Comentarios: <span class="alert-info">{{ $propiedad->comentarios }}
        </span></p>
    @if (null != $propiedad->user_borro)
        <p>Esta propiedad fue borrada por {{ $propiedad->userBorro->name }} el
            {{ $propiedad->borrado_dia_semana }}
            {{ $propiedad->borrado_con_hora }}.
        </p>
    @endif
    @if ((null != $propiedad->user_actualizo) and (1 < $propiedad->user_actualizo))
        <p>Esta propiedad fue actualizada por {{ $propiedad->userActualizo->name }}
            el
            {{ $propiedad->actualizado_dia_semana }}
            {{ $propiedad->actualizado_con_hora }}.
        </p>
    @endif

    <p>
        <!-- a href="{{ action('PropiedadController@index') }}">Regresar al listado de propiedad</a -->
        @if ('' == $col_id)
            <a href="{{ route($rutRetorno) }}" class="btn btn-link">
        @else
            <a href="{{ route($rutRetorno, [$propiedad[$col_id], 'id']) }}" class="btn btn-link">
        @endif
                <button class="btn btn-primary col-sm-5" title="Ir a la lista de propiedades">
                    Ir a la lista de propiedades
                </button>
            </a>
    </p>
    </div>
</div>
@endsection
