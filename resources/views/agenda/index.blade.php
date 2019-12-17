@extends('layouts.app')

@section('content')
  <!-- div class="card-body">
    <h1>{{ $title }}</h1>
  </div -->
@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
<div>
    <form method="POST" class="form-horizontal" action="{{ route('agenda.post') }}"
          onSubmit="return alertaFechaRequerida()">
      {!! csrf_field() !!}

    @includeWhen(!$movil, 'include.intervalo')

      <div class="form-row my-0 py-0 mx-1 px-1">
    @include('include.fechas')
    @includeWhen(Auth::user()->is_admin, 'include.asesor', ['berater' => 'asesor']) {{-- Obligatorio berater (asesor en aleman) --}}
    @include('include.botonMostrar')
      </div>
    {{--@if (!$movil)
      <div class="form-row my-0 py-0 mx-1 px-1">
        @foreach (['hoy', 'ayer', 'manana', 'esta_semana', 'semana_pasada', 'proxima_semana',
                  'este_mes', 'mes_pasado', 'proximo_mes', 'todo', 'intervalo'] as $intervalo)
        <div class="form-check form-check-inline my-1 py-0 mx-1 px-0">
          <input class="form-check-input form-check-input-sm" type="radio" required name="periodo"
                  id="_{{ $intervalo }}" value="{{ $intervalo }}"
          @if ($rPeriodo == $intervalo)
            checked
          @endif
          >
          <label class="form-check-label form-check-label-sm" for="_{{ $intervalo }}">
          @if ('manana' == $intervalo)
          Mañana
          @elseif ('intervalo' == $intervalo)
          Otro
          @else
          {{ str_replace('_', ' ', ucfirst($intervalo)) }}
          @endif
          </label>
        </div>
        @endforeach
      </div>
    @endif
      <div class="form-row my-0 py-0 mx-1 px-1">
        <div class="form-group form-inline my-1 py-0 mx-0 px-0">
          <label class="control-label" for="fecha_desde">
            Desde:</label>
          <input class="form-control form-control-sm" type="date" name="fecha_desde"
                  id="fecha_desde" min="{{ now() }}" max="{{ now() }}"
                  value="{{ old('fecha_desde', $fecha_desde) }}">
        </div>
        <div class="form-group form-inline my-1 py-0 mx-0 px-0">
          <label class="control-label" for="fecha_hasta">
            Hasta</label>
          <input class="form-control form-control-sm" type="date" name="fecha_hasta"
                  id="fecha_hasta" min="{{ now() }}" max="{{ now() }}"
                  value="{{ old('fecha_hasta', $fecha_hasta) }}">
        </div>
      @if (Auth::user()->is_admin)
        <div class="form-group form-inline my-1 py-0 mx-0 px-0">
          <select class="form-control form-control-sm" name="asesor" id="asesor">
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
<div class="d-flex justify-content-between align-items-end mb-1">
  @if (!isset($accion) or ('html' == $accion))
    @if ($movil)
    <h4 class="pb-1">Agenda</h4>
    @else
    <h1 class="pb-1">Agenda</h1>
    @endif
  @else (!isset($accion) or ('html' == $accion))
    <h1 style="text-align:center;">Agenda</h1>
  @endif (!isset($accion) or ('html' == $accion))

  @if (!isset($accion) or ('html' == $accion))
    <p>
        <a href="{{ route('agendaPersonal.crear') }}" class="btn btn-primary">
        @if ($movil)
            Crear
        @else
            Crear Cita Personal
        @endif
        </a>
    </p>
  @endif (!isset($accion) or ('html' == $accion))
</div>

@if ($alertar)
    <script>alert('Fue enviado un correo a cada asesor con todas sus citas.');</script>
@endif
@if ($agendas->isNotEmpty())
<table
@if (!isset($accion) or ('html' == $accion))
  class="table table-striped table-hover table-bordered"
@else (!isset($accion) or ('html' == $accion))
  class="center"
@endif (!isset($accion) or ('html' == $accion))
>
  <thead class="thead-dark">
    <tr
    @if (isset($accion) and ('html' != $accion))
      class="encabezado"
    @else (isset($accion) and ('html' != $accion))
      class="my-0 py-0"
    @endif (isset($accion) and ('html' != $accion))
    >
      <th scope="col">
        <a class=@if('html'==$accion) "btn btn-link" href=
            @else "enlaceDesabilitado" name=
            @endif "{{ route('agenda.orden', 'fecha_evento') }}">
          Fecha
        </a>
      </th>
      <th scope="col">Hora</th>
      <th scope="col">Cita</th>
      <th scope="col">
      @if ($movil)
        Contacto
      @else
        <a
          @if (Auth::user()->is_admin)
            title="Al ordenar por nombre de contacto, no mostrará los turnos"
          @endif
            class=@if('html'==$accion) "btn btn-link" href=
            @else "enlaceDesabilitado" name=
            @endif "{{ route('agenda.orden', 'name') }}">
          Nombre de la cita
        </a>
      @endif
      </th>
    @if (!$movil)
      <th scope="col">Telefono</th>
      <th scope="col">
      @if (Auth::user()->is_admin)
      <a class=@if('html'==$accion) "btn btn-link" href=
         @else "enlaceDesabilitado" name=
         @endif "{{ route('agenda.orden', 'user_id') }}">
        Nombre Asesor
        </a>
      @else
        Correo electrónico
      @endif
      </th>
    @if (!isset($accion) or ('html' == $accion))
      <th scope="col">Acciones</th>
    @endif (!isset($accion) or ('html' == $accion))
    @endif (!$movil)
    </tr>
  </thead>
  <tbody>
  @foreach ($agendas as $agenda)
    <tr class="
    @if (0 == ($loop->iteration % 2))
        table-primary
    @else
        table-info
    @endif
    ">
      <td>
      @if (($movil) && (NULL != $agenda->contacto_id))
        <a href="{{ route('agenda.show', $agenda->contacto) }}" class="btn btn-link">
          {{ $agenda->evento_dia_semana }}
        </a>
      @else (($movil) && (NULL != $agenda->contacto_id))
        {{ $agenda->evento_dia_semana }}
      @endif (($movil) && (NULL != $agenda->contacto_id))
      @if ($movil)
        {{ substr($agenda->fec_eve, 0, 5) }}
      @else ($movil)
        {{ $agenda->fec_eve }}
      @endif ($movil)
      </td>
      <td>
        {{ $agenda->hora_evento }}
      </td>
      <td>
        {{ $agenda->descripcion }}
      </td>
      <td title="DIRECCIÓN: {{ $agenda->direccion??'No fue suministrada' }}">
        {{ $agenda->name }}
      </td>
    @if (!$movil)
    @if (Auth::user()->is_admin)
      <td title="CORREO: {{ $agenda->email??'No fue suministrado' }}">
    @else (Auth::user()->is_admin)
      <td>
    @endif (Auth::user()->is_admin)
      @if ('' != $agenda->telefono)
        {{ $agenda->telefono_f }}
      @elseif (Auth::user()->is_admin)
        {{ $agenda->user->telefono_f }}
      @endif (Auth::user()->is_admin)
      </td>
      <td>
      @if (Auth::user()->is_admin)
        {{ $users->find($agenda->user_id)->name }}
      @else (Auth::user()->is_admin)
        {{ $agenda->email }}
      @endif (Auth::user()->is_admin)
      </td>
    @if (!isset($accion) or ('html' == $accion))
      <td class="d-flex align-items-end">
      @if (('T' == $agenda->tipo) or (NULL == $agenda->contacto_id))  {{-- La cita es un turno --}}
        &nbsp;
      @else (('T' == $agenda->tipo) or (NULL == $agenda->contacto_id))  {{-- La cita no es un turno --}}
        <a href="
      @if ('C' == $agenda->tipo)  {{-- Esta cita es con un contacto incial --}}
        {{ route('agenda.show', $agenda->contacto) }}
      @elseif ('A' == $agenda->tipo)  {{-- Esta cita es personal, contacto_id => id --}}
        {{ route('agendaPersonal.show', $agenda->contacto_id) }}  {{-- contacto_id es id de la cita personal --}}
      @else
        #
      @endif ('C' == $agenda->tipo)  {{-- Esta cita es con un contacto incial --}}
            " class="btn btn-link" title="Motrar datos de esta cita.">
          <span class="oi oi-eye"></span>
        </a>
        <a href="
      @if ('C' == $agenda->tipo)  {{-- Esta cita es con un contacto incial --}}
          {{ route('agenda.crear', $agenda->contacto) }}
      @elseif ('A' == $agenda->tipo)  {{-- Esta cita es personal, contacto_id => id --}}
          {{ route('agendaPersonal.edit', $agenda->contacto_id) }}  {{-- contacto_id es id de la cita personal --}}
      @else
        #
      @endif ('C' == $agenda->tipo)  {{-- Esta cita es con un contacto incial --}}
            " class="btn btn-link" title="Editar datos de esta cita.">
          <span class="oi oi-pencil"></span>
        </a>
      @if (Auth::user()->is_admin)
      @if ($agenda->fecha_evento > $hoy)
        <a href="
      @if ('C' == $agenda->tipo)  {{-- Esta cita es con un contacto incial --}}
          {{ route('agenda.emailcita', $agenda->contacto) }}
      @elseif ('A' == $agenda->tipo)  {{-- Esta cita es personal, contacto_id => id --}}
          #{{-- route('agendaPersonal.emailcita', $agenda->contacto_id) --}}
      @else
        #
      @endif ('C' == $agenda->tipo)  {{-- Esta cita es con un contacto incial --}}
            " class="btn btn-link"
            title="Enviar correo a '{{ $users->find($agenda->user_id)->name }}' sobre esta cita">
          <span class="oi oi-envelope-closed"></span>
        </a>
      @endif ($agenda->fecha_evento > $hoy)
      @endif (Auth::user()->is_admin)
      @endif (('T' == $agenda->tipo) or (NULL == $agenda->contacto_id))
      </td>
    @endif (!isset($accion) or ('html' == $accion))
    @endif (!$movil)
    </tr>
  @endForeach
  </tbody>
@if (!isset($accion) or ('html' == $accion))
  @if (Auth::user()->is_admin)
  <tfoot>
    <tr>
      <td colspan="7">
        <a href="{{ route('agenda.emailtodascitas', 'todas') }}" class="btn btn-link">
          Enviar correo de las citas a los asesores
        </a>
      </td>
    </tr>
  </tfoot>
  @endif (Auth::user()->is_admin)
@endif (!isset($accion) or ('html' == $accion))
</table>
@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
    {{ $agendas->links() }}
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

@include('include.botonesPdf', ['enlace' => 'agenda'])

@else ($agendas->isNotEmpty())
    <p>No tiene agenda registrada.</p>
@endif ($agendas->isNotEmpty())

@endsection

@section('js')

<script>
@if ($movil)
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
  if ('' == fecha_desde) {
    alert("Usted tiene que suministrar la fecha 'Hasta'");
    return false;
  }
  return true;
}
@else
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
  if ('' == fecha_desde) {
    alert("Usted ha seleccionado 'Intervalo' y tiene que suministrar la fecha 'Hasta'");
    return false;
  }
  return true;
}
@endif
</script>

@endsection
