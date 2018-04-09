
@extends('layouts.app')

@section('content')
  <!-- div class="card-body">
    <h1>{{ $title }}</h1>
  </div -->
<div>
    <form method="POST" class="form-horizontal" action="{{ url('/agenda') }}">
      {!! csrf_field() !!}

      <div class="form-group col-md-12">
        <label>Hoy</label>
        <input type="radio" name="periodo" id="periodo" value="hoy">
        <label>Ayer</label>
        <input type="radio" name="periodo" id="periodo" value="ayer">
        <label>Mañana</label>
        <input type="radio" name="periodo" id="periodo" value="manana">
        <label>Esta semana</label>
        <input type="radio" name="periodo" id="periodo" value="estsem">
        <label>Semana pasada</label>
        <input type="radio" name="periodo" id="periodo" value="sempas">
        <label>Próxima semana</label>
        <input type="radio" name="periodo" id="periodo" value="prosem">
        <label>Este mes</label>
        <input type="radio" name="periodo" id="periodo" value="estmes">
        <label>Mes pasado</label>
        <input type="radio" name="periodo" id="periodo" value="mespas">
        <label>Próximo mes</label>
        <input type="radio" name="periodo" id="periodo" value="promes">
        <br>
        <label>Desde:</label>
        <input type="date" name="fecha_desde" id="fecha_desde" min="{{ now() }}" max="{{ now() }}"
                        value="{{ old('fecha_desde') }}">
        <label>Hasta:</label>
        <input type="date" name="fecha_hasta" id="fecha_hasta" min="{{ now() }}" max="{{ now() }}"
                        value="{{ old('fecha_hasta') }}">
        <button type="submit" class="btn btn-success">Mostrar</button>
      </div>
    </form>
</div>

@if ($agendas->isNotEmpty())
<table class="table table-striped table-hover table-bordered">
  <thead class="thead-dark">
    <tr>
      <th scope="col">
        <a href="{{ route('agenda.orden', 'fecha_evento') }}" class="btn btn-link">
          Fecha
        </a>
      </th>
      <th scope="col">Hora</th>
      <th scope="col">Cita</th>
      <th scope="col">
        <a href="{{ route('agenda.orden', 'name') }}" class="btn btn-link">
          Nombre Contacto
        </a>
      </th>
      <th scope="col">Telefono</th>
      <th scope="col">
      @if (Auth::user()->is_admin)
      <a href="{{ route('agenda.orden', 'user_id') }}" class="btn btn-link">
        Nombre Asesor
        </a>
      @else
        Correo electrónico
      @endif
      </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($agendas as $agenda)
    <tr>
      <td>
        {{ $diaSemana[$agenda->fecha_evento->dayOfWeek] }}
        {{ $agenda->fecha_evento->format('d/m/Y') }}
      </td>
      <td>
        {{ $agenda->hora_evento }}
      </td>
      <td>
        {{ $agenda->descripcion }}
      </td>
      <td title="{{ $agenda->direccion }}">
        {{ $agenda->name }}
      </td>
      @if (Auth::user()->is_admin)
      <td title="{{ $agenda->email }}">
      @else
      <td>
      @endif
        @if ('' != $agenda->telefono)
        0{{ substr($agenda->telefono, 0, 3) }}-{{ substr($agenda->telefono, 3, 3) }}-{{ substr($agenda->telefono, 6) }}
        @endif
      </td>
      <td>
      @if (Auth::user()->is_admin)
        {{ $users->find($agenda->user_id)->name }}
      @else
        {{ $agenda->email }}
      @endif
      </td>
    </tr>
  @endForeach
  </tbody>
</table>
{{ $agendas->links() }}
@else
<p>No tiene agenda registrada.</p>
@endif

@endsection