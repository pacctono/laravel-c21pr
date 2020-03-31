@extends('layouts.app')

@section('content')
<div class="card m-0 p-0">
  <h4 class="card-header m-0 p-1">{{ $title }}</h4>
  <div class="card-body m-0 p-0">
    @include('include.exitoCrear')
    @include('include.errorData')

    <form method="POST" class="form align-items-end-horizontal"
        id="formulario" action="{{ url('contactos') }}">
        {!! csrf_field() !!}

        <div class="form-row my-1 mx-0 p-0">  {{-- margen(m) arriba y abajo(y) 0 y padding(p) arriba y abajo(y) 0(0) --}}
{{-- Otros valores para margen y padding: 't':tope, 'b':bottom, 'l':left, 'r':right y 'x':left y right --}}
          <div class="form-group col-lg-3 d-flex m-0 py-0 px-1">
            <label class="control-label" for="cedula">C&eacute;dula</label>
            <input type="text" class="form-control form-control-sm"
                    size="8" maxlength="8" minlength="6" 
                    name="cedula" id="cedula"
                    placeholder="numero de cedula"
                    value="{{ old('cedula') }}">
          </div>
          <div class="form-group col-lg-9 d-flex m-0 py-0 px-1">
            <label class="control-label" for="name">Nombre</label>
            <input type="text" class="form-control form-control-sm" required size="30" maxlength="30" 
                    name="name" id="name" placeholder="Nombre del contacto inicial" value="{{ old('name') }}">
          </div>
        </div>

        <div class="form-row bg-suave mt-1 mb-0 mx-0 p-0">
          <div class="form-group col-lg-4 d-flex m-0 py-0 px-1">
            <label class="control-label" for="telefono">Teléfono</label>
            <select class="form-control form-control-sm" name="ddn" id="ddn">
              <option value="">ddn</option>
            @foreach ($ddns as $ddn)
            @if (old('ddn', '414') == $ddn->ddn)
              <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
            @else
              <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
            @endif
            @endforeach
            </select>
            <input class="form-control form-control-sm" type="text" size="7" maxlength="7" name="telefono"
                    id="telefono" placeholder="numero sin area" value="{{ old('telefono') }}">
          </div>
          <div class="form-group col-lg-8 d-flex m-0 py-0 px-1">
            <label class="control-label" for="email">Correo electr&oacute;nico</label>
            <input type="email" class="form-control form-control-sm col-lg-6" size="30" maxlength="30" name="email" 
                    id="email" placeholder="correo electronico" value="{{ old('email') }}">
          </div>
        </div>
        <div class="form-row bg-suave mt-0 mb-1 mx-0 p-0">
          <div class="form-group col-lg-8 d-flex m-0 py-0 px-1">
            <label class="control-label" for="otro_telefono">Otro telefono</label>
            <input type="text" class="form-control form-control-sm col-lg-6" size="20" maxlength="20"
                    name="otro_telefono" id="otro_telefono"
                    placeholder="Quizas internacional" value="{{ old('otro_telefono') }}">
          </div>
        </div>

        <div class="form-row my-1 mx-0 p-0">
          <div class="form-group col-lg-12 d-flex m-0 py-0 px-1">
            <label class="control-label" for="direccion">Direcci&oacute;n</label>
            <textarea class="form-control form-control-sm" rows="2"
                  cols="80" maxlength="160" name="direccion" id="direccion"
                  placeholder="Calle, Casa, Apto, Edificio, Barrio, Ciudad">{{ old('direccion') }}</textarea>
          </div>
        </div>

        <div class="form-row bg-suave my-1 mx-0 p-0">
          <div class="form-group d-flex my-1 mx-0 p-0">
            <label class="control-label mx-1 p-0" for="deseo">Desea</label>
            <select class="form-control form-control-sm" name="deseo_id" id="deseo">
              <option value="">Qué desea?</option>
            @foreach ($deseos as $deseo)
            @if (old('deseo_id') == $deseo->id)
              <option value="{{ $deseo->id }}" selected>{{ $deseo->descripcion }}</option>
            @else
              <option value="{{ $deseo->id }}">{{ $deseo->descripcion }}</option>
            @endif
            @endforeach
            </select>
          </div>
          <div class="form-group d-flex my-1 mx-2 p-0">
            <label class="control-label mx-0 p-0" for="tipo">Tipo</label>
            <select class="form-control form-control-sm" name="tipo_id" id="tipo_id">
              <option value="">Qué tipo?</option>
            @foreach ($tipos as $tipo)
            @if (old('tipo_id') == $tipo->id)
              <option value="{{ $tipo->id }}" selected>{{ $tipo->descripcion }}</option>
            @else
              <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
            @endif
            @endforeach
            </select>
          </div>
          <div class="form-group d-flex my-1 mx-2 p-0">
            <label class="control-label mx-0 p-0" for="zona">Zona</label>
            <select class="form-control form-control-sm" name="zona_id" id="zona">
              <option value="">Qué zona?</option>
            @foreach ($zonas as $zona)
            @if (old('zona_id') == $zona->id)
              <option value="{{ $zona->id }}" selected>{{ $zona->descripcion }}</option>
            @else
              <option value="{{ $zona->id }}">{{ $zona->descripcion }}</option>
            @endif
            @endforeach
            </select>
          </div>
          <div class="form-group d-flex my-1 mx-1 p-0">
            <label class="control-label mx-0 p-0" for="precio">Precio</label>
            <div id="idPrecio">
            <select class="form-control form-control-sm" name="precio_id" id="precio">
            @foreach ($precios as $precio)
            @if (old('precio_id') == $precio->id)
              <option value="{{ $precio->id }}" selected>
                {{ $precio->descripcion }}
              </option>
            @else
              <option value="{{ $precio->id }}">
                {{ $precio->descripcion }}
              </option>
            @endif
            @endforeach
            </select>
            </div>
          </div>
        </div>

        <div class="form-row my-1 mx-0 p-0">
          <div class="form-group col-lg-3 d-flex m-0 py-0 px-1">
            <label class="control-label" for="origen">Origen</label>
            <select class="form-control form-control-sm"
                name="origen_id" id="origen_id">
              <option value="">Cómo supo de nosotros?</option>
            @foreach ($origenes as $origen)
            @if (old('origen_id') == $origen->id)
              <option value="{{ $origen->id }}" selected>{{ $origen->descripcion }}</option>
            @else
              <option value="{{ $origen->id }}">{{ $origen->descripcion }}</option>
            @endif
            @endforeach
            </select>
          </div>
          <div class="form-group col-lg-9 d-flex m-0 py-0 px-1">
            <label class="control-label" for="resultado">Resultado</label>
            <select class="form-control form-control-sm" name="resultado_id" id="resultado_id">
              <option value="">Cuál fue el resultado?</option>
            @foreach ($resultados as $resultado)
            @if (old('resultado_id') == $resultado->id)
              <option value="{{ $resultado->id }}" selected>{{ $resultado->descripcion }}</option>
            @else
              <option value="{{ $resultado->id }}">{{ $resultado->descripcion }}</option>
            @endif
            @endforeach
            </select>
            <label class="control-label sr-only" for="fecha_evento">Fecha</label>
            <input class="form-control form-control-sm" type="date" name="fecha_evento" id="fecha_evento"
                    min="{{ now('America/Caracas')->format('Y-m-d') }}"
                    max="{{ now('America/Caracas')->addWeeks(4)->format('Y-m-d') }}"
                    title="Fecha en caso del Resultado sea llamarle o se haya concretado una cita."
                    value="{{ old('fecha_evento') }}" disabled><!-- Los campos desabilitados no pasan la variable -->
            <label class="control-label sr-only" for="hora_evento">Hora</label>
            <input class="form-control form-control-sm" type="time" name="hora_evento" id="hora_evento"
                    title="Si se concreta una cita o 'llamarle', suministre la hora aproximada."
                    value="{{ old('hora_evento') }}" disabled><!-- Los campos desabilitados no pasan la variable -->
          </div>
        </div>

        <div class="form-row bg-suave my-1 mx-0 p-0">
          <div class="col-lg-12 d-flex m-0 py-0 px-1">
            <label class="control-label" for="observaciones">Observaciones</label>
            <textarea class="form-control form-control-sm" rows="2"
                    maxlength="190" cols="95"
                    name="observaciones" id="observaciones" 
                    placeholder="Coloque aqui las observaciones que tuvo de la conversación con el contacto inicial.">{{ old('observaciones') }}</textarea>
          </div>
        </div>

        <div class="form-row my-1 mx-0 p-0">  {{-- margen(m) arriba y abajo(y) 0.25*$spacer(1) y padding(p) arriba y abajo(y) 0(0) --}}
          <button type="submit" class="btn btn-success col-lg-5 m-0 py-0 px-1">
            Agregar contacto inicial
          </button>
          <a href="{{ url('/contactos') }}" class="btn btn-link m-0 py-0 px-1">
            Regresar al listado de contactos iniciales
          </a>
        </div>
    </form>
  </div>
</div>
@endsection

@section('js')

@includeIf("contactos.revisar", ['vista' => 'crear'])

@endsection
