function justNumbers(e) {
    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 0) || (keynum == 8) || (keynum == 46))
        return true;

    return /\d/.test(String.fromCharCode(keynum));
}

function calculaSubtotal() {

    $precio = (parseFloat($('#item_precio').val()));
    $dto = (parseFloat($('#item_dto').val()));

    $cantidad = (parseFloat($('#cantidad').val()));
    $bonificacion = (parseFloat($('#bonificacion').val()));
    $iva = (parseFloat($("#iva option:selected").html()));

    $precio_dto = ($precio - (($dto * $precio) / 100));
    $precio_bonif = ($precio_dto - (($bonificacion * $precio) / 100));
    $precio_iva = ($precio_dto + (($iva * $precio_dto) / 100));
    $precio_bonif_iva = ($precio_bonif + (($iva * $precio_bonif) / 100));

    $subtotal = ($precio_bonif_iva * $cantidad).toFixed(2);

    // Setea subtotal
    if ($precio) {
        $("#subtotal").val($subtotal);
    } else {
        $("#subtotal").val("0");
    }
    
}


