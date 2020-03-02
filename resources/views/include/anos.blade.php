      @if (isset($muestra) and isset($arr) and isset($prop))
        <div class="form-group form-inline mt-0 mb-1 p-0 mx-0"
            style="font-size:{{ $tamFuente??1 }}rem">
          <select class="form-control form-control-sm my-0 py-0" name="{{ $prop }}" id="{{ $prop }}"
            style="font-size:{{ $tamFuente??1 }}rem">
	    <option value="" style="font-size:{{ $tamFuente??1 }}rem">
	      {{ ucfirst($muestra) }}
            </option>
            @foreach ($$arr as $ano)
	      <option value="{{ $ano->$prop }}"
              @if (old("$prop", $$prop) == $ano->$prop)
                selected
              @endif (old("$prop", $$prop) == $ano->$prop)
                style="font-size:{{ $tamFuente??1 }}rem">
                {{ $ano->$prop }}
              </option>
            @endforeach ($$arr as $ano)
          </select>
        </div>
      @endif (isset($muestra))
