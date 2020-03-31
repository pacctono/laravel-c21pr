<script>
    $(function () {
        //$('[data-toggle="tooltip"]').tooltip('enable')
        $("td.codigo").tooltip('enable')
    })
    @includeIf('include.alertar')
    @includeIf('include.confirmar')
    $(document).ready(function() {
    @includeWhen((Auth::user()->is_admin) and (!$movil) and
                 (!isset($accion) or ('html' == $accion)), 'propiedades.ajax')

    @if (($propRepetidas->isNotEmpty()) and (Auth::user()->is_admin))
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
    @endif ($propRepetidas->isNotEmpty())

    @if (!$movil and (!isset($accion) or ('html' == $accion)) and isset($alertar))
    @if (0 < $alertar)
        alertar("Le fue enviado el correo con el 'Reporte de Cierre' de la propiedad.");
    @elseif (0 > $alertar)
        alertar("No fue enviado el correo con el 'Reporte de Cierre' de la propiedad. Probablemente, problemas con Internet! Revise su conexión");
    @endif (0 < $alertar)
    @endif (!$movil and (!isset($accion) or ('html' == $accion)))

        $("td.nombre").click(function(ev) {
            const id = $(this).attr('id').substring(7);
            const cd = $("#"+id+"-codigo").val();
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
        });
        $("td.precio,td.lados,td.franquicia,td.sanaf,td.neto").click(function(ev) {
            var $t = $(this);
            if (undefined === $t.attr('title')) {
                $t.attr('title', $t.attr('titulo'));
                $t.removeAttr('titulo');
                $t.tooltip('enable');           // Trate  $("td.precio").tooltip('enable'), al principio y no funciono.
            } else {
                $t.attr('titulo', $t.attr('title'));
                $t.removeAttr('title');
                $t.tooltip('disable');          // Trate $("td.precio").tooltip('enable'), al principio y no funciono.
            }
        });

        var codigo;
        $("a.editarCodigo").click(function(ev) {
            ev.preventDefault();
            $("input.codigo").prop('disabled', true);
            const id  = $(this).attr('id');        // Tambien $(ev.target).attr('id')
            const entrada = $('#' + id + "-codigo");
            codigo = entrada.val();
            const texto = `Desea cambiar al codigo MLS <u>${codigo}</u> de esta ` +
                            `propiedad.`;
            confirmar(texto, 'small', function(accion) {
                if (accion) {
                    entrada.parent().tooltip('disable');
                    entrada.prop('disabled', false);
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
                if ('undefined' == ac.id) {
                    alert(`Algo extraño paso. Reportelo. Esto no deberia ocurrir, id:${ac.id}`);
                    $(this).prop('disabled', true);
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
                    that.parent().tooltip('enable');    // Estas dos lineas deben ser eliminadas al ir al servidor
                    that.prop('disabled', true);        // a realizar 'update'. Pero, debo dejarlas con ajax.
                    nvoUrl = `/propiedades/actCodigo/${id}/${codNuevo}`;
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
        });
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
