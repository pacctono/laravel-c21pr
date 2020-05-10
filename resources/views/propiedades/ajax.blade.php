        var asesores, aPropiedades, estatus, negociaciones, aCodigos;

        $.ajax({url: "/propiedades/ajax",       // @ajaxPropiedades
            success: function(resultado) {
                    asesores  = JSON.parse(resultado[0]);       // $.parseJSON ha sido o sera deprecated.
                    aPropiedades = JSON.parse(resultado[1]);    // $.parseJSON ha sido o sera deprecated.
                    estatus = JSON.parse(resultado[2]);         // $.parseJSON ha sido o sera deprecated.
                    negociaciones = JSON.parse(resultado[3]);   // $.parseJSON ha sido o sera deprecated.
                    aCodigos = JSON.parse(resultado[4]);        // $.parseJSON ha sido o sera deprecated.
                    //alert(aPropiedades[aCodigos['915127'].id].nb+';'+aPropiedades[aCodigos['915127'].id].uid+';'+asesores[aPropiedades[aCodigos['915127'].id].uid]);
            },
            method: "get",
            data: { 'ajax': true },
            error: function() {
                alert("No se realizo una buena conexion con el servidor; no se podra " +
                    "verificar el 'codigo MLS'");
            }
        });
