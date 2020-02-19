        <div class="form-group form-inline mt-0 mb-1 p-0 mx-0">
          <label class="control-label m-0 p-0" style="font-size:0.50" for="fecha_desde"
                  title="Fecha {{ isset($tipoFecha)?$tipoFecha:'' }}desde">
            Desde</label>
          <input class="form-control form-control-sm m-0 p-0" type="date" name="fecha_desde"
                  id="fecha_desde" min="{{ now() }}" max="{{ now() }}"
                  title="Fecha {{ isset($tipoFecha)?$tipoFecha:'' }}desde"
                  value="{{ old('fecha_desde', $fecha_desde) }}">
        </div>
        <div class="form-group form-inline mt-0 mb-1 mx-0 p-0">
          <label class="control-label my-0 py-0" style="font-size:0.50" for="fecha_hasta"
                  title="Fecha {{ isset($tipoFecha)?$tipoFecha:'' }}hasta">
            Hasta</label>
          <input class="form-control form-control-sm m-0 p-0" type="date" name="fecha_hasta"
                  id="fecha_hasta" min="{{ now() }}" max="{{ now() }}"
                  title="Fecha {{ isset($tipoFecha)?$tipoFecha:'' }}hasta"
                  value="{{ old('fecha_hasta', $fecha_hasta) }}">
        </div>
