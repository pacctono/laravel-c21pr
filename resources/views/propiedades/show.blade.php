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
    	<div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Estatus del Inmueble:<span class="alert-info">{{ $propiedad->estatus_alfa }}</span>
            </div>
        </div>

    	<div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Fecha de Reserva:<span class="alert-info">{{ $propiedad->reserva_en }}</span>
            </div>
            <div class="mx-1 px-2">
                Fecha de la firma:<span class="alert-info">{{ $propiedad->firma_en }}</span>
            </div>
        </div>

    	<div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Comisi&oacute;n:<span class="alert-info">{{ $propiedad->comision_p }}</span>
            </div>
            <div class="mx-1 px-2">
                Reserva sin IVA:<span class="alert-info">{{ $propiedad->reserva_sin_iva_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Reserva con IVA:<span class="alert-info">{{ $propiedad->reserva_con_iva_ven }}</span>
                <span class="alert-info">(IVA:{{ $propiedad->iva_p }})</span>
            </div>
        </div>

    	<div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Lados:<span class="alert-info">{{ $propiedad->lados }}</span>
            </div>
            <div class="mx-1 px-2">
                Compartido con otra oficina sin IVA:<span class="alert-info">
                {{ $propiedad->compartido_sin_iva_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Compartido con otra oficina con IVA:<span class="alert-info">
                {{ $propiedad->compartido_con_iva_ven }}</span>
            </div>
        </div>

        <!--fieldset class="datosPropiedad" style="border:solid 2px #000000">
            <legend>
                <button id="datosPropiedad" title="Presione para mostrar/esconder los datos de la propiedad">
                    Datos de la propiedad
                </button>
            </legend-->
        <div class="row mt-2 mb-0 px-0 borde-arriba">
            <div class="mx-1 px-2">
                Tipo:<span class="alert-info">{{ $propiedad->tipo->descripcion }}</span>
            </div>
            <div class="mx-1 px-2">
                Metraje:<span class="alert-info">{{ $propiedad->metraje }}</span>
            </div>
            <div class="mx-1 px-2">
                Habitaciones:<span class="alert-info">{{ $propiedad->habitaciones }}</span>
            </div>
            <div class="mx-1 px-2">
                Ba&ntilde;os:<span class="alert-info">{{ $propiedad->banos }}</span>
            </div>
        </div>

        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Niveles:<span class="alert-info">{{ $propiedad->niveles }}</span>
            </div>
            <div class="mx-1 px-2">
                Puestos de estacionamiento:<span class="alert-info">{{ $propiedad->puestos }}</span>
            </div>
            <div class="mx-1 px-2">
                A&ntilde;o de construccion:<span class="alert-info">{{ $propiedad->anoc }}</span>
            </div>
            <div class="mx-1 px-2">
                Caracter&iacute;sticas:<span class="alert-info">{{ $propiedad->caracteristica->descripcion }}</span>
            </div>
        </div>

        <div class="row my-0 py-0">
            <div class="col-lg-12 mx-1 px-2">
                Descripci&oacute;n:<span class="alert-info">{{ $propiedad->descripcion }}</span>
            </div>
        </div>

        <div class="row my-0 py-0 bg-suave">
            <div class="col-lg-12 mx-1 px-2">
                Direcci&oacute;n:<span class="alert-info">{{ $propiedad->direccion }}</span>
            </div>
        </div>

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Ciudad:
                <span class="alert-info">{{ $propiedad->ciudad->descripcion }}</span>
            </div>
            <div class="mx-1 px-2">
                Codigo postal:
                <span class="alert-info">{{ $propiedad->codigo_postal }}</span>
            </div>
            <div class="mx-1 px-2">
                Municipio:
                <span class="alert-info">{{ $propiedad->municipio->descripcion }}</span>
            </div>
            <div class="mx-1 px-2">
                Estado:
                <span class="alert-info">{{ $propiedad->estado->descripcion }}</span>
            </div>
        </div>

        <div class="row mt-0 mb-2 py-0 bg-suave borde-abajo">
            <div class="mx-1 px-2">
                Cliente</label>
                <span class="alert-info">{{ $propiedad->cliente->name }}</span>
            </div>
            <div class="mx-1 px-2">
                Telefono del cliente:<span class="alert-info">
                    {{ $propiedad->cliente->telefono_f }}
                </span>
            </div>
            <div class="mx-1 px-2">
                Correo del cliente:<span class="alert-info">
                    {{ $propiedad->cliente->email }}
                </span>
            </div>
        </div>
        <!--/fieldset-->

    @if (Auth::user()->is_admin)
        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Reportado a casa nacional:
                <span class="alert-info">{{ $propiedad->reportado_casa_nacional_p }}</span>
            </div>
            <div class="mx-1 px-2">
                Franquicia de reserva sin IVA:
                <span class="alert-info">{{ $propiedad->franquicia_reservado_sin_iva_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Franquicia de reserva con IVA:
                <span class="alert-info">{{ $propiedad->franquicia_reservado_con_iva_ven }}</span>
            </div>
        </div>

        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Franquicia a pagar reportada: <span class="alert-info">
                    {{ $propiedad->franquicia_pagar_reportada_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Regalia:<span class="alert-info">{{ $propiedad->regalia_ven }}
                    ({{ $propiedad->porc_regalia_p }})</span>
            </div>
            <div class="mx-1 px-2">
                SANAF 5%: <span class="alert-info">
                    {{ $propiedad->sanaf_5_por_ciento_ven }}</span>
            </div>
        </div>

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Oficina bruto real:<span class="alert-info">
                    {{ $propiedad->oficina_bruto_real_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Base para honorarios socios: <span class="alert-info">
                    {{ $propiedad->base_honorarios_socios_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Base para honorarios: <span class="alert-info">
                    {{ $propiedad->base_para_honorarios_ven }}</span>
            </div>
        </div>

        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Asesor captador: <span class="alert-info">
                    {{ ('1' == $propiedad->asesor_captador_id)?
                                $propiedad->asesor_captador:
                                $propiedad->captador->name }}</span>
                    [{{ $propiedad->porc_captador_prbr_p }}]
                <span class="alert-info">{{ ($propiedad->captador_prbr_ven) }}</span>
            </div>
            <div class="mx-1 px-2">
                Asesor cerrador: <span class="alert-info">
                    {{ ('1' == $propiedad->asesor_cerrador_id)?
                                $propiedad->asesor_cerrador:
                                $propiedad->cerrador->name }}</span>
                    [{{ $propiedad->porc_cerrador_prbr_p }}]
                <span class="alert-info">{{ ($propiedad->cerrador_prbr_ven) }}</span>
            </div>
        </div>

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                GERENTE:[{{ $propiedad->porc_gerente_p }}]<span class="alert-info">
                    {{ $propiedad->gerente_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Bonificaciones:<span class="alert-info">{{ $propiedad->bonificaciones_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Comisi&oacute;n bancaria descontada:
                <span class="alert-info">{{ $propiedad->comision_bancaria_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Ingreso neto a oficina:<span class="alert-info">
                    {{ $propiedad->ingreso_neto_oficina_ven }}</span>
            </div>
        </div>

        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Recibo No.:<span class="alert-info">
                    {{ ($propiedad->numero_recibo)?$propiedad->numero_recibo:'?' }}</span>
            </div>
            <div class="mx-1 px-2">
                Pago Gerente:<span class="alert-info">{{ $propiedad->pago_gerente }}</span>
            </div>
            <div class="mx-1 px-2">
                Fact.:<span class="alert-info">
                    {{ ($propiedad->factura_gerente)?$propiedad->factura_gerente:'?' }}</span>
            </div>
        </div>

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Pago Asesores: <span class="alert-info">{{ $propiedad->pago_asesores }}</span>
            </div>
            <div class="mx-1 px-2">
                Fact.: <span class="alert-info">
                    {{ ($propiedad->factura_asesores)?$propiedad->factura_asesores:'?' }}</span>
            </div>
        </div>

    @if (1 == $propiedad->lados)
        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Pago Otra oficina:<span class="alert-info">{{ $propiedad->pago_otra_oficina }}</span>
            </div>
        </div>
    @endif

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Pagado Casa Nacional: <span class="alert-info">
                    {{ ($propiedad->pagado_casa_nacional)?'Si':'No' }}</span>
            </div>
            <div class="mx-1 px-2">
                Estatus sistema C21: <span class="alert-info">
                    {{ $propiedad->estatus_c21_alfa }}</span>
            </div>
            <div class="mx-1 px-2">
                Reporte casa nacional: <span class="alert-info">
                    {{ $propiedad->reporte_casa_nacional }}</span>
            </div>
        </div>

    @if ($propiedad->factura_AyS)
        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Factura A & S: <span class="alert-info">{{ $propiedad->factura_AyS }}</span>
            </div>
        </div>
    @endif

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Comentarios: <span class="alert-info">{{ $propiedad->comentarios }}</span>
            </div>
        </div>

    @if (null != $propiedad->user_borro)
        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Esta propiedad fue borrada por {{ $propiedad->userBorro->name }} el
                {{ $propiedad->borrado_dia_semana }}
                {{ $propiedad->borrado_con_hora }}.
            </div>
        </div>

    @endif
    @if ((null != $propiedad->user_actualizo) and (1 < $propiedad->user_actualizo))
        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Esta propiedad fue actualizada por {{ $propiedad->userActualizo->name }} el
                {{ $propiedad->actualizado_dia_semana }}
                {{ $propiedad->actualizado_con_hora }}.
            </div>
        </div>

    @endif
    @endif

    <p>
        <!-- a href="{{ action('PropiedadController@index') }}">Regresar al listado de propiedad</a -->
    @if ((isset($col_id)) and ('' != $col_id))
        <a href="{{ route($rutRetorno, [$propiedad[$col_id], $orden]).$nroPagina }}"
            class="btn btn-link">
    @else
        <a href="{{ route($rutRetorno, $orden).$nroPagina }}" class="btn btn-link">
    @endif
            <button class="btn btn-primary" title="Ir a la lista de propiedades">
                Ir a la lista de propiedades
            </button>
        </a>
    </p>
    </div>
</div>
@endsection
