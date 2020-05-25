        $(".chequeado").change(function(ev) {
            if ('' == $("#telefono").val()) return;
            const that = $(this);
            const app = that.attr('id').substr(-2);     // Devuelve 'wa' o 'te'.
            if (that.is(":checked")) {
                $("#ddn"+app).val($("#ddn").val());
                $("#"+app).val($("#telefono").val());
            } else if (-1 !== document.location.href.indexOf('nuevo')) {
                $("#ddn"+app).val('414');
                $("#"+app).val('');
            }
        })
