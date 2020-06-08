        var deseos, deseosC, deseosV, tipos, ciudades, zonas, ddns, arrContacto;

        $.ajax({url: "/welcome/ajax",       // @ajaxWelcome
            success: function(resultado) {
                    deseos  = JSON.parse(resultado[0]);    // $.parseJSON ha sido o sera deprecated.
                    deseosC = JSON.parse(resultado[1]);    // Desea comprar o alquilar.
                    deseosV = JSON.parse(resultado[2]);     // Desea vender o dar en alquiler.
                    tipos = JSON.parse(resultado[3]);       // $.parseJSON ha sido o sera deprecated.
                    precios  = JSON.parse(resultado[4]);       // Precios para compra/venta.
                    preciosA = JSON.parse(resultado[5]);       // Precios para alquilar o dar en alquiler.
                    ciudades = JSON.parse(resultado[6]);       // $.parseJSON ha sido o sera deprecated.
                    zonas = JSON.parse(resultado[7]);       // $.parseJSON ha sido o sera deprecated.
                    ddns = JSON.parse(resultado[8]);       // $.parseJSON ha sido o sera deprecated.
                    arrContacto = {                         // Usado para contactarnos.
                            ddn: ddns,
                            deseo: deseos,
                            tipo: tipos,
                            precio: precios,
                            precioA: preciosA,
                            ciudad: ciudades,
                            zona: zonas
                    }
            },
            method: "get",
            data: { 'ajax': true },
            error: function() {
                alert("Problemas de conexion con el servidor");
            }
        });
