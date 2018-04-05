@extends('layouts.app')

@section('content')
<div class="card col-8">
    <h4 class="card-header">{{ $title }}</h4>
    <div class="card-body">
    @if ($errors->any())
    <div class="alert alert-danger">
        <h5>Por favor corrige los errores debajo:</h5>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ url("/turnos/{$turno->id}") }}" id="forma-editar-turnos">
        {{ method_field('PUT') }}
        {!! csrf_field() !!}

        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Turno</th>
                @for ($d = 0; $d < 3; $d++)
                    <th scope="col">{{ $diaSemana[$d] }}</th>
                    @if ('Miercoles' == $diaSemana[$d])
                        @break;
                    @endif
                @endfor
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="col">Mañana</th>
                @for ($d = 0; $d < 3; $d++)
                    <td>
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
            <tr>
                <th scope="col">Tarde</th>
                @for ($d = 0; $d < 3; $d++)
                @if ('Sabado' != $diaSemana[$d])
                    <td>
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
            <tr>
                <th scope="col">Turno</th>
                @for ($d = 3; $d < 6; $d++)
                    <th scope="col">{{ $diaSemana[$d] }}</th>
                @endfor
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="col">Mañana</th>
                @for ($d = 3; $d < 6; $d++)
                    <td>
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
            <tr>
                <th scope="col">Tarde</th>
                @for ($d = 3; $d < 6; $d++)
                @if ('Sabado' != $diaSemana[$d])
                    <td>
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
            <tr>
                <td colspan="2">
                    <button type="submit" class="btn btn-primary" id="actualizar-turnos">
                        Actualizar Turno
                    </button>
                </td>
                <td colspan="2">Preparar turno para:
                    <select name="semana" id="semana"
                        onchange="document.getElementById('actualizar-turnos').click();">
                        <option value="">Semana</option>
                        @foreach ($semanas as $lSemana)
                            <option value="{{ $loop->iteration }}">
                                {{ $diaSemana[$lSemana->dayOfWeek - 1] }}
                                {{ $lSemana->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </td>
            </tr>
            </tbody>
        </table> 
    </form>   
    </div>
</div>

@endsection
