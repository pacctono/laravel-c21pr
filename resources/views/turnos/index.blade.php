@extends('layouts.app')

@section('content')
@if (isset($accion) and ('html' != $accion))
<div>
    <h4 style="text-align:center;margin:0.25px 0px 0.25px 0px0px;padding:0px">
      {{ $title }}
    </h4>
</div>
@endif (isset($accion) and ('html' != $accion))

@if (isset($alertar))
@if (0 < $alertar)
  <script>alert('El correo con los turnos fue enviado a cada asesor');</script>
@elseif (0 > $alertar)
    <script>alert("No fue enviado el correo con los turnos. Probablemente, problemas con Internet! Revise su conexión");</script>
@endif (0 < $alertar)
@endif (isset($alertar))

@if ((!$movil) and (!isset($accion) or ('html' == $accion)) and (Auth::user()->is_admin))
<div class="row no-gutters">
<div class="col-3 no-gutters">
  <div class="card mt-0 mb-1 py-0 mx-0 py-0">
    <h3 class="card-header my-0 py-0 mx-0 py-0">Crear turno</h3>
    <div class="card-body my-0 py-0 mx-0 py-0">
        <select name="semana" id="semana"
          onchange="javascript:location.href = this.value;">
          <option value="">Semana</option>
          @foreach ($semanas as $semana)
              <option value="{{ route('turnos.crear', $loop->index) }}"
                @if ($semana[1])
                  style="color:red"
                @endif ($semana[1])
              >
                  {{ $diaSemana[$semana[0]->dayOfWeek] }}
                  {{ $semana[0]->format('d/m/Y') }}
              </option>
          @endforeach
        </select>
    </div>
  </div>
  <div class="card mt-1 mb-0 py-0 mx-0 py-0">
    <h3 class="card-header my-0 py-0 mx-0 py-0">Filtrar listado</h3>
    <div class="card-body my-0 py-0 mx-0 py-0">
      <form method="POST" class="form-vertical my-0 py-0 mx-0 px-0"
            action="{{ route('turnos.post') }}" onSubmit="return alertaFechaRequerida()">
        {!! csrf_field() !!}

        @includeWhen(!$movil, 'include.intervalo')

        @include('include.fechas')
        @includeWhen(Auth::user()->is_admin, 'include.asesor', ['berater' => 'asesor'])   {{-- Obligatorio pasar la variable 'berater' --}}
        @include('include.botonMostrar')
      </form>
    </div>
  </div>
</div>
<div class="col-9 no-gutters">
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

@if ($turnos->isNotEmpty())
{{--@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
    {{ $turnos->links() }}
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))--}}
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
      class="my-0 py-0"
    @endif (isset($accion) and ('html' != $accion))
    >
      <th class="my-0 py-0" scope="col">
        <a class=@if('html'==$accion) "btn btn-link my-0 py-0" href=
            @else "enlaceDesabilitado" name=
            @endif "{{ route('turnos.orden', 'turno') }}">
          Fecha
        </a>
      </th>
      <th class="my-0 py-0" scope="col">Turno</th>
    @if (Auth::user()->is_admin)
      <th class="my-0 py-0" scope="col">
        <a class=@if('html'==$accion) "btn btn-link my-0 py-0" href=
            @else "enlaceDesabilitado" name=
            @endif "{{ route('turnos.orden', 'user_id') }}">
          Asesor
        </a>
      </th>
    @endif
    @if (!$movil)
      <th class="my-0 py-0" scope="col">Preparado por</th>
    @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
      <th class="my-0 py-0" scope="col">Acci&oacute;n</th>
    @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
    @endif
    </tr>
  </thead>
  <tbody>
  @foreach ($turnos as $turno)
    <tr class="my-0 py-0
    @if ('C' == $turno->tarde)
        table-danger" data-toggle="tooltip" data-html="true" title="{{ $turno->descripcion }}
    @elseif (('m' == $turno->tarde) or ('t' == $turno->tarde))
        table-warning" data-toggle="tooltip" data-html="true" title="{{ $turno->descripcion }}
    @elseif (0 == ($loop->iteration % 2))
        table-primary
    @else
        table-info
    @endif
    ">
      <td class="my-0 py-0">
        {{ $diaSemana[$turno->turno->dayOfWeek] }}
        {{ $turno->turno_fecha }}
      </td>
      <td class="my-0 py-0">
        {{ $turno->fec_tur }}
      </td>
    @if (Auth::user()->is_admin)
      <td class="my-0 py-0">
      @if (!isset($accion) or ('html' == $accion))
        <select name="{{ $turno->id }}" disabled class="my-0 py-0 asesor"
      id="sa{{ $turno->id }}-{{ $turno->user_id }}">
        @foreach ($users as $user)
        @if ($turno->user->id == $user->id)
          <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
        @else ($turno->user->id == $user->id)
          <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endif ($turno->user->id == $user->id)
        @endforeach
        </select>
      @else (!isset($accion) or ('html' == $accion))
        {{ $turno->user->name }}
      @endif (!isset($accion) or ('html' == $accion))
      </td>
    @endif (Auth::user()->is_admin)
    @if (!$movil)
      <td class="my-0 py-0">{{ $turno->userCreo->name }}</td>
    @if ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
      <td class="my-0 py-0">
        <a href="#" class="btn btn-link my-0 py-0 editarTurno"
            id="{{ $turno->id }}-{{ $turno->user_id }}"
            title="Cambiar al asesor '{{ $turno->user->name }}' de este turno">
          <span class="oi oi-brush my-0 py-0"></span>
        </a>
      </td>
    @endif ((Auth::user()->is_admin) and (!isset($accion) or ('html' == $accion)))
    @endif (!$movil)
    </tr>
  @endForeach
  </tbody>
@if (!isset($accion) or ('html' == $accion))
  @if (Auth::user()->is_admin)
  <tfoot>
    <tr class="my-0 py-0">
      <td colspan="4" class="my-0 py-0">
        <a href="{{ route('turnos.correoTurnos') }}" class="btn btn-link my-0 py-0">
          Enviar correo de los turnos a los asesores
        </a>
      </td>
    </tr>
  </tfoot>
  @endif
@endif (!isset($accion) or ('html' == $accion))
</table>
@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
    {{ $turnos->links() }}
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

@include('include.botonesPdf', ['enlace' => 'turnos'])

@else ($turnos->isNotEmpty())
  @includeif('include.noRegistros', ['elemento' => 'turnos'])
@endif ($turnos->isNotEmpty())

@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
</div><!--div class="col-9"-->
</div><!--div class="row"-->
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

@endsection

@section('js')

@includeIf("turnos.jqmenu", ['vista' => 'index'])

@endsection
