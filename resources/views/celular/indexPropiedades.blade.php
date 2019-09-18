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

        <select name="estatus" id="estatus">
            <option value="">Estatus</option>
        @foreach ($arrEstatus as $opcion => $muestra)
            <option value="{{$opcion}}"
        @if (old('estatus', $estatus) == $opcion)
            selected
        @endif
            >{{ substr($muestra, 0, 35) }}</option>
        @endforeach
        </select><br>

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

    <div class="row">
        <div class="col-sm-2">
            <h5 class="pb-1">{{ substr($title, 11) }}</h5>
        </div>
        <div class="col-sm-2 offset-sm-2">
            <a href="{{ route('propiedades.create') }}" class="btn btn-primary float-right">
                Crear</a>
        </div>
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
                <a href="{{ route('propiedades.show', $propiedad) }}"
                    class="btn btn-link float-right">
                    {{ $propiedad->codigo }}
                </a>
            </td>

        <?php $propiedad->mMoZero = false;  // Si el monto es 0, mostrar 'espacio vacio'. ?>
        <?php $propiedad->espMonB = false;  // Eliminar espacio entre simbolo de la moneda y el monto. ?>

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
                <a href="{{ route('propiedades.edit', [$propiedad->id, 'id']) }}"
                    class="btn btn-link float-right">
                    {{ $propiedad->lados }}
                </a>
            </td>

        </tr>

        <?php $propiedad->espMonB = true; // Restablecer variable cambiada antes de <precio> ?>

        @endforeach
        </tbody>
    </table>
    @if ($paginar)
    {{ $propiedades->links() }}
    @endif

    <div class="col-md-12">
        TOTS: {{ $filas }} props
        <span class="alert-success" title="Precio">
            {{ Prop::numeroVen($tPrecio, 0) }}</span>{{-- 'Prop' es un alias definido en config/app.php --}}<br>
        @if (Auth::user()->is_admin)
            <span class="alert-info">Compartido con IVA:</span>
            <span class="alert-success" title="Compartido con otra oficina con IVA">
                {{ Prop::numeroVen($tCompartidoConIva, 2) }}</span><br>
            <span class="alert-info">Lados:</span>
            <span class="alert-success" title="Sumatoria de los lados">
                {{ $tLados }}</span><br>
            <span class="alert-info">Franquicia pagar reportada:</span>
            <span class="alert-success" title="Franquicia a pagar reportada">
                {{ Prop::numeroVen($tFranquiciaPagarR, 2) }}</span><br>
            <span class="alert-info">Regalia:</span>
            <span class="alert-success" title="Regalia">
                {{ Prop::numeroVen($tRegalia, 2) }}</span><br>
            <span class="alert-info">Sanaf:</span>
            <span class="alert-success" title="Sanaf - 5%">
                {{ Prop::numeroVen($tSanaf5PorCiento, 2) }}</span><br>
            <span class="alert-info">Captador:</span>
            <span class="alert-success" title="Captador PRBR">
                {{ Prop::numeroVen($tCaptadorPrbr, 2) }}
                @if (0 < $tCaptadorPrbrSel)
                ({{ Prop::numeroVen($tCaptadorPrbrSel, 2) }} [{{ $tLadosCap }}])
                @endif
            </span><br>
            <span class="alert-info">Cerrador:</span>
            <span class="alert-success" title="Cerrador PRBR">
                {{ Prop::numeroVen($tCerradorPrbr, 2) }}
                @if (0 < $tCerradorPrbrSel)
                ({{ Prop::numeroVen($tCerradorPrbrSel, 2) }} [{{ $tLadosCer }}])
                @endif
            </span><br>
        @else
            <span class="alert-info">Lados:</span>
            <span class="alert-success" title="Sumatoria de los lados">
                {{ $tLadosCap + $tLadosCer }}</span><br>
            @if (0 < $tCaptadorPrbrSel)
                <span class="alert-info">Captador:</span>
                <span class="alert-success" title="Captador PRBR">
                    {{ Prop::numeroVen($tCaptadorPrbrSel, 2) }}
                </span><br>
            @endif
            @if (0 < $tCerradorPrbrSel)
            <span class="alert-info">Cerrador:</span>
            <span class="alert-success" title="Cerrador PRBR">
                {{ Prop::numeroVen($tCerradorPrbrSel, 2) }}
            </span><br>
            @endif
        @endif
        @if (0 < $tBonificaciones)
            <span class="alert-info">Bonificaciones:</span>
            <span class="alert-success" title="Bonificaciones">
                {{ Prop::numeroVen($tBonificaciones, 2) }}</span><br>
        @endif
        @if (Auth::user()->is_admin)
            <span class="alert-info">Gerente:</span>
            <span class="alert-success" title="Gerente">
                {{ Prop::numeroVen($tGerente, 2) }}</span><br>
            @if (0 < $tComisionBancaria)
                <span class="alert-info">Coms:</span>
                <span class="alert-success" title="Comision bancaria">
                    {{ Prop::numeroVen($tComisionBancaria, 2) }}</span><br>
            @endif
            <span class="alert-info">Neto:</span>
            <span class="alert-success" title="Ingreso neto de la oficina">
                {{ Prop::numeroVen($tIngresoNetoOfici, 2) }}</span><br>
            <span class="alert-info">Precio Venta Real:</span>
            <span class="alert-success" title="Precio de venta real">
                {{ Prop::numeroVen($tPrecioVentaReal, 2) }}
                @if ((0 < $tPvrCaptadorPrbrSel) || (0 < $tPvrCerradorPrbrSel))
                    ({{ Prop::numeroVen($tPvrCaptadorPrbrSel+$tPvrCerradorPrbrSel, 2) }})
                @endif
            </span><br>
            <span class="alert-info">Total Comision:</span>
            <span class="alert-success" title="Total de comisiones: Captado + Cerrado">
                {{ Prop::numeroVen($tCaptadorPrbr+$tCerradorPrbr, 2) }}
                @if ((0 < $tCaptadorPrbrSel) or (0 < $tCerradorPrbrSel))
                    ({{ Prop::numeroVen($tCaptadorPrbr+$tCerradorPrbrSel, 2) }} [{{ $tLadosCap+$tLadosCer }}])
                @endif
            </span>
        @else
            @if ((0 < $tPvrCaptadorPrbrSel) || (0 < $tPvrCerradorPrbrSel))
                <span class="alert-info">Precio Venta Real:</span>
                <span class="alert-success" title="Precio de venta real">
                    {{ Prop::numeroVen($tPvrCaptadorPrbrSel+$tPvrCerradorPrbrSel, 2) }}
                </span><br>
            @endif
            @if ((0 < $tCaptadorPrbrSel) or (0 < $tCerradorPrbrSel))
                <span class="alert-info">Total Comision:</span>
                <span class="alert-success" title="Total de comisiones: Captado + Cerrado">
                        {{ Prop::numeroVen($tCaptadorPrbr+$tCerradorPrbrSel, 2) }}
                </span>
            @endif
        @endif
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
