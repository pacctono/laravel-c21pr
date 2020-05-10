<script>
    $(function () {
        //$('[data-toggle="tooltip"]').tooltip('enable')
        $("td.mostrarTooltip").tooltip('enable')
        $("a.mostrarTooltip").tooltip('enable')
        $("span.mostrarTooltip").tooltip('enable')
    });
    $.fn.renombrar = function(nombAtrNuevo, nombAtrActual) {
        let $t = $(this);
        $t.attr(nombAtrNuevo, $t.attr(nombAtrActual));
        $t.removeAttr(nombAtrActual);
    }
    @includeIf('include.alertar')
    @includeIf('include.confirmar')
    var botones = {
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirmar',
                    },
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancelar',
                    }
                };
    $(document).ready(function() {
    @includeWhen((!$movil) and (!isset($accion) or ('html' == $accion)), 'propiedades.ajax')

        function promptFecha(cd, nb, fecha, tipoFecha='reserva', cfecha='', funcion=false) {
            bootbox.prompt({
                size: 'small',
                title: `Cambiar la fecha de ${tipoFecha} ${cfecha}de (${cd}) ${nb}.`,
                inputType: 'date',
                value: fecha,
                buttons: botones,
                callback: function(res) {   // Si se cancela o cierra la ventana devuelve null (res=null).
                    funcion(res);
                }
            })
        }
        function cambiarConAjax(that, id, col, res, tipoCol) {
            //let data = {};
            //data['id'] = id;
            //data[col] = res;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('propiedades.post.actualizar') }}",
                //data: data,
                data: {id: id, [col]: res},
                success: function(data, estatus, jq) {
                    alertar(data.success, `Cambio de ${tipoCol}`, 'small');
/*                    alertar(`responseText:${jq.responseText}`,
                            `data:${data}, Estatus:${estatus},
                                readyState:${jq.readyState}, status:${jq.status}`,
                            'extra-large'
                    );*/
                    that.html(data.nuevoValor);
                },
                error: function(jq, estatus, error) {
                    bootbox.dialog({
                        size: 'extra-large',
                        title: `No se pudo actualizar ${tipoCol}:
                                Estatus:${estatus}, Error:${error}`,
                        message: `readyState:${jq.readyState}, status:${jq.status},
                                responseText:${jq.responseText}`,
                        onEscape: true,
                        backdrop: true,
                        scrollable: true,
                        buttons: false
                    })
                }
            });
        }

    @if ((Auth::user()->is_admin) and isset($propRepetidas) and ($propRepetidas->isNotEmpty()))
        var valCodigo, linea = '';
        @foreach ($propRepetidas as $pr)
        @if (0 == $loop->index)
        valCodigo = '{{ $pr->codigo }}';
        var pie = "<br>El primer codigo ({{ $pr->codigo }}, id={{ $pr->id }}), esta colocado " +
                    "en el campo 'codigo' a la izquierda. Solo presione el boton 'Mostrar', " +
                    "para visualizar.";

        @endif (0 == $loop->index)
        linea += '{{ $pr->id }}) {{ $pr->codigo }} - {{ $pr->nombre }}<br>';
        @endforeach ($propRepetidas as $pr)
        alertar(linea + pie, 'Existen propiedades REPETIDAS', 'large', function() {
                                                        $('#vmCodigo').val(valCodigo);
                                                    });
    @endif ((Auth::user()->is_admin) and isset($propRepetidas) and ($propRepetidas->isNotEmpty()))

    @if (!$movil and (!isset($accion) or ('html' == $accion)) and isset($alertar))
    @if (0 < $alertar)
        alertar("Le fue enviado el correo con el 'Reporte de Cierre' de la propiedad.");
    @elseif (0 > $alertar)
        alertar("No fue enviado el correo con el 'Reporte de Cierre' de la propiedad. " +
                "Probablemente, problemas con Internet! Revise su conexión");
    @endif (0 < $alertar)
    @endif (!$movil and (!isset($accion) or ('html' == $accion)))

    @if (Auth::user()->is_admin)
        $("td.codigo").click(function(ev) {
            const that = $(this);
            const id = that.attr('id').split('-')[0]; // 'id' de la propiedad.
            const codigo = that.attr('id').split('-')[1];   // 'codigo' de la propiedad.
            const ap = aPropiedades[id];
            bootbox.prompt({
                size: 'small',
                title: `Cambiar el codigo de (${ap.cd}) ${ap.nb}.`,
                inputType: 'text',
                value: codigo,
                buttons: botones,
                callback: function(res) {
                    if (res) {
                        if (res == codigo) return;
                        let codNuevo = res;
                        var ac = aCodigos[codNuevo];
                        if (ac) {
                            if (undefined == ac.id) {
                                alert(`Algo extraño paso. Reportelo. Esto no deberia ocurrir, id:${ac.id}`);
                                return;
                            }
                            var ap = aPropiedades[ac.id];
                            if (ap) {
                                var st = estatus[ap.st];
                                var ng = negociaciones[ap.ng];
                            }
                        }   // if (ac)
                        if (ac && ap && st && ng) {
                            alertar(`Otra propiedad con este mismo codigo: <b>${codNuevo}</b>, ` +
                                    `fue creada por <u>${asesores[ap.uid]}</u> ` +
                                    `el <u>${ap.fc}</u> a las <u>${ap.ho}</u>; con nombre: ` +
                                    `<u>${ap.nb}</u> y actualmente, tiene el estatus: ` +
                                    `<u>${st}</u> como una negociacion de <u>${ng}</u>.`);
                            return;
                        }   // if (ac && ap && st && ng)
                        /*nvoUrl = `/propiedades/actualizar/${id}/codigo/${codNuevo}`;
                        location.href = nvoUrl;*/
                        cambiarConAjax(that, id, 'codigo', codNuevo, 'el codigo');
                    }   // if (res)
                }   // callback: function(res)
            });     //  bootbox.prompt
        });     // FIN cambio de codigo.
        $("td.estatus").click(function(ev) {
            const that = $(this);
            const id = that.attr('id').split('-')[0]; // 'id' de la propiedad.
            const status = that.attr('id').split('-')[1]; // 'estatus' de la propiedad.
            let aEstatus = [], i = 0;
            for (let k in estatus) {
                aEstatus[i++] = {
                    text: estatus[k],
                    value: k
                }
            }
            const ap = aPropiedades[id];
            bootbox.prompt({
                size: 'small',
                title: `Cambiar estatus (${estatus[status]}) de (${ap.cd}) ${ap.nb}.`,
                inputType: 'select',
                value: status,
                inputOptions: aEstatus,
                buttons: botones,
                callback: function(res) {
                    if (res) {
                        nvoUrl = `/propiedades/actualizar/${id}/estatus/${res}`;
                        location.href = nvoUrl;
                    }
                }
            });
        });     // FIN cambio de estatus.
        $("td.reserva").click(function(ev) {
            const that = $(this);
            const id = that.attr('id').split('.')[0]; // 'id' de la propiedad.
            const reserva = that.attr('id').split('.')[1].trim(); // 'fecha_reserva' de la propiedad.
            const creserva = ('' != reserva)?`(${reserva}) `:'';
            const ap = aPropiedades[id];
            if (ap) promptFecha(ap.cd, ap.nb, reserva, 'reserva', creserva, function(res) {
                    if ((res) || ('' == res)) {
                        cambiarConAjax(that, id, 'fecha_reserva', res, 'la fecha de reserva');
                    }
                }
            );
        });     // FIN cambio fecha de reserva.
        $("td.firma").click(function(ev) {
            const that = $(this);
            const id = that.attr('id').split('.')[0]; // 'id' de la propiedad.
            const firma = that.attr('id').split('.')[1].trim(); // 'fecha_firma' de la propiedad.
            const cfirma = ('' != firma)?`(${firma}) `:'';
            const ap = aPropiedades[id];
            if (ap) promptFecha(ap.cd, ap.nb, firma, 'la firma', cfirma, function(res) {
                    if ((res) || ('' == res)) {
                        cambiarConAjax(that, id, 'fecha_firma', res, 'la fecha de la firma');
                    }
                }
            );
        });     // FIN cambio fecha de la firma.
        $("td.nombre").click(function(ev) {
            const id = $(this).attr('id').substring(7);
            const cd = $("#"+id+"-codigo").text();
            const nb = $(this).text();
            const titulo = `${id}) ${cd} - ${nb}`;
            const fecres = $("#"+id+"-fecres").text();
            const fecfir = $("#"+id+"-fecfir").text();
            const precio = $("#"+id+"-precio").text();
            const lados  = $("#"+id+"-lados").text();
            var ap = aPropiedades[id];
            let st='', ng='', fi='', acp='', acr='', dsc='', com='';
            if (ap) {
                st = estatus[ap.st];
                ng = negociaciones[ap.ng];
                fi = ap.fi;
                acp = (isNaN(ap.acp))?ap.acp:(1 == ap.acp)?'Sin asignar':asesores[ap.acp];
                acr = (isNaN(ap.acr))?ap.acr:(1 == ap.acr)?'Sin asignar':asesores[ap.acr];
                dsc = ap.dsc;
                com = ap.com;
            }
            const texto  = `<em>Fecha inicial:</em> <b>${fi}</b> <em>Reserva:</em> <b>${fecres}</b> <em>Firma:</em> <b>${fecfir}</b><br>` +
                            `<em>Precio:</em> <b>${precio}</b> <em>Lados:</em> <b>${lados}</b><br>` +
                            `<em>Estatus:</em> <b>${st}</b> <em>Negociacion:</em> <b>${ng}</b><br>` +
                            `<em>Asesor captador:</em> <b>${acp}</b> <em>cerrador:</em> <b>${acr}</b><br>` +
                            `<em>Descripcion:</em> <u>${dsc}</u><br>` +
                            `<em>Comentarios:</em> <u>${com}</u>.`;
            alertar(texto, titulo);
        });     // Click en nombre.
    @endif (Auth::user()->is_admin)
    @if (Auth::user()->is_admin)
        $("td.observacion,td.precio,td.lados,td.franquicia,td.sanaf,td.neto").click(function(ev) {
    @else (Auth::user()->is_admin)
        $("td.codigo").click(function(ev) {
    @endif (Auth::user()->is_admin)
            let $t = $(this);
            if (undefined === $t.attr('title')) {
                //$t.attr('title', $t.attr('titulo'));
                //$t.removeAttr('titulo');
                $t.renombrar('title', 'titulo');    // Mi propia funcion 'renombrar'. Definida al principio de este archivo.
                $t.tooltip('enable');           // Trate  $("td.precio").tooltip('enable'), al principio y no funciono.
                $t.tooltip('show');           // Trate  $("td.precio").tooltip('enable'), al principio y no funciono.
            } else {
                //$t.attr('titulo', $t.attr('title'));
                //$t.removeAttr('title');
                $t.renombrar('titulo', 'title');    // Mi propia funcion 'renombrar'. Definida al principio de este archivo.
                $t.tooltip('disable');          // Trate $("td.precio").tooltip('enable'), al principio y no funciono.
            }
        });
        $("a.cargarimagen").click(function(ev) {
            ev.preventDefault();
            const that = $(this);
            const id = that.attr('idprop'); // 'id' de la propiedad.
            const ap = aPropiedades[id];
            if (!ap) return
            const msjHtml = `<div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="{{ route('carga.imagen.propiedad') }}" id="formacargar"
                                                    class="form align-items-horizontal"
                                                    method="POST" enctype="multipart/form-data">
                                                {!! csrf_field() !!}
                                                <div class="form-group form-inline m-0 py-0 px-1">
                                                    <input type="file" class="form-control" name="imagen">
                                                    <input type="hidden" name="id" value="${id}">
                                                    <input type="hidden" name="codigo" value="${ap.cd}">
                                                    <input type="hidden" name="captador" value="${ap.acp}">

                                                    <button class="btn btn-success">Cargar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
            var dialogo = bootbox.dialog({
                size: 'large',
                title: `Cargar imagen de (${ap.cd}) ${ap.nb}.`,
                message: msjHtml,
                onEscape: true,
                backdrop: true,
                buttons: false,
            });
            $("#formacargar").on('submit', (function(e) {
                e.preventDefault();
                dialogo.modal('hide');
                var forma = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('carga.imagen.propiedad') }}",
                    data: forma,
/*
 * cache (default: true, false for dataType 'script' and 'jsonp')
 * If set to false, it will force requested pages not to be cached by the browser. Note: Setting
 * cache to false will only work correctly with HEAD and GET requests. It works by appending
 * "_={timestamp}" to the GET parameters. The parameter is not needed for other types of requests,
 * except in IE8 when a POST is made to a URL that has already been requested by a GET.
 */                    
                    cache: false,
/*
 * contentType (default: 'application/x-www-form-urlencoded; charset=UTF-8')
 * Type: Boolean or String
 * When sending data to the server, use this content type. Default is
 * "application/x-www-form-urlencoded; charset=UTF-8", which is fine for most cases.
 * If you explicitly pass in a content-type to $.ajax(), then it is always sent to the server
 * (even if no data is sent). As of jQuery 1.6 you can pass false to tell jQuery to not set
 * any content type header. Note: The W3C XMLHttpReques specification dictates that the
 * charset is always UTF-8; specifying another charset will not force the browser to change
 * the encoding. Note: For cross-domain requests, setting the content type to anything other
 * than application/x-www-form-urlencoded, multipart/form-data, or text/plain will trigger
 * the browser to send a preflight OPTIONS request to the server.
 */                    
                    contentType: false,
/*
 * processData (default true)
 * By default (true), data passed in to the data option as an object (technically,
 * anything other than a string) will be processed and transformed into a query string,
 * fitting to the default content-type "application/x-www-form-urlencoded".
 * If you want to send a DOMDocument, or other non-processed data, set this option to false.                            
 */
                    processData: false,
                    success: function(data, estatus, jq) {
/*                        alert(`data:${data}, Estatus:${estatus},
                            readyState:${jq.readyState}, status:${jq.status},
                            responseText:${jq.responseText}`);*/
                        bootbox.dialog({
                            size: 'small',
                            title: data.success,
                            message: `<div class="row bg-transparent" style="height:150;overflow:hidden">
                                        <img class="img-fluid" src="{{ asset('storage/imgprop/') }}/${data.nombreImagen}"
                                                alt="Propiedad cargada" style="height:150px;">
                                    </div>`,
                            onEscape: true,
                            backdrop: true,
                            buttons: false,
                        })
                    },
                    error: function(jq, estatus, error) {
                        bootbox.dialog({
                            size: 'large',
                            title: `No se pudo cargar la imagen: Estatus:${estatus}, Error:${error}`,
                            message: `readyState:${jq.readyState},
                                    status:${jq.status}, responseText:${jq.responseText}`,
                            onEscape: true,
                            backdrop: true,
                            buttons: botones
                        })
                    }
                });
            }));
        });
        $("a.mostrarimagen").click(function(ev) {
            ev.preventDefault();
            const that = $(this);
            const nombreBase = that.attr('nombreBase'); // Nombre base sin secuencia, ni extension.
            const arreglo = nombreBase.split('_');      // id_codigo
            const id = arreglo[0];
            const codigo = arreglo[1];         // codigo de la propiedad.
            const nombre = $("#nombre-"+id).text();         // Nombre de la propiedad.
            const img = JSON.parse(that.attr('img'));       // extensiones de imagenes. Se pudo usar: $.parseJSON(...)
            const long = Object.keys(img).length;           // Object.keys(img): arreglo numerico de llaves de img: [k0, k1, ..., kn].
            //alert(`long: ${long}`);
            let msjHtml = `<div id="miCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
                            <div class="carousel-inner">`;
            let activar = true;
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

        {{--var codigo;     // Comentarios desde aqui hasta la linea anterior a $("#formulario").submit(function(ev) {
        $("a.editarCodigo").click(function(ev) {
            ev.preventDefault();
            $("input.codigo").prop('disabled', true);
            const id  = $(this).attr('id');        // Tambien $(ev.target).attr('id')
            const entrada = $('#' + id + "-codigo");
            codigo = entrada.text();
            const texto = `Desea cambiar al codigo MLS <u>${codigo}</u> de esta ` +
                            `propiedad.`;
            confirmar(texto, 'small', function(accion) {
                if (accion) {
                    entrada.parent().tooltip('disable');
                    entrada.prop('disabled', false);
                    //entrada.css("background-color", "white");
                    entrada.focus();            // No funciona.
                    //that.get(0).focus();      // No funciona.
                    //elemento.focus();         // TAMPOCO FUNCIONA.
                    //$('#'+id).get(0).focus(); // TAMPOCO FUNCIONA.
                }
            });
        });
        $("input.codigo").change(function(ev) {
            var that = $(this);
            var codNuevo = $(this).val();
            if (codigo == codNuevo) {       // 'codigo' es global a este jQuery.
                $(this).prop('disabled', true);
                return;
            }
            var ac = aCodigos[codNuevo];
            let id;
            if (ac) {
                if (undefined == ac.id) {
                    alert(`Algo extraño paso. Reportelo. Esto no deberia ocurrir, id:${ac.id}`);
                    that.prop('disabled', true);
                    return;
                }
                var ap = aPropiedades[ac.id];
                if (ap) {
                    var st = estatus[ap.st];
                    var ng = negociaciones[ap.ng];
                }
            }
            id = that.attr('id').split('-')[0]; // 'id' de la propiedad.
            let texto;
            //const id = that.attr('id');
            //const elemento = document.getElementById(id);
            if (ac && ap && st && ng) {
                texto = `Otra propiedad con este mismo codigo: <b>${codNuevo}</b>, ` +
                        `fue creada por <u>${asesores[ap.uid]}</u> ` +
                        `el <u>${ap.fc}</u> a las <u>${ap.ho}</u>; con nombre: ` +
                        `<u>${ap.nb}</u> y actualmente, tiene el estatus: ` +
                        `<u>${st}</u> como una negociacion de <u>${ng}</u>.` +
                        `<br>¿Desea continuar, cambiando <u>${codigo}</u> por ` +
                        `<u>${codNuevo}</u>?`;
            } else {
                texto = `Desea cambiar el codigo MLS <u>${codigo}</u> de esta ` +
                        `propiedad a <u>${codNuevo}</u>.`;
            }
            confirmar(texto, 'large', function(accion) {
                if (accion) {
                    that.parent().tooltip('enable');    // Estas dos lineas deberian ser eliminadas al ir al servidor
                    that.prop('disabled', true);        // a realizar 'update'. Pero, debo dejarlas para el futuro con ajax.
                    nvoUrl = `/propiedades/actualizar/${id}/codigo/${codNuevo}`;
                    location.href = nvoUrl;
                } else {
                    that.val(codigo);                   // Coloca el valor original.
                    that.prop('disabled', true);        // Desabilita la edicion del campo 'codigo' de 'input'
                    that.parent().tooltip('enable');    // Vuelve a habilitar la tooltip en la celda que contiene el campo 'codigo'.
                    //that.focus();               // NO FUNCIONA.
                    //that.get(0).focus();      // NO FUNCIONA.
                    //elemento.focus();         // TAMPOCO FUNCIONA.
                    //$('#'+id).get(0).focus(); // TAMPOCO FUNCIONA.
                }
            });
        });--}}
        $("#formulario").submit(function(ev) {
            const fecha_desde = $('#fecha_desde').val();
            const fecha_hasta = $('#fecha_hasta').val();
            const ano = $('#anoc').val();
            const codigo = $('#codigo').val();
            const negociacion = $('#negociacion').val();
            const estatus = $('#estatus').val();
            const desde = $('#desde').val();
            const hasta = $('#hasta').val();
        @if (Auth::user()->is_admin)
            const asesor = $('#asesor').val();
        @else (Auth::user()->is_admin)
            const asesor = 0;
        @endif (Auth::user()->is_admin)
            //alert(`fecha_desde:${fecha_desde},fecha_hasta:${fecha_hasta},ano:${ano},codigo:${codigo},` +
            //    `negociacion:${negociacion},estatus:${estatus},desde:${desde},hasta:${hasta},asesor:${asesor}`);

            if (('' == fecha_desde) && ('' == fecha_hasta) && ('' == ano) &&
                ('' == codigo) && ('' == negociacion) && ('' == estatus) &&
                ('' == desde) && ('' == hasta) && (0 == asesor)) {
                const texto = "Usted debe suministrar la fecha de reserva 'Desde' o " +
                        "el 'ano creado' o el 'codigo MLS' o el tipo de 'negociacion' o " +
                        "el 'estatus' o el precio 'minimo' o el precio 'maximo'" +
        @if (Auth::user()->is_admin)
                        " o el 'asesor'";
        @else (Auth::user()->is_admin)
                        "";
        @endif (Auth::user()->is_admin)
                //$('#textoModal').text(texto);
                //$('#alertar').modal('show');
                bootbox.alert(texto);
                ev.preventDefault();
            }
        });
    })
</script>
