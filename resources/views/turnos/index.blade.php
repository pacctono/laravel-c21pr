
@extends('layouts.app')

@section('content')
@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
<div>
    <form method="POST" class="form-horizontal" action="{{ route('turnos.post') }}"
          onSubmit="return alertaFechaRequerida()">
      {!! csrf_field() !!}

    @includeWhen(!$movil, 'include.intervalo')

      <div class="form-row my-0 py-0 mx-1 px-1">
    @include('include.fechas')
    @includeWhen(Auth::user()->is_admin, 'include.asesor', ['berater' => 'asesor'])   {{-- Obligatorio pasar la variable 'berater' --}}
    @include('include.botonMostrar')
      </div>
    {{--@if (!$movil)
      <div class="row form-group">
        <div class="col-lg-12">
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
          @elseif ('intervalo' == $intervalo)
          Otro
          @else
          {{ str_replace('_', ' ', ucfirst($intervalo)) }}
          @endif
          </label>
        @endforeach
        </div>
      </div>
    @endif
      <div class="row form-group">
        <div class="col-lg-3">
          <label>Desde:</label>
          <input type="date" name="fecha_desde" id="fecha_desde" min="{{ now() }}" max="{{ now() }}"
                          value="{{ old('fecha_desde', $fecha_desde) }}">
        </div>
        <div class="col-lg-3">
          <label>Hasta:</label>
          <input type="date" name="fecha_hasta" id="fecha_hasta" min="{{ now() }}" max="{{ now() }}"
                          value="{{ old('fecha_hasta', $fecha_hasta) }}">
        </div>
      @if (Auth::user()->is_admin)
        <div class="col-lg-2">
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
        </div>
      @endif
        <div class="col-lg-2">
          <button type="submit" class="btn btn-success">Mostrar</button>
        </div>
      </div>--}}
    </form>
</div>
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

<div class="d-flex justify-content-between align-items-end my-0">
  @if (!isset($accion) or ('html' == $accion))
  @if ($movil)
    <h4 class="pb-0">{{ substr($title, 11) }}</h4>
  @else
    <h1 class="pb-0">{{ $title }}</h1>
  @endif
  @else ((!$movil) and (!isset($accion) or ('html' == $accion)))
    <h1 align="center">{{ $title }}</h1>
  @endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

  @if ((!$movil) and (!isset($accion) or ('html' == $accion)))
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
  @endif ((!$movil) and (!isset($accion) or ('html' == $accion)))
</div>

@if ($alertar)
  <script>alert('El correo con los turnos fue enviado a cada asesor');</script>
@endif
@if ($turnos->isNotEmpty())
<table
@if (!isset($accion) or ('html' == $accion))
  class="table table-striped table-hover table-bordered"
@else (!isset($accion) or ('html' == $accion))
  align="center"
@endif (!isset($accion) or ('html' == $accion))
>
  <thead class="thead-dark my-0 py-0">
    <tr
    @if (isset($accion) and ('html' != $accion))
      class="encabezado"
    @else (isset($accion) and ('html' != $accion))
      class="my-0 py-0"
    @endif (isset($accion) and ('html' != $accion))
    >
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
    @if (!$movil)
      <th scope="col">Preparado por</th>
    @endif
    </tr>
  </thead>
  <tbody>
  @foreach ($turnos as $turno)
    <tr class="
    @if (0 == ($loop->iteration % 2))
        table-primary
    @else
        table-info
    @endif
    ">
      <td>
        {{ $diaSemana[$turno->turno->dayOfWeek] }}
        {{ $turno->turno_fecha }}
      </td>
      <td>
        {{ $turno->fec_tur }}
      </td>
    @if (Auth::user()->is_admin)
      <td>{{ $turno->user->name }}</td>
    @endif
    @if (!$movil)
      <td>{{ $turno->userCreo->name }}</td>
    @endif
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
@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
    {{ $turnos->links() }}
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

@else ($turnos->isNotEmpty())
  <p>No hay turnos registrados.</p>
@endif ($turnos->isNotEmpty())

@if (!isset($accion) or ('html' == $accion))
    <a target="_blank" href="{{ route('turnos.orden', 'ver') }}">
        <button>Ver PDF</button>
    </a>
    <a target="_blank" href="{{ route('turnos.orden', 'descargar') }}">
        <button>Descargar PDF</button>
    </a>
@endif (!isset($accion) or ('html' == $accion))

@endsection

@section('js')

@if ($movil)
<script>
function alertaFechaRequerida() {
  var fecha_desde = document.getElementById('fecha_desde').value;
  var fecha_hasta = document.getElementById('fecha_hasta').value;
  var asesor      = document.getElementById('asesor').value;

  if ('0' != asesor) {
    return true;
  }
  if ('' == fecha_desde) {
    alert("Usted tiene que suministrar la fecha 'Desde'");
    return false;
  }
  if ('' == fecha_hasta) {
    alert("Usted tiene que suministrar la fecha 'Hasta'");
    return false;
  }
  return true;
}
</script>
@else
<script>
function alertaFechaRequerida() {
  var periodo    = document.getElementsByName('periodo');
  var fecha_desde = document.getElementById('fecha_desde').value;
  var fecha_hasta = document.getElementById('fecha_hasta').value;
  var asesor      = document.getElementById('asesor').value;
  var valorPeriodo;

  for (var i=0, len=periodo.length; i<len; i++) {
    if (periodo[i].checked) {
      valorPeriodo = periodo[i].value;
      break;
    }
  }
  if (('intervalo' != valorPeriodo) || ('0' != asesor)) {
    return true;
  }
  if ('' == fecha_desde) {
    alert("Usted ha seleccionado 'Intervalo' y tiene que suministrar la fecha 'Desde'");
    return false;
  }
  if ('' == fecha_hasta) {
    alert("Usted ha seleccionado 'Intervalo' y tiene que suministrar la fecha 'Hasta'");
    return false;
  }
  return true;
}
</script>
@endif
@endsection
