<div class="row no-gutters">
<div class="col-2 no-gutters" style="font-size:0.65rem">
  <div class="card mt-0 mb-1 p-0 mx-0">
    <h4 class="card-header m-0 p-0">Contacto Inicial</h4>
    <div class="card-body m-0 p-0">
        <a href="{{ route('contactos.create') }}" class="btn btn-primary my-0 py-0 mx-2">
        @if ($movil)
            Crear
        @else
            Crear Contacto Inicial
        @endif
        </a>
    </div>
  </div>
  <div class="card mt-1 mb-0 mx-0 p-0">
    <h3 class="card-header m-0 p-0">Filtrar listado</h3>
    <div class="card-body m-0 p-0">
        <form method="POST" class="form-horizontal" action="{{ route('contactos.post') }}"
                {{--onSubmit="return alertaCampoRequerido()"--}}>
            {!! csrf_field() !!}

        {{--<div class="form-row justify-content-center">
        @includeif('include.fechas', ['tipoFecha' => 'de la firma '])
        </div>--}}
            <div class="form-row justify-content-center" style="font-size:0.75rem">
            {{-- print_r($deseos) --}}
            @includeif('include.filtro', ['filtro' => 'deseo', 'filtros' => 'deseos',
                                            'tamFont' => 1.0])
            </div>
            <div class="form-row justify-content-center">
            @includeWhen(Auth::user()->is_admin, 'include.asesor', ['berater' => 'asesor'])   {{-- Obligatorio pasar la variable 'berater' --}}
            </div>
            <div class="form-row justify-content-center">
            @include('include.botonMostrar')
            </div>
        </form>
    </div>
  </div>
</div>
<div class="col-10 no-gutters">
