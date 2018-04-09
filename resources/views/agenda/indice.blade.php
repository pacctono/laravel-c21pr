
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-end mb-3">
    <h1 class="pb-1">{{ $title }}</h1>

</div>

@if ($turnos->isNotEmpty())
<table class="table table-striped table-hover table-bordered">
  <thead class="thead-dark">
    <tr>
      <th scope="col">
        <a href="{{ route('turnos.orden', 'turno_en') }}" class="btn btn-link">
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
        {{ $diaSemana[$turno->turno_en->dayOfWeek] }}
        {{ $turno->turno_en->format('d/m/Y') }}
      </td>
      <td>
        @if ('08' == $turno->turno_en->format('H'))
          Mañana
        @else
          Tarde
        @endif
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