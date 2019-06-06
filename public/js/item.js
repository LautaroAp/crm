function justNumbers(e) {
    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 0) || (keynum == 8) || (keynum == 46))
        return true;

    return /\d/.test(String.fromCharCode(keynum));
}

function calculaSubtotal() {

    precio = (parseFloat($('#item_precio').val()));
    cantidad = (parseFloat($('#cantidad').val()));
    descuento = (parseFloat($('#descuento').val()));
    iva = (parseFloat($("#iva option:selected").html()));

    precio_dto = (precio - ((descuento * precio) / 100));
    precio_iva = (precio + ((iva * precio) / 100));
    precio_dto_iva = (precio_dto + ((iva * precio_dto) / 100));

    subtotal = (precio_dto_iva * cantidad).toFixed(2);


    // Setea subtotal
    if (subtotal){
        // $("#subtotal").val(subtotal);
        $("#subtotal").val((parseFloat(subtotal).toFixed(2)));
    }
    else{
        $("#subtotal").val('0.00');
    }
}


