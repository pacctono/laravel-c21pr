
@extends('layouts.app')

@section('content')

<div><!--div class="d-flex justify-content-between align-items-end mb-1"-->
  <form method="POST" class="form-horizontal" action="{{ url('/reportes') }}"
        onSubmit="return alertaFechaRequerida()">
    {!! csrf_field() !!}

    <div class="form-row my-0 py-0 mx-1 px-1">
      <input type="hidden" name="periodo" value="intervalo">
  @include('include.fechas')
  @includeWhen(((Auth::user()->is_admin) and
               (('Fecha' == $muestra) or ('Negociaciones' == $muestra) or
                ('LadMes' == $muestra) or ('ComMes' == $muestra))),
               'include.asesor', ['berater' => 'asesor']) {{-- Obligatorio berater (asesor en aleman) --}}
  @include('include.botonMostrar')
    </div>
      {{--<label>Desde:</label>
      <input type="date" name="fecha_desde" id="fecha_desde" min="{{ now() }}" max="{{ now() }}"
                      value="{{ old('fecha_desde', substr($fecha_desde, 0, 10)) }}">
      <label>Hasta:</label>
      <input type="date" name="fecha_hasta" id="fecha_hasta" min="{{ now() }}" max="{{ now() }}"
                      value="{{ old('fecha_hasta', substr($fecha_hasta, 0, 10)) }}">
      @if ((Auth::user()->is_admin) and ('Fecha' == $muestra))
      <select name="asesor" id="asesor">
        <option value="0">Asesor</option>
        @foreach ($users as $user)
          <option value="{{ $user->id }}"
          @if (old("asesor", $asesor) == $user->id)
            selected
          @endif
          >
            {{ $user->name }}
          </option>
        @endforeach
      </select>
      @endif
      <button type="submit" class="btn btn-success">Mostrar</button>
    </div>--}}
  </form>
</div>

<div class="d-flex justify-content-between align-items-end mb-1 col-sm-12">
  <div class="col-sm-9"><h4 class="pb-1">{{ $title }}</h4></div>

  <div class="col-sm-3">
  @if ('Cumpleanos' != $muestra)
  <p class="text-right">
      <!-- a href="{{ route('reportes.chart', 'bar') }}" class="btn btn-primary">Crear Gráfico</a -->
      Crear gráfico de:
      <select name="grafico" id="grafico"
        onchange="javascript:location.href = this.value;">
        <option value="">tipo</option>
        @foreach (array('bar' => 'barra', 'pie' => 'torta', 'line' => 'línea')
                  as $graph => $grafico)
          <option value="{{ route('reportes.chart', $graph) }}">
            {{ $grafico }}
          </option>
        @endforeach
      </select>
  </p>
  @else
  &nbsp;
  @endif
  </div>
</div>

@if ($elemsRep->isNotEmpty())

<table class="table table-striped table-hover table-bordered">
  <thead class="thead-dark">
    <tr>
      <th scope="col">
      @if (('Conexion' == $muestra) ||
           ('Cumpleanos' == $muestra) ||
           ('Lados' == $muestra) ||
           ('Comision' == $muestra))
        Asesor
      @elseif (('Negociaciones' == $muestra) ||
           ('LadMes' == $muestra) ||
           ('ComMes' == $muestra) ||
           ('Cumpleanos' == $muestra))
        Mes
      @else
        {{ $muestra }}
      @endif
      </th>
      <th scope="col">
      @if ('Conexion' == $muestra)
        Conexiones
      @elseif ('Cumpleanos' == $muestra)
        Cumpleaños
      @elseif (('Lados' == $muestra) ||
               ('Comision' == $muestra) ||
               ('Negociaciones' == $muestra))
        {{ $muestra }}
      @elseif ('LadMes' == $muestra)
        Lados
      @elseif ('ComMes' == $muestra)
        Comision
      @else
        Atendidos
      @endif
      </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($elemsRep as $elemento)
    {{-- @continue (0 >= $elemento->atendidos) --}}
    <tr class="
    @if (0 == ($loop->iteration % 2))
        table-primary
    @else
        table-info
    @endif
    ">
      <td>
    @if ('Fecha' == $muestra)
      {{ $elemento->fecha }}
    @elseif ('Origen' == $muestra)
      {{ $elemento->descripcion }}
    @elseif (('Negociaciones' == $muestra) ||
             ('ComMes' == $muestra) ||
             ('LadMes' == $muestra))
      @if (('' == $elemento->agno) || ('' == $elemento->mes))
        Fecha no suministrada
      @else
        {{ $elemento->agno . '-' . $elemento->mes }}
      @endif
    @else
      {{ $elemento->name }}
    @endif
      </td>
      <td>
    @if ('Cumpleanos' == $muestra)
      {{ $elemento->fecha_cumpleanos->format('d-m') }}
      @if ($hoy == $elemento->fecha_cumpleanos->format('d-m'))
      <a href="{{ route('agenda.cumpleano', $elemento) }}" class="btn btn-link"
              title="Enviar correo a '{{ $elemento->name }}', porque esta de cumpleaños!">
          <span class="oi oi-envelope-closed"></span>
      </a>
      @endif
    @elseif ('Lados' == $muestra)
      {{ $elemento->captadas + $elemento->cerradas }}
    @elseif ('Comision' == $muestra)
      {{ $elemento->comision }}
    @elseif ('Negociaciones' == $muestra)
      {{ $elemento->negociaciones }}
    @elseif ('LadMes' == $muestra)
      {{ $elemento->lados }}
    @elseif ('ComMes' == $muestra)
      {{ $elemento->comision }}
    @else
      {{ $elemento->atendidos }}
    @endif
      </td>
    </tr>
  @endForeach
  </tbody>
</table>
@else
<div class="d-flex justify-content-between align-items-end mb-1 col-sm-12">
  <p>No hay registros.</p>
</div>
@endif

<script>
function alertaFechaRequerida() {
  var fecha_desde = document.getElementById('fecha_desde').value;
  var fecha_hasta = document.getElementById('fecha_hasta').value;
  var asesor = document.getElementById('asesor').value;

  if (('' == fecha_desde) && ('0' == asesor)) {
    alert("Usted tiene que suministrar, como minimo, la fecha 'Desde' y/o el 'asesor'");
    return false;
  }
  return true;
}
</script>

@endsection
