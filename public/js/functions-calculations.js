// C A L C U L O     P R O D U C T O S
// $('#precio_compra').change(calculaCostoTotalCompra);

function justNumbers(e) {
    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 8) || (keynum == 46))
    return true;
     
    return /\d/.test(String.fromCharCode(keynum));
    }
    
    // Precio Venta
    function calculaCostoTotalCompra() {
        // Precio Total de Compra
        $total = parseFloat($('#precio_compra').val());
        $total = $total + parseFloat($('#costos_directos').val());
        $total = ($total + parseFloat($('#gastos_directos').val())).toFixed(2);
        if ($total){
            $("#precio_compra_total").val($total);
        } else {
            $("#precio_compra_total").val("0");
        }
        // Precio Venta
        calculaCMPorcentual();
        // Precio Final
        calculaDescuentoIVA();
    }
    
    // Contribucion Marginal Valor
    function calculaCMValor() {
        $("#cm_valor").removeClass("prod-imput-gray");
        // Precio de Venta
        $c_total = parseFloat($('#precio_compra_total').val());
        $cm_valor = parseFloat($('#cm_valor').val());
        $p_venta = ($c_total + $cm_valor).toFixed(2);
        if ($p_venta){
            $("#precio_venta").val($p_venta);
            $("#precio_venta").addClass("prod-imput-gray");
        } else {
            $("#precio_venta").val("0");
        }
        // Contribucion Marginal Porcentual
        $cm_porcentual = (($cm_valor * 100) / $c_total).toFixed(2);
        if ($cm_porcentual){
            $("#cm_porcentual").val($cm_porcentual);
            $("#cm_porcentual").addClass("prod-imput-gray");
        } else {
            $("#cm_porcentual").val("0");
        }
        // Precio Final
        calculaDescuentoIVA();
    }
    
    // Contribucion Marginal Porcentual
    function calculaCMPorcentual() {
        $("#cm_porcentual").removeClass("prod-imput-gray");
        // Precio de Venta
        $c_total = parseFloat($('#precio_compra_total').val());
        $cm_porcentual = parseFloat($('#cm_porcentual').val()) / 100;
        $p_venta = (($c_total * $cm_porcentual) + $c_total).toFixed(2);
        if ($p_venta){
            $("#precio_venta").val($p_venta);
            $("#precio_venta").addClass("prod-imput-gray");
        } else {
            $("#precio_venta").val("0");
        }
        // Contribucion Marginal Valor
        $cm_valor = ($cm_porcentual * $c_total).toFixed(2);
        if ($cm_valor){
            $("#cm_valor").val($cm_valor);
            $("#cm_valor").addClass("prod-imput-gray");
        } else {
            $("#cm_valor").val("0");
        }
        // Precio Final
        calculaDescuentoIVA();
    }
    
    // Precio de Venta
    function calculaPrecioVenta() {
        $("#precio_venta").removeClass("prod-imput-gray");
        $c_total = parseFloat($('#precio_compra_total').val());
        $p_venta = parseFloat($('#precio_venta').val());
        // Contribucion Marginal Valor
        $cm_valor = ($p_venta - $c_total).toFixed(2);
        if ($cm_valor){
            $("#cm_valor").val($cm_valor);
            $("#cm_valor").addClass("prod-imput-gray");
        } else {
            $("#cm_valor").val("0");
        }
        // Contribucion Marginal Porcentual
        $cm_porcentual = (($cm_valor * 100) / $c_total).toFixed(2);
        if ($cm_porcentual){
            $("#cm_porcentual").val($cm_porcentual);
            $("#cm_porcentual").addClass("prod-imput-gray");
        } else {
            $("#cm_porcentual").val("0");
        }
        // Precio Final
        calculaDescuentoIVA();
    }
    
    // Descuento y IVA
    function calculaDescuentoIVA() {
        $p_venta = parseFloat($('#precio_venta').val());
        $descuento = parseFloat($('#descuento').val()) / 100;
        $iva = parseFloat($('#iva').val()) / 100;
        // Precio Venta con Dto.
        $p_venta_dto = ($p_venta - ($p_venta * $descuento)).toFixed(2);
        if ($p_venta_dto){
            $("#precio_venta_dto").val($p_venta_dto);
        } else {
            $("#precio_venta_dto").val("0");
        }
        // Total IVA.
        $iva_gravado = ($p_venta * $iva).toFixed(2);
        if ($p_venta_dto){
            $("#iva_gravado").val($iva_gravado);
        } else {
            $("#iva_gravado").val("0");
        }
        // Precio Publico + IVA
        $p_final_iva = ($p_venta + ($p_venta * $iva)).toFixed(2);
        if ($p_final_iva){
            $("#precio_final_iva").val($p_final_iva);
        } else {
            $("#precio_final_iva").val("0");
        }
        // Precio Publico + IVA + Dto.
        $p_final_iva_dto = ( ($p_venta - ($p_venta * $descuento)) + (($p_venta - ($p_venta * $descuento)) * $iva) ).toFixed(2);
        if ($p_final_iva_dto){
            $("#precio_final_iva_dto").val($p_final_iva_dto);
        } else {
            $("#precio_final_iva_dto").val("0");
        }
    }
    

// C A L C U L O     S E R V I C I O S

function calcularPrecios(){
    $precio = (parseFloat($('#precio_venta').val()));
    $descuento = (parseFloat($('#descuento').val()));
    $iva = (parseFloat($('#iva').val()));
    $precio_dto = ($precio - (($descuento * $precio)/100));
    $precio_iva = ($precio + (($iva * $precio)/100));
    $precio_dto_iva= ($precio_dto + (($iva * $precio_dto)/100));
    $total_iva= ($precio_iva-$precio);
    $precio = ($precio).toFixed(2);
    //setea el precio publico

    if($precio){
        $("#precio_publico").val($precio);
    }
    else{
        $("#precio_publico").val("0");
    }
    //setea el precio publico con descuento
    if($descuento){
        $precio_dto_f= ($precio_dto).toFixed(2);
        $("#precio_publico_dto").val($precio_dto_f);
    }
    else{
        $("#precio_publico_dto").val($precio);
    }
    //setea el precio publico con iva
    $precio_publico_sin_iva= (parseFloat($('#precio_publico_dto').val())).toFixed(2);
    if($iva){
        $precio_iva_f= ($precio_iva).toFixed(2);
        $("#precio_publico_iva").val($precio_iva_f);
    }
    else{
        $("#precio_publico_iva").val($precio);
    }
    //setea el precio publico con iva y descuento

    if ($iva){
        $precio_dto_iva_f=($precio_dto_iva).toFixed(2);
        $("#precio_publico_iva_dto").val($precio_dto_iva_f);
    }else{

        $("#precio_publico_iva_dto").val($precio_publico_sin_iva);
    }     

    if ($iva){
        $total_iva_f=($total_iva).toFixed(2);
        $("#iva_total").val($total_iva_f);
    }else{
        $("#iva_total").val("0");
    }     
}


function calculaPrecioDescuento(){
    $precio = parseFloat($('#precio_venta').val()).toFixed(2);
    $descuento =parseFloat($('#descuento').val()).toFixed(2);
    if ($precio && $descuento){
        $precio_dto = $precio - (($descuento * $precio)/100).toFixed(2);
        $("#precio_publico_dto").val($precio_dto);
        $("#precio_publico_iva_dto").val($precio_dto);
        $("#precio_publico_iva").val($precio_dto);
    }
}


