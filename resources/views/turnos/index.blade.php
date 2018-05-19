
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-end mb-3">
    <h1 class="pb-1">{{ $title }}</h1>

    @if (Auth::user()->is_admin)
    <p>
        <!-- a href="{{ route('turnos.crear', '0') }}" class="btn btn-primary">Preparar turno</a -->
        Preparar turno para:
        <select name="semana" id="semana"
          onchange="javascript:location.href = this.value;">
          <option value="">Semana</option>
          @foreach ($semanas as $semana)
              <option value="{{ route('turnos.crear', $loop->index) }}">
                  {{ $diaSemana[$semana->dayOfWeek] }}
                  {{ $semana->format('d/m/Y') }}
              </option>
          @endforeach
        </select>
    </p>
    @endif
</div>

@if ($turnos->isNotEmpty())
<table class="table table-striped table-hover table-bordered">
  <thead class="thead-dark">
    <tr>
      <th scope="col">
        <a href="{{ route('turnos.orden', 'turno') }}" class="btn btn-link">
          Fecha
        </a>
      </th>
      <th scope="col">Turno</th>
      @if (Auth::user()->is_admin)
      <th scope="col">
        <a href="{{ route('turnos.orden', 'user_id') }}" class="btn btn-link">
          Asesor
        </a>
      </th>
      @endif
      <th scope="col">Preparado por</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($turnos as $turno)
    <tr>
      <td>
        {{ $diaSemana[$turno->turno->dayOfWeek] }}
        {{ $turno->turno_fecha }}
      </td>
      <td>
        {{ $turno->turno_en }}
      </td>
      @if (Auth::user()->is_admin)
      <td>{{ $turno->user->name }}</td>
      @endif
      <td>{{ $turno->userCreo->name }}</td>
    </tr>
  @endForeach
  </tbody>
</table>
{{ $turnos->links() }}
@else
<p>No hay turnos registrados.</p>
@endif

@endsection
