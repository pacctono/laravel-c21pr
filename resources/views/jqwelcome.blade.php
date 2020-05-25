<script>
    $(function () {
        $(".mostrarTooltip").tooltip('enable')
    });
    @includeIf('include.botonesDialog')

    $(document).ready(function() {
        var ciudadc, ciudadv, negociacion, tipoc, tipov, nombre, telefono;
/*
 * ciudadc, ciudadv: ciudad donde va a comprar o vender. 'select'
 * negociacion: compra o alquila. 'select'
 * tipoc, tipov: Tipo de inmueble a comprar o vender. 'select'
 */
        $("#ciudadc,#ciudadv,#negociacion,#tipoc,#tipov").click(function(ev) {
            const that = $(this);
            const id = that.attr('id');
            let info, texto, arreglo;
            if (-1 !== id.indexOf('ciudad')) {
                texto = `la ciudad.`;
                arreglo = [
                    {text: 'Barcelona', value: 'Barcelona'},
                    {text: 'Lecheria', value: 'Lecheria'},
                    {text: 'Puerto La Cruz', value: 'Puerto La Cruz'},
                ]
            } else if (-1 !== id.indexOf('negociacion')) {
                texto = 'el tipo de negociacion.';
                arreglo = [
                    {text: 'Vender', value: 'V'},
                    {text: 'Alquilar', value: 'A'},
                ]
            } else if (-1 !== id.indexOf('tipo')) {
                texto = 'el tipo de inmueble.';
                arreglo = [
                    {text: 'Casa', value: 'Casa'},
                    {text: 'Apartamento', value: 'Apartamento'},
                ]
            } else return;
            bootbox.prompt({
                size: 'small',
                title: `Seleccione ${texto}`,
                inputType: 'select',
                inputOptions: arreglo,
                buttons: botones,
                callback: function(res) {
                    if (res) {
                        that.text(res);
                    }
                }
            });
        });
/*
 * nombre: Nombre del vendedor. 'text'
 * telefono: Número de teléfono contacto del vendedor. 'text'
 */
        $("#nombre,#telefono").click(function(ev) {
            const that = $(this);
            const id = that.attr('id');
        });
/*
 * comprar: Boton para enviar los datos del comprador. 'submit'
 * enviar: Enviar mensaje con la información del vendedor. 'submit'
 */
        $("#comprar,#enviar").click(function(ev) {
            const that = $(this);
            const id = that.attr('id');
        })
    })
</script>
  