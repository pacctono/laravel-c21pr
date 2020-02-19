    <div class="form-group form-inline m-0 p-0">
        <select class="form-control form-control-sm" name="estatus" id="estatus"
	    style="font-size:0.50rem">
            <option value="">Estatus</option>
        @foreach ($arrEstatus as $opcion => $muestra)
            <option value="{{$opcion}}"
        @if (old('estatus', $estatus) == $opcion)
            selected
        @endif
	    style="font-size:0.50rem">
		{{ substr($muestra, 0, 35) }}
            </option>
        @endforeach
        </select>
    </div>
