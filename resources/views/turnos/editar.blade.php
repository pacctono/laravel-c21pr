@extends('layouts.app')

@section('jshead')
  <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet"><!-- fullcalendar -->
  <script src="{{ asset('js/moment.min.js') }}"></script><!-- moment -->
  <script src="{{ asset('js/fullcalendar.min.js') }}"></script><!-- fullcalendar -->
  <script type='text/javascript' src='js/locale/es.js'></script><!-- fullcalendar en español -->
@endsection

@section('content')
<div class="card col-8 my-0 py-0">
    <h6 class="card-header my-0 py-0">{{ $title }}</h6>
    <div class="card-body my-0 py-0">
    @include('include.errorData')

    <form method="POST" action="{{ url("/turnos/{$turno->id}") }}" id="forma-editar-turnos">
        {{ method_field('PUT') }}
        {!! csrf_field() !!}

        <table class="table table-striped table-bordered my-0 py-0" style="font-size:0.75rem">
            <thead class="thead-dark">
            <tr class="my-0 py-1">
                <th class="my-0 py-0" scope="col">Turno</th>
                @for ($d = 0; $d < 3; $d++)
                    <th class="my-0 py-0" scope="col">{{ $diaSemana[$d] }}</th>
                    @if ('Miercoles' == $diaSemana[$d])
                        @break;
                    @endif
                @endfor
            </tr>
            </thead>
            <tbody>
            <tr class="my-0 py-1">
                <th class="my-0 py-0" scope="col">Mañana</th>
                @for ($d = 0; $d < 3; $d++)
                    <td class="my-0 py-0">
                        <select required name="u{{ $d }}" id="u{{ $d }}">
                            <option value="">mañana {{ $diaSemana[$d] }}</option>
                            @foreach ($users as $user)
                            @if (old("u{$d}", $turnos[$d]->user_id) == $user->id)
                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @else
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                            @endforeach
                        </select>
                        <input type="hidden" name="f{{ $d }}" value="{{ $dia[$d] }} 08">
                    </td>
                @endfor
            </tr>
            <tr class="my-0 py-1">
                <th class="my-0 py-0" scope="col">Tarde</th>
                @for ($d = 0; $d < 3; $d++)
                @if ('Sabado' != $diaSemana[$d])
                    <td class="my-0 py-0">
                        <select required name="u{{ 3+$d }}" id="u{{ 3+$d }}">
                            <option value="">tarde {{ $diaSemana[$d] }}</option>
                            @foreach ($users as $user)
                            @if (old("u{3+$d}", $turnos[3+$d]->user_id) == $user->id)
                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @else
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                            @endforeach
                        </select>
                        <input type="hidden" name="f{{ 3+$d }}" value="{{ $dia[$d] }} 12">
                    </td>
                @endif
                @endfor
            </tr>
            </tbody>

            <thead class="thead-dark">
            <tr class="my-0 py-1">
                <th class="my-0 py-0" scope="col">Turno</th>
                @for ($d = 3; $d < 6; $d++)
                    <th class="my-0 py-0" scope="col">{{ $diaSemana[$d] }}</th>
                @endfor
            </tr>
            </thead>
            <tbody>
            <tr class="my-0 py-1">
                <th class="my-0 py-0" scope="col">Mañana</th>
                @for ($d = 3; $d < 6; $d++)
                    <td class="my-0 py-0">
                        <select required name="u{{ 3+$d }}" id="u{{ 3+$d }}">
                            <option value="">mañana {{ $diaSemana[$d] }}</option>
                            @foreach ($users as $user)
                            @if (old("u{3+$d}", $turnos[3+$d]->user_id) == $user->id)
                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @else
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                            @endforeach
                        </select>
                        <input type="hidden" name="f{{ 3+$d }}" value="{{ $dia[$d] }} 08">
                    </td>
                @endfor
            </tr>
            <tr class="my-0 py-1">
                <th class="my-0 py-0" scope="col">Tarde</th>
                @for ($d = 3; $d < 6; $d++)
                @if ('Sabado' != $diaSemana[$d])
                    <td class="my-0 py-0">
                        <select required name="u{{ 6+$d }}" id="u{{ 6+$d }}">
                            <option value="">tarde {{ $diaSemana[$d] }}</option>
                            @foreach ($users as $user)
                            @if (old("u{6+$d}", $turnos[6+$d]->user_id) == $user->id)
                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @else
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                            @endforeach
                        </select>
                        <input type="hidden" name="f{{ 6+$d }}" value="{{ $dia[$d] }} 12">
                    </td>
                @endif
                @endfor
            </tr>
            <tr class="my-0 py-1">
                <td class="my-0 py-0" colspan="2">
                    <button type="submit" class="btn btn-primary my-0 py-0" id="actualizar-turnos">
                        Actualizar Turno
                    </button>
                </td>
                <td class="my-0 py-0" colspan="2">Preparar turno para:
                    <select name="semana" id="semana"
                        onchange="document.getElementById('actualizar-turnos').click();">
                        <option value="">Semana</option>
                        @foreach ($semanas as $lSemana)
                            @if (($semana) != $loop->index)
                            <option value="{{ $loop->index }}">
                                {{ $diaSemana[$lSemana->dayOfWeek - 1] }}
                                {{ $lSemana->format('d/m/Y') }}
                            </option>
                            @endif
                        @endforeach
                    </select>
                </td>
            </tr>
            </tbody>
        </table> 
    </form>   
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-body" id="calendario">
      {!! $calendar->calendar() !!}
    </div>
</div>

@endsection

@section('js')
  {!! $calendar->script() !!}
@endsection