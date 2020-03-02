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

@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
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
    <tr class="
    @if ('C' == $turno->tarde)
        table-warning" title="No se conecto en su turno
    @elseif (('m' == $turno->tarde) or ('t' == $turno->tarde))
        table-danger" title="Llego tarde a su turno
    @elseif (0 == ($loop->iteration % 2))
        table-primary
    @else
        table-info
    @endif
    my-0 py-0">
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
  if ('' == fecha_hasta) {
    alert("Usted tiene que suministrar la fecha 'Hasta'");
    return false;
  }
  return true;
}
@else
  $(document).ready(function(){
    $("a.editarTurno").click(function(ev) {
      ev.preventDefault();
      var id  = $(this).attr('id');        // Tambien $(ev.target).attr('id')
      var sel = document.getElementById('sa'+id);
      var nombre = sel.options[sel.selectedIndex].text;
      var accion;
      accion = confirm('Desea cambiar al asesor ' + nombre + ' del turno.');
      if (accion) {
        //alert('sa'+id);
        $('#sa'+id).prop('disabled', false);
      }
    })
    $("select.asesor").change(function(ev) {
      var idSel = $(this).attr('id');        // Tambien $(ev.target).attr('id')
      var sel = document.getElementById(idSel); // No es necesario. Pude usar $this.
      var idAseNvo = sel.value;
      var arrIds = idSel.split('-');
      var idAseAct = arrIds[1];
      var nombre = sel.options[sel.selectedIndex].text;
// Debe funcionar, tambien.      var nombre = $("#" + idSel + " option:selected").text();
// Debe funcionar, tambien.      var nombre = $("#" + idSel).find("option:selected").text();
// Debe funcionar, tambien.      var nombre = $(this).find("option:selected").text();
      var idTurno = sel.name;     // Tambien podria sel idSel.split('-')[0].substr(2)
      var accion;
      //alert(idAseNvo+'|'+idAseAct+'|'+nombre+'|'+idTurno);
      if (idAseNvo != idAseAct) {
        accion = confirm('Desea colocar al asesor ' + nombre + ' en ese turno.');
        if (accion) {
          //alert('Se procedera a cambiar al asesor de este turno. Ir a:' + location.href);
          nvoUrl = '/turnos/editar/' + idTurno + '/' + idAseNvo;
          location.href = nvoUrl;
        } else {
          sel.value = idAseAct;
          $('#'+idSel).prop('disabled', true);  // $('#'+idSel) === $(this)
        }
      }
    })
  })
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
@endif
</script>
@endsection
