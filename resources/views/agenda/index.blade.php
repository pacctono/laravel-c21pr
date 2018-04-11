
@extends('layouts.app')

@section('content')
  <!-- div class="card-body">
    <h1>{{ $title }}</h1>
  </div -->
<div>
    <form method="POST" class="form-horizontal" action="{{ url('/agenda') }}"
          onSubmit="return alertaFechaRequerida()">
      {!! csrf_field() !!}

      <div class="form-group col-md-12">
      @foreach (['hoy', 'ayer', 'manana', 'esta_semana', 'semana_pasada', 'proxima_semana',
                'este_mes', 'mes_pasado', 'proximo_mes', 'todo', 'intervalo'] as $intervalo)
        <input type="radio" required name="periodo" id="_{{ $intervalo }}" value="{{ $intervalo }}"
        @if ($rPeriodo == $intervalo)
          checked
        @endif
        >
        <label>
        @if ('manana' == $intervalo)
        Mañana
        @else
        {{ str_replace('_', ' ', ucfirst($intervalo)) }}
        @endif
        </label>
      @endforeach
        <br>
        <label>Desde:</label>
        <input type="date" name="fecha_desde" id="fecha_desde" min="{{ now() }}" max="{{ now() }}"
                        value="{{ old('fecha_desde', $fecha_desde) }}">
        {{-- $fecha_desde --}}
        <label>Hasta:</label>
        <input type="date" name="fecha_hasta" id="fecha_hasta" min="{{ now() }}" max="{{ now() }}"
                        value="{{ old('fecha_hasta', $fecha_hasta) }}">
        @if (Auth::user()->is_admin)
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
        <a title="Al ordenar por nombre de contacto, no mostrará los turnos" 
            href="{{ route('agenda.orden', 'name') }}" class="btn btn-link">
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

@section('js')

<script>
function alertaFechaRequerida() {
  var periodo    = document.getElementsByName('periodo');
  var fecha_desde = document.getElementById('fecha_desde').value;
  var fecha_hasta = document.getElementById('fecha_hasta').value;
  var valorPeriodo;

  for (var i=0, len=periodo.length; i<len; i++) {
    if (periodo[i].checked) {
      valorPeriodo = periodo[i].value;
      break;
    }
  }
  if ('interv' != valorPeriodo) {
    return true;
  }
  if ('' == fecha_desde) {
    alert("Usted ha seleccionado 'Intervalo' y tiene que suministrar la fecha 'Desde'");
    return false;
  }
  if ('' == fecha_desde) {
    alert("Usted ha seleccionado 'Intervalo' y tiene que suministrar la fecha 'Hasta'");
    return false;
  }
  return true;
//  submit();
}
</script>

@endsection
