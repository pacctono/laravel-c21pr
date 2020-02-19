@extends('layouts.app')

@section('jshead')
  <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet"><!-- fullcalendar -->
  <script src="{{ asset('js/moment.min.js') }}"></script><!-- moment -->
  <script src="{{ asset('js/fullcalendar.min.js') }}"></script><!-- fullcalendar -->
  <script type='text/javascript' src='js/locale/es.js'></script><!-- fullcalendar en español -->
@endsection

@section('content')

  <div class="panel panel-primary"><!-- mt-5: margin top de 3rem -->
    <div class="panel-heading d-flex justify-content-between align-items-end mb-1">
    @if ((!$movil) and (!isset($accion) or ('html' == $accion)))
      @if (Auth::user()->is_admin)
        <div>
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
      </div>
      <div>
        <a class="my-0 py-0" href="{{ route('turnos') }}">
          <h4 class="btn btn-link my-0 py-0">
            Mostrar listado
          </h4>
        </a>
      </div>
      @endif
    @endif ((!$movil) and (!isset($accion) or ('html' == $accion)))
    </div>
    <div class="panel-body" id="calendario">
      {!! $calendar->calendar() !!}
    </div>
  </div>

@endsection

@section('js')
  {!! $calendar->script() !!}
@endsection