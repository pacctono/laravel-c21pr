<script>
    $(document).ready(function(){
        $("fieldset.datosPropiedad").children("div").hide();  // Clase "datosPropiedad"

    @if ('crear' == $vista)
        if ('X' != $("#cliente_id").val()) {
            $("div.nuevo").hide();          // Clase "nuevo"
            $("#etiqDirCliente, #dirCliente, #etiqObservaciones, #observaciones").hide();     // Id's
        }
        var asesores, aPropiedades, estatus, negociaciones, aCodigos;

        $.ajax({url: "/propiedades/ajax",
            success: function(resultado) {
                    asesores  = $.parseJSON(resultado[0]);
                    aPropiedades = $.parseJSON(resultado[1]);
                    estatus = $.parseJSON(resultado[2]);
                    negociaciones = $.parseJSON(resultado[3]);
                    aCodigos = $.parseJSON(resultado[4]);
                    //alert(aPropiedades[aCodigos['915127'].id].nb+';'+aPropiedades[aCodigos['915127'].id].uid+';'+asesores[aPropiedades[aCodigos['915127'].id].uid]);
            },
            method: "get",
            data: { 'ajax': true },
            error: function() {
                alert("No se realizo una buena conexion con el servidor; no se podra " +
                    "verificar el 'codigo MLS'");
            }
        });
        $("#codigo").change(function(ev) {
            var codigo = $(this).val();
            var ac = aCodigos[codigo];
            if (ac) var ap = aPropiedades[ac.id];
            if (ap) {
                var st = estatus[ap.st];
                var ng = negociaciones[ap.ng];
            }
            if (ac && ap && st && ng) {
                alert("Esta propiedad fue creada por '" + asesores[ap.uid] + "' el '" +
                    ap.fc + "' a las '" + ap.ho + "'; con nombre: '" + ap.nb + "' y " +
                    "actualmente, tiene el estatus: '" + st + "', como una negociacion" +
                    " de '" + ng + "'.");
                $(this).focus();
            }
        });
        $("#cliente_id").change(function(ev){            // Id "cliente_id"
            if ('X' == $("#cliente_id").val()) {        // Indica que es nuevo cliente.
                $("div.nuevo, #etiqDirCliente, #dirCliente, #etiqObservaciones, #observaciones").show();
            } else {
                $("div.nuevo, #etiqDirCliente, #dirCliente, #etiqObservaciones, #observaciones").hide();
            }
        });
    @endif ('crear' == $vista)
        $("#datosPropiedad").click(function(ev){         // Id "datosPropiedad"
            $("fieldset.datosPropiedad").children("div").toggle(1000);  // Clase "datosPropiedad"
            ev.preventDefault();
        });
        $("#datosOficina").click(function(ev){           // Id "datosOficina"
            $("fieldset.datosOficina").children("div").toggle(1000);  // Clase "datosOficina"
            ev.preventDefault();
        });
        $("#asesor_captador_id").change(function(ev){            // Id "asesor_captador_id"
            if ('1' == $('#asesor_captador_id').val()) {
                alert('Proceda a incluir el nombre de la oficina CAPTADORA ' +
                        '(y el nombre del asesor CAPTADOR, si es posible)');
                $("#asesor_captador").focus();
            }
        });
        $("#asesor_cerrador_id").change(function(ev){            // Id "asesor_cerrador_id"
            if ('1' == $('#asesor_cerrador_id').val()) {
                alert('Proceda a incluir el nombre de la oficina CERRADORA ' +
                        '(y el nombre del asesor CERRADOR, si es posible)');
                $("#asesor_cerrador").focus();
            }
        });
        $("#fecha_reserva").change(function(ev) {
            var j;
            var opciones = $("#estatus option");
            var l = opciones.length;
            if ('' != $(this).val()) {
                for (j=0; j<l; j++) {
                    if ('I' == opciones[j].value) break;
                }
                var pendiente = opciones[j].text;
                alert("Al colocar la 'fecha de reserva', debemos comenzar una negociacion," +
                        " por eso el 'estatus' debe ser " + pendiente);
                $("#estatus").val('I');
                if ('' != $("#fecha_firma").val()) {
                    for (j=0; j<l; j++) {
                        if ('P' == opciones[j].value) break;
                    }
                    var pagosPendientes = opciones[j].text;
                    for (j=0; j<l; j++) {
                        if ('C' == opciones[j].value) break;
                    }
                    var cerrado = opciones[j].text;
                    alert("Como tenemos 'fecha de la firma':" + $("#fecha_firma").val() +
                            ", también; el 'estatus'" + ' debe ser ' + pagosPendientes +
                            ' o ' + cerrado);
                    $("#estatus").val('P');
                }
                $("#estatus").focus();
            }
        });
        $("#fecha_firma").change(function(ev) {
            if (('' == $("#fecha_reserva").val()) && ('' != $(this).val())) {
                alert("Una propiedad no deberia tener 'fecha de la firma':" +
                        $("#fecha_firma").val() + " y vacia la 'fecha de reserva'");
                var resp = confirm("Desea asignar la 'fecha de reserva' igual a la " +
                                    "'fecha de la firma'");
                if (resp) {
                    alert("También, procederé a cambiar el 'estatus'.");
                    $("#fecha_reserva").val($(this).val());
                    $("#estatus").val('P');
                    $("#estatus").focus();
                } else
                    $("#fecha_reserva").focus();
                return
            }
            if ('' != $(this).val()) {
                if (!(('P' == $("#estatus").val()) || ('C' == $("#estatus").val()))) {
                    var j;
                    var opciones = $("#estatus option");
                    var l = opciones.length;
                    for (j=0; j<l; j++) {
                        if ('P' == opciones[j].value) break;
                    }
                    var pagosPendientes = opciones[j].text;
                    for (j=0; j<l; j++) {
                        if ('C' == opciones[j].value) break;
                    }
                    var cerrado = opciones[j].text;
                    alert('Al colocar la fecha de la firma, debemos comenzar el cierre de la negociacion,' +
                            " por eso el <estatus> debe ser " + pagosPendientes + ' o ' + cerrado);
                    $("#estatus").val('P');
                    $("#estatus").focus();
                }
            }
        });
        $("#formulario").submit(function(ev) {
            if (('' == $("#fecha_reserva").val()) && ('' != $("#fecha_firma").val())) {
                ev.preventDefault();
                alert("Una propiedad no puede tener 'fecha de la firma':" +
                        $("#fecha_firma").val() + " y vacia la 'fecha de reserva'");
                $("#fecha_reserva").focus();
                return
            }
            if (('' != $("#fecha_reserva").val()) && (('A' == $("#estatus").val()) ||
                                                      ('S' == $("#estatus").val()))) {
                ev.preventDefault();
                alert("Una propiedad no puede tener 'fecha de reserva':" +
                        $("#fecha_reserva").val() + " y el 'estatus' [A]ctivo o [S]");
                $("#estatus").focus();
                return
            }
            if (('' != $("#fecha_firma").val()) && (('A' == $("#estatus").val()) ||
                        ('I' == $("#estatus").val()) || ('S' == $("#estatus").val()))) {
                ev.preventDefault();
                alert("Una propiedad no puede tener 'fecha de la firma':" + $("#fecha_firma").val()
                        + " y el 'estatus' [A]ctivo, [I]nmueble pendiente o [S]");
                $("#estatus").focus();
                return
            }
            if ('1' == $('#asesor_captador_id').val()) {
                if ('' == $('#asesor_captador').val()) {
                    ev.preventDefault();
                    alert('No puede crear esta propiedad sin agregar el nombre de la ' +
                        'oficina CAPTADORA (y, opcionalmente, el nombre del asesor CAPTADOR)');
                    $("#asesor_captador").focus();
                }
    @if ('crear' == $vista)
            } else if ('X' == $("#cliente_id").val()) {
                if ('' === $("#name").val()) {
                    ev.preventDefault();
                    alert("Usted seleccciono un 'Nuevo...' cliente; pero, no suministro su nombre!");
                    $("#name").focus();
                }
            } else {
                var name = $("#cliente_id option:selected").text();
                $("#name").val(name);   // Etiq 'Nombre' (Nombre del cliente). Evita errores de campo 'requerido'.
    @endif ('crear' == $vista)
            }
        });
    })
</script>
