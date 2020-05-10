@extends('layouts.app')

@section('content')
<div class="card">
@if (isset($alertar))
@if (0 < $alertar)
    <script>alert("Le fue enviado el correo con el 'Reporte de Cierre' de esta propiedad.");</script>
@elseif (0 > $alertar)
    <script>alert("No fue enviado el correo con el 'Reporte de Cierre' de esta propiedad. Probablemente, problemas con Internet! Revise su conexión");</script>
@endif ('S' == $alertar)
@endif (isset($alertar))
    <h4 class="card-header m-0 p-0 {{ $propiedad->estatus_color }}">
        Propiedad:
        [{{ $propiedad->id}}]{{ $propiedad->codigo }} 
        {{ substr($propiedad->nombre, 0, 30) }}
        {{ $propiedad->negociacion_alfa }}.
        {{ $propiedad->precio_ven }}
        @if (1 < $propiedad->user_id)
            ([{{ $propiedad->user_id }}] {{ substr($propiedad->user->name, 0, 20) }})
        @endif
    </h4>
    <div class="card-body my-0 mx-2 py-0 px-2">
    	<div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Estatus del Inmueble:<span class="alert-info">{{ $propiedad->estatus_alfa }}</span>
            </div>
            <div class="mx-1 px-2">
                Exclusividad:<span class="alert-info">{{ ($propiedad->exclusividad)?'Si':'No' }}</span>
            </div>
            <div class="mx-1 px-2">
                Fecha inicial:<span class="alert-info">{{ $propiedad->fec_ini }}</span>
            </div>
        @if (0 < count($propiedad->imagenes))
            <div class="ml-2 p-0">
                <button type="button"
                        class="btn btn-{{ substr($propiedad->estatus_color, strpos($propiedad->estatus_color, '-')+1) }}
                            btn-sm my-0 mx-1 p-0"
                        nombreBase="{{ $propiedad->id }}_{{ $propiedad->codigo }}"
                        img="{{ json_encode($propiedad->imagenes, JSON_FORCE_OBJECT) }}"
                        id="mostrarfotos" title="Presione para mostrar/esconder las fotos de la propiedad">
                    Mostrar fotos
                </button>
            </div>
            <div class="ml-2 p-0">
                <button type="button"
                        class="btn btn-{{ substr($propiedad->estatus_color, strpos($propiedad->estatus_color, '-')+1) }}
                            btn-sm my-0 mx-1 p-0"
                        nombreBase="{{ $propiedad->id }}_{{ $propiedad->codigo }}"
                        img="{{ json_encode($propiedad->imagenes, JSON_FORCE_OBJECT) }}"
                        id="mostrarcarrusel" title="Presione para mostrar/esconder las fotos de la propiedad">
                    Mostrar Carrusel de fotos
                </button>
            </div>
            <input type="hidden" id="nombre-{{ $propiedad->id }}" value="{{ $propiedad->nombre }}">
        @endif (0 < count($propiedad->imagenes))
        </div>

    	<div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Fecha de Reserva:<span class="alert-info">{{ $propiedad->fec_res }}</span>
            </div>
            <div class="mx-1 px-2">
                Forma de pago:<span class="alert-info">
                    {{ $propiedad->forma_pago_reserva->descripcion }}
                </span>
            </div>
            <div class="mx-1 px-2">
                Factura:<span class="alert-info">{{ $propiedad->factura_reserva }}</span>
            </div>
        </div>

    	<div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Fecha de la firma:<span class="alert-info">{{ $propiedad->fec_fir }}</span>
            </div>
            <div class="mx-1 px-2">
                Forma de pago:<span class="alert-info">
                    {{ $propiedad->forma_pago_firma->descripcion }}
                </span>
            </div>
            <div class="mx-1 px-2">
                Factura:<span class="alert-info">{{ $propiedad->factura_firma }}</span>
            </div>
        </div>

    	<div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Comisi&oacute;n:<span class="alert-info">{{ $propiedad->comision_p }}</span>
            </div>
            <div class="mx-1 px-2">
                Reserva sin IVA:<span class="alert-info">{{ $propiedad->reserva_sin_iva_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Reserva con IVA:<span class="alert-info">{{ $propiedad->reserva_con_iva_ven }}</span>
                <span class="alert-info">(IVA:{{ $propiedad->iva_p }})</span>
            </div>
        </div>

    	<div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Lados:<span class="alert-info">{{ $propiedad->lados }}</span>
            </div>
            <div class="mx-1 px-2">
                Compartido con otra oficina sin IVA:<span class="alert-info">
                {{ $propiedad->compartido_sin_iva_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Compartido con otra oficina con IVA:<span class="alert-info">
                {{ $propiedad->compartido_con_iva_ven }}</span>
            </div>
        </div>

        @if (!Auth::user()->is_admin)
        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Asesor captador: <span class="alert-info">
                    {{ ('1' == $propiedad->asesor_captador_id)?
                                ($propiedad->asesor_captador??'?'):
                                $propiedad->captador->name }}</span>
                <span class="alert-info">{{ ($propiedad->captador_prbr_ven) }}</span>
            </div>
            <div class="mx-1 px-2">
                Asesor cerrador: <span class="alert-info">
                    {{ ('1' == $propiedad->asesor_cerrador_id)?
                                ($propiedad->asesor_cerrador??'?'):
                                $propiedad->cerrador->name }}</span>
                <span class="alert-info">{{ ($propiedad->cerrador_prbr_ven) }}</span>
            </div>
        </div>
        @endif (!Auth::user()->is_admin)

    @if (0 < count($propiedad->imagenes))
        <div id="fotosestaticas">
        </div>
    @endif (0 < count($propiedad->imagenes))

        <!--fieldset class="datosPropiedad" style="border:solid 2px #000000">
            <legend>
                <button id="datosPropiedad" title="Presione para mostrar/esconder los datos de la propiedad">
                    Datos de la propiedad
                </button>
            </legend-->
        <div class="row mt-2 mb-0 px-0 border-top border-dark">
            <div class="mx-1 px-2">
                Tipo:<span class="alert-info">{{ $propiedad->tipo->descripcion }}</span>
            </div>
            <div class="mx-1 px-2">
                Metraje:<span class="alert-info">{{ $propiedad->metraje }}</span>
            </div>
            <div class="mx-1 px-2">
                Habitaciones:<span class="alert-info">{{ $propiedad->habitaciones }}</span>
            </div>
            <div class="mx-1 px-2">
                Ba&ntilde;os:<span class="alert-info">{{ $propiedad->banos }}</span>
            </div>
        </div>

        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Niveles:<span class="alert-info">{{ $propiedad->niveles }}</span>
            </div>
            <div class="mx-1 px-2">
                Puestos de estacionamiento:<span class="alert-info">{{ $propiedad->puestos }}</span>
            </div>
            <div class="mx-1 px-2">
                A&ntilde;o de construccion:<span class="alert-info">{{ $propiedad->anoc }}</span>
            </div>
            <div class="mx-1 px-2">
                Caracter&iacute;sticas:<span class="alert-info">{{ $propiedad->caracteristica->descripcion }}</span>
            </div>
        </div>

        <div class="row my-0 py-0">
            <div class="col-lg-12 mx-1 px-2">
                Descripci&oacute;n:<span class="alert-info">{{ $propiedad->descripcion }}</span>
            </div>
        </div>

        <div class="row my-0 py-0 bg-suave">
            <div class="col-lg-12 mx-1 px-2">
                Direcci&oacute;n:<span class="alert-info">{{ $propiedad->direccion }}</span>
            </div>
        </div>

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Ciudad:
                <span class="alert-info">{{ $propiedad->ciudad->descripcion }}</span>
            </div>
            <div class="mx-1 px-2">
                Codigo postal:
                <span class="alert-info">{{ $propiedad->codigo_postal }}</span>
            </div>
            <div class="mx-1 px-2">
                Municipio:
                <span class="alert-info">{{ $propiedad->municipio->descripcion }}</span>
            </div>
            <div class="mx-1 px-2">
                Estado:
                <span class="alert-info">{{ $propiedad->estado->descripcion }}</span>
            </div>
        </div>

        <div class="row my-0 py-0 bg-suave border-bottom border-dark">
            <div class="mx-1 px-2">
                Cliente</label>
                <span class="alert-info">{{ $propiedad->cliente->name }}</span>
            </div>
            <div class="mx-1 px-2">
                Telefono del cliente:<span class="alert-info">
                    {{ $propiedad->cliente->telefono_f }}
                </span>
            </div>
            <div class="mx-1 px-2">
                Correo del cliente:<span class="alert-info">
                    {{ $propiedad->cliente->email }}
                </span>
            </div>
        </div>
        <!--/fieldset-->

    @if (Auth::user()->is_admin)
        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Reportado a casa nacional:
                <span class="alert-info">{{ $propiedad->reportado_casa_nacional_p }}</span>
            </div>
            <div class="mx-1 px-2">
                Franquicia de reserva sin IVA:
                <span class="alert-info">{{ $propiedad->franquicia_reservado_sin_iva_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Franquicia de reserva con IVA:
                <span class="alert-info">{{ $propiedad->franquicia_reservado_con_iva_ven }}</span>
            </div>
        </div>

        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Franquicia a pagar reportada: <span class="alert-info">
                    {{ $propiedad->franquicia_pagar_reportada_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Regalia:<span class="alert-info">{{ $propiedad->regalia_ven }}
                    ({{ $propiedad->porc_regalia_p }})</span>
            </div>
            <div class="mx-1 px-2">
                SANAF 5%: <span class="alert-info">
                    {{ $propiedad->sanaf_5_por_ciento_ven }}</span>
            </div>
        </div>

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Oficina bruto real:<span class="alert-info">
                    {{ $propiedad->oficina_bruto_real_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Base para honorarios socios: <span class="alert-info">
                    {{ $propiedad->base_honorarios_socios_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Base para honorarios: <span class="alert-info">
                    {{ $propiedad->base_para_honorarios_ven }}</span>
            </div>
        </div>

        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Bonificaciones:<span class="alert-info">{{ $propiedad->bonificaciones_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Comisi&oacute;n bancaria descontada:
                <span class="alert-info">{{ $propiedad->comision_bancaria_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Ingreso neto a oficina:<span class="alert-info">
                    {{ $propiedad->ingreso_neto_oficina_ven }}</span>
            </div>
        </div>

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Pago Asesores: <span class="alert-info">{{ $propiedad->pago_asesores }}</span>
            </div>
            <div class="mx-1 px-2">
                Fact.: <span class="alert-info">
                    {{ ($propiedad->factura_asesores)?$propiedad->factura_asesores:'?' }}</span>
            </div>
        </div>

        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Asesor captador: <span class="alert-info">
                    {{ ('1' == $propiedad->asesor_captador_id)?
                                ($propiedad->asesor_captador??'?'):
                                $propiedad->captador->name }}</span>
                    [{{ $propiedad->porc_captador_prbr_p }}]
                <span class="alert-info">{{ ($propiedad->captador_prbr_ven) }}</span>
            </div>
            <div class="mx-1 px-2">
                Fecha pago:<span class="alert-info">{{ $propiedad->fec_cap }}</span>
            </div>
            <div class="mx-1 px-2">
                Forma de pago:<span class="alert-info">
                    {{ $propiedad->forma_pago_captador->descripcion }}
                </span>
            </div>
        </div>

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Factura:<span class="alert-info">{{ $propiedad->factura_captador }}</span>
            </div>
        </div>

        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Asesor cerrador: <span class="alert-info">
                    {{ ('1' == $propiedad->asesor_cerrador_id)?
                                ($propiedad->asesor_cerrador??'?'):
                                $propiedad->cerrador->name }}</span>
                    [{{ $propiedad->porc_cerrador_prbr_p }}]
                <span class="alert-info">{{ ($propiedad->cerrador_prbr_ven) }}</span>
            </div>
            <div class="mx-1 px-2">
                Fecha pago:<span class="alert-info">{{ $propiedad->fec_cer }}</span>
            </div>
            <div class="mx-1 px-2">
                Forma de pago:<span class="alert-info">
                    {{ $propiedad->forma_pago_cerrador->descripcion }}
                </span>
            </div>
        </div>

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Factura:<span class="alert-info">{{ $propiedad->factura_cerrador }}</span>
            </div>
        </div>

        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Gerente:[{{ $propiedad->porc_gerente_p }}]<span class="alert-info">
                    {{ $propiedad->gerente_ven }}</span>
            </div>
            <div class="mx-1 px-2">
                Fecha pago:<span class="alert-info">{{ $propiedad->fec_ger }}</span>
            </div>
            <div class="mx-1 px-2">
                Forma de pago:<span class="alert-info">
                    {{ $propiedad->forma_pago_gerente->descripcion }}
                </span>
            </div>
        </div>

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Factura:
                <span class="alert-info">
                    {{ $propiedad->factura_gerente }}
                </span>
            </div>
        </div>

    @if (1 == $propiedad->lados)
        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Pago Otra oficina:<span class="alert-info">{{ $propiedad->pago_otra_oficina }}</span>
            </div>
            <div class="mx-1 px-2">
                Fecha pago:<span class="alert-info">{{ $propiedad->fec_otr }}</span>
            </div>
            <div class="mx-1 px-2">
                Forma de pago:<span class="alert-info">
                    {{ $propiedad->forma_pago_otra_oficina->descripcion }}
                </span>
            </div>
        </div>

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Factura:
                <span class="alert-info">
                    {{ $propiedad->factura_otra_oficina }}
                </span>
            </div>
        </div>
    @endif

        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Recibo No.:<span class="alert-info">
                    {{ ($propiedad->numero_recibo)?$propiedad->numero_recibo:'?' }}</span>
            </div>
            <div class="mx-1 px-2">
                Pago Gerente:<span class="alert-info">{{ $propiedad->pago_gerente }}</span>
            </div>
            <!--div class="mx-1 px-2">
                Fact.:<span class="alert-info">
                    {{ ($propiedad->factura_gerente)?$propiedad->factura_gerente:'?' }}</span>
            </div-->
        </div>

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Pagado Casa Nacional: <span class="alert-info">
                    {{ ($propiedad->pagado_casa_nacional)?'Si':'No' }}</span>
            </div>
            <div class="mx-1 px-2">
                Estatus sistema C21: <span class="alert-info">
                    {{ $propiedad->estatus_c21_alfa }}</span>
            </div>
            <div class="mx-1 px-2">
                Reporte casa nacional: <span class="alert-info">
                    {{ $propiedad->reporte_casa_nacional }}</span>
            </div>
        </div>

    @if ($propiedad->factura_AyS)
        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Factura A & S: <span class="alert-info">{{ $propiedad->factura_AyS }}</span>
            </div>
        </div>
    @endif

        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Comentarios: <span class="alert-info">{{ $propiedad->comentarios }}</span>
            </div>
        </div>

    @if (null != $propiedad->user_borro)
        <div class="row my-0 py-0 bg-suave">
            <div class="mx-1 px-2">
                Esta propiedad fue borrada por {{ $propiedad->userBorro->name }} el
                {{ $propiedad->borrado_dia_semana }}
                {{ $propiedad->borrado_con_hora }}.
            </div>
        </div>

    @endif
    @if ((null != $propiedad->user_actualizo) and (1 < $propiedad->user_actualizo))
        <div class="row my-0 py-0">
            <div class="mx-1 px-2">
                Esta propiedad fue actualizada por {{ $propiedad->userActualizo->name }} el
                {{ $propiedad->actualizado_dia_semana }}
                {{ $propiedad->actualizado_con_hora }}.
            </div>
        </div>

    @endif
    @endif

    <p>
        <!-- a href="{{ action('PropiedadController@index') }}">Regresar al listado de propiedad</a -->
    @if ((isset($col_id)) and ('' != $col_id))
        <a href="{{ route($rutRetorno, [$propiedad[$col_id], $orden]).$nroPagina }}"
            class="btn btn-link">
    @else
        <a href="{{ route($rutRetorno, $orden).$nroPagina }}" class="btn btn-link">
    @endif
            <button class="btn btn-primary" title="Ir a la lista de propiedades">
                Ir a la lista de propiedades
            </button>
        </a>
    @if ((('P' == $propiedad->estatus) || ('C' == $propiedad->estatus)) &&
            (isset($propiedad->fecha_reserva) && isset($propiedad->fecha_firma)))
        <a href="{{ route('propiedad.correo', [$propiedad->id, 2]) }}" class="btn btn-link"
                title="Enviar correo de 'Reporte de Cierre' a '{{ (1 == $propiedad->user->id)?'Administrador':$propiedad->user->name }}' sobre esta propiedad ({{ $propiedad->codigo }}, {{ $propiedad->nombre }}).">
            Reporte de Cierre
        </a>
    @endif (('P' == $propiedad->estatus) || ('C' == $propiedad->estatus))
    </p>
    </div>
</div>
@endsection

@section('js')

<script>
    @includeIf('include.alertar')
    $(document).ready(function() {
        $("#fotosestaticas").hide();
        $("#mostrarfotos").click(function(ev) {
            ev.preventDefault();
            const that = $(this);
            if (!$("#fotosestaticas").hasClass("row")) {
                const nombreBase = that.attr('nombreBase'); // Nombre base sin secuencia, ni extension.
                const arreglo = nombreBase.split('_');      // id_codigo
                const id = arreglo[0];
                const codigo = arreglo[1];         // codigo de la propiedad.
                const nombre = $("#nombre-"+id).val();         // Nombre de la propiedad.
                const img = JSON.parse(that.attr('img'));       // extensiones de imagenes. Se pudo usar: $.parseJSON(...)
                const long = Object.keys(img).length;
                let divHtml = `<div class="row ximagen bg-transparent m-0 p-0">`;
                for (const i in img) {
                    divHtml += `<img class="img-fluid m-0 p-0 imagenPropiedad"
                            src="{{ asset('storage/imgprop/') }}/${nombreBase}-${i}.${img[i]}"
                            style="height:300px;">`;
                }
                divHtml += `</div>`;
                $("#fotosestaticas").html(divHtml);
                $("#fotosestaticas").addClass("row bg-transparent border-top border-dark m-0 p-0");
            }
            $("#fotosestaticas").toggle();
            if ($("#fotosestaticas").is(":hidden")) {
                $("#mostrarfotos").text("Mostrar fotos")
            } else {
                $("#mostrarfotos").text("Ocultar fotos")
            }
        });
        $("#mostrarcarrusel").click(function(ev) {
            ev.preventDefault();
            const that = $(this);
            const nombreBase = that.attr('nombreBase'); // Nombre base sin secuencia, ni extension.
            const arreglo = nombreBase.split('_');      // id_codigo
            const id = arreglo[0];
            const codigo = arreglo[1];         // codigo de la propiedad.
            const nombre = $("#nombre-"+id).val();         // Nombre de la propiedad.
            const img = JSON.parse(that.attr('img'));       // extensiones de imagenes. Se pudo usar: $.parseJSON(...)
            const long = Object.keys(img).length;
            let activar = true;
            let msjHtml = `<div id="miCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
                            <div class="carousel-inner">`;
            for (const i in img) {
                msjHtml += `<div class="carousel-item ` + ((activar)?'active':'') + `">
                                    <img class="img-fluid imagenPropiedad"
                                        src="{{ asset('storage/imgprop/') }}/${nombreBase}-${i}.${img[i]}"
                                        style="height:300px;">
                                </div>`;
                if (activar) activar = false;
            }
            msjHtml += `</div>`;
            if (1 < long)
                msjHtml += `<a class="carousel-control-prev" href="#miCarousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Anterior</span>
                                </a>
                            <a class="carousel-control-next" href="#miCarousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Próximo</span>
                            </a>`;
            msjHtml += `</div>`;
            const titulo = `${id}) ${codigo} ${nombre}`;
            alertar( msjHtml, titulo, 'large')
        });
    })
</script>

@endsection
