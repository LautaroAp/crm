var arrayMonedas;
var arrayFormasPago;
var arrayFormasEnvio;
var recargo=null;
var bonificacion = null;
function completarMonedas(monedas){
    arrayMonedas=monedas;
    var myDiv = document.getElementById("monedas");

    //Create and append select list
    var selectList = document.createElement("select");
    selectList.id = "select_monedas";
    selectList.name="moneda";
    selectList.setAttribute("class", "form-control");
    myDiv.appendChild(selectList);
    console.log(monedas);
    //Create and append the options

    var option = document.createElement("option");
    option.value = "-1";
    option.text = "Seleccionar Moneda";
    option.setAttribute("hidden","");
    selectList.appendChild(option);


    var option = document.createElement("option");
    option.value = "-1";
    option.text = "NO DEFINIDO";
    selectList.appendChild(option);

    for (var i = 0; i < monedas.length; i++) {
        var option = document.createElement("option");
        option.value = monedas[i]['Id'];
        option.text = monedas[i]['Nombre'];
        selectList.appendChild(option);
    }
}

function completarFormasPago(formasPago, transaccion=null){
    arrayFormasPago=formasPago;
    var myDiv = document.getElementById("formasPago");

    //Create and append select list
    var selectList = document.createElement("select");
    selectList.id = "forma_pago";
    selectList.name="forma_pago";
    selectList.setAttribute("onchange", "changeBonificacionRecargo()");
    selectList.setAttribute("class", "form-control");
    myDiv.appendChild(selectList);
    console.log(formasPago);
    //Create and append the options
    var option = document.createElement("option");
    if (transaccion){
        if ("Forma de Pago" in transaccion == true){
        option.value = transaccion["Forma de Pago"]["Id"];
        option.text = transaccion["Forma de Pago"]["Nombre"];
        recargo= transaccion["Forma de Pago"]["Recargo"];
        bonificacion = transaccion["Forma de Pago"]["Bonificacion"];
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
    option.text = "NO DEFINIDO";
    selectList.appendChild(option);

    for (var i = 0; i < formasPago.length; i++) {
        var option = document.createElement("option");
        option.value = formasPago[i]['Id'];
        option.text = formasPago[i]['Nombre'];
        selectList.appendChild(option);
    }
}



function completarFormasEnvio(formasEnvio){
    arrayFormasEnvio= formasEnvio;
    var myDiv = document.getElementById("formasEnvio");

    //Create and append select list
    var selectList = document.createElement("select");
    selectList.id = "forma_envio";
    selectList.name = "forma_envio";
    selectList.setAttribute("class", "form-control");
    myDiv.appendChild(selectList);
    console.log(formasEnvio);
    //Create and append the options

    var option = document.createElement("option");
    option.value = "-1";
    option.text = "Seleccionar Forma de Envío";
    option.setAttribute("hidden","");
    selectList.appendChild(option);


    var option = document.createElement("option");
    option.value = "-1";
    option.text = "NO DEFINIDO";
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
        selectedPos = getPosId(arrayFormasPago, selectedId);
        recargo = arrayFormasPago[selectedPos]['Recargo'];
        bonificacion = arrayFormasPago[selectedPos]['Bonificacion'];
        $("#recargo_general").val(recargo);
        $("#bonificacion_general").val(bonificacion);
    }
    else{
        $("#recargo_general").val("0.00");
        $("#bonificacion_general").val("0.00");
    }
    calcularSubcampos();
    

}
