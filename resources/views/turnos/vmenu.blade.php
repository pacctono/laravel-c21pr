<div class="row no-gutters">
<div class="col-{{ $nCol }} no-gutters">
  <div class="card mt-0 mb-1 py-0 mx-0">
    <h4 class="card-header my-0 py-0 mx-0">Crear turno</h4>
    <div class="card-body my-0 py-0 mx-0">
        <select name="semana" id="semana"
          onchange="javascript:location.href = this.value;">
          <option value="">Semana</option>
          @foreach ($semanas as $semana)
              <option value="{{ route('turnos.crear', $loop->index) }}"
                @if ($semana[1])
                  style="color:red"
                @endif ($semana[1])
              >
                  {{ $diaSemana[$semana[0]->dayOfWeek] }}
                  {{ $semana[0]->format('d/m/Y') }}
              </option>
          @endforeach
        </select>
    </div>
  </div>
  @if (isset($ultimaSemanaCreada))
  <div class="card my-1 py-0 mx-0">
    <h6 class="card-header m-0 py-0">Borrar última semana</h6>
    <div class="card-body my-0 py-0 mx-0">
      <form action="{{ route('turnos.destroy', $ultimaSemanaCreada->id) }}" method="POST"
          class="form-inline m-0 p-0">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button class="btn btn-link m-0 p-0">
          {{ $diaSemana[$ultimaSemanaCreada->turno->dayOfWeek] }}
          {{ $ultimaSemanaCreada->turno->format('d/m/Y') }}
        </button>
      </form>
    </div>
  </div>
  @endif (isset($ultimaSemanaCreada))
  <div class="card mt-1 mb-0 py-0 mx-0">
    <h4 class="card-header my-0 py-0 mx-0">Filtrar listado</h4>
    <div class="card-body m-0 p-0">
        <form method="POST" class="form" action="{{ route('calendario.post') }}">
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
