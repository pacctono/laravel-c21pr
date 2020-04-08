@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">{{ $title }}</h4>
    <div class="card-body">
    @include('include.errorData')

        <form method="POST" action="{{ url($rutActualizar) }}">
            {{ method_field('PUT') }}
            {!! csrf_field() !!}

        @if (isset($singular) and ('Feriado' == $singular))
        <div class="form-row my-0 py-0 bg-suave">
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label pr-2" for="fecha">*Fecha</label>
                <input type="date" class="form-control form-control-sm" name="fecha"
                    id="fecha" value="{{ old('fecha', $objModelo->fecha_bd) }}">
            </div>
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label pr-2" for="tipo">*Tipo</label>
                <!--input type="text" class="form-control form-control-sm" name="tipo"
                    id="tipo" value="{{ old('tipo', $objModelo->tipo) }}"-->
                <select class="form-control form-control-sm" name="tipo" id="tipo">
                @foreach ($tipos as $opcion => $muestra)
                    <option value="{{$opcion}}"
                    @if (old('tipo', $objModelo->tipo) == $opcion)
                        selected
                    @endif (old('tipo', $objModelo->tipo) == $opcion)
                        >{{$muestra}}</option>
                @endforeach
                </select>
            </div>
        </div>
        @endif (isset($singular) and ('Feriado' == $singular))

        <div class="form-row my-0 py-0">
        @if (isset($singular) and ('Price' == $singular))
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label pr-2" for="menor">*Valor menor</label>
                <input type="text" class="form-control form-control-md" size="60"
                        maxlength="90" name="menor" id="menor" required
                        value="{{ old('menor', $objModelo->menor) }}">
            </div>
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label pr-2" for="mayor">*Valor mayor</label>
                <input type="text" class="form-control form-control-md" size="60"
                        maxlength="90" name="mayor" id="mayor" required
                        value="{{ old('mayor', $objModelo->mayor) }}">
            </div>
        @else (isset($singular) and ('Price' == $singular))
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label pr-2" for="descripcion">*Descripcion</label>
                <input type="text" class="form-control form-control-md" size="60"
                        maxlength="90" name="descripcion" id="descripcion" required
                        value="{{ old('descripcion', $objModelo->descripcion) }}">
            </div>
        @endif (isset($singular) and ('Price' == $singular))
        </div>

        @if (isset($singular) and ('Texto' == $singular))
        <div class="form-row my-0 py-0">
            <div class="form-group form-inline mx-2 px-2">
                <label class="control-label px-2" for="enlace">Direcci&oacute;n web</label>
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
        @endif (isset($singular) and ('Texto' == $singular))

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