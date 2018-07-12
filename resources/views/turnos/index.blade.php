
@extends('layouts.app')

@section('content')
<div>
    <form method="POST" class="form-horizontal" action="{{ route('turnos.post') }}"
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

<div class="d-flex justify-content-between align-items-end mb-1">
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

@if ($alertar)
  <script>alert('El correo con los turnos fue enviado a cada asesor');</script>
@endif
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
  @if (Auth::user()->is_admin)
  <tfoot>
    <tr>
      <td colspan="4">
        <a href="{{ route('agenda.emailturnos') }}" class="btn btn-link">
          Enviar correo de los turnos a los asesores
        </a>
      </td>
    </tr>
  </tfoot>
  @endif
</table>
{{ $turnos->links() }}
@else
<p>No hay turnos registrados.</p>
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
