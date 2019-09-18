@extends('layouts.app')

@section('content')
<div>
    <form method="POST" class="form-horizontal" action="{{ route('propiedades.post') }}"
          onSubmit="return alertaCampoRequerido()">
      {!! csrf_field() !!}

      <div class="form-group col-md-12">
        <label title="Fecha de la firma desde">Desde:</label>
        <input type="date" name="fecha_desde" id="fecha_desde" max="{{ now() }}"
            title="Fecha de la firma desde" value="{{ old('fecha_desde', $fecha_desde) }}">
        {{-- $fecha_desde --}}
        <label title="Fecha de la firma hasta">Hasta:</label>
        <input type="date" name="fecha_hasta" id="fecha_hasta" max="{{ now() }}"
            title="Fecha de la firma hasta" value="{{ old('fecha_hasta', $fecha_hasta) }}">

        <select name="estatus" id="estatus">
            <option value="">Estatus</option>
        @foreach ($arrEstatus as $opcion => $muestra)
            <option value="{{$opcion}}"
        @if (old('estatus', $estatus) == $opcion)
            selected
        @endif
            >{{ substr($muestra, 0, 35) }}</option>
        @endforeach
        </select>

    @if (Auth::user()->is_admin)
        <select name="captador" id="captador">
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

        <select name="cerrador" id="cerrador">
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
    @endif
        <button type="submit" class="btn btn-success">Mostrar</button>
      </div>
    </form>

    <div class="col-md-12">
        TOTS: {{ $filas }} props
        <span class="alert-success" title="Precio">
            {{ Prop::numeroVen($tPrecio, 0) }}</span>{{-- 'Prop' es un alias definido en config/app.php --}}
        @if (Auth::user()->is_admin)
            <span class="alert-info">Compartido con IVA:</span>
            <span class="alert-success" title="Compartido con otra oficina con IVA">
                {{ Prop::numeroVen($tCompartidoConIva, 2) }}</span>
            <span class="alert-info">Lados:</span>
            <span class="alert-success" title="Sumatoria de los lados">
                {{ $tLados }}</span>
            <span class="alert-info">Franq a pagar rep:</span>
            <span class="alert-success" title="Franquicia a pagar reportada">
                {{ Prop::numeroVen($tFranquiciaPagarR, 2) }}</span>
            <span class="alert-info">Regalia:</span>
            <span class="alert-success" title="Regalia">
                {{ Prop::numeroVen($tRegalia, 2) }}</span>
            <span class="alert-info">Sanaf:</span>
            <span class="alert-success" title="Sanaf - 5%">
                {{ Prop::numeroVen($tSanaf5PorCiento, 2) }}</span>
            <br>
            <span class="alert-info">Captador:</span>
            <span class="alert-success" title="Captador PRBR">
                {{ Prop::numeroVen($tCaptadorPrbr, 2) }}
                @if (0 < $tCaptadorPrbrSel)
                ({{ Prop::numeroVen($tCaptadorPrbrSel, 2) }} [{{ $tLadosCap }}])
                @endif
            </span>
            <span class="alert-info">Cerrador:</span>
            <span class="alert-success" title="Cerrador PRBR">
                {{ Prop::numeroVen($tCerradorPrbr, 2) }}
                @if (0 < $tCerradorPrbrSel)
                ({{ Prop::numeroVen($tCerradorPrbrSel, 2) }} [{{ $tLadosCer }}])
                @endif
            </span>
        @else
            <span class="alert-info">Lados:</span>
            <span class="alert-success" title="Sumatoria de los lados">
                {{ $tLadosCap + $tLadosCer }}</span>
            @if (0 < $tCaptadorPrbrSel)
                <span class="alert-info">Captador:</span>
                <span class="alert-success" title="Captador PRBR">
                    {{ Prop::numeroVen($tCaptadorPrbrSel, 2) }}
                </span>
            @endif
            @if (0 < $tCerradorPrbrSel)
            <span class="alert-info">Cerrador:</span>
            <span class="alert-success" title="Cerrador PRBR">
                {{ Prop::numeroVen($tCerradorPrbrSel, 2) }}
            </span>
            @endif
        @endif
        @if (0 < $tBonificaciones)
            <span class="alert-info">Bons:</span>
            <span class="alert-success" title="Bonificaciones">
                {{ Prop::numeroVen($tBonificaciones, 2) }}</span>
        @endif
        @if (Auth::user()->is_admin)
            <span class="alert-info">Gerente:</span>
            <span class="alert-success" title="Gerente">
                {{ Prop::numeroVen($tGerente, 2) }}</span>
            @if (0 < $tComisionBancaria)
                <span class="alert-info">Coms:</span>
                <span class="alert-success" title="Comision bancaria">
                    {{ Prop::numeroVen($tComisionBancaria, 2) }}</span>
            @endif
            <span class="alert-info">Neto:</span>
            <span class="alert-success" title="Ingreso neto de la oficina">
                {{ Prop::numeroVen($tIngresoNetoOfici, 2) }}</span>
            <span class="alert-info">PrVeRe:</span>
            <span class="alert-success" title="Precio de venta real">
                {{ Prop::numeroVen($tPrecioVentaReal, 2) }}
                @if ((0 < $tPvrCaptadorPrbrSel) || (0 < $tPvrCerradorPrbrSel))
                    ({{ Prop::numeroVen($tPvrCaptadorPrbrSel+$tPvrCerradorPrbrSel, 2) }})
                @endif
            </span>
            <br>
            <span class="alert-info">Tot Comision:</span>
            <span class="alert-success" title="Total de comisiones: Captado + Cerrado">
                {{ Prop::numeroVen($tCaptadorPrbr+$tCerradorPrbr, 2) }}
                @if ((0 < $tCaptadorPrbrSel) or (0 < $tCerradorPrbrSel))
                    ({{ Prop::numeroVen($tCaptadorPrbr+$tCerradorPrbrSel, 2) }} [{{ $tLadosCap+$tLadosCer }}])
                @endif
            </span>
        @else
            @if ((0 < $tPvrCaptadorPrbrSel) || (0 < $tPvrCerradorPrbrSel))
                <span class="alert-info">PrVeRe:</span>
                <span class="alert-success" title="Precio de venta real">
                    {{ Prop::numeroVen($tPvrCaptadorPrbrSel+$tPvrCerradorPrbrSel, 2) }}
                </span>
            @endif
            @if ((0 < $tCaptadorPrbrSel) or (0 < $tCerradorPrbrSel))
                <span class="alert-info">Tot Comision:</span>
                <span class="alert-success" title="Total de comisiones: Captado + Cerrado">
                        {{ Prop::numeroVen($tCaptadorPrbr+$tCerradorPrbrSel, 2) }}
                </span>
            @endif
        @endif
    </div>
</div>

    <div class="row">
        <div class="col-sm-8">
            <h1 class="pb-1">{{ $title }}</h1>
        </div>
        <div class="col-sm-4">
            <a href="{{ route('propiedades.create') }}" class="btn btn-primary float-right">
                Crear Propiedad</a>
        </div>
    </div>
    @if ($propiedades->isNotEmpty())
    <table class="table table-striped table-hover table-bordered table-sm">
        <thead class="thead-dark">
        <tr>
            <!-- th scope="col">#</th -->
            <th scope="col">
                <a href="{{ route('propiedades.orden', 'codigo') }}" class="btn btn-link">
                    C&oacute;digo
                </a>
            </th>
            <th scope="col" title="Fecha de reserva">
                <a href="{{ route('propiedades.orden', 'fecha_reserva') }}" class="btn btn-link">
                    Reserva
                </a>
        @if (Auth::user()->is_admin)
                <br>
        @else
            </th>
            <th scope="col" title="Fecha de la firma">
        @endif
                <a href="{{ route('propiedades.orden', 'fecha_firma') }}" class="btn btn-link">
                    Firma
                </a>
            </th>
            <th scope="col" title="Tipo de negociaci&oacute;n">
                <a href="{{ route('propiedades.orden', 'negociacion') }}" class="btn btn-link">
                    N
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('propiedades.orden', 'nombre') }}" class="btn btn-link">
                    Nombre
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('propiedades.orden', 'precio') }}" class="btn btn-link">
                    Precio
                </a>
            </th>
            <th scope="col" title="Lados">
                <a href="{{ route('propiedades.orden', 'lados') }}" class="btn btn-link">
                    L
                </a>
            </th>
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
        @endif
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>

        <?php $var = 0; // Variable para manejar el estilo de cada fila. ?>

        @foreach ($propiedades as $propiedad)
        <?php $var++;   // Variable para manejar el estilo de cada fila. ?>
        <tr class="
        @if ('I' == $propiedad->estatus)
            {{--table-light--}}
            table-active
        @elseif ('P' == $propiedad->estatus)
            table-warning
        @elseif ('S' == $propiedad->estatus)
            table-danger
        @else
            @if (0 == ($var % 2))
                table-primary
            @else
                table-info
            @endif
        @endif
        ">
            <td title="{{ $propiedad->id }}) {{ $propiedad->estatus_alfa }}
Reporte en casa nacional: {{ $propiedad->reporte_casa_nacional_ven }}
Estatus en sistema C21: {{ $propiedad->estatus_c21_alfa.(($propiedad->pagado_casa_nacional)?' y PAGADO A CASA NACIONAL':'') }}
{{ (($propiedad->factura_AyS)?'Factura A & S: '.$propiedad->factura_AyS.'.':'') }}">
                <span class="float-right"> {{ $propiedad->codigo }} </span>
            </td>

            <td>
                <span title="Fecha de reserva">
                    {{ $propiedad->reserva_en }}</span>
        @if (Auth::user()->is_admin)
                <br>
        @else
            </td>
            <td>
        @endif
                <span title="Fecha de la firma">
                    {{ $propiedad->firma_en }}</span>
            </td>

            <td title="{{ $propiedad->negociacion_alfa }}">
                <span class="float-center">{{ $propiedad->negociacion }}</span></td>

            @if (Auth::user()->is_admin)
            <td title="{{ $propiedad->comentarios }}">
            @else
            <td>
            @endif
            {{ $propiedad->nombre }}</td>

        <?php $propiedad->mMoZero = false;  // Si el monto es 0, mostrar 'espacio vacio'. ?>
        <?php $propiedad->espMonB = false;  // Eliminar espacio entre simbolo de la moneda y el monto. ?>

            @if (Auth::user()->is_admin)
            <td title="Comisi&oacute;n: {{ $propiedad->comision_p }}
 Reserva s/IVA(I):{{ $propiedad->reserva_sin_iva_ven }};
                  IVA:{{ $propiedad->iva_p }};
 Reserva c/IVA(K):{{ $propiedad->reserva_con_iva_ven }}">
            @else
            <td>
            @endif
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
            @endif
            </td>
            @if (Auth::user()->is_admin)
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

            @if (!(Auth::user()->is_admin))
                <td><span class="float-right">{{ $propiedad->comision_p }}</span></td>
                <td><span class="float-right">{{ $propiedad->iva_p }}</span></td>
                <td><span class="float-right" title="Precio de venta real">
                    {{ $propiedad->precio_venta_real_ven }}</span>
                </td>
            @endif

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
            @endif

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
            <td><span class="float-right">puntos</span></td-->
            @endif

            <td class="d-flex align-items-end">
                <a href="{{ route('propiedades.show', $propiedad) }}" class="btn btn-link" 
                        title="Mostrar los datos de esta propiedad.">
                    <span class="oi oi-eye"></span>
                </a>
                <a href="{{ route('propiedades.edit', $propiedad) }}" class="btn btn-link"
                        title="Editar los datos de esta propiedad.">
                    <span class="oi oi-pencil"></span>
                </a>

                @if (1 == Auth::user()->is_admin)
                <form action="{{ route('propiedades.destroy', $propiedad) }}" method="POST" 
                        class="form-inline mt-0 mt-md-0"
                        onSubmit="return confirm('Realmente, desea borrar (borrado lógico) los datos de esta propiedad de la base de datos?')">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-link" title="Borrar (lógico) propiedad.">
                        <span class="oi oi-trash" title="Borrar">
                        </span>
                    </button>
                </form>
                    @if ((4 <= $propiedad->resultado_id) and (7 >= $propiedad->resultado_id))
                    <a href="{{ route('agenda.emailcita', $propiedad) }}" class="btn btn-link"
                            title="Enviar correo a '{{ $propiedad->user->name }}', sobre cita con esta propiedad">
                        <span class="oi oi-envelope-closed"></span>
                    </a>
                    @endif
                @endif
            </td>
        </tr>

        <?php $propiedad->espMonB = true; // Restablecer variable cambiada antes de <precio> ?>

        @endforeach
        </tbody>
    </table>
    @if ($paginar)
    {{ $propiedades->links() }}
    @endif
    @else
        <p>No hay propiedades registradas.</p>
    @endif

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
