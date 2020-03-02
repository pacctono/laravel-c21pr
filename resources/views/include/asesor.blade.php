      @if (isset($berater))
        <div class="form-group form-inline m-0 p-0">
          <select class="form-control form-control-sm my-0 mx-2" name="{{ $berater }}" id="{{ $berater }}">
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
