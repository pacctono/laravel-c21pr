@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">{{ $title }}</h4>
    <div class="card-body">
    @if ($exito)
    <div class="alert alert-success">
        <h5>{{ $exito }}</h5>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <h5>Por favor corrige los errores debajo</h5>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" class="form align-items-end-horizontal" action="{{ url('contactos') }}">
        {!! csrf_field() !!}

        <div class="form-row my-0 py-0">  {{-- margen(m) arriba y abajo(y) 0 y padding(p) arriba y abajo(y) 0(0) --}}
{{-- Otros valores para margen y padding: 't':tope, 'b':bottom, 'l':left, 'r':right y 'x':left y right --}}
          <div class="form-group col-lg-3 d-flex">
            <label class="control-label" for="cedula">Cedula</label>
            <input type="text" class="form-control" size="8" maxlength="8" minlength="6" 
                    name="cedula" id="cedula" placeholder="numero de cedula" value="{{ old('cedula') }}">
          </div>
          <div class="form-group col-lg-9 d-flex">
            <label class="control-label" for="name">Nombre</label>
            <input type="text" class="form-control" required size="30" maxlength="30" 
                    name="name" id="name" placeholder="Nombre del contacto inicial" value="{{ old('name') }}">
          </div>
        </div>

        <div class="form-row bg-suave my-0 py-0">
          <div class="form-group col-lg-4 d-flex">
            <label class="control-label" for="telefono">Teléfono</label>
            <select class="form-control" name="ddn" id="ddn">
              <option value="">ddn</option>
            @foreach ($ddns as $ddn)
            @if (old('ddn', '414') == $ddn->ddn)
              <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
            @else
              <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
            @endif
            @endforeach
            </select>
            <input class="form-control" type="text" size="7" maxlength="7" name="telefono"
                    id="telefono" placeholder="numero sin area" value="{{ old('telefono') }}">
          </div>
          <div class="form-group col-lg-8 d-flex">
            <label class="control-label" for="email">Correo electr&oacute;nico</label>
            <input type="email" class="form-control col-lg-6" size="30" maxlength="30" name="email" 
                    id="email" placeholder="correo electronico" value="{{ old('email') }}">
          </div>
        </div>

        <div class="form-row my-0 py-0">
          <div class="form-group col-lg-12 d-flex">
            <label class="control-label" for="direccion">Direcci&oacute;n</label>
            <textarea class="form-control" rows="3" maxlength="190" name="direccion" 
              id="direccion" placeholder="Calle, Casa, Apto, Edificio, Barrio, Ciudad">{{ old('direccion') }}</textarea>
          </div>
        </div>

        <div class="form-row bg-suave my-0 py-0">
          <div class="form-group col-lg-3 d-flex">
            <label class="control-label" for="deseo">Desea</label>
            <select class="form-control" name="deseo_id" id="deseo">
              <option value="">Qué desea hacer?</option>
            @foreach ($deseos as $deseo)
            @if (old('deseo_id') == $deseo->id)
              <option value="{{ $deseo->id }}" selected>{{ $deseo->descripcion }}</option>
            @else
              <option value="{{ $deseo->id }}">{{ $deseo->descripcion }}</option>
            @endif
            @endforeach
            </select>
          </div>
          <div class="form-group col-lg-3 d-flex">
            <label class="control-label" for="tipo">Tipo</label>
            <select class="form-control" name="tipo_id" id="tipo_id">
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
          <div class="form-group col-lg-3 d-flex">
            <label class="control-label" for="zona">Zona</label>
            <select class="form-control" name="zona_id" id="zona">
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
          <div class="form-group col-lg-3 d-flex">
            <label class="control-label" for="precio">Precio</label>
            <select class="form-control" name="precio_id" id="precio">
            @foreach ($precios as $precio)
            @if (old('precio_id') == $precio->id)
              <option value="{{ $precio->id }}" selected>{{ $precio->descripcion }}</option>
            @else
              <option value="{{ $precio->id }}">{{ $precio->descripcion }}</option>
            @endif
            @endforeach
            </select>
          </div>
        </div>

        <div class="form-row my-0 py-0">
          <div class="form-group col-lg-3 d-flex">
            <label class="control-label" for="origen">Origen</label>
            <select class="form-control" name="origen_id" id="origen_id">
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
          <div class="form-group col-lg-9 d-flex">
            <label class="control-label" for="resultado">Resultado</label>
            <select class="form-control" name="resultado_id" id="resultado_id"
                                        onChange="alertaFechaRequerida()">
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
            <input class="form-control" type="date" name="fecha_evento" id="fecha_evento"
                    min="{{ now()->format('d/m/Y') }}" max="{{ now()->addWeeks(4)->format('d/m/Y') }}"
                    value="{{ old('fecha_evento') }}">
            <label class="control-label sr-only" for="hora_evento">Hora</label>
            <input class="form-control" type="time" name="hora_evento" id="hora_evento"
                    value="{{ old('hora_evento') }}">
          </div>
        </div>

        <div class="form-row my-0 py-0">
          <div class="col-lg-12 d-flex">
            <label class="control-label" for="observaciones">Observaciones</label>
            <textarea class="form-control" rows="3" maxlength="190" 
                      name="observaciones" id="observaciones" 
                      placeholder="Coloque aqui las observaciones que tuvo de la conversación con el contacto inicial.">{{ old('observaciones') }}</textarea>
          </div>
        </div>

        <div class="form-row my-1 py-0">  {{-- margen(m) arriba y abajo(y) 0.25*$spacer(1) y padding(p) arriba y abajo(y) 0(0) --}}
          <button type="submit" class="btn btn-success col-lg-5">Agregar contacto inicial</button>
          <a href="{{ url('/contactos') }}" class="btn btn-link">Regresar al listado de contactos iniciales</a>
        </div>
    </form>
    </div>
</div>
@endsection

@section('js')

<script>
function alertaFechaRequerida() {
  var resValor = document.getElementById('resultado').value;
  var fecha    = document.getElementById('fecha_evento');

  if (('' == resValor) || (4 > parseInt(resValor)) || (7 < parseInt(resValor))) {
    return;
  }

  if (4 == parseInt(resValor)) {
    tipo = 'llamada';
  } else {
    tipo = 'cita';
  }

  alert("Como resultado de este contacto inicial, usted debe realizar una '" + tipo +
    "', suministre la fecha y hora de la '" + tipo + "'");
  fecha.focus();
}
</script>

@endsection
