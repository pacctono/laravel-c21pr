        <div class="form-group form-inline mt-0 mb-1 p-0 mx-0">
          <select class="form-control form-control-sm my-0 py-0" name="{{ $filtro }}" id="{{ $filtro }}"
              style="font-size:{{ $tamFont??0.50 }}rem">
            <option value="">{{ ucfirst($filtro) }}</option>
            @foreach ($$filtros as $elemento)
              <option value="{{ $elemento->id }}"
              @if (old("$filtro", $$filtro) == $elemento->id)
                selected
              @endif (old("$filtro", $$filtro) == $elemento->id)
              style="font-size:{{ $tamFont??0.50 }}rem">
                {{ $elemento->descripcion }}
              </option>
            @endforeach ($$filtros as $elemento)
          </select>
        </div>
