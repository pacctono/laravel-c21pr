
@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-end mb-3">
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
      <button type="submit" class="btn btn-success">Mostrar</button>
    </div>
  </form>
</div>

<div class="d-flex justify-content-between align-items-end mb-3">
  <h3 class="pb-1">{{ $title }}</h3>

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
</div>

@if ($contactos->isNotEmpty())

<table class="table table-striped table-hover table-bordered">
  <thead class="thead-dark">
    <tr>
      <th scope="col">{{ $muestra }}</th>
      <th scope="col">Atendidos</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($contactos as $contacto)
    <tr>
    @if ('Asesor' == $muestra)
      <td>{{ $contacto->user->name }}</td>
    @else
      <td>{{ $contacto->fecha }}</td>
    @endif
      <td>{{ $contacto->atendidos }}</td>
    </tr>
  @endForeach
  </tbody>
</table>
@else
<p>No hay contactos registrados.</p>
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