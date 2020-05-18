<script>
    $(function () {
        $(".mostrarTooltip").tooltip('enable')
    });
    @includeIf('include.alertar')
    @includeIf('include.confirmar')
    @includeIf('include.botonesDialog')

    $(document).ready(function() {
        $('a.desAct').click(function(ev) {
            ev.preventDefault();
            var user_id = $(this).attr('id');        // Tambien $(ev.target).attr('id')
            var nombre = $('#nombre'+user_id).val();
            var activo = $('#activo'+user_id).val();
            /*var accion;
            if ('A' == activo)
                accion = confirm('Desea desactivar al asesor ' + nombre);
            else
                accion = confirm('Desea activar al asesor ' + nombre);
            if (!accion) {
                ev.preventDefault();
            }*/
            let texto;
            if ('A' == activo)
                texto = 'Desea desactivar al asesor ' + nombre;
            else
                texto = 'Desea activar al asesor ' + nombre;
            confirmar(texto, 'small', function(accion) {
                if (accion) {
                    nvoUrl = `/usuarios/${user_id}/desActivar`;
                    location.href = nvoUrl;
                }
            })
        });
        $("a.aviso").click(function(ev) {
            ev.preventDefault();
            let user_id = $(this).attr('id').substring(5);        // 'avisoid'
            var nombre = $('#nombre'+user_id).val();
            // Funciona y devuelve una tabla. Ahora, hay que buscarla donde desplegarla.
            // el id: 'div'+user_id fue comenatdo arriba, intencionalmente.
            $.ajax({url: "/avisos/asesor/"+user_id, success: function(resultado) {
                        //$("#div"+user_id).html(resultado);
                        alertar(resultado, nombre);   // Mientras se resuelve donde desplegar la tabla.
                    }});
        });
    @includeIf('users.cargarFoto')
        $("a.mostrarimagen").click(function(ev) {
            ev.preventDefault();
            const that = $(this);
            const nombreBase = that.attr('nombreBase'); // Nombre base, ni extension.
            const id = that.attr('iduser');
            const cedula = that.attr('cedula');         // cedula del user.
            const nombre = that.attr('nombre');         // Nombre del user (asesor).
            const ext = that.attr('ext');       // extension de la foto.
            let msjHtml = `<div class="row bg-transparent">
                        <img class="img-fluid" src="{{ asset('storage/fotos/') }}/${nombreBase}.${ext}"
                                alt="Foto del asesor(a)" style="height:285;">
                    </div>`;
            const titulo = `${id}) ${cedula} ${nombre}`;
            alertar( msjHtml, titulo, 'small')
        });
        $(".formaBorrar").submit(function(ev) {
            const that = $(this);
            const id = that.attr('iduser');
            const nroContactos = $('#contactos'+id).val();
            const nroContactosBorrados = $('#contactosBorrados'+id).val();
            if (0 < nroContactos) {
                alert('Este asesor ha creado ' + nroContactos +
                                    ' contactos iniciales, por lo tanto, no puede borrar sus datos.');
                ev.preventDefault();
                return;
            }
            if (0 < nroContactosBorrados) {
                if (confirm('Este asesor tiene ' + nroContactosBorrados +
                                    " 'Contactos Iniciales borrados', " +
                                    'esta seguro de querer borrar sus datos de la base de datos?')) {
                    ev.preventDefault();
                    return;
                }
            }
            if (confirm('Realmente, desea borrar los datos de este asesor de la base de datos?')) {
                ev.preventDefault();
                return;
            }
        });
    });
</script>
