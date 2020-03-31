function alertar(texto, titulo='', tamano='large', funcion=false) {
    bootbox.alert({
        message: texto,
        title: titulo,
        size: tamano,
        backdrop: true,
        locale: 'es',
        callback: function() {
            if (funcion) funcion();
        }
    });
}