@extends('layouts.app')

@section('content')
<div class="card col-md-10">
    <h4 class="card-header">{{ $title }}</h4>
    <div class="card-body">
    @include('include.errorData')

        <form method="POST" action="{{ url($rutActualizar) }}">
            {{ method_field('PUT') }}
            {!! csrf_field() !!}

            <div class="form-group d-flex align-items-end">
                <label for="descripcion">Descripci&oacute;n</label>
                <input type="text" size="60" maxlength="90" required name="descripcion"
                        id="descripcion" value="{{ old('descripcion', $objModelo->descripcion) }}">
            </div>
            <div class="form-group d-flex align-items-end">
                <label for="enlace">Direcci&oacute;n web</label>
                <input type="text" size="60" maxlength="90" required name="enlace"
                        id="enlace" value="{{ old('enlace', $objModelo->enlace) }}">
            </div>
            <div class="form-group d-flex align-items-end">
                <label for="textoEnlace">Texto a mostrar del enlace</label>
                <input type="text" size="60" maxlength="90" required name="textoEnlace"
                        id="textoEnlace" value="{{ old('textoEnlace', $objModelo->textoEnlace) }}">
            </div>
            <div class="form-group d-flex align-items-end">
                <button class="btn btn-primary">Actualizar {{ $singular }}</button>
                <a href="{{ route($ruta) }}" class="btn btn-link">
                    Regresar al listado de {{ $plural }}
            </div>
        </form>
    </div>
</div>
@endsection