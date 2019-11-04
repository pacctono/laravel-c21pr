@extends('layouts.app')

@section('content')
    <div class="card col-10">
        <h4 class="card-header">{{ $title }}</h4>
        <div class="card-body">
        @include('include.exitoCrear')
        @include('include.errorData')

        <form method="POST" class="form-horizontal" action="{{ url($url) }}">
            {!! csrf_field() !!}

            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="descripcion">Descripcion:</label>
                <input type="text" class="form-control col-sm-5" size="60" maxlength="90"
                        name="descripcion" id="descripcion"
                        placeholder="descripcion del item" value="{{ old('descripcion') }}">
            </div>
            <div class="form-group d-flex">
                <button type="submit" class="btn btn-primary col-sm-4">
                    Crear {{ ucfirst($elemento) }}
                </button>
                <a href="{{ url($url) }}" class="btn btn-link col-sm-4">
                    Regresar al listado de {{ strtolower($tipo) }}
                </a>
            </div>
        </div>
    </div>

@endsection