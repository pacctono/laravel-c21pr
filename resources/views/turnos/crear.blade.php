@extends('layouts.app')

@section('content')
<div class="card col-12">
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

    <table class="table table-striped table-hover table-bordered">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Turno</th>
            @foreach ($diaSemana as $dia)
                <th scope="col">{{ $dia }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="col">Mañana</th>
            @foreach ($diaSemana as $dia)
                <td>
                    <select name="{{ $dia }}Am" id="{{ $dia }}Am">
                      <option value="">mañana {{ $dia }}</option>
                        @foreach ($users as $user)
                        @if (old("{{ $dia }}Am") == $user->id)
                          <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                        @else
                          <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </td>
            @endforeach
        </tr>
        <tr>
            <th scope="col">Tarde</th>
            @foreach ($diaSemana as $dia)
              @if ('Sabado' != $dia)
                <td>
                    <select name="{{ $dia }}Pm" id="{{ $dia }}Pm">
                      <option value="">tarde {{ $dia }}</option>
                        @foreach ($users as $user)
                        @if (old("{{ $dia }}Pm") == $user->id)
                          <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                        @else
                          <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </td>
              @endif
            @endforeach
        </tr>
        </tbody>

        <thead class="thead-dark">
        <tr>
            <th scope="col">Turno</th>
            @foreach ($diaSemana as $dia)
                <th scope="col">{{ $dia }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="col">Mañana</th>
            @foreach ($diaSemana as $dia)
                <td>
                    <select name="{{ $dia }}Am" id="{{ $dia }}Am">
                      <option value="">mañana {{ $dia }}</option>
                        @foreach ($users as $user)
                        @if (old("{{ $dia }}Am") == $user->id)
                          <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                        @else
                          <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </td>
            @endforeach
        </tr>
        <tr>
            <th scope="col">Tarde</th>
            @foreach ($diaSemana as $dia)
              @if ('Sabado' != $dia)
                <td>
                    <select name="{{ $dia }}Pm" id="{{ $dia }}Pm">
                      <option value="">tarde {{ $dia }}</option>
                        @foreach ($users as $user)
                        @if (old("{{ $dia }}Pm") == $user->id)
                          <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                        @else
                          <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </td>
              @endif
            @endforeach
        </tr>
        </tbody>
    </table>    
    </div>
</div>

@endsection