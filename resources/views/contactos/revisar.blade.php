<script>
  $(document).ready(function() {
    var descrComprar = '';
    var descrAlquilar = '';
    var asesores, precios, vClientes, vCedulas, vTelefonos, vCorreos;
    $.ajax({url: "/clientes/vClientes",
        success: function(resultado) {
                asesores  = $.parseJSON(resultado[0]);
                var precios = $.parseJSON(resultado[1]);
                vClientes = $.parseJSON(resultado[2]);
                vCedulas = $.parseJSON(resultado[3]);
                vTelefonos = $.parseJSON(resultado[4]);
                vCorreos = $.parseJSON(resultado[5]);
                //alert(vTelefonos[4121030341].nb+';'+vTelefonos[4121030341].uid+';'+asesores[vTelefonos[4121030341].uid]);
                $.each(precios, function(idx, val) {
                      descrComprar +=
                            "        <option value='" + val[0] + "'>" +
                            "          " + val[1] +
                            "        </option>";
                      descrAlquilar +=
                            "        <option value='" + val[0] + "'>" +
                            "          " + val[2] +
                            "        </option>";
                });
        },
        method: "get",
        data: { 'ajax': true },
        error: function() {
            alert("No se realizo una buena conexion con el servidor; no se podra " +
                  "verificar el telefono, ni cambiar la tabla de precios para alquileres.")
        }
    });
    $("#cedula").change(function(ev) {
      var cedula = $(this).val();
      var vci = vCedulas[cedula];
      if (vci) var vc = vClientes[vci.id + vci.tp];
    @if ('editar' == $vista)
      if (vci && vc && ('I' == vci.tp) && (vci.id != $("#id").val())) {  // Este 'if' solo se ejecuta, si estamos en la vista 'editar'.
    @elseif ('crear' == $vista)
      if (vci && vc) {
    @endif ('editar' == $vista)
        alert("Este contacto inicial fue creado por " + asesores[vc.uid] + ' el ' +
          vc.fc + ' a las ' + vc.ho + '; como ' +
          //vc.id + '-' + $("#id").val() + ' ' +
          (('C'==vc.tp)?'cliente':'contacto inicial') + ', con nombre: ' + vc.nb + '.');
        $(this).focus();
      }
    });
    $("#ddn,#telefono").change(function(ev) {
      var telefono = $("#ddn").val() + $("#telefono").val();
      var vt = vTelefonos[telefono];
      if (vt) var vc = vClientes[vt.id + vt.tp];
    @if ('editar' == $vista)
      if (vt && vc && ('I' == vt.tp) && (vt.id != $("#id").val())) {  // Este 'if' solo se ejecuta, si estamos en la vista 'editar'.
    @elseif ('crear' == $vista)
      if (vt && vc) {
    @endif ('editar' == $vista)
        alert("Este contacto inicial fue creado por " + asesores[vc.uid] + ' el ' +
          vc.fc + ' a las ' + vc.ho + '; como ' +
          //vc.id + '-' + $("#id").val() + ' ' +
          (('C'==vc.tp)?'cliente':'contacto inicial') + ', con nombre: ' + vc.nb + '.');
        $(this).focus();
      }
    });
    $("#email").change(function(ev) {
      var email = $(this).val();
      var ve = vCorreos[email];
      if (ve) var vc = vClientes[ve.id + ve.tp];
    @if ('editar' == $vista)
      if (ve && vc && ('I' == ve.tp) && (ve.id != $("#id").val())) {  // Este 'if' solo se ejecuta, si estamos en la vista 'editar'.
    @elseif ('crear' == $vista)
      if (ve && vc) {
    @endif ('editar' == $vista)
        alert("Este contacto inicial fue creado por " + asesores[vc.uid] + ' el ' +
          vc.fc + ' a las ' + vc.ho + '; como ' +
          //vc.id + '-' + $("#id").val() + ' ' +
          (('C'==vc.tp)?'cliente':'contacto inicial') + ', con nombre: ' + vc.nb + '.');
        $(this).focus();
      }
    });

    @if ('crear' == $vista)
    $("#deseo").change(function(ev) {
      var deseo = $(this).val();
      var descr = $("option:selected", this).text();  // opcion seleccionada en el 'this' ambito.
      //var descr = $(this).children("option:selected").text();  // FUNCIONA. Toma 'option:selected' de todos los hijos diretos.
      var precio = $("select#precio").val();
      //alert('Deseo:' + deseo + '-' + descr + ' (' + precio + ').');
      //alert(descrAlquilar);
      //alert(descrComprar);
      if (('' != descrComprar) && ('' != descrAlquilar)) {
        $("select#precio").empty();
        if ((1 == deseo) || (2 == deseo)) $("select#precio").html(descrComprar);
        else if ((3 == deseo) || (4 == deseo)) $("select#precio").html(descrAlquilar);
      }
    });
    $("#resultado_id").change(function(ev) {
      var resultado = $(this).val();
      if (('' == resultado) || (4 > parseInt(resultado)) || (7 < parseInt(resultado))) {
        $("#fecha_evento").prop('disabled', true);
        $("#hora_evento").prop('disabled', true);
        return;
      }
      var tipo;
      if (4 == parseInt(resultado)) tipo = 'llamada';
      else tipo = 'cita';
      alert("Como resultado de este contacto inicial, usted debe realizar una '" + tipo +
        "', suministre la fecha y hora de la '" + tipo + "'");
      $("#fecha_evento").prop('disabled', false);
      $("#hora_evento").prop('disabled', false);
      $("#fecha_evento").focus();
    });
    $("#fecha_evento").change(function(ev) {
      var resultado = $("#resultado_id").val();
      if (('' == resultado) || (4 > parseInt(resultado)) || (7 < parseInt(resultado))) {
        if  ('' != $(this).val()) {
          alert("Esta fecha, solo es necesaria, en caso que el Resultado sea llamar o " +
              "se haya concretado una cita. Si, todavia desea incluir esta fecha, debe" +
              " tambien, incluir la hora.");
          $("#hora_evento").focus();
        }
      } else {
        if  ('' == $(this).val()) {
          alert("Recuerde suministrar la fecha de la llamada o cita.");
          $(this).focus();
        } else {
          $("#hora_evento").focus();
        }
      }
    });
    @endif ('crear' == $vista)

    $("#formulario").submit(function(ev) {
      var telefono = $("#ddn").val() + $("#telefono").val();
      var vt = vTelefonos[telefono];
      if (vt) var vc = vClientes[vt.id + vt.tp];
    @if ('editar' == $vista)
      if (vt && vc && ('I' == vt.tp) && (vt.id != $("#id").val())) {  // Este 'if' solo se ejecuta, si estamos en la vista 'editar'.
    @elseif ('crear' == $vista)
      if (vt && vc) {
    @endif ('editar' == $vista)
        var accion = confirm("Este contacto inicial fue creado por " + asesores[vc.uid] +
                ' el ' + vc.fc + ' a las ' + vc.ho + '; como ' +
                //vc.id + '-' + $("#id").val() + ' ' +
                (('C'==vc.tp)?'cliente':'contacto inicial') + ', con nombre: ' + vc.nb +
                '.\n' + "Desea continuar creando este 'Contacto inicial'?");
        if (!accion) {
          ev.preventDefault();
          $("#telefono").focus();
        }
      }
    });
});

</script>
