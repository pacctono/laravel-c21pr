<div class="row no-gutters">
<div class="col-{{ $nCol }} no-gutters" style="font-size:0.65rem">
  <div class="card mt-0 mb-1 p-0 mx-0">
    <h4 class="card-header m-0 p-0">Propiedades</h4>
    <div class="card-body m-0 p-0">
      <a href="{{ route('propiedades.create') }}" class="btn btn-primary mt-0 mb-1 py-0 mx-3 px-auto">
      @if ($movil)
        Crear
      @else
        Crear Propiedad
      @endif
      </a>
      <div class="mt-1 mb-0 mx-3 p-0">
        <form method="POST" class="form-horizontal" action="{{ route('propiedades.post') }}"
            id="formaMisVentas">
            {!! csrf_field() !!}

          <input type="hidden" name="fecha_desde" value="">
          <input type="hidden" name="fecha_hasta" value="">
          <input type="hidden" name="estatus" value="V">
          <input type="hidden" name="asesor" value="{{ (1 == Auth::user()->id)?0:Auth::user()->id }}">
          <button type="submit" class="btn btn-success m-0 py-0 px-auto">
          @if (1 == Auth::user()->id)
            Todas las Ventas
          @else (1 == Auth::user()->id)
            Mis Ventas
          @endif (1 == Auth::user()->id)
          </button>
        </form>
      </div>
    </div>
  </div>
  <div class="card mt-1 mb-0 mx-0 p-0">
    <h3 class="card-header m-0 p-0">Filtrar listado</h3>
    <div class="card-body m-0 p-0">
      <form method="POST" class="form-horizontal" action="{{ route('propiedades.post') }}"
            id="formulario">
            {!! csrf_field() !!}

        <div class="form-row justify-content-center">
          @includeif('include.fechas', ['tipoFecha' => 'de la firma '])
        </div>
        <div class="form-row justify-content-center">
          @includeif('include.anos', ['muestra' => 'Año creada', 'arr' => 'anosc',
                                    'prop' => 'anoc', 'tamFuente' => 0.75])
        </div>
          {{-- print_r($arrNegociacion) --}}
        <div class="form-row my-1 justify-content-center">
          @includeif('include.entrada', ['nombre' => 'codigo', 'tamano' => '8',
                            'minTam' => 1, 'req' => false, 'colocar' => 'Cod. MLS',
                            'nombId' => 'vmCodigo', 'label' => true, 'tamFuente' => 0.75])
        </div>
        <div class="form-row my-1 justify-content-center">
          @includeif('include.negociacion')
        </div>
        <div class="form-row my-1 justify-content-center">
          @includeif('include.estatus', ['venta' => true])
        </div>
          {{--@includeif('include.filtro', ['filtro' => 'precio', 'filtros' => 'precios'])--}}
        <h5 class="card-header my-0 mx-1 p-0">Precio</h5>
        <div class="form-row my-1 justify-content-center">
            @includeif('include.entrada', ['nombre' => 'desde', 'tamano' => '7',
                            'minTam' => 2, 'req' => false, 'colocar' => 'Minimo',
                            'tamFuente' => 0.75])
            @includeif('include.entrada', ['nombre' => 'hasta', 'tamano' => '8',
                            'minTam' => 2, 'req' => false, 'colocar' => 'Maximo',
                            'tamFuente' => 0.75])
        </div>
        <div class="my-1 justify-content-center">
          @includeWhen(Auth::user()->is_admin, 'include.asesor', ['berater' => 'asesor'])   {{-- Obligatorio pasar la variable 'berater' --}}
        </div>
        <div class="my-1 justify-content-center">
          @include('include.botonMostrar')
        </div>
      </form>
    </div>
  </div>
</div>
<div class="col-{{ 12-$nCol }} no-gutters">
