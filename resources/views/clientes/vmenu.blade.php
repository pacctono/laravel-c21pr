<div class="row no-gutters">
<div class="col-{{ $nCol }} no-gutters" style="font-size:0.65rem">
  <div class="card mt-0 mb-1 p-0 mx-0">
    <h4 class="card-header m-0 p-0">Clientes</h4>
    <div class="card-body m-0 p-0">
        <a href="{{ route('clientes.create') }}" class="btn btn-primary my-0 py-0 mx-2">
        @if ($movil)
            Crear
        @else
            Crear Cliente Inicial
        @endif
        </a>
    </div>
  </div>
  <div class="card mt-1 mb-0 mx-0 p-0">
    <h3 class="card-header m-0 p-0">Filtrar listado</h3>
    <div class="card-body m-0 p-0">
        <form method="POST" class="form-horizontal" action="{{ route('clientes.post') }}">
            {!! csrf_field() !!}

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
<div class="col-{{ 12-$nCol }} no-gutters">
