<div class="row no-gutters">
<div class="col-2 no-gutters" style="font-size:0.75rem">
  <div class="card mt-0 mb-1 p-0 mx-0">
    <h4 class="card-header m-0 p-0">Agenda de citas</h4>
    <div class="card-body m-0 p-0">
      <a href="{{ route('agendaPersonal.crear') }}" class="btn btn-primary my-0 py-0 mx-2 px-auto">
      @if ($movil)
        Crear
      @else
        Crear Cita Personal
      @endif
      </a>
    </div>
  </div>
  <div class="card mt-1 mb-0 mx-0 p-0">
    <h4 class="card-header m-0 p-0">Filtrar listado</h4>
    <div class="card-body m-0 p-0">
      <form method="POST" class="form-vertical m-0 p-0"
            action="{{ route('agenda.post') }}" onSubmit="return alertaFechaRequerida()">
        {!! csrf_field() !!}

        @includeWhen(!$movil, 'include.intervalo')

        @include('include.fechas')
        @includeWhen(Auth::user()->is_admin, 'include.asesor', ['berater' => 'asesor'])   {{-- Obligatorio pasar la variable 'berater' --}}
        @include('include.botonMostrar')
      </form>
    </div>
  </div>
</div>
<div class="col-10 no-gutters">
