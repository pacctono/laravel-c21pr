
@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-end mb-1">
  <form method="POST" class="form-horizontal" action="{{ url('/reportes') }}"
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
    </div>
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
      @if (('Conexion' == $muestra) || ('Cumpleanos' == $muestra))
      Asesor
      @else
      {{ $muestra }}
      @endif
      </th>
      <th scope="col">
      @if ('Conexion' == $muestra)
      Conexiones
      @elseif ('Cumpleanos' == $muestra)
      Cumpleaños
      @else
      Atendidos
      @endif
      </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($elemsRep as $elemento)
    {{-- @continue (0 >= $elemento->atendidos) --}}
    <tr>
      <td>
    @if ('Fecha' == $muestra)
      {{ $elemento->fecha }}
    @elseif ('Origen' == $muestra)
      {{ $elemento->descripcion }}
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

  if ('' == fecha_desde) {
    alert("Usted tiene que suministrar la fecha 'Desde'");
    return false;
  }
  if ('' == fecha_desde) {
    alert("Usted tiene que suministrar la fecha 'Hasta'");
    return false;
  }
  return true;
//  submit();
}
</script>

@endsection