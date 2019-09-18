@extends('layouts.app')

@section('content')
<div class="card col-10">
    <h4 class="card-header">{{ $title }}
        [{{ $propiedad->id}}] {{ $propiedad->codigo }} 
    </h4>
    <div class="card-body">
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

        <form class="form align-items-end-horizontal" method="POST" 
                action="{{ url("/propiedades/{$propiedad->id}") }}">
            {{ method_field('PUT') }}
            {!! csrf_field() !!}
            <!-- input name="_method" type="hidden" value="PUT" -->
            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="nombre">*Nombre:</label>
                <input type="text" class="form-control col-sm-10" size="100" maxlength="150"
                    name="nombre" id="nombre" required
                    value="{{ old('nombre', $propiedad->nombre) }}">
            </div>

            <div class="form-group d-flex">
              <label class="control-label col-sm-3" for="negociacion">
                *Negociaci&oacute;n:
              </label>
              <div class="form-control col-sm-3">
                <select name="negociacion" id="negociacion">
                  <option value="">Tipo de negociacion?</option>
                @foreach (array('V' => 'Venta', 'A' => 'Alquiler') as $opcion => $muestra)
                  <option value="{{$opcion}}"
                  @if (old('negociacion', $propiedad->negociacion) == $opcion)
                    selected
                  @endif
                    >{{$muestra}}</option>
                @endforeach
                </select>
              </div>
                &nbsp;
                <label class="control-label col-sm-2" for="fecha_reserva">Reserva:</label>
                <input type="date" class="form-control col-sm-4" name="fecha_reserva"
                    id="fecha_reserva" max="{{ now()->addWeeks(4)->format('d/m/Y') }}"
                    value="{{ old('fecha_reserva',
                        ($propiedad->fecha_reserva)?$propiedad->fecha_reserva_bd:'') }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="estatus">*Estatus:</label>
              <div class="form-control col-sm-5">
                <select name="estatus" id="estatus">
                @foreach ($cols['estatus']['opcion'] as $opcion => $muestra)
                  <option value="{{$opcion}}"
                  @if (old('estatus', $propiedad->estatus) == $opcion)
                    selected
                  @endif
                    >{{$muestra}}</option>
                @endforeach
                </select>
              </div>
                &nbsp;
                <label class="control-label col-sm-2" for="fecha_firma">Firma:</label>
                <input type="date" class="form-control col-sm-3" name="fecha_firma"
                    id="fecha_firma" max="{{ now()->addWeeks(4)->format('d/m/Y') }}"
                    value="{{ old('fecha_firma',
                        ($propiedad->fecha_firma)?$propiedad->fecha_firma_bd:'') }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="precio">*Precio:</label>
                <input class="form-control col-sm-1" size="2" maxlength="2" required
                    name="moneda" id="moneda"
                    value="{{ old('moneda', $propiedad->moneda) }}" list="monedas">
                <datalist id="monedas">
                @foreach ($cols['moneda']['opcion'] as $opcion => $muestra)
                    <option value="{{ $opcion }}">
                @endforeach
                </datalist>
                <input type="float" class="form-control col-sm-3" size="20" maxlength="20"
                    name="precio" id="precio" required placeholder="Precio del inmueble"
                    value="{{ old('precio', $propiedad->precio) }}">
                <label class="control-label col-sm-2" for="comision">*Comision:</label>
                <input type="float" class="form-control col-sm-1" size="6" maxlength="6"
                    name="comision" id="comision" required
                    value="{{ old('comision', $propiedad->comision) }}">%
                <label class="control-label col-sm-2" for="iva">*IVA:</label>
                <input type="float" class="form-control col-sm-1" size="5" maxlength="5"
                    required name="iva" id="iva" value="{{ old('iva', $propiedad->iva) }}">%
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="lados">Lados:</label>
                <input type="number" class="form-control col-sm-2" size="1" maxlength="1"
                    required name="lados" id="lados"
                    value="{{ old('lados', $propiedad->lados) }}">
            </div>

            @if (Auth::user()->is_admin)
            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="porc_franquicia">
                    *Franquicia:
                </label>
                <input type="float" class="form-control col-sm-1" size="5" maxlength="5"
                    name="porc_franquicia" id="porc_franquicia" required
                    value="{{ old('porc_franquicia', $propiedad->porc_franquicia) }}">%
                {{--<label class="control-label col-sm-4" for="aplicar_porc_franquicia">
                    *Aplicar % franquicia:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_porc_franquicia" id="aplicar_porc_franquicia"
                    title="{{ $cols['aplicar_porc_franquicia']['come'] }}"
                    {{ old('aplicar_porc_franquicia',
                    $propiedad->aplicar_porc_franquicia) ? "checked" : "" }}>--}}
            <!--/div-->

            {{--<div class="form-group d-flex">
                <label class="control-label col-sm-11"
                    for="aplicar_porc_franquicia_pagar_reportada">
                    Aplicar formula con precio para franquicia a pagar reportada:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_porc_franquicia_pagar_reportada"
                    id="aplicar_porc_franquicia_pagar_reportada"
                    title="{{ $cols['aplicar_porc_franquicia_pagar_reportada']['come'] }}"
                    {{ old('aplicar_porc_franquicia_pagar_reportada',
                    $propiedad->aplicar_porc_franquicia_pagar_reportada) ? "checked" : "" }}>
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-11"
                    for="aplicar_franquicia_pagar_reportada_bruto">
                    Aplicar franquicia a pagar reportada para bruto real:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_franquicia_pagar_reportada_bruto"
                    id="aplicar_franquicia_pagar_reportada_bruto"
                    title="{{ $cols['aplicar_franquicia_pagar_reportada_bruto']['come'] }}"
                    {{ old('aplicar_franquicia_pagar_reportada_bruto',
                    $propiedad->aplicar_franquicia_pagar_reportada_bruto) ? "checked" : "" }}>
            </div>--}}

            <!--div class="form-group d-flex"-->
                <label class="control-label col-sm-4" for="reportado_casa_nacional">
                    *Reportado casa nacional:</label>
                <input type="float" class="form-control col-sm-1" size="5" maxlength="5"
                    name="reportado_casa_nacional" id="reportado_casa_nacional"
                    value="{{ old('reportado_casa_nacional',
                        $propiedad->reportado_casa_nacional) }}">%
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="porc_regalia">*Regalia:</label>
                <input type="float" class="form-control col-sm-1" size="5" maxlength="5"
                    name="porc_regalia" id="porc_regalia"
                    required value="{{ old('porc_regalia', $propiedad->porc_regalia) }}">%
                <label class="control-label col-sm-4" for="porc_gerente">
                    *Porcentaje gerente:</label>
                <input type="float" class="form-control col-sm-1" size="5" maxlength="5"
                    name="porc_gerente" id="porc_gerente" required
                    value="{{ old('porc_gerente', $propiedad->porc_gerente) }}">%
                {{--<label class="control-label col-sm-3" for="sanaf_5_porciento">
                    SANAF-5-PORCIENTO:
                </label>
                <span class="col-sm-2">{{ $propiedad->sanaf_5_porciento }}</span>--}}
                {{--<input type="float" class="form-control col-sm-2" size="18" maxlength="18"
                    name="sanaf_5_porciento" id="sanaf_5_porciento"
                    value="{{ old('sanaf_5_porciento', $propiedad->sanaf_5_porciento) }}">--}}
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="porc_captador_prbr">
                    *Porcentaje captador PRBR:</label>
                <input type="float" class="form-control col-sm-1" size="5" maxlength="5"
                    name="porc_captador_prbr" id="porc_captador_prbr" required
                    value="{{ old('porc_captador_prbr', $propiedad->porc_captador_prbr) }}">%
                {{--<label class="control-label col-sm-5" for="aplicar_porc_captador">
                    *Aplicar % captador PRBR:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_porc_captador" id="aplicar_porc_captador"
                    title="{{ $cols['aplicar_porc_captador']['come'] }}"
                    {{ old('aplicar_porc_captador',
                        $propiedad->aplicar_porc_captador) ? "checked" : "" }}>--}}
            {{--</div>

            <div class="form-group d-flex">--}}
                {{--<label class="control-label col-sm-5" for="aplicar_porc_gerente">
                    *Aplicar % gerente:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_porc_gerente" id="aplicar_porc_gerente"
                    title="{{ $cols['aplicar_porc_gerente']['come'] }}"
                    {{ old('aplicar_porc_gerente',
                        $propiedad->aplicar_porc_gerente) ? "checked" : "" }}>--}}
            {{--</div>

            <div class="form-group d-flex">--}}
                <label class="control-label col-sm-4" for="porc_cerrador_prbr">
                    *Porcentaje cerrador PRBR:</label>
                <input type="float" class="form-control col-sm-1" size="5" maxlength="5"
                    name="porc_cerrador_prbr" id="porc_cerrador_prbr" required
                    value="{{ old('porc_cerrador_prbr', $propiedad->porc_cerrador_prbr) }}">%
                {{--<label class="control-label col-sm-5" for="aplicar_porc_cerrador">
                    *Aplicar % cerrador PRBR:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_porc_cerrador" id="aplicar_porc_cerrador"
                    title="{{ $cols['aplicar_porc_cerrador']['come'] }}"
                    {{ old('aplicar_porc_cerrador',
                        $propiedad->aplicar_porc_cerrador) ? "checked" : "" }}>--}}
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-4" for="porc_bonificacion">
                    *Porcentaje bonificacion:</label>
                <input type="float" class="form-control col-sm-1" size="5" maxlength="5"
                    name="porc_bonificacion" id="porc_bonificacion" required
                    value="{{ old('porc_bonificacion', $propiedad->porc_bonificacion) }}">%
                {{--<label class="control-label col-sm-5" for="aplicar_porc_bonificacion">
                    *Aplicar % bonificacion:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="aplicar_porc_bonificacion" id="aplicar_porc_bonificacion"
                    title="{{ $cols['aplicar_porc_bonificacion']['come'] }}"
                    {{ old('aplicar_porc_bonificacion',
                        $propiedad->aplicar_porc_bonificacion) ? "checked" : "" }}>--}}
                <label class="control-label col-sm-3" for="comision_bancaria">
                    Comision bancaria:</label>
                <input type="float" class="form-control col-sm-3" size="15" maxlength="15"
                    name="comision_bancaria" id="comision_bancaria"
                    value="{{ old('comision_bancaria', $propiedad->comision_bancaria) }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="numero_recibo">
                    N&uacute;mero de recibo:</label>
                <input type="text" class="form-control col-sm-3" size="30" maxlength="30"
                    name="numero_recibo" id="numero_recibo"
                    value="{{ old('numero_recibo', $propiedad->numero_recibo) }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="asesor_captador_id">
                    *Asesor captador:</label>
                <select name="asesor_captador_id" id="asesor_captador_id">
                    {{--<option value="">Asesor oficina</option>
                    <option value="1">Pablo Caraballo</option>--}}
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                        @if (old("asesor_captador_id",
                                $propiedad->asesor_captador_id) == $user->id)
                            selected
                        @endif
                            >{{ $user->name }}</option>
                    @endforeach
                </select>
                <label class="control-label col-sm-2" for="asesor_captador">
                    +Asesor:</label>
                <input type="text" class="form-control col-sm-5" size="40" maxlength="100"
                    name="asesor_captador" id="asesor_captador"
                    value="{{ old('asesor_captador', $propiedad->asesor_captador) }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="asesor_cerrador_id">
                    *Asesor cerrador:</label>
                <select name="asesor_cerrador_id" id="asesor_cerrador_id">
                    {{--<option value="">Asesor oficina</option>
                    <option value="1">Pablo Caraballo</option>--}}
                    @foreach ($users as $user)
                        @if (old("asesor_cerrador_id",
                                $propiedad->asesor_cerrador_id) == $user->id)
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
                    value="{{ old('asesor_cerrador', $propiedad->asesor_cerrador) }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="pago_gerente">
                    Pago gerente:</label>
                <input type="text" class="form-control col-sm-4" size="40" maxlength="100"
                    name="pago_gerente" id="pago_gerente"
                    value="{{ old('pago_gerente', $propiedad->pago_gerente) }}">
                <label class="control-label col-sm-3" for="factura_gerente">
                    Factura gerente:</label>
                <input type="text" class="form-control col-sm-3" size="40" maxlength="100"
                    name="factura_gerente" id="factura_gerente"
                    value="{{ old('factura_gerente', $propiedad->factura_gerente) }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-2" for="pago_asesores">
                    Pago asesores:</label>
                <input type="text" class="form-control col-sm-4" size="40" maxlength="100"
                    name="pago_asesores" id="pago_asesores"
                    value="{{ old('pago_asesores', $propiedad->pago_asesores) }}">
                <label class="control-label col-sm-3" for="factura_asesores">
                    Factura asesores:</label>
                <input type="text" class="form-control col-sm-3" size="40" maxlength="100"
                    name="factura_asesores" id="factura_asesores"
                    value="{{ old('factura_asesores', $propiedad->factura_asesores) }}">
            </div>

        <div class="row">
            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="pago_otra_oficina">
                    Pago otra oficina:</label>
                <input type="text" class="form-control col-sm-10" size="50" maxlength="100"
                    name="pago_otra_oficina" id="pago_otra_oficina"
                    placeholder="Como se realizo el pago a otra oficina"
                    value="{{ old('pago_otra_oficina', $propiedad->pago_otra_oficina) }}">
            </div>
        </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="pagado_casa_nacional">
                    *Pagado casa nacional:</label>
                <input type="checkbox" class="form-control col-sm-1"
                    name="pagado_casa_nacional" id="pagado_casa_nacional"
                    {{ old('pagado_casa_nacional',
                        $propiedad->pagado_casa_nacional) ? "checked" : "" }}>
                <label class="control-label col-sm-3" for="estatus_sistema_c21">
                    *Estatus sistema C21:</label>
              <div class="form-control col-sm-3">
                <select name="estatus_sistema_c21" id="estatus_sistema_c21">
                  <option value="">Estatus?</option>
                @foreach (array('V' => 'Vendido', 'P' => 'Pendiente') as $opcion => $muestra)
                  <option value="{{$opcion}}"
                  @if (old('estatus_sistema_c21',
                        $propiedad->estatus_sistema_c21) == $opcion)
                    selected
                  @endif
                    >{{$muestra}}</option>
                @endforeach
                </select>
              </div>
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="reporte_casa_nacional">
                    reporte casa nacional:</label>
                <input type="text" class="form-control col-sm-2" size="10" maxlength="10"
                    name="reporte_casa_nacional" id="reporte_casa_nacional"
                    value="{{ old('reporte_casa_nacional',
                                $propiedad->reporte_casa_nacional) }}">
                <label class="control-label col-sm-3" for="factura_AyS">
                    Factura A&S:</label>
                <input type="text" class="form-control col-sm-4"
                    size="30" maxlength="100" name="factura_AyS" id="factura_AyS"
                    value="{{ old('factura_AyS', $propiedad->factura_AyS) }}">
            </div>

            <div class="form-group d-flex">
                <label class="control-label col-sm-3" for="comentarios">
                    Comentarios:</label>
                <input type="text" class="form-control col-sm-8"
                    name="comentarios" id="comentarios" size="50" maxlength="600"
                    value="{{ old('comentarios', $propiedad->comentarios) }}">
            </div>
            @else
                <input type="hidden" name="porc_franquicia" value="{{ $propiedad->porc_franquicia }}">
                <input type="hidden" name="reportado_casa_nacional" value="{{ $propiedad->reportado_casa_nacional }}">
                <input type="hidden" name="porc_regalia" value="{{ $propiedad->porc_regalia }}">
                <input type="hidden" name="porc_gerente" value="{{ $propiedad->porc_gerente }}">
                <input type="hidden" name="porc_captador_prbr" value="{{ $propiedad->porc_captador_prbr }}">
                <input type="hidden" name="porc_cerrador_prbr" value="{{ $propiedad->porc_cerrador_prbr }}">
                <input type="hidden" name="porc_bonificacion" value="{{ $propiedad->porc_bonificacion }}">
                <input type="hidden" name="asesor_captador_id" value="{{ $propiedad->asesor_captador_id }}">
                <input type="hidden" name="asesor_cerrador_id" value="{{ $propiedad->asesor_cerrador_id }}">
                <input type="hidden" name="estatus_sistema_c21" value="{{ $propiedad->estatus_sistema_c21 }}">
            @endif

            <div class="form-group d-flex">
                <button type="submit" class="btn btn-primary col-sm-5">
                    Actualizar Propiedad
                </button>
                <!-- a href="{{ action('PropiedadController@index') }}">Regresar al listado de usuarios</a -->
                <a href="{{ url('/propiedades') }}" class="btn btn-link">
                    Regresar al listado de propiedades
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
