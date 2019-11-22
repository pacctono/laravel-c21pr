@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">{{ $title }}</h4>
    <div class="card-body">
    @include('include.errorData')

        <form method="POST" action="{{ url($rutActualizar) }}">
            {{ method_field('PUT') }}
            {!! csrf_field() !!}

        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label px-3" for="descripcion">*Descripcion</label>
                <input type="text" class="form-control form-control-md" size="60"
                        maxlength="90" name="descripcion" id="descripcion" required
                        value="{{ old('descripcion', $objModelo->descripcion) }}">
            </div>
        </div>
        @if ('Texto' == $singular)
        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label px-3" for="enlace">Direcci&oacute;n web</label>
                <input type="text" size="60" maxlength="90" required name="enlace"
                        id="enlace" value="{{ old('enlace', $objModelo->enlace) }}">
            </div>
        </div>
        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label px-3" for="textoEnlace">Texto a mostrar del enlace</label>
                <input type="text" size="60" maxlength="90" required name="textoEnlace"
                        id="textoEnlace" value="{{ old('textoEnlace', $objModelo->textoEnlace) }}">
            </div>
        </div>
        @endif ('Texto' == $singular)
        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-2 px-2">
                <button type="submit" class="btn btn-primary">
                    Actualizar {{ $singular }}
                </button>
                <a href="{{ route($ruta) }}" class="btn btn-link">
                    Regresar al listado de {{ $plural }}
                </a>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection