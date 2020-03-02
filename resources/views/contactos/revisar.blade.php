<script>
  $(document).ready(function() {
    var descrComprar = '';
    var descrAlquilar = '';
    var asesores, precios, vClientes;
    $.ajax({url: "/clientes/vClientes", success: function(resultado) {
                asesores  = $.parseJSON(resultado[0]);
                var precios = $.parseJSON(resultado[1]);
                vClientes = $.parseJSON(resultado[2]);
                //alert(vClientes[4121030341].nb+';'+vClientes[4121030341].uid+';'+asesores[vClientes[4121030341].uid]);
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
        }, method: "get",
        data: { 'ajax': true },
        error: function() {
            alert("No se realizo una buena conexion con el servidor; no se podra " +
                  "verificar el telefono, ni cambiar la tabla de precios para alquileres.")
        }
    });
    $("#ddn,#telefono").change(function(ev) {
      var telefono = $("#ddn").val() + $("#telefono").val();
      var vc = vClientes[telefono];
    @if ('editar' == $vista)
      if (vc && ('I' == vc.tp) && (vc.id != $("#id").val()))  // Este 'if' solo se ejecuta, si estamos en la vista 'editar'.
    @endif ('editar' == $vista)
      if (vc) {
        alert("Este contacto inicial fue creado por " + asesores[vc.uid] + ' el ' +
          vc.fc + ' a las ' + vc.ho + '; como ' +
          //vc.id + '-' + $("#id").val() + ' ' +
          (('C'==vc.tipo)?'cliente':'contacto inicial') + ', con nombre: ' + vc.nb + '.');
        $(this).focus();
      }
    });

    @if ('crear' == $vista)
    $("#resultado_id").change(function(ev) {
      var resultado = $(this).val();
      if (('' == resultado) || (4 > parseInt(resultado)) || (7 < parseInt(resultado))) {
        return;
      }
      var tipo;
      if (4 == parseInt(resultado)) tipo = 'llamada';
      else tipo = 'cita';
      alert("Como resultado de este contacto inicial, usted debe realizar una '" + tipo +
        "', suministre la fecha y hora de la '" + tipo + "'");
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
    $("#deseo").change(function(ev) {
      var deseo = $(this).val();
      var descr = $("option:selected", this).text();  // opcion seleccionada en el 'this' ambito.
      //var descr = $(this).children("option:selected").text();  // FUNCIONA. Toma 'option:selected' de todos los hijos diretos.
      var precio = $("select#precio").val();
      //alert('Deseo:' + deseo + '-' + descr + ' (' + precio + ').');
      //alert(descrAlquilar);
      //alert(descrComprar);
      $("select#precio").empty();
      if ((1 == deseo) || (2 == deseo)) $("select#precio").html(descrComprar);
      else if ((3 == deseo) || (4 == deseo)) $("select#precio").html(descrAlquilar);
    });
    @endif ('crear' == $vista)

    $("#formulario").submit(function(ev) {
      var telefono = $("#ddn").val() + $("#telefono").val();
      var vc = vClientes[telefono];
    @if ('editar' == $vista)
      if (vc && ('I' == vc.tp) && (vc.id != $("#id").val())) {  // Este 'if' solo se ejecuta, si estamos en la vista 'editar'.
    @elseif ('crear' == $vista)
      if (vc) {
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
