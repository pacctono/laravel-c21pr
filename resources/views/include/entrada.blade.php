    <div class="form-group form-inline my-0 mx-1 p-0">
        @if (isset($label) and $label)
	<label class="m-0 p-0" for="{{ $nombre }}" style="font-size:{{ $tamFuente??1 }}rem">
            {{ ucfirst($nombre) }}
        </label>
        @endif (isset($label) and $label)
	<input type="text" class="form-control form-control-sm m-0 p-1" size="{{ $tamano }}"
            style="font-size:{{ $tamFuente??1 }}rem"
            maxlength="{{ $maxTam??$tamano }}" minlength="{{ $minTam??$tamano }}"
            name="{{ $nombre }}" id="{{ $nombId??$nombre }}" {{ $req?'required':'' }}
            placeholder="{{ $colocar??'' }}" value="{{ old('$nombre', $$nombre) }}">
    </div>
