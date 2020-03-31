@extends('layouts.app')

@section('content')
    <div class="card">
        <h4 class="card-header">{{ $title }}</h4>
        <div class="card-body">
        @include('include.exitoCrear')
        @include('include.errorData')

        <form method="POST" class="form-horizontal" action="{{ url($url) }}">
            {!! csrf_field() !!}

        @if ('Feriado' == $singular)
        <div class="form-row my-0 py-0 bg-suave">
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label pr-2" for="fecha">*Fecha</label>
                <input type="date" class="form-control form-control-sm" name="fecha"
                    id="fecha" value="{{ old('fecha', now('America/Caracas')) }}">
            </div>
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label pr-2" for="tipo">*Tipo</label>
                <!--input type="text" class="form-control form-control-sm" name="tipo"
                    id="tipo" value="{{ old('tipo') }}"-->
                <select class="form-control form-control-sm" name="tipo" id="tipo">
                @foreach ($tipos as $opcion => $muestra)
                    <option value="{{$opcion}}"
                    @if (old('tipo') == $opcion)
                        selected
                    @endif (old('tipo') == $opcion)
                        >{{$muestra}}</option>
                @endforeach
                </select>
            </div>
        </div>
        @endif ('Feriado' == $singular)

        <div class="form-row my-0 py-0">
        @if ('Price' == $singular)
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label pr-2" for="menor">*Valor menor</label>
                <input type="text" class="form-control form-control-md" size="60"
                        maxlength="90" name="menor" id="menor" required
                        value="{{ old('menor') }}">
            </div>
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label pr-2" for="mayor">*Valor mayor</label>
                <input type="text" class="form-control form-control-md" size="60"
                        maxlength="90" name="mayor" id="mayor" required
                        value="{{ old('mayor') }}">
            </div>
        @else ('Price' == $singular)
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label px-2" for="descripcion">*Descripcion</label>
                <input type="text" class="form-control form-control-md" size="60" maxlength="90"
                        name="descripcion" id="descripcion" value="{{ old('descripcion') }}"
                        placeholder="descripcion de {{ $elemento }}">
            </div>
        @endif ('Price' == $singular)
        </div>

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-2 px-2">
                <button type="submit" class="btn btn-primary">
                    Crear {{ ucfirst($elemento) }}
                </button>
                <a href="{{ url($url) }}" class="btn btn-link">
                    Regresar al listado de {{ strtolower($tipo) }}
                </a>
            </div>
        </div>
    </div>

@endsection