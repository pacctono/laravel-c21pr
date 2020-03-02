    <div class="form-group form-inline my-1 mx-0 p-0">
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
        @if (isset($venta) and $venta)
	    <option value="V"
            @if (old('estatus', $estatus) == 'V')
                selected
            @endif
            >
		Ventas (Pagos pendientes y cerrado)
            </option>
        @endif (isset($venta) and $venta)
        @if (isset($vencimiento) and $vencimiento)
	    <option value="X"
            @if (old('estatus', $estatus) == 'X')
                selected
            @endif
            >
		Vencidas (Mas de 90 dias creadas)
            </option>
        @endif (isset($vencimiento) and $vencimiento)
        </select>
    </div>
