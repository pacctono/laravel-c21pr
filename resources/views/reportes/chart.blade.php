@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-end mb-1 col-sm-12">
  <div class="col-sm-9">
  <form method="POST" class="form-horizontal" action="{{ url('/reportes/chart/'.$tipo) }}"
        onSubmit="return alertaFechaRequerida()">
    {!! csrf_field() !!}

    <input type="hidden" name="periodo" value="intervalo">
    <label>Desde:</label>
    <input type="date" name="fecha_desde" id="fecha_desde" min="{{ now() }}" max="{{ now() }}"
                    value="{{ old('fecha_desde', substr($fecha_desde, 0, 10)) }}">
    {{-- $fecha_desde --}}
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
  </form>
  </div>

  <!-- h3 class="pb-1">{{ $title }}</h3 -->

      <!-- a href="{{ route('reportes.chart', 'bar') }}" class="btn btn-primary">Crear Gráfico</a -->
  <div class="col-sm-3">
      Mostrar gráfico de:
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
  </div>
</div>

<div>{!! $chart->container() !!}</div>

@if ($elemsRep->isNotEmpty())
<table class="table table-striped table-hover table-bordered">
  <thead class="thead-dark">
    <tr>
      @if ('Conexion' == $muestra)
      <th scope="col">Asesor</th>
      @else
      <th scope="col">{{ $muestra }}</th>
      @endif
      @if ('Conexion' == $muestra)
      <th scope="col">Conexiones</th>
      @else
      <th scope="col">Atendidos</th>
      @endif
      @if ('Conexion' == $muestra)
      <th scope="col">Asesor</th>
      @else
      <th scope="col">{{ $muestra }}</th>
      @endif
      @if ('Conexion' == $muestra)
      <th scope="col">Conexiones</th>
      @else
      <th scope="col">Atendidos</th>
      @endif
    </tr>
  </thead>
  <tbody>
  @foreach ($elemsRep as $elemento)
    {{-- $loop->index, comienza desde 0, $loop-iteration, desde 1 --}}
    @if (0 == ($loop->index % 2))
    <tr>
    @endif
      <td>
    @if ('Fecha' == $muestra)
      {{ $elemento->fecha }}
    @elseif ('Origen' == $muestra)
      {{ $elemento->descripcion }}
    @else
      {{ $elemento->name }}
    @endif
      </td>
      <td>{{ $elemento->atendidos }}</td>
    @if (0 != ($loop->index % 2))
    </tr>
    @endif
  @endForeach
  </tbody>
</table>
@else
<p>No hay registros.</p>
@endif

@endsection

@section('js')
{{-- http://www.chartjs.org/docs/latest/ libreria usada por el proyecto --}}
{{-- https://erik.cat/projects/charts este es el proyecto que estoy usando --}}
    <script src=//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js charset=utf-8></script>
    {!! $chart->script() !!}

@endsection