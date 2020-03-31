<script>
@if ($movil)
function alertaFechaRequerida() {
  var fecha_desde = document.getElementById('fecha_desde').value;
  var fecha_hasta = document.getElementById('fecha_hasta').value;
  var asesor      = document.getElementById('asesor').value;

  if ('0' != asesor) {
    return true;
  }
  if ('' == fecha_desde) {
    alert("Usted tiene que suministrar la fecha 'Desde'");
    return false;
  }
  if ('' == fecha_hasta) {
    alert("Usted tiene que suministrar la fecha 'Hasta'");
    return false;
  }
  return true;
}
@else
  $(function () {
      $('[data-toggle="tooltip"]').tooltip('enable')
  })
  $(document).ready(function(){
    $("a.editarTurno").click(function(ev) {
      ev.preventDefault();
      var id  = $(this).attr('id');        // Tambien $(ev.target).attr('id')
      var sel = document.getElementById('sa'+id);
      var nombre = sel.options[sel.selectedIndex].text;
      var accion;
      accion = confirm('Desea cambiar al asesor ' + nombre + ' del turno.');
      if (accion) {
        //alert('sa'+id);
        $('#sa'+id).prop('disabled', false);
      }
    })
    $("select.asesor").change(function(ev) {
      var idSel = $(this).attr('id');        // Tambien $(ev.target).attr('id')
      var sel = document.getElementById(idSel); // No es necesario. Pude usar $this.
      var idAseNvo = sel.value;
      var arrIds = idSel.split('-');
      var idAseAct = arrIds[1];
      var nombre = sel.options[sel.selectedIndex].text;
// Debe funcionar, tambien.      var nombre = $("#" + idSel + " option:selected").text();
// Debe funcionar, tambien.      var nombre = $("#" + idSel).find("option:selected").text();
// Debe funcionar, tambien.      var nombre = $(this).find("option:selected").text();
      var idTurno = sel.name;     // Tambien podria sel idSel.split('-')[0].substr(2)
      var accion;
      //alert(idAseNvo+'|'+idAseAct+'|'+nombre+'|'+idTurno);
      if (idAseNvo != idAseAct) {
        accion = confirm('Desea colocar al asesor ' + nombre + ' en ese turno.');
        if (accion) {
          //alert('Se procedera a cambiar al asesor de este turno. Ir a:' + location.href);
          nvoUrl = '/turnos/editar/' + idTurno + '/' + idAseNvo;
          location.href = nvoUrl;
        } else {
          sel.value = idAseAct;
          $('#'+idSel).prop('disabled', true);  // $('#'+idSel) === $(this)
        }
      }
    })
  })
function alertaFechaRequerida() {
  var periodo    = document.getElementsByName('periodo');
  var fecha_desde = document.getElementById('fecha_desde').value;
  var fecha_hasta = document.getElementById('fecha_hasta').value;
  var asesor      = document.getElementById('asesor').value;
  var valorPeriodo;

  for (var i=0, len=periodo.length; i<len; i++) {
    if (periodo[i].checked) {
      valorPeriodo = periodo[i].value;
      break;
    }
  }
  if (('intervalo' != valorPeriodo) || ('0' != asesor)) {
    return true;
  }
  if ('' == fecha_desde) {
    alert("Usted ha seleccionado 'Intervalo' y tiene que suministrar la fecha 'Desde'");
    return false;
  }
  if ('' == fecha_hasta) {
    alert("Usted ha seleccionado 'Intervalo' y tiene que suministrar la fecha 'Hasta'");
    return false;
  }
  return true;
}
@endif
</script>
