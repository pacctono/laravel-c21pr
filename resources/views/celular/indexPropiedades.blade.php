@extends('layouts.app')

@section('content')
<div>
    <form method="POST" class="form-horizontal" action="{{ route('propiedades.post') }}"
          onSubmit="return alertaCampoRequerido()">
      {!! csrf_field() !!}

      <div class="form-group col-md-12">
        <label>Fecha de reserva:</label><br>
        <label>Desde:</label>
        <input type="date" name="fecha_desde" id="fecha_desde" max="{{ now() }}"
            value="{{ old('fecha_desde', $fecha_desde) }}"><br>
        <label>Hasta:</label>
        <input type="date" name="fecha_hasta" id="fecha_hasta" max="{{ now() }}"
            value="{{ old('fecha_hasta', $fecha_hasta) }}"><br>
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
        </select><br>
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
        </select><br>
        @endif
        <button type="submit" class="btn btn-success">Mostrar</button>
        <br>
      </div>
    </form>
</div>

    <div class="d-flex justify-content-between align-items-end mb-1">
        <h1 class="pb-1">Propiedades</h1>

        <p>
            <a href="{{ route('propiedades.create') }}" class="btn btn-primary">Crear</a>
        </p>
    </div>
    @if ($propiedades->isNotEmpty())
    <table class="table table-striped table-hover table-bordered table-sm">
        <thead class="thead-dark">
        <tr>
            <th scope="col">
                <a href="{{ route('propiedades.orden', 'codigo') }}" class="btn btn-link">
                    C&oacute;digo
                </a>
            </th>
            {{-- <th scope="col">
                <a href="{{ route('propiedades.orden', 'fecha_reserva') }}" class="btn btn-link">
                    Fechas
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('propiedades.orden', 'negociacion') }}" class="btn btn-link">
                    N
                </a>
            </th>--}}
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
            <th scope="col">
                <a href="{{ route('propiedades.orden', 'lados') }}" class="btn btn-link">
                    L
                </a>
            </th>
            {{-- <th scope="col" title="Franquicia">
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
            <th scope="col">Acciones</th> --}}
        </tr>
        </thead>
        <tbody>

        <?php $var = 0; // Variable para manejar el estilo de cada fila. ?>

        @foreach ($propiedades as $propiedad)
        <?php $var++;   // Variable para manejar el estilo de cada fila. ?>
        <tr class="
        @if ('I' == $propiedad->estatus)
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
            <td>
                <a href="{{ route('propiedades.edit', [$propiedad->id, 'id']) }}"
                    class="btn btn-link float-right">
                    {{ $propiedad->codigo }}
                </a>
            </td>

        <?php $propiedad->mMoZero = false;  // Si el monto es 0, mostrar 'espacio vacio'. ?>
        <?php $propiedad->espMonB = false;  // Eliminar espacio entre simbolo de la moneda y el monto. ?>

            {{-- <td>
                <span title="Fecha de reserva">{{ $propiedad->reserva_en }}</span><br>
                <span title="Fecha de la firma">{{ $propiedad->firma_en }}</span>
            </td>

            <td>
                {{ $propiedad->negociacion }}</td>--}}
            <td>{{ $propiedad->nombre }}({{ $propiedad->negociacion }})
            </td>

            <td>

                <span class="float-right">
                    {{ $propiedad->precio_ven }}
                </span><br>
                <span>
                    Com:{{ $propiedad->comision_p }}
                </span><br>
                <span class="float-right">
                    IVA:{{ $propiedad->iva_p }}
                </span><br>
                <span class="float-right">
                    {{ $propiedad->precio_venta_real_ven }}
                </span>
            </td>

            <td>
                <a href="{{ route('propiedades.show', $propiedad) }}" class="btn btn-link float-right">
                    {{ $propiedad->lados }}
                </a>
            </td>

            {{-- <td title="Franquicia">
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

            <td title="Comisi&oacute;n del asesor captador
Comisi&oacute;n del gerente y
Comisi&oacute;n del asesor cerrador."
            >
                <span class="float-right" title="Captador PRBR(X){{ $propiedad->nombre_captador }}">
                    {{ $propiedad->captador_prbr_ven }}
                </span><br>
                <span class="float-right" title="Gerente(Y)">
                    {{ $propiedad->gerente_ven }}
                </span><br>
                <span class="float-right" title="Cerrador PRBR(Z){{ $propiedad->nombre_cerrador }}">
                    {{ $propiedad->cerrador_prbr_ven }}
                </span><br>
                <span class="float-right" title="Bonificaciones">
                    {{ $propiedad->bonificaciones_ven }}
                </span>
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
            </td>--}}
        </tr>

        <?php $propiedad->espMonB = true; // Restablecer variable cambiada antes de <precio> ?>

        @endforeach
        </tbody>
    </table>
    @if ($paginar)
    {{ $propiedades->links() }}
    @endif

    <div class="form-group col-md-12">
        TOTS: {{ $filas }} props
        <span class="alert-success">
            {{ Prop::numeroVen($tPrecio, 0) }}</span>{{-- 'Prop' es un alias definido en config/app.php --}}<br>
        <span class="alert-info">Compartido con IVA:</span>
        <span class="alert-success">
            {{ Prop::numeroVen($tCompartidoConIva, 2) }}</span><br>
        <span class="alert-info">Lados:</span>
        <span class="alert-success">
            {{ $tLados }}</span><br>
        <span class="alert-info">Captador:</span>
        <span class="alert-success">
            {{ Prop::numeroVen($tCaptadorPrbr, 2) }}
            @if (0 < $tCaptadorPrbrSel)
            ({{ Prop::numeroVen($tCaptadorPrbrSel, 2) }} [{{ $tLadosCap }}])
            @endif
        </span><br>
        <span class="alert-info">Gerente:</span>
        <span class="alert-success">
            {{ Prop::numeroVen($tGerente, 2) }}</span><br>
        <span class="alert-info">Cerrador:</span>
        <span class="alert-success">
            {{ Prop::numeroVen($tCerradorPrbr, 2) }}
            @if (0 < $tCerradorPrbrSel)
            ({{ Prop::numeroVen($tCerradorPrbrSel, 2) }} [{{ $tLadosCer }}])
            @endif
        </span><br>
        @if (0 < $tBonificaciones)
        <span class="alert-info">Bons:</span>
        <span class="alert-success">
            {{ Prop::numeroVen($tBonificaciones, 2) }}</span><br>
        @endif
        @if (0 < $tComisionBancaria)
        <span class="alert-info">Coms:</span>
        <span class="alert-success">
            {{ Prop::numeroVen($tComisionBancaria, 2) }}</span><br>
        @endif
        <span class="alert-info">Neto:</span>
        <span class="alert-success">
            {{ Prop::numeroVen($tIngresoNetoOfici, 2) }}</span><br>
        <span class="alert-info">PVR:</span>
        <span class="alert-success">
            {{ Prop::numeroVen($tPrecioVentaReal, 2) }}
            @if ((0 < $tPvrCaptadorPrbrSel) || (0 < $tPvrCerradorPrbrSel))
            ({{ Prop::numeroVen($tPvrCaptadorPrbrSel+$tPvrCerradorPrbrSel, 2) }})
            @endif
            </span><br>
    </div>

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
