      <div class="form-row my-0 py-0 mx-1 px-1">
        @foreach (['hoy', 'ayer', 'manana', 'esta_semana', 'semana_pasada', 'proxima_semana',
                  'este_mes', 'mes_pasado', 'proximo_mes', 'todo', 'intervalo'] as $intervalo)
        <div class="form-check form-check-inline my-1 py-0 mx-1 px-0">
          <input class="form-check-input form-check-input-sm" type="radio" required name="periodo"
                  id="_{{ $intervalo }}" value="{{ $intervalo }}"
          @if ($rPeriodo == $intervalo)
            checked
          @endif
          >
          <label class="form-check-label form-check-label-sm" for="_{{ $intervalo }}">
          @if ('manana' == $intervalo)
          Mañana
          @elseif ('intervalo' == $intervalo)
          Otro
          @else
          {{ str_replace('_', ' ', ucfirst($intervalo)) }}
          @endif
          </label>
        </div>
        @endforeach
      </div>