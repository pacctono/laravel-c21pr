@extends('layouts.app')

@section('content')

@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
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
  </form>
</div>
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

<div class="d-flex justify-content-between align-items-end my-0 py-0 col-lg-12">
  <div class="col-lg-9">
  @if (!isset($accion) or ('html' == $accion))
    <h4 class="m-0 p-0">{{ $title }}</h4>
  @else (!isset($accion) or ('html' == $accion))
    <h5 style="text-align:center;">{{ $title }}</h5>
  @endif (!isset($accion) or ('html' == $accion))
  </div>

  @if (!isset($accion) or ('html' == $accion))
  <div class="col-lg-3">
  @if ('Cumpleanos' != $muestra)
  <p class="text-right m-0 p-0">
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
  @else ('Cumpleanos' != $muestra)
  &nbsp;
  @endif ('Cumpleanos' != $muestra)
  </div>
  @endif (!isset($accion) or ('html' == $accion))
</div>

@if ($elemsRep->isNotEmpty())

<table
@if (!isset($accion) or ('html' == $accion))
  class="table table-striped table-hover table-bordered m-0 p-0"
@else (!isset($accion) or ('html' == $accion))
  class="center"
@endif (!isset($accion) or ('html' == $accion))
>
  <thead class="thead-dark">
    <tr
    @if ((isset($accion) and ('html' != $accion)))
        class="encabezado"
    @else ((isset($accion) and ('html' != $accion)))
        class="m-0 p-0"
    @endif ((isset($accion) and ('html' != $accion)))
    >
      <th class="m-0 py-0 px-1" scope="col">
      @if (('Conexion' == $muestra) ||
           ('Cumpleanos' == $muestra) ||
           ('Lados' == $muestra) ||
           ('NoConTurno' == $muestra) ||
           ('TardeTurno' == $muestra) ||
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
      <th class="m-0 py-0 px-1" scope="col">
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
      @elseif ('NoConTurno' == $muestra)
        Sin conectarse
      @elseif ('TardeTurno' == $muestra)
        Tarde
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
    @if (isset($elemento->activo) and (!$elemento->activo))
        table-danger
    @elseif (0 == ($loop->iteration % 2))
        table-primary
    @else
        table-info
    @endif (isset($elemento->activo) and (!$elemento->activo))
    m-0 p-0">
      <td class="m-0 py-0 px-1"
      @if (isset($elemento->activo) and (!$elemento->activo))
          title="Asesor no est&aacute; activo"
      @endif (isset($elemento->activo) and (!$elemento->activo))
      >
    @if ('Fecha' == $muestra)
      {{ $elemento->fechaContacto }}
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
      <td
    @if ((isset($accion) and ('html' != $accion)))
        style="text-align:right;"
    @else
        class="m-0 py-0 px-1"
    @endif ((isset($accion) and ('html' != $accion)))
      >
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

@include('include.botonesPdf', ['enlace' => 'reportes'])

@else ($elemsRep->isNotEmpty())
<p>No hay registros.</p>
@endif ($elemsRep->isNotEmpty())

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
