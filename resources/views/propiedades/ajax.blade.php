        var asesores, aPropiedades, estatus, negociaciones, aCodigos;

        $.ajax({url: "/propiedades/ajax",       // @ajPropiedades
            success: function(resultado) {
                    asesores  = $.parseJSON(resultado[0]);
                    aPropiedades = $.parseJSON(resultado[1]);
                    estatus = $.parseJSON(resultado[2]);
                    negociaciones = $.parseJSON(resultado[3]);
                    aCodigos = $.parseJSON(resultado[4]);
                    //alert(aPropiedades[aCodigos['915127'].id].nb+';'+aPropiedades[aCodigos['915127'].id].uid+';'+asesores[aPropiedades[aCodigos['915127'].id].uid]);
            },
            method: "get",
            data: { 'ajax': true },
            error: function() {
                alert("No se realizo una buena conexion con el servidor; no se podra " +
                    "verificar el 'codigo MLS'");
            }
        });
