<script>
    function agregarTexto(texto, jerror, textoNuevo) {
        if (0 < jerror) texto += '<br>';
        jerror++;
        texto += `${jerror}) ${textoNuevo}`;
        return [jerror, texto];
    }
    $(function () {
        $('[data-toggle="tooltip"]').tooltip('enable')
    })
    @includeIf('include.alertar')
    @includeIf('include.confirmar')
    $(document).ready(function(){
        $("fieldset.datosPropiedad").children("div").hide();  // Clase "datosPropiedad"
        $("input.nombreAsesor").prop('disabled', true);
        //$("input:focus").css("background-color", "lightgrey"); 

    @includeif('propiedades.ajax')
        function borrarImagenConAjax(that, nombreActual, nombreNuevo, idDivImg) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('propiedades.borrarImagen') }}",
                data: {nombreActual: nombreActual, nombreNuevo: nombreNuevo},
                success: function(data, estatus, jq) {
                    alertar(data.success);
                    $("#"+idDivImg).remove();
                },
                error: function(jq, estatus, error) {
                    alertar(`readyState:${jq.readyState}, status:${jq.status}, // jq.responseText es una string, el objeto es responseJSON.
                                mensaje:${jq.responseJSON.message},
                                exception:${jq.responseJSON.exception},
                                file:${jq.responseJSON.file}, line: ${jq.responseJSON.line}`,
                            `No se pudo borrar la imagen: Estatus:${estatus}, Error:${error}`,
                            'extra-large'
                    )
                }
            });
        }
    @if ('crear' == substr($view_name, -5))
        if ('X' != $("#cliente_id").val()) {
            $("div.nuevo").hide();          // Clase "nuevo". Solo se muestra, si se va a crear un nuevo cliente.
            $("#etiqDirCliente, #dirCliente, #etiqObservaciones, #observaciones").hide();     // Id's
        }
        $("#codigo").change(function(ev) {
            const $t = $(this);
            const codigo = $t.val();          // 'codigo' se necesita global;
            const ac = aCodigos[codigo];
            let ap, st, ng;
            if (ac) ap = aPropiedades[ac.id];
            if (ap) {
                st = estatus[ap.st];        // 'estatus' es global, descargada por ajax.
                ng = negociaciones[ap.ng];
            }
            if (ac && ap && st && ng) {
                alertar(`Este codigo <b>${codigo}</b>, ya fue asignado a otra ` +
                    `propiedad creada por <u>${asesores[ap.uid]}</u> el ` +
                    `<u>${ap.fc}</u> a las <u>${ap.ho}</u>; con nombre: ` +
                    `<u>${ap.nb}</u> y actualmente, tiene el estatus: <u>${st}</u>, ` +
                    `como una negociacion de <u>${ng}<u>.`,
                    'CODIGO REPETIDO',
                    'large',
                    function() {
                        $t.focus();
                    });
            }
        });
        $("#cliente_id").change(function(ev){            // Id "cliente_id"
            if ('X' == $("#cliente_id").val()) {        // Indica que es nuevo cliente.
                $("div.nuevo, #etiqDirCliente, #dirCliente, #etiqObservaciones, #observaciones").show();
            } else {
                $("div.nuevo, #etiqDirCliente, #dirCliente, #etiqObservaciones, #observaciones").hide();
            }
        });
    @endif ('crear' == substr($view_name, -5))
    @if ('editar' == substr($view_name, -6))
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
                let divHtml = `<div class="row bg-transparent m-0 p-0" style="height:250">`;
                for (const i in img) {        // Quite la clase .w-auto, al primer div.
                    divHtml += `<div id="${nombreBase}-${i}" style="position:relative">
                            <img class="img-fluid m-0 p-0"
                                src="{{ asset('storage/imgprop/') }}/${nombreBase}-${i}.${img[i]}"
                                style="height:250px;">
                            <div style="position:absolute;right:50px;bottom:10px;">
                                <a class="btn btn-sm btn-outline-danger imagenPropiedad"
                                        href="" nombre="${nombreBase}" sec="${i}"
                                        ext="${img[i]}" role="button">
                                    Borrar
                                </a>
                            </div>
                        </div>`;
                }
                divHtml += `</div>`;
                $("#fotosestaticas").html(divHtml);
                $("#fotosestaticas").addClass("row bg-transparent border-top border-dark m-0 p-0");
                $("a.imagenPropiedad").click(function(ev) {
                    ev.preventDefault();
                    const that = $(this);
                    const nombre = that.attr('nombre');
                    const sec = that.attr('sec');
                    const ext = that.attr('ext');
                    const asesor = {{ Auth::user()->id }};
                    borrarImagenConAjax(that, `${nombre}-${sec}.${ext}`,
                                        `${nombre}-${sec}_${asesor}.${ext}`,
                                        `${nombre}-${sec}`);
                });
            }
            $("#fotosestaticas").toggle();
            if ($("#fotosestaticas").is(":hidden")) {   // Tambien ":visibility"
                $("#mostrarfotos").text("Mostrar fotos")
            } else {
                $("#mostrarfotos").text("Ocultar fotos")
            }
        });
    @endif ('editar' == substr($view_name, -6))
        $("#datosPropiedad").click(function(ev){         // Id "datosPropiedad"
            $("fieldset.datosPropiedad").children("div").toggle(1000);  // Clase "datosPropiedad"
            ev.preventDefault();
        });
        $("#datosOficina").click(function(ev){           // Id "datosOficina"
            $("fieldset.datosOficina").children("div").toggle(1000);  // Clase "datosOficina"
            ev.preventDefault();
        });
        $("#asesor_captador_id").change(function(ev){            // Id "asesor_captador_id"
            let acaptador = $("#asesor_captador");
            if ('1' == $(this).val()) {
                alertar('Proceda a incluir el nombre de la oficina CAPTADORA ' +
                        '(y el nombre del asesor CAPTADOR, si es posible)',
                        'CAPTADOR DE OTRA OFICINA',
                        'large',
                        function() {
                            acaptador.prop('disabled', false);
                            acaptador.focus();
                        });
            } else acaptador.prop('disabled', true);
        });
        $("#asesor_cerrador_id").change(function(ev){            // Id "asesor_cerrador_id"
            let acerrador = $("#asesor_cerrador");
            if ('1' == $(this).val()) {
                alertar('Proceda a incluir el nombre de la oficina CERRADORA ' +
                        '(y el nombre del asesor CERRADOR, si es posible)',
                        'CERRADOR DE OTRA OFICINA',
                        'large',
                        function() {
                            acerrador.prop('disabled', false);
                            acerrador.focus();
                        });
            } else acerrador.prop('disabled', true);
        });
        $("#fecha_reserva").change(function(ev) {
            const opciones = $("#estatus option");
            const l = opciones.length;
            const status  = $("#estatus");     // No se puede definir como 'estatus' porque es una variable global, decargada por ajax.
            const vestatus = status.val();
            let texto = '';
            if ('' != $(this).val()) {
                //let j;
                if ('A' == vestatus) {
                    /*for (j=0; j<l; j++) {
                        if ('I' == opciones[j].value) break;
                    }
                    const pagosPendientes = opciones[j].text;*/
                    const pendiente = estatus['I'];
                    texto = "Al colocar la <u>fecha de reserva</u>, debemos comenzar una " +
                        `negociacion, por eso el <u>estatus</u> debe ser <b>${pendiente}</b>.`;
                    status.val('I');
                }
                const vfirma  = $("#fecha_firma").val();
                if (('' != vfirma) && (('A' == vestatus) || ('I' == vestatus))) {
                    /*for (j=0; j<l; j++) {
                        if ('P' == opciones[j].value) break;
                    }
                    const pagosPendientes = opciones[j].text;
                    for (j=0; j<l; j++) {
                        if ('C' == opciones[j].value) break;
                    }
                    const cerrado = opciones[j].text;*/
                    const pagosPendientes = estatus['P'];
                    const cerrado = estatus['C'];
                    if ('' != texto) texto += '<br>';
                    texto += `Como tenemos <u>fecha de la firma</u>: <b>${vfirma}</b>, ` +
                        `también; el <u>estatus</u> debe ser <b>${pagosPendientes}</b> o ` +
                        `<b>${cerrado}</b>.`;
                        status.val('P');
                }
            }
            alertar(texto, 'INCLUSION/CAMBIO DE LA FECHA DE RESERVA', 'large',
                    function() {
                        status.focus();
                    });
        });
        $("#fecha_firma").change(function(ev) {
            const reserva  = $("#fecha_reserva");
            const vreserva = reserva.val();
            const firma    = $(this);
            const vfirma   = firma.val();
            const status  = $("#estatus");
            const vestatus = status.val();
            if (('' == vreserva) && ('' != vfirma)) {
                confirmar("Una propiedad no deberia tener <u>fecha de la firma</u>:" +
                        `<b>${vfirma}</b> y vacia la <u>fecha de reserva</u><br>` +
                        "Desea asignar la <u>fecha de reserva</u> igual a la " +
                        "<u>fecha de la firma</u>.<br>Si la respuesta es afirmativa; " +
                        `también, procederé a cambiar el <u>estatus</u> por ` +
                        `<b>${estatus['P']}</b>.`,
                        'large',
                        function(resp) {
                            if (resp) {
                                reserva.val(vfirma);
                                status.val('P');
                                status.focus();
                            } else
                                reserva.focus();
                        });
                return;
            }
            if ('' != vfirma) {
                if (!(('P' == vestatus) || ('C' == vestatus))) {
                    /*let j;
                    const opciones = $("#estatus option");
                    const l = opciones.length;
                    for (j=0; j<l; j++) {
                        if ('P' == opciones[j].value) break;
                    }
                    const pagosPendientes = opciones[j].text;
                    for (j=0; j<l; j++) {
                        if ('C' == opciones[j].value) break;
                    }
                    const cerrado = opciones[j].text;*/
                    const pagosPendientes = estatus['P'];
                    const cerrado = estatus['C'];
                    alertar(`Al colocar la <u>fecha de la firma</u>, debemos comenzar ` +
                            `el cierre de la negociacion, por eso el <u>estatus</u> ` +
                            `debe ser <b>${pagosPendientes}</b> o <b>${cerrado}</b>.`,
                            'INCLUSION/CAMBIO DE LA FECHA DE LA FIRMA',
                            'large',
                            function() {
                                status.val('P');
                                status.focus();
                            });
                }
            }
        });
        $("#formulario").submit(function(ev) {
            const reserva  = $("#fecha_reserva");
            const vreserva = reserva.val();
            const firma    = $("#fecha_firma");
            const vfirma   = firma.val();
            let texto      = '', jerror = 0;
            if (('' == vreserva) && ('' != vfirma)) {
                [jerror, texto] = agregarTexto(texto, jerror, 
                    `Una propiedad no puede tener ` +
                    `<u>fecha de la firma</u>: ${vfirma} y vacia la ` +
                    `<u>fecha de reserva</u>`);
                //reserva.focus();
            }
            const status  = $("#estatus");
            const vestatus = status.val();
            if (('' != vreserva) && (('A' == vestatus) ||
                                                      ('S' == vestatus))) {
                [jerror, texto] = agregarTexto(texto, jerror, 
                    `Una propiedad no puede tener ` +
                    `<u>fecha de reserva</u>: ${vreserva} y el <u>estatus</u> ` +
                    `<b>${estatus[vestatus]}</b>`);
                //status.focus();
            }
            if (('' != vfirma) && (('A' == vestatus) ||
                        ('I' == vestatus) || ('S' == vestatus))) {
                [jerror, texto] = agregarTexto(texto, jerror, 
                    `Una propiedad no puede tener ` +
                    `<u>fecha de la firma</u>: ${vfirma} y el <u>estatus</u> ` +
                    `<b>${estatus[vestatus]}</b>`);
                //status.focus();
            }
            const vcaptador = $("#asesor_captador_id").val();   // Si es 1; el captador es de otra oficina.
            const ncaptador = $("#asesor_captador").val();      // Solo se usa, cuando es otra oficina (vcaptador=1):
            if ('1' == vcaptador) {
                if ('' == ncaptador) {
                    [jerror, texto] = agregarTexto(texto, jerror, 
                        `No puede crear esta propiedad; la cual, ` +
                        `se ha identificado como <u>otra oficina</u>, sin agregar ` +
                        `el nombre de la oficina CAPTADORA (y, opcionalmente, ` +
                        `el nombre del asesor CAPTADOR) y ha dejado el campo vacio.`);
                    //captador.focus();
                }
            }
// Al crear o editar una propiedad, es muy probable que no se necesite el cerrador.            
            const vcerrador = $("#asesor_cerrador_id").val();   // Si es 1; el cerrador es de otra oficina.
            const ncerrador = $("#asesor_cerrador").val();      // Solo se usa, cuando es otra oficina (vcerrador=1):
            if ((('P' == vestatus) || ('C' == vestatus)) && ('1' == vcerrador)) {   // vestatus se define unas lineas arriba.
                if ('' == ncerrador) {
                    [jerror, texto] = agregarTexto(texto, jerror, 
                        `No puede crear esta propiedad; la cual, ` +
                        `se ha identificado como <u>otra oficina</u> y ademas, ` +
                        `coloco el <u>estatus</u> en <b>${estatus[vestatus]}</b>, ` +
                        `sin agregar el nombre de la oficina CERRADORA ` +
                        `(y, opcionalmente, el nombre del asesor CERRADOR) y ha ` +
                        `dejado el campo vacio.`);
                    //captador.focus();
                }
            }
    @if ('crear' == substr($view_name, -5))
            const codigo = $("#codigo").val();
            const ac = aCodigos[codigo];
            let ap, st, ng;
            if (ac) ap = aPropiedades[ac.id];
            if (ap) {
                st = estatus[ap.st];        // 'estatus' es global, descargada por ajax.
                ng = negociaciones[ap.ng];
            }
            if (ac && ap && st && ng) {
                [jerror, texto] = agregarTexto(texto, jerror, 
                    `Este codigo: <b>${codigo}</b> fue asignado a ` +
                    `una propiedad creada por <b>${asesores[ap.uid]}</b> el ` +
                    `<b>${ap.fc}</b> a las <b>${ap.ho}</b>; con nombre: ` +
                    `<b>${ap.nb}</b> y actualmente, tiene el estatus: <b>${st}</b>, ` +
                    `como una negociacion de <b>${ng}</b>.`);
                /*if (!resp) {
                    ev.preventDefault();
                    $(this).focus();
                }*/
            }
            const cliente  = $("#cliente_id");
            const vcliente = cliente.val();
            const nname    = $("#name");
            const vname    = nname.val();
            if ('X' == vcliente) {
                if ('' === vname) {
                    [jerror, texto] = agregarTexto(texto, jerror, 
                        `Usted seleccciono un <u>Nuevo...</u> ` +
                        `cliente; pero, no suministro su <em>nombre</em>!`);
                    //nname.focus();
                }
            }
    @endif ('crear' == substr($view_name, -5))
            if (0 < jerror) {
                ev.preventDefault();
                alertar(texto, 'LISTA DE ERRORES');
            } else if ('X' != vcliente) {
                const name = $("#cliente_id option:selected").text();
                nname.val(name);   // Etiq 'Nombre' (Nombre del cliente). Evita errores de campo 'requerido'.
            }
        });
    })
</script>
