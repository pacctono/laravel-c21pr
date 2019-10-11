      @if (isset($berater))
        <div class="form-group form-inline my-1 py-0 mx-0 px-0">
          <select class="form-control form-control-sm" name="{{ $berater }}" id="{{ $berater }}">
            <option value="0">{{ ucfirst($berater) }}</option>
            @foreach ($users as $user)
              <option value="{{ $user->id }}"
              @if (old("$berater", $$berater) == $user->id)
                selected
              @endif (old("$berater", $$berater) == $user->id)
              >
                {{ $user->name }}
              </option>
            @endforeach ($users as $user)
          </select>
        </div>
      @endif (isset($berater))