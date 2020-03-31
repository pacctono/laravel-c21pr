@extends('layouts.app')

@section('content')

@if (!isset($accion) or ('html' == $accion))
<div class="d-flex justify-content-between align-items-end mb-1 col-sm-12">
@if (!$movil)
  <div class="col-sm-9">
  <form method="POST" class="form-horizontal" action="{{ url('/reportes/chart/'.$tipo) }}"
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
@endif (!$movil)

  <!-- h3 class="pb-1">{{ $title }}</h3 -->

      <!-- a href="{{ route('reportes.chart', 'bar') }}" class="btn btn-primary">Crear Gráfico</a -->
  <div class="col-sm-3">
      Mostrar gráfico de:
      <select name="grafico" id="grafico"
        onchange="javascript:location.href = this.value;">
        <option value="">tipo</option>
        @foreach (array('bar' => 'barra', 'pie' => 'torta', 'line' => 'línea')
                  as $graph => $grafico)
          <!--option value="{{ route('reportes.chart', $graph) }}"-->
          <option value="{{ route('reportes.chart', $graph) }}"
          @if ($graph == $tipo)
            selected
          @endif ($graph == $tipo)
          >
            {{ $grafico }}
          </option>
        @endforeach
      </select>
  </div>
</div>
@endif (!isset($accion) or ('html' == $accion))

<div>{!! $chart->container() !!}</div>

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
           ('Lados' == $muestra) ||
           ('NoConTurno' == $muestra) ||
           ('TardeTurno' == $muestra) ||
           ('Comision' == $muestra))
        Asesor
      @elseif (('Negociaciones' == $muestra) ||
           ('LadMes' == $muestra) ||
           ('ComMes' == $muestra))
        Mes
      @else
        {{ $muestra }}
      @endif
      </th>
      <th class="m-0 py-0 px-1" scope="col">
      @if ('Conexion' == $muestra)
        Conexiones
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
      <th class="m-0 py-0 px-1" scope="col">
      @if (('Conexion' == $muestra) ||
           ('Lados' == $muestra) ||
           ('NoConTurno' == $muestra) ||
           ('TardeTurno' == $muestra) ||
           ('Comision' == $muestra))
        Asesor
      @elseif (('Negociaciones' == $muestra) ||
           ('LadMes' == $muestra) ||
           ('ComMes' == $muestra))
        Mes
      @else
        {{ $muestra }}
      @endif
      </th>
      <th class="m-0 py-0 px-1" scope="col">
      @if ('Conexion' == $muestra)
        Conexiones
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
    {{-- $loop->index, comienza desde 0, $loop-iteration, desde 1 --}}
    @if (0 == ($loop->index % 2))
    <tr class="
    @if (0 == ($loop->index % 4))
        table-primary
    @else
        table-info
    @endif
    m-0 p-0">
    @endif
      <td class="m-0 py-0 px-1">
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
      <td class="m-0 py-0 px-1">
    @if ('Lados' == $muestra)
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
    @if (0 != ($loop->index % 2))
    </tr>
    @endif
  @endForeach
  </tbody>
</table>

@include('include.botonesPdf', ['enlace' => 'reportes.chart'])

@else ($elemsRep->isNotEmpty())
<p>No hay registros.</p>
@endif ($elemsRep->isNotEmpty())

@endsection

@section('js')
{{-- http://www.chartjs.org/docs/latest/ libreria usada por el proyecto --}}
{{-- https://erik.cat/projects/charts este es el proyecto que estoy usando --}}
    <!--script src=//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js charset=utf-8></script-->
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    {!! $chart->script() !!}

@endsection