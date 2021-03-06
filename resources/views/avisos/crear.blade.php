@extends('layouts.app')

@section('content')
<div class="card m-0 p-0">
    <h4 class="card-header m-0 p-1">{{ $title }}</h4>
    <div class="card-body m-0 p-0">
    @include('include.exitoCrear')
    @include('include.errorData')

    <form method="POST" class="form align-items-end-horizontal"
        id="formulario" action="{{ route('avisos.store') }}">
        {!! csrf_field() !!}

        <div class="form-row my-1 mx-0 p-0">
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label>*</label>
                @includeIf('include.asesor', ['berater' => 'asesor', 'asesor' => 0])   {{-- Obligatorio pasar la variable 'berater'. El include requiere la variable $asesor. --}}
            </div>
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="tipo">*Tipo</label>
                <select class="form-control form-control-sm" name="tipo" id="tipo">
                @foreach ($tipos as $opcion => $muestra)
                  <option value="{{$opcion}}"
                  @if (old('tipo', $tipoXDef) == $opcion)
                    selected
                  @endif
                    >{{$muestra}}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="fecha">*Fecha</label>
                <input class="form-control form-control-sm" type="date" name="fecha" id="fecha"
                        required title="Fecha del aviso, amonestacion, notificacion, etc."
                        value="{{ old('fecha') }}">
                <label class="control-label" for="hora">*Hora</label>
                <input class="form-control form-control-sm" type="time" name="hora" id="hora"
                        title="Hora del aviso" required value="{{ old('hora') }}">
            </div>
        </div>

        <div class="form-row bg-suave my-1 mx-0 p-0">
            <div class="form-group form-inline my-0 mx-2 py-0 px-1">
                <label class="control-label" for="descripcion" id="etiqDescripcion">
                    *Descripci&oacute;n</label>
                <textarea class="form-control form-control-sm" rows="1"
                        cols="160" required name="descripcion" id="descripcion"
                        placeholder="Descripcion de la amonestacion o cualquiera que sea el aviso. Hasta 160 caracteres.">{{ old('descripcion') }}</textarea>
            </div>
        </div>

        <div class="form-row my-1 mx-1 p-0">
            <div class="form-group form-inline m-0 py-0 px-1">
                <button type="submit" class="btn btn-success m-0 py-0 px-1">
                    Agregar aviso
                </button>
                <a href="{{ route('avisos') }}" class="btn btn-link">
                    Regresar a la lista de avisos
                </a>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection

@section('js')

@includeIf("avisos.revisar", ['vista' => 'crear'])

@endsection