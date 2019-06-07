var arrayMonedas;
var arrayFormasPago;
var arrayFormasEnvio;
var recargo=null;
var bonificacion = null;

function completarMonedas(monedas, transaccion=null){
    console.log(empresa);
    arrayMonedas=monedas;
    var myDiv = document.getElementById("monedas");
    var selectList = document.getElementById("select_monedas");
    if(selectList != null){
        if (transaccion){
            if ("Moneda" in transaccion == true){
                $("#select_monedas").val(transaccion["Moneda"]["Id"]);
            }
        }
    }
    //Create and append select list
    else {
        var selectList = document.createElement("select");
        selectList.id = "select_monedas";
        selectList.name="moneda";
        selectList.setAttribute("class", "form-control");
        // SI ES FACTURA "Disable all except the selected option")
        if(tipoTransaccion == "factura"){
            selectList.setAttribute("readonly", true);
        }
        myDiv.appendChild(selectList);
        //Create and append the options
        var option = document.createElement("option");
        if (transaccion){
            if ("Moneda" in transaccion == true){
                option.value = transaccion["Moneda"]["Id"];
                option.text = transaccion["Moneda"]["Nombre"];
            }
            else{
                option.value = empresa["Moneda"]["Id"];
                option.text = empresa["Moneda"]["Nombre"];
                if(tipoTransaccion == "factura"){
                    option.setAttribute("disabled", true);
                }
            }
        }
        else{
            option.value = empresa["Moneda"]["Id"];
            option.text = empresa["Moneda"]["Nombre"];
        }
        option.setAttribute("hidden","");
        selectList.appendChild(option);
        for (var i = 0; i < monedas.length; i++) {
            var option = document.createElement("option");
            option.value = monedas[i]['Id'];
            option.text = monedas[i]['Nombre'];
            if(tipoTransaccion == "factura"){
                option.setAttribute("disabled", true);
            }
            selectList.appendChild(option);
        }
    }
}

function completarIvas (ivas){
    var myDiv = document.getElementById("ivas");
    var selectList = document.createElement("select");
    selectList.id = "select_ivas";
    selectList.name="iva";
    selectList.setAttribute("class", "form-control");
    myDiv.appendChild(selectList);
    //Create and append the options
    var option = document.createElement("option");
    option.value = "-1";
    option.text = "Seleccionar";
    option.setAttribute("hidden","");
    selectList.appendChild(option);
    for (var i = 0; i < ivas.length; i++) {
        var option = document.createElement("option");
        option.value = i;
        option.text = ivas[i]['Valor'];
        selectList.appendChild(option);
    }
}

function completarTiposFactura (tiposFactura, tipoComprobantePersona){
    var myDiv = document.getElementById("tipos_factura");
    var selectList = document.createElement("select");
    selectList.id = "select_facturas";
    selectList.name="tipo_comprobante";
    selectList.setAttribute("class", "form-control");
    selectList.required=true;    
    myDiv.appendChild(selectList);
    //Create and append the options
    var option = document.createElement("option");
    if ((tipoComprobantePersona=="-1") ||(tipoComprobantePersona==="")){
           option.value = "";
           option.text = "Seleccionar";
    }
    else{
        option.value = tipoComprobantePersona['Id'];
        option.text = tipoComprobantePersona['Nombre'];
    }
    option.setAttribute("hidden","");
    selectList.appendChild(option);
    for (var i = 0; i < tiposFactura.length; i++) {
        var option = document.createElement("option");
        option.value = tiposFactura[i]['Id'];
        option.text = tiposFactura[i]['Tipo'];
        selectList.appendChild(option);
    }
}

function completarFormasPago(formasPago, transaccion=null){
    arrayFormasPago=formasPago;
    var myDiv = document.getElementById("formasPago");
    var selectList = document.getElementById("forma_pago");
    if(selectList != null){
        if (transaccion){
            if ("Forma de Pago" in transaccion == true){
                $("#forma_pago").val(transaccion["Forma de Pago"]["Id"]).change();
            }
        }
    }
    //Create and append select list
    else {    
        selectList = document.createElement("select");
        selectList.id = "forma_pago";
        selectList.name="forma_pago";
        selectList.setAttribute("onchange", "changeBonificacionRecargo()");
        selectList.setAttribute("class", "form-control");
        myDiv.appendChild(selectList);
        //Create and append the options
        var option = document.createElement("option");
        if (transaccion){
            if ("Forma de Pago" in transaccion == true){
                option.value = transaccion["Forma de Pago"]["Id"];
                option.text = transaccion["Forma de Pago"]["Nombre"];
                recargo= parseFloat(transaccion["Recargo general"]).toFixed(2);
                bonificacion = parseFloat(transaccion["Bonificacion general"]).toFixed(2);
            }
            else{
                option.value = "-1";
                option.text = "Seleccionar Forma de Pago";
            }
        }
        else{
            option.value = "-1";
            option.text = "Seleccionar Forma de Pago";
        }

        option.setAttribute("hidden","");
        selectList.appendChild(option);

        var option = document.createElement("option");
        option.value = "-1";
        option.text = "No definido";
        selectList.appendChild(option);

        for (var i = 0; i < formasPago.length; i++) {
            var option = document.createElement("option");
            option.value = formasPago[i]['Id'];
            option.text = formasPago[i]['Nombre'];
            selectList.appendChild(option);
        }
    
    }

}


function completarFormasEnvio(formasEnvio, transaccion=null){
    arrayFormasEnvio=formasEnvio;
    var myDiv = document.getElementById("formasEnvio");
    //Create and append select list
    var selectList = document.createElement("select");
    selectList.id = "forma_envio";
    selectList.name="forma_envio";
    // selectList.setAttribute("onchange", "changeBonificacionRecargo()");
    selectList.setAttribute("class", "form-control");
    myDiv.appendChild(selectList);
    //Create and append the options
    var option = document.createElement("option");
    if (transaccion){
        if ("Forma de Envio" in transaccion == true){
            option.value = transaccion["Forma de Envio"]["Id"];
            option.text = transaccion["Forma de Envio"]["Nombre"];
        }
        else{
            option.value = "-1";
            option.text = "Seleccionar Forma de Envio";
        }
    }
    else{
        option.value = "-1";
        option.text = "Seleccionar Forma de Envio";
    }
    option.setAttribute("hidden","");
    selectList.appendChild(option);
    var option = document.createElement("option");
    option.value = "-1";
    option.text = "No definido";
    selectList.appendChild(option);
    for (var i = 0; i < formasEnvio.length; i++) {
        var option = document.createElement("option");
        option.value = formasEnvio[i]['Id'];
        option.text = formasEnvio[i]['Nombre'];
        selectList.appendChild(option);
    }
}

function setBonificacionRecargo(){
    if (recargo!=null){
        $("#recargo_general").val(recargo);
    }
    else{
        $("#recargo_general").val("0.00");
    }
    if (bonificacion!=null){
        $("#bonificacion_general").val(bonificacion);
    }
    else{
        $("#bonificacion_general").val("0.00")
    }
}

function getPosId(array, id){
    for(var i = 0; i < array.length; i++){
        if (array[i]["Id"] == id)
            return i;
    }
    return null;
}


function changeBonificacionRecargo(){

    selectedId = $("#forma_pago option:selected").val();
    if (selectedId!="-1"){
        var selectedPos = getPosId(arrayFormasPago, selectedId);
        var recargo = arrayFormasPago[selectedPos]['Recargo'];
        var bonificacion = arrayFormasPago[selectedPos]['Bonificacion'];
        if(recargo){
            $("#recargo_general").val(parseFloat(recargo).toFixed(2));
        }
        else {
            $("#recargo_general").val("0.00");
        }
        if (bonificacion){
            $("#bonificacion_general").val(parseFloat(bonificacion).toFixed(2));
        }
        else{
            $("#bonificacion_general").val("0.00");
        }
    }
    else{
        $("#recargo_general").val("0.00");
        $("#bonificacion_general").val("0.00");
    }
    calcularSubcampos();
    

}
