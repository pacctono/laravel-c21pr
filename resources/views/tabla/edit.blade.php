@extends('layouts.app')

@section('content')
<div class="card col-md-10">
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

        <form method="POST" action="{{ url($rutActualizar) }}">
            {{ method_field('PUT') }}
            {!! csrf_field() !!}

            <div class="form-group d-flex align-items-end">
                <label for="descripcion">Descripcion:</label>
                <input type="text" maxlength="30" required name="descripcion" id="descripcion"
                        value="{{ old('descripcion', $objModelo->descripcion) }}">
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