        $("a.mostrarimagen").click(function(ev) {
            ev.preventDefault();
            const that = $(this);
            const nombreBase = that.attr('nombreBase'); // Nombre base sin secuencia, ni extension.
            const arreglo = nombreBase.split('_');      // id_codigo
            const id = arreglo[0];
            const codigo = arreglo[1];                  // codigo de la propiedad.
        @if ('inicio-propiedades' == $view_name)
            const nombre = that.attr('nombre');         // Nombre de la propiedad.
        @elseif ('propiedades-index' == $view_name)
            const nombre = $("#nombre-"+id).text();         // Nombre de la propiedad.
        @else ('propiedades-index' == $view_name)
            const nombre = '';
        @endif ('inicio-propiedades' == $view_name)
            const img = JSON.parse(that.attr('img'));       // extensiones de imagenes. Se pudo usar: $.parseJSON(...)
            const long = Object.keys(img).length;           // Object.keys(img): arreglo numerico de llaves de img: [k0, k1, ..., kn].
            //alert(`long: ${long}`);
            let msjHtml = `<div id="slides">
                            <div id="miCarousel" class="carousel slide" data-ride="carousel" data-interval="5000">
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
                                <span class="carousel-control-prev-icon" aria-hidden="true" style="color:black"></span>
                                <span class="sr-only">Anterior</span>
                                </a>
                            <a class="carousel-control-next" href="#miCarousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true" style="color:black"></span>
                                <span class="sr-only">Próximo</span>
                            </a>`;
            msjHtml += `</div></div>`;
        @if ('propiedades-index' == $view_name)
            const titulo = `${id}) ${codigo} ${nombre}`;
        @else ('propiedades-index' == $view_name)
            const titulo = nombre;
        @endif ('inicio-propiedades' == $view_name)
            alertar(msjHtml, titulo, 'large')
        });
