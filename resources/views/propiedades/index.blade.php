@extends('layouts.app')

@section('content')
<div>
    <form method="POST" class="form-horizontal" action="{{ route('propiedades.post') }}"
          onSubmit="return alertaCampoRequerido()">
          {{-- onSubmit="return confirm('Desde:|'+document.getElementById('fecha_desde').value+'|'+
                            'Captador:|'+document.getElementById('captador').value+'|');"> --}}
      {!! csrf_field() !!}

      <div class="form-group col-md-12">
      {{-- @foreach (['hoy', 'ayer', 'manana', 'esta_semana', 'semana_pasada', 'proxima_semana',
                'este_mes', 'mes_pasado', 'proximo_mes', 'todo', 'intervalo'] as $intervalo)
        <input type="radio" required name="periodo" id="_{{ $intervalo }}"
                value="{{ $intervalo }}"
        @if ($rPeriodo == $intervalo)
          checked
        @endif
        >
        <label>
        @if ('manana' == $intervalo)
        Mañana
        @else
        {{ str_replace('_', ' ', ucfirst($intervalo)) }}
        @endif
        </label>
      @endforeach
        <br> --}}
        <label>Fecha de reserva desde:</label>
        <input type="date" name="fecha_desde" id="fecha_desde" max="{{ now() }}"
            value="{{ old('fecha_desde', $fecha_desde) }}">
        {{-- $fecha_desde --}}
        <label>Hasta:</label>
        <input type="date" name="fecha_hasta" id="fecha_hasta" max="{{ now() }}"
            value="{{ old('fecha_hasta', $fecha_hasta) }}">
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
        @endif
        @if (Auth::user()->is_admin)
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
        <br>
        TOTS: {{ $filas }} props
        <spam class="alert-success" title="Precio">
            {{ Prop::numeroVen($tPrecio, 0) }}</spam>{{-- 'Prop' es un alias definido en config/app.php --}}
        <spam class="alert-info">Compartido con IVA:</spam>
        <spam class="alert-success" title="Compartido con otra oficina con IVA">
            {{ Prop::numeroVen($tCompartidoConIva, 2) }}</spam>
        <spam class="alert-info">Lados:</spam>
        <spam class="alert-success" title="Sumatoria de los lados">
            {{ $tLados }}</spam>
        <spam class="alert-info">Franq a pagar rep:</spam>
        <spam class="alert-success" title="Franquicia a pagar reportada">
            {{ Prop::numeroVen($tFranquiciaPagarR, 2) }}</spam>
        <spam class="alert-info">Regalia:</spam>
        <spam class="alert-success" title="Regalia">
            {{ Prop::numeroVen($tRegalia, 2) }}</spam>
        <spam class="alert-info">Sanaf:</spam>
        <spam class="alert-success" title="Sanaf - 5%">
            {{ Prop::numeroVen($tSanaf5PorCiento, 2) }}</spam>
        <br>
        <spam class="alert-info">Captador:</spam>
        <spam class="alert-success" title="Captador PRBR">
            {{ Prop::numeroVen($tCaptadorPrbr, 2) }}
            @if (0 < $tCaptadorPrbrSel)
            ({{ Prop::numeroVen($tCaptadorPrbrSel, 2) }} [{{ $tLadosCap }}])
            @endif
        </spam>
        <spam class="alert-info">Gerente:</spam>
        <spam class="alert-success" title="Gerente">
            {{ Prop::numeroVen($tGerente, 2) }}</spam>
        <spam class="alert-info">Cerrador:</spam>
        <spam class="alert-success" title="Cerrador PRBR">
            {{ Prop::numeroVen($tCerradorPrbr, 2) }}
            @if (0 < $tCerradorPrbrSel)
            ({{ Prop::numeroVen($tCerradorPrbrSel, 2) }} [{{ $tLadosCer }}])
            @endif
        </spam>
        @if (0 < $tBonificaciones)
        <spam class="alert-info">Bons:</spam>
        <spam class="alert-success" title="Bonificaciones">
            {{ Prop::numeroVen($tBonificaciones, 2) }}</spam>
        @endif
        @if (0 < $tComisionBancaria)
        <spam class="alert-info">Cons:</spam>
        <spam class="alert-success" title="Comision bancaria">
            {{ Prop::numeroVen($tComisionBancaria, 2) }}</spam>
        @endif
        <spam class="alert-info">Neto:</spam>
        <spam class="alert-success" title="Ingreso neto de la oficina">
            {{ Prop::numeroVen($tIngresoNetoOfici, 2) }}</spam>
        <spam class="alert-info">PVR:</spam>
        <spam class="alert-success" title="Precio de venta real">
            {{ Prop::numeroVen($tPrecioVentaReal, 2) }}</spam>
      </div>
    </form>
</div>

    <div class="d-flex justify-content-between align-items-end mb-1">
        <h1 class="pb-1">{{ $title }}</h1>

        <p>
            <a href="{{ route('propiedades.create') }}" class="btn btn-primary">Crear Propiedad</a>
        </p>
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
            <th scope="col" title="Fecha de reserva y
            Fecha de la firma">
                <a href="{{ route('propiedades.orden', 'fecha_reserva') }}" class="btn btn-link">
                    Fechas
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
                <spam class="float-right"> {{ $propiedad->codigo }} </spam>
            </td>

            <td>
                <spam title="Fecha de reserva">{{ $propiedad->reserva_en }}</spam><br>
                <spam title="Fecha de la firma">{{ $propiedad->firma_en }}</spam>
            </td>

            <td title="{{ $propiedad->negociacion_alfa }}">
                {{ $propiedad->negociacion }}</td>

            <td title="{{ $propiedad->comentarios }}">{{ $propiedad->nombre }}</td>

            <td title="Comisi&oacute;n: {{ $propiedad->comision_p }}
 Reserva s/IVA(I):{{ $propiedad->reserva_sin_iva_ven }};
                  IVA:{{ $propiedad->iva_p }};
 Reserva c/IVA(K):{{ $propiedad->reserva_con_iva_ven }}"
            >

        <?php $propiedad->mMoZero = false;  // Si el monto es 0, mostrar 'espacio vacio'. ?>
        <?php $propiedad->espMonB = false;  // Eliminar espacio entre simbolo de la moneda y el monto. ?>

                <spam class="float-right" title="Precio del inmueble">
                    {{ $propiedad->precio_ven }}
                </spam><br>
                <spam title="Comisi&oacute;n((H)">
                    Com:{{ $propiedad->comision_p }}
                </spam><br>
                <spam class="float-right" title="IVA(J)">
                    IVA:{{ $propiedad->iva_p }}
                </spam><br>
                <spam class="float-right" title="Precio de venta real">
                    {{ $propiedad->precio_venta_real_ven }}
                </spam>
            </td>

            <td title="Compartido con otra oficina
                  s/IVA(M):{{ $propiedad->compartido_sin_iva_ven }};
 Reserva s/IVA(I):{{ $propiedad->reserva_sin_iva_ven }}
 Reserva c/IVA(K):{{ $propiedad->reserva_con_iva_ven }}"
            >
                {{ $propiedad->lados }}
            </td>

            <td title="Franquicia">
                <spam class="float-right" title="Franquicia de reserva sin IVA(O) ({{ $propiedad->porc_franquicia_p }})">
                    {{ $propiedad->franquicia_reservado_sin_iva_ven }} 
                </spam><br>
                <spam class="float-right" title="Franquicia de reserva con IVA(P) ({{ $propiedad->porc_franquicia_p }})">
                    {{ $propiedad->franquicia_reservado_con_iva_ven }}
                </spam><br>
                <spam class="float-right" title="Franquicia a pagar reportada(Q) ({{ $propiedad->reportado_casa_nacional_p }})">
                    {{ $propiedad->franquicia_pagar_reportada_ven }}
                </spam><br>
                <spam class="float-right" title="Compartido con IVA(L)">
                    {{ $propiedad->compartido_con_iva_ven }}
                </spam>
            </td>

            <td>
                <spam class="float-right" title="Porcentaje de REGALIA(S):{{ $propiedad->porc_regalia_p }}">
                    {{ $propiedad->regalia_ven }}
                </spam><br>
                <spam class="float-right" title="SANAF 5 Porciento(T)">
                    {{ $propiedad->sanaf_5_por_ciento_ven }}
                </spam><br>
                <spam title="Porcentaje reportado a casa nacional(R)">
                    RCN:{{ $propiedad->reportado_casa_nacional_p }}
                </spam>
            </td>

            <td>
                <spam class="float-right" title="Oficina bruto real(U)">
                    {{ $propiedad->oficina_bruto_real_ven }}
                </spam><br>
                <spam class="float-right" title="Base para honorarios socios(V)">
                    {{ $propiedad->base_honorarios_socios_ven }}
                </spam><br>
                <spam class="float-right" title="Base para honorarios(W)">
                    {{ $propiedad->base_para_honorarios_ven }}
                </spam><br>
                <spam class="float-right" title="Ingreso neto a oficina(AC)
{{ ($propiedad->numero_recibo)?('Recibo No.: '.$propiedad->numero_recibo):'' }}">
                    {{ $propiedad->ingreso_neto_oficina_ven }}
                </spam>
            </td>

            <td title="Comisi&oacute;n del asesor captador
Comisi&oacute;n del gerente y
Comisi&oacute;n del asesor cerrador."
            >
                <spam class="float-right" title="Captador PRBR(X){{ $propiedad->nombre_captador }}">
                    {{ $propiedad->captador_prbr_ven }}
                </spam><br>
                <spam class="float-right" title="Gerente(Y)">
                    {{ $propiedad->gerente_ven }}
                </spam><br>
                <spam class="float-right" title="Cerrador PRBR(Z){{ $propiedad->nombre_cerrador }}">
                    {{ $propiedad->cerrador_prbr_ven }}
                </spam><br>
                <spam class="float-right" title="Bonificaciones">
                    {{ $propiedad->bonificaciones_ven }}
                </spam>
            </td>

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
  var captador = document.getElementById('captador').value;
  var cerrador = document.getElementById('cerrador').value;

  if (('' == fecha_desde) && (0 == captador) && (0 == cerrador)) {
    alert("Usted debe suministrar la fecha de reserva 'Desde' o el 'captador' o el 'cerrador'");
    return false;
  }
  return true;
}
</script>

@endsection