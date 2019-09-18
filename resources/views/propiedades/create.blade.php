@extends('layouts.app')

@section('content')
<div class="card col-12">
    <h4 class="card-header">{{ $title }}</h4>
    <div class="card-body">
    @if ($exito)
    <div class="alert alert-success">
        <h5>{{ $exito }}</h5>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <h5>Por favor corrige los errores debajo:</h5>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="POST" class="form align-items-end-horizontal"
        action="{{ url('propiedades') }}" onSubmit="return fnSometerForma()">
        {!! csrf_field() !!}

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="codigo">*C&oacute;digo:</label>
                <input type="text" class="form-control col-sm-3" size="8" maxlength="8"
                        minlength="6" name="codigo" id="codigo" required
                        placeholder="12345678" value="{{ old('codigo') }}">
                <label class="control-label col-sm-2" for="estatus">*Estatus:</label>
                &nbsp;
              <div class="form-control col-sm-6">
                <select name="estatus" id="estatus">
                @foreach ($cols['estatus']['opcion'] as $opcion => $muestra)
                  <option value="{{$opcion}}"
                  @if (old('estatus', $cols['estatus']['xdef']) == $opcion)
                    selected
                  @endif
                    >{{$muestra}}</option>
                @endforeach
                </select>
              </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
              <label class="control-label col-sm-8" for="negociacion">
                *Negociaci&oacute;n:
              </label>
              <div class="form-control col-sm-6">
                <select name="negociacion" id="negociacion">
                  {{--<option value="">Tipo de negociacion?</option>--}}
                {{-- @foreach (array('V' => 'Venta', 'A' => 'Alquiler') as $opcion => $muestra)--}}
                @foreach ($cols['negociacion']['opcion'] as $opcion => $muestra)
                  <option value="{{$opcion}}"
                  @if (old('negociacion', $cols['negociacion']['xdef']) == $opcion)
                    selected
                  @endif
                    >{{$muestra}}</option>
                @endforeach
                </select>
              </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="fecha_reserva">Fecha de reserva:</label>
                <input type="date" class="form-control col-sm-3" name="fecha_reserva"
                        id="fecha_reserva" min="{{ now()->format('d/m/Y') }}"
                        max="{{ now()->addWeeks(4)->format('d/m/Y') }}"
                        value="{{ old('fecha_reserva') }}">
                &nbsp;
                <label class="control-label col-sm-4" for="fecha_firma">Fecha de la firma:</label>
                <input type="date" class="form-control col-sm-3" name="fecha_firma"
                    id="fecha_firma" min="{{ now()->addWeeks(-4)->format('d/m/Y') }}"
                    max="{{ now()->addWeeks(4)->format('d/m/Y') }}"
                    value="{{ old('fecha_firma') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="nombre">*Nombre:</label>
                <input type="text" class="form-control col-sm-8" size="80" maxlength="150"
                    name="nombre" id="nombre" required
                    placeholder="nombre o descripci&oacute;n de la propiedad"
                    value="{{ old('nombre') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="precio">*Precio:</label>
                <input class="form-control col-sm-1" size="2" maxlength="2" required
                    name="moneda" id="moneda"
                    value="{{ old('moneda', $cols['moneda']['xdef']) }}" list="monedas">
                <datalist id="monedas">
                @foreach ($cols['moneda']['opcion'] as $opcion => $muestra)
                    <option value="{{ $opcion }}">
                @endforeach
                </datalist>
                <input type="float" class="form-control col-sm-3" size="20" maxlength="20"
                    name="precio" id="precio" required placeholder="Precio del inmueble"
                    value="{{ old('precio') }}">
                <label class="control-label col-sm-2" for="comision">*Comision:</label>
                <input type="float" class="form-control col-sm-1" size="6" maxlength="6"
                    name="comision" id="comision" required
                    value="{{ old('comision', $cols['comision']['xdef']) }}">%
                <label class="control-label col-sm-2" for="iva">*IVA:</label>
                <input type="float" class="form-control col-sm-1" size="5" maxlength="5"
                    required name="iva" id="iva"
                    value="{{ old('iva', $cols['iva']['xdef']) }}">%
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-8" for="lados">Lados:</label>
                <input type="number" class="form-control col-sm-3" size="1" maxlength="1"
                    required name="lados" id="lados" value="{{ old('lados') }}">
            </div>
        </div>

        @if (Auth::user()->is_admin)
        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="porc_franquicia">*Franquicia:</label>
                <input type="float" class="form-control col-sm-2" size="5" maxlength="5"
                    name="porc_franquicia" id="porc_franquicia" placeholder="10" required
                    value="{{ old('porc_franquicia', $cols['porc_franquicia']['xdef']) }}">%
                {{--<label class="control-label col-sm-5" for="aplicar_porc_franquicia">
                    Aplicar % reserva s/iva:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_porc_franquicia" id="aplicar_porc_franquicia"
                    title="{{ $cols['aplicar_porc_franquicia']['come'] }}"
                    {{ old('aplicar_porc_franquicia',
                        $cols['aplicar_porc_franquicia']['xdef']) ? "checked" : "" }}>--}}
            {{--</div>
        </div>--}}

        {{--<div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-12"
                    for="aplicar_porc_franquicia_pagar_reportada">
                    Aplicar formula con precio para franquicia a pagar reportada:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_porc_franquicia_pagar_reportada"
                    id="aplicar_porc_franquicia_pagar_reportada"
                    title="{{ $cols['aplicar_porc_franquicia_pagar_reportada']['come'] }}"
                    {{ old('aplicar_porc_franquicia_pagar_reportada',
                        $cols['aplicar_porc_franquicia_pagar_reportada']['xdef']) ?
                        "checked" : "" }}>
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-12"
                    for="aplicar_franquicia_pagar_reportada_bruto">
                    Aplicar franquicia a pagar reportada para bruto real:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_franquicia_pagar_reportada_bruto"
                    id="aplicar_franquicia_pagar_reportada_bruto"
                    title="{{ $cols['aplicar_franquicia_pagar_reportada_bruto']['come'] }}"
                    {{ old('aplicar_franquicia_pagar_reportada_bruto',
                        $cols['aplicar_franquicia_pagar_reportada_bruto']['xdef']) ?
                        "checked" : "" }}>
            </div>
        </div>--}}

        {{--<div class="row">
            <div class="form-group d-flex">--}}
                <label class="control-label col-sm-6" for="reportado_casa_nacional">
                    *Reportado casa nacional:</label>
                <input type="float" class="form-control col-sm-2" size="5" maxlength="5"
                    name="reportado_casa_nacional" id="reportado_casa_nacional"
                    value="{{ old('reportado_casa_nacional',
                                $cols['reportado_casa_nacional']['xdef']) }}">%
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="porc_regalia">*Regalia:</label>
                <input type="float" class="form-control col-sm-2" size="5" maxlength="5"
                    name="porc_regalia" id="porc_regalia" required
                    value="{{ old('porc_regalia', $cols['porc_regalia']['xdef']) }}">%
                {{--<label class="control-label col-sm-2">SANAF 5 %:</label>
                <span class="col-sm-2">&nbsp;</span>
                <input type="float" class="form-control col-sm-2" size="18" maxlength="18"
                    name="sanaf_5_porciento" id="sanaf_5_porciento" placeholder="ddd.ddd,dd"
                    value="{{ old('sanaf_5_porciento') }}">--}}
            {{--</div>
        </div>

        <div class="row">
            <div class="form-group d-flex">--}}
                <label class="control-label col-sm-5" for="porc_gerente">
                    *Porcentaje gerente:</label>
                <input type="float" class="form-control col-sm-2" size="5" maxlength="5"
                    name="porc_gerente" id="porc_gerente" required
                    value="{{ old('porc_gerente', $cols['porc_gerente']['xdef']) }}">%
                {{--<label class="control-label col-sm-5" for="aplicar_porc_captador">
                    *Aplicar % captador PRBR:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_porc_captador" id="aplicar_porc_captador"
                    title="{{ $cols['aplicar_porc_captador']['come'] }}"
                    {{ old('aplicar_porc_captador',
                        $cols['aplicar_porc_captador']['xdef']) ? "checked" : "" }}>--}}
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-5" for="porc_captador_prbr">
                    *Porcentaje captador PRBR:</label>
                <input type="float" class="form-control col-sm-2" size="5" maxlength="5"
                    name="porc_captador_prbr" id="porc_captador_prbr" required
                    value="{{ old('porc_captador_prbr',
                                $cols['porc_captador_prbr']['xdef']) }}">%
                {{--<label class="control-label col-sm-5" for="aplicar_porc_gerente">
                    *Aplicar % gerente:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_porc_gerente" id="aplicar_porc_gerente"
                    title="{{ $cols['aplicar_porc_gerente']['come'] }}"
                    {{ old('aplicar_porc_gerente',
                        $cols['aplicar_porc_gerente']['xdef']) ? "checked" : "" }}>--}}
            {{--</div>
        </div>

        <div class="row">
            <div class="form-group d-flex">--}}
                <label class="control-label col-sm-5" for="porc_cerrador_prbr">
                    *Porcentaje cerrador PRBR:</label>
                <input type="float" class="form-control col-sm-2" size="5" maxlength="5"
                    name="porc_cerrador_prbr" id="porc_cerrador_prbr" required
                    value="{{ old('porc_cerrador_prbr',
                                $cols['porc_cerrador_prbr']['xdef']) }}">%
                {{--<label class="control-label col-sm-5" for="aplicar_porc_cerrador">
                    *Aplicar % cerrador PRBR:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_porc_cerrador" id="aplicar_porc_cerrador"
                    title="{{ $cols['aplicar_porc_cerrador']['come'] }}"
                    {{ old('aplicar_porc_cerrador',
                        $cols['aplicar_porc_cerrador']['xdef']) ? "checked" : "" }}>--}}
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="porc_bonificacion">
                    *Porcentaje bonificacion:</label>
                <input type="float" class="form-control col-sm-2" size="5" maxlength="5"
                    name="porc_bonificacion" id="porc_bonificacion" required
                    value="{{ old('porc_bonificacion',
                        $cols['porc_bonificacion']['xdef']) }}">%
                {{--<label class="control-label col-sm-5" for="aplicar_porc_bonificacion">
                    *Aplicar % bonificacion:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_porc_bonificacion" id="aplicar_porc_bonificacion"
                    title="{{ $cols['aplicar_porc_bonificacion']['come'] }}"
                    {{ old('aplicar_porc_bonificacion',
                        $cols['aplicar_porc_bonificacion']['xdef']) ? "checked" : "" }}>--}}
            {{--</div>
        </div>

        <div class="row">
            <div class="form-group d-flex">--}}
                <label class="control-label col-sm-4" for="comision_bancaria">
                    Comision bancaria:</label>
                <input type="float" class="form-control col-sm-3" size="15" maxlength="15"
                    name="comision_bancaria" id="comision_bancaria"
                    placeholder="ddd.ddd,dd" value="{{ old('comision_bancaria') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-6" for="numero_recibo">
                    N&uacute;mero de recibo:</label>
                <input type="text" class="form-control col-sm-4" size="30" maxlength="30"
                    name="numero_recibo" id="numero_recibo"
                    placeholder="Numero del recibo"
                    value="{{ old('numero_recibo') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="asesor_captador_id">
                    *Asesor captador:</label>
                <select name="asesor_captador_id" id="asesor_captador_id">
                    {{--<option value="">Asesor oficina</option>
                    <option value="1">Pablo Caraballo</option>--}}
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        @if (old("asesor_captador_id") == $user->id)
                            selected
                        @endif
                            >{{ $user->name }}</option>
                    @endforeach
                </select>
                <label class="control-label col-sm-2" for="asesor_captador">
                    +Asesor:</label>
                <input type="text" class="form-control col-sm-5" size="40" maxlength="100"
                    name="asesor_captador" id="asesor_captador"
                    placeholder="Nombre captador otra oficina"
                    value="{{ old('asesor_captador') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="asesor_cerrador_id">
                    *Asesor cerrador:</label>
                <select name="asesor_cerrador_id" id="asesor_cerrador_id">
                    {{--<option value="">Asesor oficina</option>
                    <option value="1">Pablo Caraballo</option>--}}
                    @foreach ($users as $user)
                    @if (old("asesor_cerrador_id") == $user->id)
                    <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                    @else
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endif
                    @endforeach
                </select>
                <label class="control-label col-sm-2" for="asesor_cerrador">
                    +Asesor:</label>
                <input type="text" class="form-control col-sm-5" size="40" maxlength="100"
                    name="asesor_cerrador" id="asesor_cerrador"
                    placeholder="Nombre cerrador otra oficina"
                    value="{{ old('asesor_cerrador') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="pago_gerente">
                    Pago gerente:</label>
                <input type="text" class="form-control col-sm-4" size="40" maxlength="100"
                    name="pago_gerente" id="pago_gerente"
                    placeholder="Como se realizo pago a gerente" value="{{ old('pago_gerente') }}">
                <label class="control-label col-sm-2" for="factura_gerente">
                    Factura gerente:</label>
                <input type="text" class="form-control col-sm-3" size="40" maxlength="100"
                    name="factura_gerente" id="factura_gerente"
                    placeholder="Factura gerente" value="{{ old('factura_gerente') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="pago_asesores">
                    Pago asesores:</label>
                <input type="text" class="form-control col-sm-4" size="40" maxlength="100"
                    name="pago_asesores" id="pago_asesores"
                    placeholder="Como se realizo el pago a los asesores"
                    value="{{ old('pago_asesores') }}">
                <label class="control-label col-sm-3" for="factura_asesores">
                    Factura asesores:</label>
                <input type="text" class="form-control col-sm-3" size="40" maxlength="100"
                    name="factura_asesores" id="factura_asesores"
                    placeholder="Factura asesores" value="{{ old('factura_asesores') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="pago_otra_oficina">
                    Pago otra oficina:</label>
                <input type="text" class="form-control col-sm-10" size="50" maxlength="100"
                    name="pago_otra_oficina" id="pago_otra_oficina"
                    placeholder="Como se realizo el pago a otra oficina"
                    value="{{ old('pago_otra_oficina') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-5" for="pagado_casa_nacional">
                    *Pagado casa nacional:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="pagado_casa_nacional" id="pagado_casa_nacional"
                    {{ old('pagado_casa_nacional',
                        $cols['pagado_casa_nacional']['xdef']) ? "checked" : "" }}>
                <label class="control-label col-sm-5" for="estatus_sistema_c21">
                    *Estatus sistema C21:</label>
              <div class="form-control col-sm-3">
                <select name="estatus_sistema_c21" id="estatus_sistema_c21">
                @foreach ($cols['estatus_sistema_c21']['opcion'] as $opcion => $muestra)
                  <option value="{{$opcion}}"
                  @if (old('estatus_sistema_c21',
                        $cols['estatus_sistema_c21']['xdef']) == $opcion)
                    selected
                  @endif
                    >{{$muestra}}</option>
                @endforeach
                </select>
              </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="reporte_casa_nacional">
                    reporte casa nacional:</label>
                <input type="text" class="form-control col-sm-2"
                    name="reporte_casa_nacional" id="reporte_casa_nacional"
                    size="10" maxlength="10" placeholder="numero"
                    value="{{ old('reporte_casa_nacional') }}">
                <label class="control-label col-sm-3" for="factura_AyS">
                    Factura A&S:</label>
                <input type="text" class="form-control col-sm-4"
                    name="factura_AyS" id="factura_AyS"
                    size="30" maxlength="100" placeholder="numero de factura"
                    value="{{ old('factura_AyS') }}">
            </div>
        </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="comentarios">
                    Comentarios:</label>
                <input type="text" class="form-control col-sm-10"
                    name="comentarios" id="comentarios"
                    size="60" maxlength="600" placeholder="comentarios........."
                    value="{{ old('comentarios') }}">
            </div>
        </div>
        @else
            <input type="hidden" name="porc_franquicia" value="{{ $cols['porc_franquicia']['xdef'] }}">
            <input type="hidden" name="reportado_casa_nacional" value="{{ $cols['reportado_casa_nacional']['xdef'] }}">
            <input type="hidden" name="porc_regalia" value="{{ $cols['porc_regalia']['xdef'] }}">
            <input type="hidden" name="porc_gerente" value="{{ $cols['porc_gerente']['xdef'] }}">
            <input type="hidden" name="porc_captador_prbr" value="{{ $cols['porc_captador_prbr']['xdef'] }}">
            <input type="hidden" name="porc_cerrador_prbr" value="{{ $cols['porc_cerrador_prbr']['xdef'] }}">
            <input type="hidden" name="porc_bonificacion" value="{{ $cols['porc_bonificacion']['xdef'] }}">
            <input type="hidden" name="asesor_captador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="asesor_cerrador_id" value="{{ Auth::user()->id }}">
        @endif

        <div class="row">
            <div class="form-group d-flex">
                <button type="submit" class="btn btn-success col-sm-5">
                    Agregar propiedad
                </button>
                <a href="{{ url('/propiedades') }}" class="btn btn-link">
                    Regresar al listado de propiedades
                </a>
            </div>
        </div>
    </form>
    </div>
</div>
@endsection

@section('js')

<script>
function fnWinOnLoad() {
    //alert("Hola, desde fnWinOnLoad().")
}

function fnSometerForma() {
    var $ = document.getElementById;
    var estatus = document.getElementById('estatus');
    var aplicarPorcFranquicia = document.getElementById('aplicar_porc_franquicia');
    var aplicarBonificacion = document.getElementById('aplicar_porc_bonificacion');
    var pagadoCasaNacional = document.getElementById('pagado_casa_nacional');

    //alert(estatus.name + ':' + estatus.value + '.');
    //alert(aplicarPorcFranquicia.name + ':' + aplicarPorcFranquicia.value + '.');
    //alert(aplicarBonificacion.name + ':' + aplicar_porc_bonificacion.value + '.');
    //alert(pagadoCasaNacional.name + ':' + pagadoCasaNacional.value + '.');
}

function fnWinOnUnload() {
    alert("Hola, desde fnWinOnUnload().")
}

function alertaFechaRequerida() {
  var resValor = document.getElementById('resultado').value;
  var fecha    = document.getElementById('fecha_evento');

  if (('' == resValor) || (4 > parseInt(resValor)) || (7 < parseInt(resValor))) {
    return;
  }

  if (4 == parseInt(resValor)) {
    tipo = 'llamada';
  } else {
    tipo = 'cita';
  }

  alert("Como resultado de este contacto inicial, usted debe realizar una '" + tipo +
    "', suministre la fecha y hora de la '" + tipo + "'");
  fecha.focus();
}
function alertaFechaRequerida() {
  var resValor = document.getElementById('resultado').value;
  var fecha    = document.getElementById('fecha_evento');

  if (('' == resValor) || (4 > parseInt(resValor)) || (7 < parseInt(resValor))) {
    return;
  }

  if (4 == parseInt(resValor)) {
    tipo = 'llamada';
  } else {
    tipo = 'cita';
  }

  alert("Como resultado de este contacto inicial, usted debe realizar una '" + tipo +
    "', suministre la fecha y hora de la '" + tipo + "'");
  fecha.focus();
}
</script>

@endsection
