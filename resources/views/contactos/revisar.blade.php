<script>
  @includeIf('include.alertar')
  @includeIf('include.confirmar')
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
    @if (('contactos' == substr($view_name, 0, 9)) && ('crear' == substr($view_name, -5)))
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
    @endif (('contactos' == substr($view_name, 0, 9)) && ('crear' == substr($view_name, -5)))
        },
        method: "get",
        data: { 'ajax': true },
        error: function() {
            alert("No se realizo una buena conexion con el servidor; no se podra " +
                  "verificar el telefono, ni cambiar la tabla de precios para alquileres.")
        }
    });
    $("#cedula").change(function(ev) {
      const cedula = $(this).val();
      const vci = vCedulas[cedula];
      if (vci) var vc = vClientes[vci.id + vci.tp];
      else return;
    @if ('editar' == substr($view_name, -6))
      if (vci && vc && (vci.id != $("#id").val())) {  // Este 'if' solo se ejecuta, si estamos en la vista 'editar'.
    @elseif ('crear' == substr($view_name, -5))
      if (vci && vc) {                  // Este 'if' solo se ejecuta, si estamos en la vista 'crear'.
    @endif ('crear' == substr($view_name, -5))
        alertar(`Este {{ ('cliente' == substr($view_name, 0, 7))?'cliente':'contacto inicial' }} ` +
              `fue creado por ${asesores[vc.uid]} el ${vc.fc} a las ${vc.ho}; como ` +
          //vc.id + '-' + $("#id").val() + ' ' +
              (('I'==vc.tp)?'contacto inicial':'cliente') + `, con nombre: ${vc.nb}.`, 'Cedula repetida');
        $(this).focus();
      }
    });
    $("#ddn,#telefono").change(function(ev) {
      const telefono = $("#ddn").val() + $("#telefono").val();
      const vt = vTelefonos[telefono];
      if (vt) var vc = vClientes[vt.id + vt.tp];
      else return;
    @if ('editar' == substr($view_name, -6))
      if (vt && vc && (vt.id != $("#id").val())) {  // Este 'if' solo se ejecuta, si estamos en la vista 'editar'.
    @elseif ('crear' == substr($view_name, -5))
      if (vt && vc) {                  // Este 'if' solo se ejecuta, si estamos en la vista 'crear'.
    @endif ('crear' == substr($view_name, -5))
        alertar(`Este {{ ('cliente' == substr($view_name, 0, 7))?'cliente':'contacto inicial' }} ` +
              `fue creado por ${asesores[vc.uid]} el ${vc.fc} a las ${vc.ho}; como ` +
          //vc.id + '-' + $("#id").val() + ' ' +
              (('I'==vc.tp)?'contacto inicial':'cliente') + `, con nombre: ${vc.nb}`, 'Telefono repetido');
        $(this).focus();
      }
    });
    $("#email").change(function(ev) {
      const email = $(this).val();
      const ve = vCorreos[email];
      if (ve) var vc = vClientes[ve.id + ve.tp];
      else return;
    @if ('editar' == substr($view_name, -6))
      if (ve && vc && (ve.id != $("#id").val())) {  // Este 'if' solo se ejecuta, si estamos en la vista 'editar'.
    @elseif ('crear' == substr($view_name, -5))
      if (ve && vc) {
    @endif ('crear' == substr($view_name, -5))
        alertar(`Este {{ ('cliente' == substr($view_name, 0, 7))?'cliente':'contacto inicial' }} ` +
              `fue creado por ${asesores[vc.uid]} el ${vc.fc} a las ${vc.ho}; como ` +
          //vc.id + '-' + $("#id").val() + ' ' +
              (('I'==vc.tp)?'contacto inicial':'cliente') + `, con nombre: ${vc.nb }`, 'Correo repetido');
        $(this).focus();
      }
    });

    @if (('contactos' == substr($view_name, 0, 9)) && ('crear' == substr($view_name, -5)))
    $("#deseo").change(function(ev) {
      const deseo = $(this).val();
      const descr = $("option:selected", this).text();  // opcion seleccionada en el 'this' ambito.
      //const descr = $(this).children("option:selected").text();  // FUNCIONA. Toma 'option:selected' de todos los hijos diretos.
      const precio = $("select#precio").val();
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
      const resultado = $(this).val();
      if (('' == resultado) || (4 > parseInt(resultado)) || (7 < parseInt(resultado))) {
        $("#fecha_evento").prop('disabled', true);
        $("#hora_evento").prop('disabled', true);
        return;
      }
      let tipo;
      if (4 == parseInt(resultado)) tipo = 'llamada';
      else tipo = 'cita';
      alertar(`Como resultado de este contacto inicial, usted debe realizar una '${tipo}', ` +
            `suministre la fecha y hora de la '${tipo}'`);
      $("#fecha_evento").prop('disabled', false);
      $("#hora_evento").prop('disabled', false);
      $("#fecha_evento").focus();
    });
    $("#fecha_evento").change(function(ev) {
      const resultado = $("#resultado_id").val();
      if (('' == resultado) || (4 > parseInt(resultado)) || (7 < parseInt(resultado))) {
        if  ('' != $(this).val()) {
          alertar("Esta fecha, solo es necesaria, en caso que el Resultado sea llamar o " +
              "se haya concretado una cita. Si, todavia desea incluir esta fecha, debe" +
              " tambien, incluir la hora.");
          $("#hora_evento").focus();
        }
      } else {
        if  ('' == $(this).val()) {
          alertar("Recuerde suministrar la fecha de la llamada o cita.");
          $(this).focus();
        } else {
          $("#hora_evento").focus();
        }
      }
    });
    @endif (('contactos' == substr($view_name, 0, 9)) && ('crear' == substr($view_name, -5)))

    $("#formulario").submit(function(ev) {
      const telefono = $("#ddn").val() + $("#telefono").val();
      const vt = vTelefonos[telefono];
      if (vt) var vc = vClientes[vt.id + vt.tp];
      else return;
    @if ('editar' == substr($view_name, -6))
      if (vt && vc && (vt.id != $("#id").val())) {  // Este 'if' solo se ejecuta, si estamos en la vista 'editar'.
    @elseif ('crear' == substr($view_name, -5))
      if (vt && vc) {
    @endif ('crear' == substr($view_name, -5))
        let accion = confirm(`Este {{ ('cliente' == substr($view_name, 0, 7))?'cliente':'contacto inicial' }} ` +
              `fue creado por ${asesores[vc.uid]} el ${vc.fc} a las ${vc.ho}; como ` +
                //vc.id + '-' + $("#id").val() + ' ' +
              (('I'==vc.tp)?'contacto inicial':'cliente') + ', con nombre: ' + vc.nb + '.\n' +
              `Desea continuar creando este '{{ ("cliente" == substr($view_name, 0, 7))?"Cliente":"Contacto inicial" }}'?`);
        if (!accion) {
          ev.preventDefault();
          $("#telefono").focus();
        }
      }
    });
  });

</script>
