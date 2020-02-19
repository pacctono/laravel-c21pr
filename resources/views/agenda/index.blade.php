@extends('layouts.app')

@section('content')
@if (isset($accion) and ('html' != $accion))
<div>
    <h4 style="text-align:center;margin:0.25px 0px 0.25px 0px;padding:0px">
      {{ $title }}
    </h4>
</div>
@endif (isset($accion) and ('html' != $accion))

@if (isset($alertar))
@if (0 < $alertar)
  <script>alert('El correo con la(s) cita(s) fue enviado al asesor');</script>
@elseif (0 > $alertar)
    <script>alert("No fue enviado el correo al asesor. Probablemente, problemas con Internet! Revise su conexión");</script>
@endif (0 < $alertar)
@endif (isset($alertar))

@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
<div class="row no-gutters">
<div class="col-2 no-gutters" style="font-size:0.75rem">
  <div class="card mt-0 mb-1 mx-0 p-0">
    <h4 class="card-header m-0 p-0">Filtrar listado</h4>
    <div class="card-body m-0 p-0">
      <form method="POST" class="form-vertical m-0 p-0"
            action="{{ route('agenda.post') }}" onSubmit="return alertaFechaRequerida()">
        {!! csrf_field() !!}

        @includeWhen(!$movil, 'include.intervalo')

        @include('include.fechas')
        @includeWhen(Auth::user()->is_admin, 'include.asesor', ['berater' => 'asesor'])   {{-- Obligatorio pasar la variable 'berater' --}}
        @include('include.botonMostrar')
      </form>
    </div>
  </div>
  <div class="card mt-1 mb-0 p-0 mx-0">
    <h4 class="card-header m-0 p-0">Cita personal</h4>
    <div class="card-body m-0 p-0">
      <a href="{{ route('agendaPersonal.crear') }}" class="btn btn-primary my-0 py-0 mx-auto px-auto">
      @if ($movil)
        Crear
      @else
        Crear Cita Personal
      @endif
      </a>
    </div>
  </div>
</div>
<div class="col-10 no-gutters">
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

{{--@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
    {{ $agendas->links() }}
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))--}}
@if ($agendas->isNotEmpty())
<table
@if (!isset($accion) or ('html' == $accion))
  class="table table-striped table-hover table-bordered my-0 py-0"
@else (!isset($accion) or ('html' == $accion))
  class="center"
@endif (!isset($accion) or ('html' == $accion))
>
  <thead class="thead-dark">
    <tr
    @if (isset($accion) and ('html' != $accion))
      class="encabezado"
    @else (isset($accion) and ('html' != $accion))
      class="my-0 py-0 mx-0 px-0"
    @endif (isset($accion) and ('html' != $accion))
    >
      <th class="my-0 py-0" scope="col">
        <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
            @else "enlaceDesabilitado" name=
            @endif "{{ route('agenda.orden', 'fecha_evento') }}">
          Fecha
        </a>
      </th>
      <th class="my-0 py-0" scope="col">Hora</th>
      <th class="my-0 py-0" scope="col">Cita</th>
      <th class="my-0 py-0" scope="col">
      @if ($movil)
        Contacto
      @else
        <a
          @if (Auth::user()->is_admin)
            title="Al ordenar por nombre de contacto, no mostrará los turnos"
          @endif
            class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
            @else "enlaceDesabilitado" name=
            @endif "{{ route('agenda.orden', 'name') }}">
          Nombre de la cita
        </a>
      @endif
      </th>
    @if (!$movil)
      <th class="my-0 py-0" scope="col">Telefono</th>
      <th class="my-0 py-0" scope="col">
      @if (Auth::user()->is_admin)
      <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
         @else "enlaceDesabilitado" name=
         @endif "{{ route('agenda.orden', 'user_id') }}">
        Nombre Asesor
        </a>
      @else
        Correo electrónico
      @endif
      </th>
    @if (!isset($accion) or ('html' == $accion))
      <th class="my-0 py-0" scope="col">Acciones</th>
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
     m-0 p-0">
      <td class="m-0 p-0">
      @if (($movil) && (NULL != $agenda->contacto_id))
        <a href="{{ route('agenda.show', $agenda->contacto) }}" class="btn btn-link m-0 p-0">
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
      <td class="m-0 p-0">
        {{ $agenda->hora_evento }}
      </td>
      <td class="m-0 p-0">
        {{ (false === strpos($agenda->descripcion, 'Turno'))?$agenda->descripcion:'Turno' }}
      </td>
      <td class="m-0 p-0" title="DIRECCIÓN: {{ $agenda->direccion??'No fue suministrada' }}">
        {{ $agenda->name }}
      </td>
    @if (!$movil)
    @if (Auth::user()->is_admin)
      <td class="m-0 p-0" title="CORREO: {{ $agenda->email??'No fue suministrado' }}">
    @else (Auth::user()->is_admin)
      <td class="m-0 p-0">
    @endif (Auth::user()->is_admin)
      @if ('' != $agenda->telefono)
        {{ $agenda->telefono_f }}
      @elseif (Auth::user()->is_admin)
        {{ $agenda->user->telefono_f }}
      @endif (Auth::user()->is_admin)
      </td>
      <td class="m-0 p-0">
      @if (Auth::user()->is_admin)
        {{ $users->find($agenda->user_id)->name }}  {{-- Si el indice es 1, retorna 'Administrador'; de la manera "correcta", retorna 'Feriado'. --}}
      @else (Auth::user()->is_admin)
        {{ $agenda->email }}
      @endif (Auth::user()->is_admin)
      </td>
    @if (!isset($accion) or ('html' == $accion))
      <td class="d-flex align-items-end m-0 p-0"> {{-- Acciones: mostrar, editar, email, borrar, etc. --}}
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
            " class="btn btn-link m-0 p-0" title="Motrar datos de esta cita.">
          <span class="oi oi-eye my-0 py-0 ml-0 mr-1 px-0"></span>
        </a>
      @if ((Auth::user()->is_admin) || ($agenda->fecha_evento > $hoy))
        <a href="
      @if ('C' == $agenda->tipo)  {{-- Esta cita es con un contacto incial --}}
          {{ route('agenda.crear', $agenda->contacto) }}
      @elseif ('A' == $agenda->tipo)  {{-- Esta cita es personal, contacto_id => id --}}
          {{ route('agendaPersonal.edit', $agenda->contacto_id) }}  {{-- contacto_id es id de la cita personal --}}
      @else
        #
      @endif ('C' == $agenda->tipo)  {{-- Esta cita es con un contacto incial --}}
            " class="btn btn-link m-0 p-0" title="Editar datos de esta cita.">
          <span class="oi oi-pencil my-0 py-0 ml-0 mr-1 px-0"></span>
        </a>
      @endif ((Auth::user()->is_admin) || ($agenda->fecha_evento > $hoy))
      {{--@if (Auth::user()->is_admin)--}}
      @if ($agenda->fecha_evento > $hoy)
        <a href="
      @if ('C' == $agenda->tipo)  {{-- Esta cita es con un contacto incial --}}
          {{ route('agenda.correoCita', $agenda->contacto) }}
      @elseif ('A' == $agenda->tipo)  {{-- Esta cita es personal, contacto_id => id --}}
          {{ route('agendaPersonal.correoCita', $agenda->contacto_id) }}
      @else
        #
      @endif ('C' == $agenda->tipo)  {{-- Esta cita es con un contacto incial --}}
            " class="btn btn-link m-0 p-0"
            title="Enviar{{ (Auth::user()->is_admin)?'':'me' }} correo a '{{ (Auth::user()->is_admin)?$users->find($agenda->user_id)->name:'mi' }}' sobre esta cita">
          <span class="oi oi-envelope-closed my-0 py-0 ml-0 mr-1 px-0"></span>
        </a>
      @endif ($agenda->fecha_evento > $hoy)
      {{--@endif (Auth::user()->is_admin)--}}
      @endif {{-- else (('T' == $agenda->tipo) or (NULL == $agenda->contacto_id))--}}
      </td>
    @endif (!isset($accion) or ('html' == $accion))
    @endif (!$movil)
    </tr>
  @endForeach
  </tbody>
@if (!isset($accion) or ('html' == $accion))
  @if (Auth::user()->is_admin)
  <tfoot>
    <tr class="my-0 py-0">
      <td class="my-0 py-0" colspan="7">
        <a href="{{ route('agenda.correoTodasCitas') }}" class="btn btn-link m-0 p-0">
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
    @includeif('include.noRegistros', ['elemento' => 'citas'])
@endif ($agendas->isNotEmpty())

@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
</div><!--div class="col-9"-->
</div><!--div class="row"-->
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

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
