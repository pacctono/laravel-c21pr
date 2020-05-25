        $("a.cargarimagen").click(function(ev) {
            ev.preventDefault();
            const that = $(this);
            const id = that.attr('iduser'); // 'id' del user (asesor).
            const nombreBase = that.attr('nombreBase'); // nombreBase de la foto: id_cedula.ext
            const cedula = that.attr('cedula'); // nombreBase de la foto: id_cedula.ext
            const nombre = that.attr('nombre');         // Nombre del user (asesor).
            if ((!id) || (!nombreBase)) return
            const msjHtml = `<div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="{{ route('carga.imagen.user') }}" id="formacargar"
                                                    class="form align-items-horizontal"
                                                    method="POST" enctype="multipart/form-data">
                                                {!! csrf_field() !!}
                                                <div class="form-group form-inline m-0 py-0 px-1">
                                                    <input type="file" class="form-control" name="imagen">
                                                    <input type="hidden" name="id" value="${id}">
                                                    <input type="hidden" name="cedula" value="${cedula}">
                                                    <input type="hidden" name="nombreBase" value="${nombreBase}">

                                                    <button class="btn btn-success">Cargar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
            var dialogo = bootbox.dialog({
                size: 'large',
                title: `Cargar foto de ${cedula} ${nombre}. Ancho 342px (max 350) y alto 285px, max`,
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
                    url: "{{ route('carga.imagen.user') }}",
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
                                        <img class="img-fluid" src="{{ asset('storage/fotos/') }}/${data.nombreImagen}"
                                                alt="Foto del asesor(a)" style="height:150px;">
                                    </div>`,
                            onEscape: true,
                            backdrop: true,
                            buttons: false,
                        })
                        if (("#foto").length) {     // Existe el selector (length > 0) "#foto". Por ahora, solo 'show' y 'editar'.
                            $("#foto").empty();
                            $("#foto").prepend(`<img class="img-fluid d-block mx-auto"
                                                        src="{{ asset($user::DIR_PUBIMG) }}/${data.nombreImagen}"
                                                        alt="Foto del asesor(a)" style="height:285px">`);
                        }
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
