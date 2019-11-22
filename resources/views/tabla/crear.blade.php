@extends('layouts.app')

@section('content')
    <div class="card">
        <h4 class="card-header">{{ $title }}</h4>
        <div class="card-body">
        @include('include.exitoCrear')
        @include('include.errorData')

        <form method="POST" class="form-horizontal" action="{{ url($url) }}">
            {!! csrf_field() !!}

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label px-3" for="descripcion">*Descripcion</label>
                <input type="text" class="form-control form-control-md" size="60" maxlength="90"
                        name="descripcion" id="descripcion" value="{{ old('descripcion') }}"
                        placeholder="descripcion de {{ $elemento }}">
            </div>
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