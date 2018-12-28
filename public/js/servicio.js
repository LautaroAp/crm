function justNumbers(e) {
    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 0) || (keynum == 8) || (keynum == 46))
        return true;

    return /\d/.test(String.fromCharCode(keynum));
}

function calcularPrecios() {
    // Setea el precios en caso de Null & NaN
    if (($("#precio_venta").val()) == "") {
        $("#precio_venta").val("0");
        calcularPrecios();
    }
    $precio = (parseFloat($('#precio_venta').val()));
    $descuento = (parseFloat($('#descuento').val()));
    $iva = (parseFloat($('#iva').val()));
    $precio_dto = ($precio - (($descuento * $precio) / 100));
    $precio_iva = ($precio + (($iva * $precio) / 100));
    $precio_dto_iva = ($precio_dto + (($iva * $precio_dto) / 100));
    $total_iva = ($precio_iva - $precio);
    $precio = ($precio).toFixed(2);
    // Setea el precio publico
    if ($precio) {
        $("#precio_publico").val($precio);
    } else {
        $("#precio_publico").val("0");
    }
    // Setea el precio publico con descuento
    if ($descuento) {
        $precio_dto_f = ($precio_dto).toFixed(2);
        $("#precio_publico_dto").val($precio_dto_f);
    } else {
        $("#precio_publico_dto").val($precio);
    }
    // Setea el precio publico con iva
    $precio_publico_sin_iva = (parseFloat($('#precio_publico_dto').val())).toFixed(2);
    if ($iva) {
        $precio_iva_f = ($precio_iva).toFixed(2);
        $("#precio_publico_iva").val($precio_iva_f);
    } else {
        $("#precio_publico_iva").val($precio);
    }
    // Setea el precio publico con iva y descuento
    if ($iva) {
        $precio_dto_iva_f = ($precio_dto_iva).toFixed(2);
        $("#precio_publico_iva_dto").val($precio_dto_iva_f);
    } else {

        $("#precio_publico_iva_dto").val($precio_publico_sin_iva);
    }

    if ($iva) {
        $total_iva_f = ($total_iva).toFixed(2);
        $("#iva_total").val($total_iva_f);
    } else {
        $("#iva_total").val("0");
    }
}

function calculaPrecioDescuento() {
    $precio = parseFloat($('#precio_venta').val()).toFixed(2);
    $descuento = parseFloat($('#descuento').val()).toFixed(2);
    if ($precio && $descuento) {
        $precio_dto = $precio - (($descuento * $precio) / 100).toFixed(2);
        $("#precio_publico_dto").val($precio_dto);
        $("#precio_publico_iva_dto").val($precio_dto);
        $("#precio_publico_iva").val($precio_dto);
    }
}