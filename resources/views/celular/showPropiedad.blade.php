@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Propiedad:
        [{{ $propiedad->id}}]{{ $propiedad->codigo }}<br> 
        {{ substr($propiedad->nombre, 0, 30) }}<br>
        {{ $propiedad->precio_ven }}
        {{ $propiedad->negociacion_alfa }}
        @if (1 < $propiedad->user_id)
        ([{{ $propiedad->user_id }}] {{ substr($propiedad->user->name, 0, 20) }})
        @endif
    </h4>

    <?php $propiedad->mMoZero = false;  // Si el monto es 0, mostrar 'espacio vacio'. ?>
    <?php $propiedad->espMonB = false;  // Eliminar espacio entre simbolo de la moneda y el monto. ?>

    <div class="card-body">
        Estatus del Inmueble:<span class="alert-info">
            {{ $propiedad->estatus_alfa }}
        </span><br>
        Fecha de Reserva:<span class="alert-info">
            {{ $propiedad->reserva_en }}
        </span><br>
        Fecha de la firma:<span class="alert-info">
            {{ $propiedad->firma_en }}
        </span>
	    Comisi&oacute;n:
        <span class="alert-info">{{ $propiedad->comision_p }}
        </span><br>
        Reserva sin IVA:<span class="alert-info">
            {{ $propiedad->reserva_sin_iva_ven }}
        </span><br>
        Reserva con IVA:<span class="alert-info">
            {{ $propiedad->reserva_con_iva_ven }}
        </span><br>
        IVA:<span class="alert-info">
            {{ $propiedad->iva_p }}
        </span><br>
	    Lados:<span class="alert-info">
        {{ $propiedad->lados }}
        </span><br>
        @if (Auth::user()->is_admin)
            Compartido con otra oficina sin IVA:<span class="alert-info">
                {{ $propiedad->compartido_sin_iva_ven }}
            </span><br>
            Compartido con otra oficina con IVA:<span class="alert-info">
                {{ $propiedad->compartido_con_iva_ven }}
            </span><br>
            Reportado a casa nacional:<span class="alert-info">
            {{ $propiedad->reportado_casa_nacional_p }}
            </span><br>
            Franquicia de reserva sin IVA:<span class="alert-info">
                {{ $propiedad->franquicia_reservado_sin_iva_ven }}
            </span><br>
            Franquicia de reserva con IVA:<span class="alert-info">
                {{ $propiedad->franquicia_reservado_con_iva_ven }}
            </span><br>
            Franquicia a pagar reportada:<span class="alert-info">
                {{ $propiedad->franquicia_pagar_reportada_ven }}
            </span><br>
            Regalia:<span class="alert-info">
                {{ $propiedad->regalia_ven }}
                ({{ $propiedad->porc_regalia_p }})
            </span><br>
            SANAF 5%:<span class="alert-info">
                {{ $propiedad->sanaf_5_por_ciento_ven }}
            </span><br>
            Oficina bruto real:<span class="alert-info">
                {{ $propiedad->oficina_bruto_real_ven }}
            </span><br>
            Base para honorarios socios:<span class="alert-info">
                {{ $propiedad->base_honorarios_socios_ven }}
            </span><br>
            Base para honorarios:<span class="alert-info">
                {{ $propiedad->base_para_honorarios_ven }}
            </span><br>
        @endif
        Captador:<span class="alert-info">
            {{ ('1' == $propiedad->asesor_captador_id)?
                    $propiedad->asesor_captador:
                    $propiedad->captador->name }}
        </span>
            [{{ $propiedad->porc_captador_prbr_p }}]
        <span class="alert-info">
            {{ ($propiedad->captador_prbr_ven) }}
        </span><br>
        Cerrador:<span class="alert-info">
            {{ ('1' == $propiedad->asesor_cerrador_id)?
                    $propiedad->asesor_cerrador:
                    $propiedad->cerrador->name }}
        </span>
            [{{ $propiedad->porc_cerrador_prbr_p }}]
        <span class="alert-info">
            {{ ($propiedad->cerrador_prbr_ven) }}
        </span><br>
        @if (Auth::user()->is_admin)
            GERENTE:
            [{{ $propiedad->porc_gerente_p }}]
            <span class="alert-info">
                {{ $propiedad->gerente_ven }}
            </span><br>
            Bonificaciones:<span class="alert-info">
                {{ $propiedad->bonificaciones_ven }}
            </span><br>
            Comisi&oacute;n bancaria descontada:<span class="alert-info">
                {{ $propiedad->comision_bancaria_ven }}
            </span><br>
            Ingreso neto a oficina:<span class="alert-info">
                {{ $propiedad->ingreso_neto_oficina_ven }}
            </span><br>
            #Recibo:<span class="alert-info">
            {{ ($propiedad->numero_recibo)?$propiedad->numero_recibo:'?' }}
            </span><br>
            Pago Gerente:<span class="alert-info">
            {{ $propiedad->pago_gerente }}
            </span><br>
            Fact gerente:<span class="alert-info">
            {{ ($propiedad->factura_gerente)?$propiedad->factura_gerente:'?' }}
            </span><br>
            Pago Asesores:<span class="alert-info">
            {{ $propiedad->pago_asesores }}
            </span><br>
            Fact asesores:<span class="alert-info">
            {{ ($propiedad->factura_asesores)?$propiedad->factura_asesores:'?' }}
            </span><br>
            @if (1 == $propiedad->lados)
                Pago Otra oficina:<span class="alert-info">
                {{ $propiedad->pago_otra_oficina }}
                </span><br>
            @endif
            Pagado Casa Nacional:<span class="alert-info">
            {{ ($propiedad->pagado_casa_nacional)?'Si':'No' }}
            </span><br>
            Estatus sistema C21:<span class="alert-info">
            {{ $propiedad->estatus_c21_alfa }}
            </span><br>
            Reporte casa nacional:<span class="alert-info">
            {{ $propiedad->reporte_casa_nacional }}
            </span><br>
            @if ($propiedad->factura_AyS)
                Factura A & S:<span class="alert-info">
                {{ $propiedad->factura_AyS }}
                </span><br>
            @endif
            Comentarios:<span class="alert-info">{{ $propiedad->comentarios }}
            </span><br>
            @if (null != $propiedad->user_borro)
                    Esta propiedad fue borrada por {{ $propiedad->userBorro->name }} el
                    {{ $propiedad->borrado_dia_semana }}
                    {{ $propiedad->borrado_con_hora }}.<br>
            @endif
            @if ((null != $propiedad->user_actualizo) and (1 < $propiedad->user_actualizo))
                Esta propiedad fue actualizada por {{ $propiedad->userActualizo->name }}
                el
                {{ $propiedad->actualizado_dia_semana }}
                {{ $propiedad->actualizado_con_hora }}.<br>
            @endif
        @endif

        @if ('' == $col_id)
            <a href="{{ route($rutRetorno) }}" class="btn btn-link">
        @else
            <a href="{{ route($rutRetorno, [$propiedad[$col_id], 'id']) }}" class="btn btn-link">
        @endif
                <button class="btn btn-primary col-sm-5" title="Ir a la lista de propiedades">
                    Ir a la lista de propiedades
                </button>
            </a>

        <?php $propiedad->espMonB = true; // Restablecer variable cambiada antes de <precio> ?>

    </div>
</div>
@endsection
