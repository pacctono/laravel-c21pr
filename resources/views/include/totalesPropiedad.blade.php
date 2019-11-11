  <div @if('html'==$accion) class="row my-0 py-0 mx-1 px-1"
      @else style="border:1px solid #000;" @endif>
    TOT.$s: {{ $filas }} props
    <span class="alert-success mx-1 px-1" title="Precio">
        {{ Prop::numeroVen($tPrecio, 0) }}</span>{{-- 'Prop' es un alias definido en config/app.php --}}
  @if (Auth::user()->is_admin)
    <span class="alert-info ml-1 mr-0 px-1">Compartido con IVA:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Compartido con otra oficina con IVA">
        {{ Prop::numeroVen($tCompartidoConIva, 2) }}</span>
    <span class="alert-info ml-1 mr-0 px-1">Lados:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Sumatoria de los lados">
        {{ $tLados }}</span>
    <span class="alert-info ml-1 mr-0 px-1">Franq a pagar rep:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Franquicia a pagar reportada">
        {{ Prop::numeroVen($tFranquiciaPagarR, 2) }}</span>
    <span class="alert-info ml-1 mr-0 px-1">Regalia:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Regalia">
        {{ Prop::numeroVen($tRegalia, 2) }}</span>
    <span class="alert-info ml-1 mr-0 px-1">Sanaf:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Sanaf - 5%">
        {{ Prop::numeroVen($tSanaf5PorCiento, 2) }}</span>
  </div>
  <div @if('html'==$accion) class="row my-0 py-0 mx-1 px-1"
      @else style="border:1px solid #000;" @endif>
    <span class="alert-info ml-1 mr-0 px-1">Captador:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Captador PRBR">
        {{ Prop::numeroVen($tCaptadorPrbr, 2) }}
        @if (0 < $tCaptadorPrbrSel)
        ({{ Prop::numeroVen($tCaptadorPrbrSel, 2) }} [{{ $tLadosCap }}])
        @endif
    </span>
    <span class="alert-info ml-1 mr-0 px-1">Cerrador:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Cerrador PRBR">
        {{ Prop::numeroVen($tCerradorPrbr, 2) }}
        @if (0 < $tCerradorPrbrSel)
        ({{ Prop::numeroVen($tCerradorPrbrSel, 2) }} [{{ $tLadosCer }}])
        @endif
    </span>
  @else (Auth::user()->is_admin)
    <span class="alert-info ml-1 mr-0 px-1">Lados:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Sumatoria de los lados">
        {{ $tLadosCap + $tLadosCer }}</span>
  @if (0 < $tCaptadorPrbrSel)
    <span class="alert-info ml-1 mr-0 px-1">Captador:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Captador PRBR">
        {{ Prop::numeroVen($tCaptadorPrbrSel, 2) }}
    </span>
  @endif (0 < $tCaptadorPrbrSel)
  @if (0 < $tCerradorPrbrSel)
    <span class="alert-info ml-1 mr-0 px-1">Cerrador:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Cerrador PRBR">
        {{ Prop::numeroVen($tCerradorPrbrSel, 2) }}
    </span>
  @endif (0 < $tCerradorPrbrSel)
  @endif (Auth::user()->is_admin)
  @if (0 < $tBonificaciones)
    <span class="alert-info ml-1 mr-0 px-1">Bons:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Bonificaciones">
        {{ Prop::numeroVen($tBonificaciones, 2) }}</span>
  @endif
  @if (Auth::user()->is_admin)
    <span class="alert-info ml-1 mr-0 px-1">Gerente:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Gerente">
        {{ Prop::numeroVen($tGerente, 2) }}</span>
    @if (0 < $tComisionBancaria)
        <span class="alert-info ml-1 mr-0 px-1">Coms:</span>
        <span class="alert-success ml-0 mr-1 px-1" title="Comision bancaria">
            {{ Prop::numeroVen($tComisionBancaria, 2) }}</span>
    @endif
    <span class="alert-info ml-1 mr-0 px-1">Neto:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Ingreso neto de la oficina">
        {{ Prop::numeroVen($tIngresoNetoOfici, 2) }}</span>
    <span class="alert-info ml-1 mr-0 px-1">PrVeRe:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Precio de venta real">
        {{ Prop::numeroVen($tPrecioVentaReal, 2) }}
        @if ((0 < $tPvrCaptadorPrbrSel) || (0 < $tPvrCerradorPrbrSel))
            ({{ Prop::numeroVen($tPvrCaptadorPrbrSel+$tPvrCerradorPrbrSel, 2) }})
        @endif
    </span>
  @if ((0 < $tCaptadorPrbrSel) || (0 < $tCerradorPrbrSel))
    {{-- $tCaptadorPrbrSel }}-{{ $tCerradorPrbr --}}
  </div>
  <div @if('html'==$accion) class="row my-0 py-0 mx-1 px-1"
      @else style="border:1px solid #000;" @endif>
  @endif ((0 != $tCaptadorPrbrSel) || (0 != $tCerradorPrbr))
    <span class="alert-info ml-1 mr-0 px-1">Puntos:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Total de puntos: Captado + Cerrado">
        {{ Prop::numeroVen($tPuntos, 2) }}
    @if ((0 < $tPuntosCaptador) or (0 < $tPuntosCerrador))
        ({{ Prop::numeroVen($tPuntosCaptador+$tPuntosCerrador, 2) }})
    @endif
    </span>
    <span class="alert-info ml-1 mr-0 px-1">Comision:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Total de comisiones: Captado + Cerrado">
        {{ Prop::numeroVen($tCaptadorPrbr+$tCerradorPrbr, 2) }}
    @if ((0 < $tCaptadorPrbrSel) or (0 < $tCerradorPrbrSel))
        ({{ Prop::numeroVen($tCaptadorPrbrSel+$tCerradorPrbrSel, 2) }} [{{ $tLadosCap+$tLadosCer }}])
    @endif
    </span>
  @else (Auth::user()->is_admin)
  @if ((0 < $tPvrCaptadorPrbrSel) || (0 < $tPvrCerradorPrbrSel))
    <span class="alert-info ml-1 mr-0 px-1">PrVeRe:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Precio de venta real">
        {{ Prop::numeroVen($tPvrCaptadorPrbrSel+$tPvrCerradorPrbrSel, 2) }}
    </span>
  @endif ((0 < $tPvrCaptadorPrbrSel) || (0 < $tPvrCerradorPrbrSel))
  @if ((0 < $tPuntosCaptador) or (0 < $tPuntosCerrador))
    <span class="alert-info ml-1 mr-0 px-1">Puntos:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Total de comisiones: Captado + Cerrado">
            {{ Prop::numeroVen($tPuntosCaptador+$tPuntosCerrador, 2) }}
    </span>
  @endif ((0 < $tPuntosCaptador) or (0 < $tPuntosCerrador))
  @if ((0 < $tCaptadorPrbrSel) or (0 < $tCerradorPrbrSel))
    <span class="alert-info ml-1 mr-0 px-1">Comision:</span>
    <span class="alert-success ml-0 mr-1 px-1" title="Total de comisiones: Captado + Cerrado">
            {{ Prop::numeroVen($tCaptadorPrbrSel+$tCerradorPrbrSel, 2) }}
    </span>
  @endif ((0 < $tCaptadorPrbrSel) or (0 < $tCerradorPrbrSel))
  @endif (Auth::user()->is_admin)
  </div>