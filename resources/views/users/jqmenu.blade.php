<script>
    @includeIf('include.alertar')
    @includeIf('include.confirmar')
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
    });
function estaSeguro(id) {
    var nroContactos         = document.getElementById('contactos.'+id).value;
    var nroContactosBorrados = document.getElementById('contactosBorrados.'+id).value;

    if (0 < nroContactos) {
        alert('Este asesor ha creado ' + nroContactos +
                            ' contactos iniciales, por lo tanto, no puede borrar sus datos.');
        return false;
    }
    if (0 < nroContactosBorrados) {
        return confirm('Este asesor tiene ' + nroContactosBorrados +
                            " 'Contactos Iniciales borrados', " +
                            'esta seguro de querer borrar sus datos de la base de datos?');
    }
    return confirm('Realmente, desea borrar los datos de este asesor de la base de datos?')
//  submit();
}
</script>
