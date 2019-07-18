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
        </spam></p>
    </h4>
    <div class="card-body">
        <p>Estatus del Inmueble: <spam class="alert-info">
            {{ $propiedad->estatus_alfa }}
        </spam></p>
        <p>Fecha de Reserva: <spam class="alert-info">
            {{ $propiedad->reserva_en }}
        </spam>&nbsp;
        Fecha de la firma: <spam class="alert-info">
            {{ $propiedad->firma_en }}
        </spam></p>
	<p>Comisi&oacute;n: <spam class="alert-info">
	    {{ $propiedad->comision_p }}
        </spam>&nbsp;Reserva sin IVA:<spam class="alert-info">
            {{ $propiedad->reserva_sin_iva }}
        </spam>&nbsp;Reserva con IVA:<spam class="alert-info">
            {{ $propiedad->reserva_con_iva }}
        </spam>&nbsp;<spam class="alert-info">(IVA:
            {{ $propiedad->iva_p }})
        </spam></p>
	<p>Lados: <spam class="alert-info">
        {{ $propiedad->lados }}
        </spam>&nbsp;Compartido con otra oficina sin IVA:<spam class="alert-info">
            {{ $propiedad->compartido_sin_iva }}
        </spam>&nbsp;Compartido con otra oficina con IVA:<spam class="alert-info">
            {{ $propiedad->compartido_con_iva }}
        </spam></p>
	<p>Reportado a casa nacional: <spam class="alert-info">
        {{ $propiedad->reportado_casa_nacional_p }}
        </spam>&nbsp;Franquicia de reserva sin IVA:<spam class="alert-info">
            {{ $propiedad->franquicia_reservado_sin_iva }}
        </spam>&nbsp;Franquicia de reserva con IVA:<spam class="alert-info">
            {{ $propiedad->franquicia_reservado_con_iva }}
        </spam></p>
	<p>Franquicia a pagar reportada: <spam class="alert-info">
            {{ $propiedad->franquicia_pagar_reportada }}
        </spam>&nbsp;Regalia: <spam class="alert-info">
            {{ $propiedad->regalia }}
            &nbsp;({{ $propiedad->porc_regalia_p }})
        </spam>&nbsp;SANAF 5%: <spam class="alert-info">
            {{ $propiedad->sanaf_5_por_ciento }}
        </spam></p>
    <p>Oficina bruto real: <spam class="alert-info">
            {{ $propiedad->oficina_bruto_real }}
        </spam>&nbsp; Base para honorarios socios: <spam class="alert-info">
            {{ $propiedad->base_honorarios_socios }}
        </spam>&nbsp; Base para honorarios: <spam class="alert-info">
            {{ $propiedad->base_para_honorarios }}
        </spam></p>
    <p>Asesor captador: <spam class="alert-info">
            {{ ('1' == $propiedad->asesor_captador_id)?
                    $propiedad->asesor_captador:
                    $propiedad->captador->name }}
        </spam>
            [{{ (($propiedad->aplicar_porc_captador)?'*':'') .
                                            $propiedad->porc_captador_prbr_p }}]
        <spam class="alert-info">
            {{ ($propiedad->captador_prbr) }}
        </spam>&nbsp;Asesor cerrador: <spam class="alert-info">
            {{ ('1' == $propiedad->asesor_cerrador_id)?
                    $propiedad->asesor_cerrador:
                    $propiedad->cerrador->name }}
        </spam>
            [{{ (($propiedad->aplicar_porc_cerrador)?'*':'') .
                                            $propiedad->porc_cerrador_prbr_p }}]
        <spam class="alert-info">
            {{ ($propiedad->cerrador_prbr) }}
        </spam></p>
    <p>GERENTE:
        [{{ (($propiedad->aplicar_porc_gerente)?'*':'') .
                                            $propiedad->porc_gerente_p }}]
        <spam class="alert-info">
            {{ $propiedad->gerente }}
        </spam>&nbsp;Bonificaciones: <spam class="alert-info">
            {{ $propiedad->bonificaciones }}
        </spam>&nbsp;Comisi&oacute;n bancaria descontada: <spam class="alert-info">
            {{ $propiedad->comision_bancaria_ven }}
        </spam>&nbsp;Ingreso neto a oficina: <spam class="alert-info">
            {{ $propiedad->ingreso_neto_oficina }}
        </spam></p>
    <p>Recibo No.: <spam class="alert-info">
        {{ ($propiedad->numero_recibo)?$propiedad->numero_recibo:'?' }}
        </spam>&nbsp;Pago Gerente: <spam class="alert-info">
        {{ $propiedad->pago_gerente }}
        </spam>&nbsp;Fact.: <spam class="alert-info">
        {{ ($propiedad->factura_gerente)?$propiedad->factura_gerente:'?' }}
        </spam></p>
    <p>Pago Asesores: <spam class="alert-info">
        {{ $propiedad->pago_asesores }}
        </spam>&nbsp;Fact.: <spam class="alert-info">
        {{ ($propiedad->factura_asesores)?$propiedad->factura_asesores:'?' }}
        </spam></p>
    @if (1 == $propiedad->lados)
    <p>Pago Otra oficina: <spam class="alert-info">
        {{ $propiedad->pago_otra_oficina }}
        </spam></p>
    @endif
    <p>Pagado Casa Nacional: <spam class="alert-info">
        {{ ($propiedad->pagado_casa_nacional)?'Si':'No' }}
        </spam>&nbsp;Estatus sistema C21: <spam class="alert-info">
        {{ $propiedad->estatus_c21_alfa }}
        </spam>&nbsp;Reporte casa nacional: <spam class="alert-info">
        {{ $propiedad->reporte_casa_nacional }}
        </spam></p>
    @if ($propiedad->factura_AyS)
    <p>Factura A & S: <spam class="alert-info">
        {{ $propiedad->factura_AyS }}
        </spam></p>
    @endif
    <p>Comentarios: <spam class="alert-info">{{ $propiedad->comentarios }}
        </spam></p>
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
