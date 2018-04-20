
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-end mb-3">
    <h1 class="pb-1">{{ $title }}</h1>

    <p>
        <!-- a href="{{ route('reportes.chart', 'bar') }}" class="btn btn-primary">Crear Gráfico</a -->
        Crear gráfico de:
        <select name="grafico" id="grafico"
          onchange="javascript:location.href = this.value;">
          <option value="">tipo</option>
          @foreach (array('line' => 'línea', 'bar' => 'barra', 'pie' => 'torta')
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
      <th scope="col">Asesor</th>
      <th scope="col">Atendidos</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($contactos as $contacto)
    <tr>
      <td>{{ $contacto->user->name }}</td>
      <td>{{ $contacto->atendidos }}</td>
    </tr>
  @endForeach
  </tbody>
</table>
@else
<p>No hay contactos registrados.</p>
@endif

@endsection