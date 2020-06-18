<script>
    $(function () {
        $(".mostrarTooltip").tooltip('enable')
    });
    @includeIf('include.alertar')
    @includeIf('include.botonesDialog')

    function prepararArreglo(arreglo, id=true) {
        let nvoArreglo = [], i = 0;
        for (let k in arreglo) {
            nvoArreglo[i++] = {
                text: arreglo[k],
                value: (id)?k:arreglo[k]
            }
        }
        return nvoArreglo;
    }
    function agregarOpcion(selector, arreglo, id=true) {
        if (1 < $("option", selector).length) return;
        for (let k in arreglo) {
            selector.append(new Option(arreglo[k], (id)?k:arreglo[k]));
        }
    }
    $(document).ready(function() {
    @includeWhen((!$movil) and (!isset($accion) or ('html' == $accion)), 'welcomeAjax')
        var obj = {};
/*
 * ciudadC: ciudad donde desea comprar o alquilar. 'select'
 * negociacionC, negociacionV: compra o alquila o Vender o dar en Alquiler. 'select'
 * tipoc, tipov: Tipo de inmueble a comprar o vender. 'select'
 */
        $("#ciudadC,#negociacionC,#negociacionV,#tipoC,#tipoV").click(function(ev) {
            const that = $(this);
            const id = that.attr('id');
            let texto, arreglo;
            if (-1 !== id.indexOf('ciudad')) {
                texto = `la ciudad donde desea comprar o alquilar.`;
                arreglo = prepararArreglo(ciudades);
                mensaje = 'CIUDAD: ';
                arrOrg = ciudades;
            } else if (-1 !== id.indexOf('negociacionC')) {
                texto = 'el tipo de negociacion.';
                arreglo = prepararArreglo(deseosC);
                mensaje = '';
                arrOrg = deseosC;
            } else if (-1 !== id.indexOf('negociacionV')) {
                texto = 'el tipo de negociacion.';
                arreglo = prepararArreglo(deseosV);
                mensaje = '';
                arrOrg = deseosV;
            } else if (-1 !== id.indexOf('tipo')) {
                texto = 'el tipo de inmueble.';
                arreglo = prepararArreglo(tipos);
                mensaje = 'TIPO: ';
                arrOrg = tipos;
            } else return;
            const valor = (obj[id])?obj[id]:arreglo[0]['value'];
            bootbox.prompt({
                size: 'small',
                title: `Seleccione ${texto}`,
                inputType: 'select',
                value: valor,
                inputOptions: arreglo,
                buttons: botones,
                callback: function(res) {
                    if (res) {
                        /*if (0 >= $("#"+id + " > br").length) $("span", "#"+id).before("<br>");
                        $("span", "#"+id).text(arrOrg[res]);*/
                        $("#"+id).text(mensaje + arrOrg[res]);
                        obj[id] = res;
/*                        for (let i=0, l=arreglo.length; i<l; i++) {
                            if (res == arreglo[i].value) {
                                //console.log($("span", "#"+id));
                                if (0 >= $("#"+id + " > br").length) $("span", "#"+id).before("<br>");
                                $("span", "#"+id).text(arreglo[i].text);
                                obj[id] = arreglo[i].value;
                                break;
                            }
                        }*/
                    }
                }
            });
        });
/*
 * ciudad: Nombre de la ciudad donde desea vender. 'text'
 * nombre: Nombre del vendedor. 'text'
 */
        $("#ciudadV,#nombre").click(function(ev) {
            const that = $(this);
            const id = that.attr('id');
            let texto;
            if (-1 !== id.indexOf('ciudad')) {
                texto = 'la ciudad donde desea vender o dar en alquiler su inmueble';
                mensaje = 'CIUDAD: ';
            } else if (-1 !== id.indexOf('nombre')) {
                texto = 'su nombre, por favor';
                mensaje = '';
            } else return;
            const valor = (obj[id])?obj[id]:'';
            bootbox.prompt({
                size: 'small',
                title: `Introduzca ${texto}`,
                inputType: 'text',
                value: valor,
                buttons: botones,
                callback: function(res) {
                    if (res) {
                        /*if (0 >= $("#"+id + " > br").length) $("span", "#"+id).before("<br>");
                        $("span", "#"+id).text(res);*/
                        $("#"+id).text(mensaje + res);
                        obj[id] = res;
                    }
                }
            });
        });
/*
 * telefono: Codigo de area. 'select'. Ultimos 7 digitos del número de teléfono contacto del vendedor. 'text'
 */
        $("#telefono").click(function(ev) {
            const that = $(this);
            const id = that.attr('id');
            const titulo = 'Introduzca los últimos siete (7) dígitos de su número de ' +
                           'teléfono o celular.';
            const valor = (obj[id])?obj[id]:'';
            bootbox.prompt({
                size: 'small',
                title: 'Seleccione el código de área.',
                inputType: 'select',
                value: (valor)?valor.substr(0, 3):'414',
                inputOptions: prepararArreglo(ddns, false),
                buttons: botones,
                callback: function(res) {
                    if (res) {
                        const codarea = res;
                        bootbox.prompt({
                            size: 'large',
                            title: titulo,
                            inputType: 'text',
                            pattern: '[0-9]{7}',
                            placeholder: '9999999',
                            maxlength: 7,
                            value: (valor)?valor.substr(3):'',
                            buttons: botones,
                            callback: function(res) {
                                if (res) {
                                    res = codarea + res;
                                    /*if (0 >= $("#"+id + " > br").length) $("span", "#"+id).before("<br>");
                                    $("span", "#"+id).text(res);*/
                                    $("#"+id).text('0' + res);
                                    obj[id] = res;
                                }
                            }
                        });
                    }
                }
            });
        });
/*
 * comprar: Boton para enviar los datos del comprador. 'submit'
 * enviar: Enviar mensaje con la información del vendedor. 'submit'
 */
        $("#comprar,#enviar").click(function(ev) {
            const that = $(this);
            const id = that.attr('id');
            let titulo, texto;
            if (-1 !== id.indexOf('comprar')) {
                if (obj['negociacionC'] && obj['ciudadC'] && obj['tipoC']) {
                    titulo = deseos[obj['negociacionC']];
                    texto = `Ciudad: ${ciudades[obj['ciudadC']]}, Tipo de imueble: ${tipos[obj['tipoC']]}.`;
                } else titulo = false;
            }
            else if (-1 !== id.indexOf('enviar'))
                if (obj['negociacionV'] && obj['ciudadV'] && obj['nombre'] &&
                        obj['tipoV'] && obj['telefono']) {
                    titulo = deseos[obj['negociacionV']];
                    texto = `Ciudad: ${obj['ciudadV']}, Tipo de imueble: ${tipos[obj['tipoV']]},
                            Nombre: ${obj['nombre']}, Teléfono: ${obj['telefono']}.`;
                } else titulo = false;
            else return;
            if (titulo) {
            @if (config('app.debug'))
                alertar(texto, titulo);
            @endif (config('app.debug'))
                if ('comprar' == id)    // Mostrar las propiedades (inicio.propiedades) con los datos seleccionados, anteriormente.
                    location.href = `/welcome/propiedades/${obj['negociacionC']}/${obj['ciudadC']}/${obj['tipoC']}`;
                else {      // Enviar correo para 'vender' o 'dar en alquiler' con los datos suministrados, anteriormente.
                    $.ajax({
                        url: "/enviarcorreo",       // @ajaxWelcome
                        success: function(data, estatus, jq) {
                            alertar(data.exito, 'Gracias por su confianza');
                        },
                        method: "get",
                        data: {
                            'deseo': obj['negociacionV'],
                            'ciudad': obj['ciudadV'],
                            'nombre': obj['nombre'],
                            'tipo': obj['tipoV'],
                            'telefono': obj['telefono'],
                        },
                        error: function(jq, estatus, error) {
                        @if (config('app.debug'))
                            alertar(`readyState:${jq.readyState}, status:${jq.status},
                                        mensaje:${jq.responseJSON.message}, exception:${jq.responseJSON.exception},
                                        file:${jq.responseJSON.file}, line: ${jq.responseJSON.line}`,
                                    `No se pudo cargar este contacto inicial: Estatus:${estatus}, Error:${error}`,
                                    'large'
                            )
                        @else (config('app.debug'))
                            alertar("Problemas con la red");
                        @endif (config('app.debug'))
                        }
                    });
                }
            }
            else alertar('Faltan datos! Por favor, incluya los datos presionando los botones a la izquierda.',
                            'NOTIFICACIÓN', 'small');
        })

// Propiedades.
        $(".verInmueble").click(function(ev) {
            ev.preventDefault();
            const that = $(this);
            const idprop = that.attr('idprop');
            location.href = `/welcome/propiedades/P/${idprop}`;
        })

// Asesores.
        $(".verInmuebles").click(function(ev) {
            ev.preventDefault();
            const that = $(this);
            const idasesor = that.attr('idasesor');
            location.href = `/welcome/propiedades/A/${idasesor}`;
        })

// Contactanos.
        $("#ddn,#deseo,#tipo,#zona").click(function(ev) {
            const that = $(this);
            const id = that.attr('id');
            const arreglo = arrContacto[id];
            //console.log(id, arrContacto, arrContacto[id], arreglo);
            agregarOpcion(that, arreglo, (arreglo!=ddns));
        })
        $("#deseo").change(function(ev) {
            const that = $(this);
            if (!that.val()) return;
            let arreglo;
            const deseo = that.val();
            const selPrecio = $("#precio");
            if ((1 == deseo) || (2 == deseo)) arreglo = precios;
            else if ((3 == deseo) || (4 == deseo)) arreglo = preciosA;
            else return;
            let opciones = $("option", selPrecio);
            let l = opciones.length;
            const ultId = opciones[l-1].value;      // id de la tabla de precio: price.
            if (1 < l) {
                for(let k = 1; k <= ultId; k++) {
                    if ($("#precio option[value=" + k + "]"))
                        $("#precio option[value=" + k + "]").remove();
                }
            }
            agregarOpcion(selPrecio, arreglo);
        })
        $("#precio").click(function(ev) {
            const that = $(this);
            const deseo = $("#deseo").val();
            if (1 >= $("option", that).length) {
                alertar("Por favor, selecciona 'que desea', antes", "NOTA");
                return;
            }
        })
        $("#direccion,#observaciones").on("input", function(){
            const that = $(this);
            const maxlength = that.attr("maxlength");
            const longitud = that.val().length;
            const idNumCars = "numCars" + that.attr("id");

            /*if (longitud >= maxlength) {
                $("#"+idNumCars).text('Ya introdujo el máximo de caractres, permitidos!');
            } else {
                const numCars = maxlength - longitud;
                $("#"+idNumCars).text('' + numCars);
            }*/
            const numCars = maxlength - longitud;
            $("#"+idNumCars).text('' + numCars);
        });
        $("#formulario").on('submit', (function(ev) {
            ev.preventDefault();
            const that = $(this);
            const nombre = $("#name").val();
            const ddn = $("#ddn").val();
            const telefono = $("#telefonoCI").val();
            const deseo = $("#deseo").val();
            if (!nombre || !ddn || !telefono || !deseo) {
                alertar('Por favor, incluya, mínimo, su nombre y teléfono, incluyendo el código de área. ' +
                        'También, que desea?', 'Faltan datos');
                return;
            }
            if (('' == nombre) || (null == nombre.match(/^[a-zA-Z]+([a-zA-Z ]+)*$/))) {
                alertar('Por favor, su nombre, sólo puede contener letras y espacio.', 'Faltan datos');
                return;
            }
            if (('' == ddn) || ('' == telefono) || (7 != telefono.length) || (null == telefono.match(/[0-9]+$/))) {
                alertar('Por favor, su número de teléfono debe contener 7 dígitos, además el código de área.', 'Faltan datos');
                return;
            }
            if ('' == deseo) {
                alertar('Por favor, seleccione que desea?');
                return;
            }

            var forma = new FormData(this);
        @if (config('app.debug'))
            let tipo = forma.get('tipo_id');
            let precio = forma.get('precio_id');
            let zona = forma.get('zona_id');
            let origen = forma.get('origen_id');
            let resultado = forma.get('resultado_id');
            alert(`Nombre: ${nombre}, Teléfono: 0${ddn}-${telefono}, Desea: (${deseo})${deseos[deseo]},
                        Tipo: (${tipo})${tipos[tipo]}, Precio: (${precio})${precios[precio]}, 
                        Zona: (${zona})${zonas[zona]}, Origen: ${origen}, Resultado: ${resultado}.`);
            console.log(this);
            console.log(forma.get('name'));
        @endif (config('app.debug'))
            if ('' == forma.get('tipo_id')) forma.set('tipo_id', '6');      // Valor 'otro' para tipo.
            if ('' == forma.get('precio_id')) forma.set('precio_id', '1');  // Valor minimo para precio.
            if ('' == forma.get('zona_id')) forma.set('zona_id', '4');      // Valor 'otro' para zona.
            forma.append('origen_id', '22');                                // Valor 'Web' para origen.
            forma.append('resultado_id', '8');                              // Valor 'otro' para resultado.
            forma.append('web', true);
        @if (config('app.debug'))
            tipo = forma.get('tipo_id');
            precio = forma.get('precio_id');
            zona = forma.get('zona_id');
            origen = forma.get('origen_id');
            resultado = forma.get('resultado_id');
            alert(`Nombre: ${nombre}, Teléfono: 0${ddn}-${telefono}, Desea: (${deseo})${deseos[deseo]},
                        Tipo: (${tipo})${tipos[tipo]}, Precio: (${precio})${precios[precio]},
                        Zona: (${zona})${zonas[zona]}, Origen: ${origen}, Resultado: ${resultado}.`);
        @endif (config('app.debug'))
            $.ajax({
                type: 'POST',
                url: "{{ url('contactos') }}",
                data: forma,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, estatus, jq) {
                    alertar(data.exito, 'NOTIFICACIÓN');
                },
                error: function(jq, estatus, error) {   // APP_DEBUG: {{ config('app.debug') }}
            @if (config('app.debug'))
                    alertar(`readyState:${jq.readyState}, status:${jq.status},
                                mensaje:${jq.responseJSON.message}, exception:${jq.responseJSON.exception},
                                file:${jq.responseJSON.file}, line: ${jq.responseJSON.line}`,
                            `No se pudo cargar este contacto inicial: Estatus:${estatus}, Error:${error}`,
                            'large'
                    )
            @else (config('app.debug'))
                    alertar('Hubo problemas con la red. No se pudo enviar la información', 'NOTIFICACIÓN');
            @endif (config('app.debug'))
                }
            });
        }))
    })
</script>
  
