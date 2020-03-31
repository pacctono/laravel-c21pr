function confirmar(texto, tamano, funcion) {
    bootbox.confirm({
        message: texto,
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Si',
                //className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                //className: 'btn-danger'
            }
        },
        size: tamano,
        locale: 'es',
        callback: function(accion) {
            funcion(accion);
        }
    });
}