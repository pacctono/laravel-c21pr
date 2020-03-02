@extends('layouts.app')

@section('jshead')
  <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet"><!-- fullcalendar -->
  <script src="{{ asset('js/moment.min.js') }}"></script><!-- moment -->
  <script src="{{ asset('js/fullcalendar.min.js') }}"></script><!-- fullcalendar -->
  <script src="{{ asset('js/locale/es.js') }}"></script><!-- fullcalendar en español -->
@endsection

@section('content')

@includeWhen(((!$movil and (!isset($accion) or ('html' == $accion))) and
                            (Auth::user()->is_admin)), 'turnos.vmenu')

    <div class="panel-body" id="calendario">
      {!! $calendar->calendar() !!}
      {!! $calendar->script() !!}
    </div>

@includeWhen(((!$movil and (!isset($accion) or ('html' == $accion))) and
                            (Auth::user()->is_admin)), 'turnos.vmenuCierre')

@endsection
