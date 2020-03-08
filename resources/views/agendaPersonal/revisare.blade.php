<script>
    $(document).ready(function(){
        $("div.nombres").hide();             // Se esconden las dos selecciones y el input.
        if ('0' < $("#contacto_id").val()) {    // Se muestra el select del contactos.
            $("#seleccion").val('C');
            $("div.contactos").show();
        } else if ('0' < $("#cliente_id").val()) {  // Se muestra el select del clientes.
            $("#seleccion").val('L');
            $("div.clientes").show();
        } else {
            $("#seleccion").val('N');   // Se muestra el input (texto) del name.
            $("div.inputNombre").show();
        }
        $('#seleccion').on('focusin', function(){
            console.log("Saving value " + $(this).val());
            $(this).data('val', $(this).val());
        });
        $("#seleccion").change(function(ev){
            var previo = $(this).data('val');
            var actual = $(this).val();
            console.log("Prev value " + previo);
            console.log("New value " + actual);
            if ('C' == $(this).val()) {
                if (1 >= $("#contacto_id option").length) {
                    alert("Usted no tiene contactos registrados que pueda seleccionar");
                    $(this).val(previo);
                    return;
                }
                $("div.nombres").hide();
                $("div.contactos").show();
                $("#cliente_id").val('0');
                $("#name").val('');
                alert('Seleccione el nombre de un contacto de la lista de ' +
                        'contactos iniciales registrados. Si son los mismos, no necesita ' +
                        'incluir: telefono, otro_telefono, email y direccion.');
                $("#contacto_id").focus();
            } else if ('L' == $(this).val()) {
                if (1 >= $("#cliente_id option").length) {
                    alert("Usted no tiene clientes registrados que pueda seleccionar");
                    $(this).val(previo);
                    return;
                }
                $("div.nombres").hide();
                $("div.clientes").show();
                $("#contacto_id").val('0');
                $("#name").val('');
                alert('Seleccione el nombre de un cliente de la lista de ' +
                        'clientes registrados. Si son los mismos, no necesita ' +
                        'incluir: telefono, otro_telefono, email y direccion.');
                $("#cliente_id").focus();
            } else {
                $("div.nombres").hide();
                $("div.inputNombre").show();
                $("#contacto_id").val('0');
                $("#cliente_id").val('0');
                alert('Suministre el nombre de la persona con quien realizara la cita');
                $("#name").focus();
            }
        })
        $("#formulario").submit(function(ev) {
            if (('0' == $("#contacto_id").val()) && ('0' == $("#cliente_id").val()) &&
                ('' == $("#name").val())) {
                ev.preventDefault();
                alert('Tiene que colocar un nombre, seleccionar un contacto o un cliente.');
                $("div.nombres").hide();
                $("div.inputNombre").show();
                $("#seleccion").val('N');
                $("#contacto_id").val('0');
                $("#cliente_id").val('0');
                $("#name").focus();
            }
        })
    })
</script>
