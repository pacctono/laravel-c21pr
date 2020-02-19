    <div class="form-group form-inline m-0 p-0">
        <select class="form-control form-control-sm" name="negociacion" id="negociacion">
            <option value="">Negociacion</option>
        @foreach ($negociaciones as $opcion => $muestra)
            <option value="{{$opcion}}"
        @if (old('negociacion', $negociacion) == $opcion)
            selected
        @endif
            >{{ $muestra }}</option>
        @endforeach
        </select>
    </div>
