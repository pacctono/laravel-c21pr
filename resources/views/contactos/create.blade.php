@extends('layouts.app')

@section('content')
<div class="card col-10">
    <h4 class="card-header">{{ $title }}</h4>
    <div class="card-body">
    @if ($exito)
    <div class="alert alert-success">
        <h5>{{ $exito }}</h5>
    </div>
    @endif
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

    <form method="POST" class="form align-items-end-horizontal" action="{{ url('contactos') }}">
        {!! csrf_field() !!}

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="cedula">Cedula:</label>
                <input type="text" class="form-control col-sm-3" size="8" maxlength="8" minlength="6" 
                        name="cedula" id="cedula" placeholder="12345678" value="{{ old('cedula') }}">
                &nbsp;
                <label class="control-label col-sm-2" for="name">Nombre:</label>
                <input type="text" class="form-control col-sm-5" required size="30" maxlength="30" 
                        name="name" id="name" placeholder="Pedro Perez" value="{{ old('name') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
              <label class="control-label col-sm-2" for="telefono">Teléfono:</label>
              <div class="form-control col-sm-3">
                <select name="ddn" id="ddn">
                  <option value="">ddn</option>
                @foreach ($ddns as $ddn)
                @if (old('ddn', '414') == $ddn->ddn)
                  <option value="{{ $ddn->ddn }}" selected>{{ $ddn->ddn }}</option>
                @else
                  <option value="{{ $ddn->ddn }}">{{ $ddn->ddn }}</option>
                @endif
                @endforeach
                </select>
                <input type="text" size="7" maxlength="7" name="telefono" id="telefono" 
                        placeholder="1234567" value="{{ old('telefono') }}">
              </div>
                <label class="control-label col-sm-2" for="email">Correo electrónico:</label>
                <input type="email" class="form-control col-sm-5" size="30" maxlength="30" name="email" 
                        id="email" placeholder="pedro@example.com" value="{{ old('email') }}">
            </div>
        </div>

        <div class="form-group d-flex">
            <label class="control-label col-sm-2" for="direccion">Dirección:</label>
            <textarea class="form-control col-sm-10" cols="5" rows="4" maxlength="190" name="direccion" 
              id="direccion" placeholder="Calle, Casa, Apto, Edificio, Barrio, Ciudad">{{ old('direccion') }}</textarea>
        </div>

        <div class="row">
            <div class="form-group d-flex">
              <label class="control-label col-sm-2" for="deseo">Desea:</label>
              <div class="form-control col-sm-4">
                <select name="deseo_id" id="deseo">
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
              <label class="control-label col-sm-3" for="propiedad">Propiedad:</label>
              <div class="form-control col-sm-3">
                <select name="propiedad_id" id="propiedad_id">
                  <option value="">Qué propiedad?</option>
                @foreach ($propiedades as $propiedad)
                @if (old('propiedad_id') == $propiedad->id)
                  <option value="{{ $propiedad->id }}" selected>{{ $propiedad->descripcion }}</option>
                @else
                  <option value="{{ $propiedad->id }}">{{ $propiedad->descripcion }}</option>
                @endif
                @endforeach
                </select>
              </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
              <label class="control-label col-sm-2" for="zona">Zona:</label>
              <div class="form-control col-sm-4">
                <select name="zona_id" id="zona">
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
              <label class="control-label col-sm-2" for="precio">Precio:</label>
              <div class="form-control col-sm-4">
                <select name="precio_id" id="precio">
                  <option value="">Qué precio?</option>
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
        </div>

        <div class="row">
            <div class="form-group d-flex">
              <label class="control-label col-sm-2" for="origen">Origen:</label>
              <div class="form-control col-sm-4">
                <select name="origen_id" id="origen_id">
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
              <label class="control-label col-sm-2" for="resultado">Resultado:</label>
              <div class="form-control col-sm-4">
                <select name="resultado_id" id="resultado"
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
                &nbsp;
                <input type="date" name="fecha_evento" id="fecha_evento"
                        min="{{ now()->format('d/m/Y') }}" max="{{ now()->addWeeks(4)->format('d/m/Y') }}"
                        value="{{ old('fecha_evento') }}">
                <input type="time" name="hora_evento" id="hora_evento" value="{{ old('hora_evento') }}">
              </div>
            </div>
        </div>

        <div class="form-group d-flex">
            <label class="control-label col-sm-2" for="observaciones">Observaciones:</label>
            <textarea class="form-control col-sm-10" cols="5" rows="5" maxlength="190" 
                      name="observaciones" id="observaciones" 
                      placeholder="Coloque aqui las observaciones que tuvo de la conversación con el contacto inicial.">{{ old('observaciones') }}</textarea>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <button type="submit" class="btn btn-success col-sm-5">Agregar contacto inicial</button>
                <a href="{{ url('/contactos') }}" class="btn btn-link">Regresar al listado de contactos iniciales</a>
            </div>
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
