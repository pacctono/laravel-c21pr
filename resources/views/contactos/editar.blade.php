@extends('layouts.app')

@section('content')
<div class="card col-10">
    <div class="row card-header">
        <div class="col-8">
            <h4>{{ $title }}</h4>
        </div>
        <div class="col-2">
            <a href="{{ route('contactos.create') }}" class="btn btn-primary"
                title="Dejar esta p&aacute;gina e ir a crear un nuevo Contacto Inicial">
                Crear Contacto Inicial
            </a>
        </div>
    </div>
    <div class="card-body">
    @include('include.errorData')

        <form class="form align-items-end-horizontal" method="POST" 
                action="{{ url("/contactos/{$contacto->id}") }}">
            {{ method_field('PUT') }}
            {!! csrf_field() !!}
            <!-- input name="_method" type="hidden" value="PUT" -->

            <div class="form-row my-0 py-0">  {{-- margen(m) arriba y abajo(y) 0 y padding(p) arriba y abajo(y) 0(0) --}}
{{-- Otros valores para margen y padding: 't':tope, 'b':bottom, 'l':left, 'r':right y 'x':left y right --}}
                <div class="form-group col-lg-3 d-flex">
                    <label class="control-label" for="cedula">Cedula</label>
                    <input type="text" class="form-control" size="8" maxlength="8" minlength="7" 
                            name="cedula" id="cedula" placeholder="numero de cedula" 
                            value="{{ old('cedula', $contacto->cedula) }}">
                </div>
                <div class="form-group col-lg-9 d-flex">
                    <label class="control-label" for="name">Nombre</label>
                    <input type="text" class="form-control" maxlength="30" required name="name" 
                            id="name" placeholder="nombre del contacto incial"
                            value="{{ old('name', $contacto->name) }}">
                </div>
            </div>

            <div class="form-row bg-suave my-0 py-0">
                <div class="form-group col-lg-4 d-flex">
                    <label class="control-label" for="telefono">Teléfono</label>
                    <select class="form-control" name="ddn" id="ddn">
                        <option value="">ddn</option>
                    @foreach ($ddns as $ddn)
                    @if (old('ddn', substr($contacto->telefono, 0, 3)) == $ddn->ddn)
                        <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                    @else
                        <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                    @endif
                    @endforeach
                    </select>
                    <input class="form-control" type="text" size="7" maxlength="7" name="telefono"
                            id="telefono" placeholder="numero sin area" 
                            value="{{ old('telefono', substr($contacto->telefono, 3)) }}">
                </div>
                <div class="form-group col-lg-8 d-flex">
                    <label class="control-label" for="email">Correo electr&oacute;nico</label>
                    <input type="email" class="form-control col-lg-6" maxlength="30" name="email" 
                            id="email" placeholder="correo electronico"
                            value="{{ old('email', $contacto->email) }}">
                </div>
            </div>
            <div class="form-row bg-suave my-0 py-0">
                <div class="form-group col-lg-8 d-flex">
                    <label class="control-label" for="otro_telefono">Otro telefono</label>
                    <input type="text" class="form-control col-lg-6" size="20" maxlength="20"
                            name="otro_telefono" id="otro_telefono"
                            placeholder="Quizas internacional"
                            value="{{ old('otro_telefono', $contacto->otro_telefono) }}">
                </div>
            </div>

            <div class="form-row my-0 py-0">
                <div class="form-group col-lg-12 d-flex">
                    <label class="control-label col-sm-2" for="direccion">Direcci&oacute;n</label>
                    <textarea class="form-control col-sm-10" rows="3" maxlength="190" 
                        name="direccion" id="direccion" 
                        placeholder="Calle, Casa, Apto, Edificio, Barrio, Ciudad">{{ old('direccion', $contacto->direccion) }}</textarea>
                </div>
            </div>

            <div class="form-row my-0 py-0">
                <div class="form-group col-lg-12 d-flex">
                    <label class="control-label" for="observaciones">Observaciones</label>
                    <textarea class="form-control" rows="3" maxlength="190" 
                        name="observaciones" id="observaciones" 
                        placeholder="Coloque aqui las observaciones que tuvo de la conversación con el contacto inicial.">{{ old('observaciones', $contacto->observaciones) }}</textarea>
                </div>
            </div>

            <div class="form-row my-1 py-0">  {{-- margen(m) arriba y abajo(y) 0.25*$spacer(1) y padding(p) arriba y abajo(y) 0(0) --}}
                <button type="submit" class="btn btn-primary col-sm-5">
                    Actualizar Contacto Inicial
                </button>
                <!-- a href="{{ action('ContactoController@index') }}">Regresar al listado de usuarios</a -->
                <a href="{{ url('/contactos') }}" class="btn btn-link">
                    Regresar al listado de contactos iniciales
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
