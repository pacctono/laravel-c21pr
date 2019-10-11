    <div class="form-group form-inline my-0 py-0 mx-0 px-0">
        <select class="form-control form-control-sm" name="estatus" id="estatus">
            <option value="">Estatus</option>
        @foreach ($arrEstatus as $opcion => $muestra)
            <option value="{{$opcion}}"
        @if (old('estatus', $estatus) == $opcion)
            selected
        @endif
            >{{ substr($muestra, 0, 35) }}</option>
        @endforeach
        </select>
    </div>