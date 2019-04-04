var items=[];
var tipoTransaccion;
var idPersona;
function addItems(bienesTransacciones, tipo, id) {
    items = bienesTransacciones;
    tipoTransaccion = tipo; 
    idPersona=id;
    var col = ["Nombre", "Descripcion", "Cantidad", "Precio","Descuento","IVA", "Subtotal"];
    //TABLE HEADER
    var table = document.createElement("table");
    table.setAttribute("class", "table table-hover");
    table.setAttribute("role","button");
    table.setAttribute("id", "table_bienes");
    var tr = table.insertRow(-1);                   // TABLE ROW.
    for (var i = 0; i < col.length; i++) {
        var th = document.createElement("th");      // TABLE HEADER.
        th.innerHTML = col[i];
        tr.appendChild(th);
    }
    //TABLE BODY
    var value = null;
    for (var i = 0; i < items.length; i++) {
        var item = items[i]
        tr = table.insertRow(-1);
        // tr.onclick= selectItem(item["id"]);
        tr.setAttribute("id", i);
        tr.setAttribute("class", "click");
        // tr.setAttribute("onclick","selectItem(event,id)");
        for (var j = 0; j < col.length; j++) {
            var tabCell = tr.insertCell(-1);
            //el id de cada celda sería la ubicacion (i) y el atributo correspondiente (col[j]) separado por "_"
            tabCell.setAttribute("id", i+"_"+col[j]);
            tabCell.setAttribute("class", "click");
            if (col[j]=="Nombre" || col[j]=="Descripcion" || col[j]=="Precio"){
                value = item["Bien"][col[j]];
            }
            else if (col[j]=="IVA"){
                value = item["IVA"]["Valor"];
            }
            else{
                value = item[col[j]];
            }
            if (col[j]=="Cantidad"){
                tabCell.setAttribute("ondblclick", "makeEditable(event)");
            }
            if ((col[j] == "Descuento") || (col[j]=="IVA")){
                value = formatPercent((parseFloat(value)).toFixed(2));
                tabCell.setAttribute("ondblclick", "makeEditable(event)");
            }
            if ((col[j] == "Precio")  || (col[j]=="Subtotal")) {value = formatMoney(value);}

            tabCell.innerHTML = value;
        }
        var butt = document.createElement('button'); // create a button
        butt.setAttribute('type','button');
        butt.setAttribute('class','btn btn-default btn-sm glyphicon glyphicon-remove'); // set attributes ...
        butt.setAttribute('id',i);
        butt.setAttribute('value','Borrar');
        butt.setAttribute("onclick","removerBien2(event,id)");
        var tabCell = tr.insertCell(-1);
        tabCell.setAttribute("class", "click");
        tabCell.appendChild(butt);
        //   tr.cells[-1].appendChild(butt);
    }
    var divContainer = document.getElementById("contenido_bienes");
    divContainer.innerHTML = "";
    divContainer.appendChild(table);


}

function getItems(){
    return items;
}

function formatMoney(number) {
    return '$ '+ number.toLocaleString('en-US');
}

function formatPercent(number) {
    return number.toLocaleString('en-US') + ' %';
}


function calcularSubcampos(){

    var table = document.getElementById("table_bienes");
    var sumBonificacion = 0;
    var sumSubtotal=0;
    var sumIva=0;
    var bonificacion_general=0;
    for(var i = 1; i < table.rows.length; i++){
        precio_unitario = table.rows[i].cells[3].innerHTML;
        precio_unitario = parseFloat(precio_unitario.substring(2, precio_unitario.length));
        descuento = table.rows[i].cells[4].innerHTML;
        var descuento = descuento.substring(0, descuento.length-2);
        sumBonificacion = sumBonificacion + (parseFloat(descuento) * precio_unitario /100 );
        
        iva = table.rows[i].cells[5].innerHTML;
        var iva = iva.substring(0, iva.length-2);
        sumIva = sumIva + (parseFloat(iva) * precio_unitario/ 100) ;

        subtotal = table.rows[i].cells[6].innerHTML;
        subtotal = parseFloat(subtotal.substring(2, subtotal.length));
        sumSubtotal = sumSubtotal + parseFloat(subtotal);

    }

    bonificacion_general = $("#bonificacion_general").val();
    recargo_general = $("#recargo_general").val();
    if (!recargo_general){
        recargo_general=0;
    }
    var total_general = sumSubtotal - (sumSubtotal* bonificacion_general/100) + (sumSubtotal* recargo_general/100);
    $("#subtotal_general").val(formatMoney(parseFloat(sumSubtotal).toFixed(2)));
    $("#descuento_total").val(formatMoney(parseFloat(sumBonificacion).toFixed(2)));
    $("#iva_total").val(formatMoney(parseFloat(sumIva).toFixed(2)));
    $("#total_general").val(formatMoney(parseFloat(total_general).toFixed(2)));
    $("#jsonitems").val(JSON.stringify(items));
}

var item = null;
var item_ant=null;
// obtengo ID de eventos
function selectItem(e,pos) {
    $('#' + pos).toggleClass('item-seleccion');
    if ($('#' + pos).hasClass('item-seleccion')) {
        item_ant=item;
        if (item_ant){
            $('#' + item_ant).toggleClass('item-seleccion');
        }
        // Guardo id de la fila seleccionada
        item = pos;
        $("#item_precio").val(items[pos]["Precio"]);
        $("#cantidad").val(1);
        $("#descuento").val(items[pos]["Descuento"]);
        $("#iva option:selected").html(items[pos]["Iva"]);
        $("#subtotal").val(items[pos]["Precio"]);
        $("#idbien").val(items[pos]["Id"]);
        calculaSubtotal();
    } else {
        // Reseteo el item
        item = null;
        $("#item_precio").val(0);
        $("#cantidad").val(0);
        $("#descuento").val(0);
        $("#iva option:selected").html(0);
        $("#subtotal").val(0);
        $("#idbien").val(0);
    }  
}

function removerBien(event,id){
    //el id indica el inicio de donde se borra y el 1 la cantidad de elementos eliminados
    items.splice(id,1);
    addItems(items);
}

function removerBien2(event,id){

    // alert("tipoTransaccion: "); alert(tipoTransaccion);
    // alert(" idPersona: "); alert(idPersona);

    $.ajax({
        "dataType": "text",
        "type": "POST",
        "data": "temp",
        "url": '/'+tipoTransaccion+'/ajax/eliminarItem/'+id+'/'+idPersona,

        "success": function (msg) {
            document.location.reload();
        },
        "error": function (msg) {
            console.log("1-error!");
        }

    }).done(function() {
        console.log("1-done!");
    })
}

function seleccionaFormaPago(){ 
    
    if($("#forma_pago option:selected").val() == '2'){
        $("#div_bonificacion").attr("hidden","");
        $("#div_recargo").removeAttr("hidden");
    }

    if($("#forma_pago option:selected").val() == '1'){
        $("#div_bonificacion").removeAttr("hidden");
        $("#div_recargo").attr("hidden","");
    }

    reseteaFormaPago();
    calcularSubcampos();

}

function reseteaFormaPago(){
    $("#bonificacion_general").val('0.00');
    $("#recargo_general").val('0.00');
}

function toggleAttr(e, attr){
    if($("#" + e).attr(attr)){
        $("#" + e).removeAttr(attr);
    } else {
        $("#" + e).attr(attr,"");
    }
}

function justNumbers(event){
    var keynum = window.event ? window.event.keyCode : event.which;
    if ((keynum == 0) || (keynum == 8) || (keynum == 46)){
        return true;
    }   
    return /\d/.test(String.fromCharCode(keynum));
}

var selectedAnt=null;
var selectedNow =null;

function isFormatDescuento(inputValue){
    if (inputValue!=null){
        return inputValue.substr(inputValue.length - 1) == "%";
    }
    return null;
}

function getNumberValue(inputValue){
    if (isFormatDescuento(inputValue)){
        return inputValue.substring(0,inputValue.length-2);
    }
    return inputValue;
}

function getIndex(tdId){
    return index = tdId.substring(0, tdId.indexOf('_'));
}

function getAttribute(tdId){
    return attribute = tdId.substring(tdId.indexOf('_')+1, tdId.length);
}

function saveValueInJson(tdId, inputValue){
    //recibo "0_Cantidad" separo en indice 0 y atributo Cantidad
    index = tdId.substring(0, tdId.indexOf('_'));
    attribute = tdId.substring(tdId.indexOf('_')+1, tdId.length);
    if (inputValue!=null){
        console.log(inputValue);
        items[index][attribute] = inputValue;
        console.log(items);
    }
    $("#jsonitems").val(JSON.stringify(items));
}
//esta funcion es llamada cuando se modifica el valor de un input
function saveValue(inputId){
    tdId= inputId.substring(5);
    // inputValue= $("#"+inputId).val();
    // document.getElementById(inputId).remove();
    // td = document.getElementById(tdId);
    // td.innerText=inputValue;
    saveTd(tdId);
    saveValueInJson(tdId, inputValue)
}

//esta funcion es llamada para guardar el input anterior al generarse un nuevo input
function saveTd(tdId){
    inputId = "input"+tdId;
    inputValue= $("#"+inputId).val();
    numValue = getNumberValue(inputValue);
    inputElement = document.getElementById(inputId);
    if (inputElement!=null){
        inputElement.remove();
        td = document.getElementById(tdId);
        attribute = getAttribute(tdId);
        if (attribute=="Descuento"){
            td.innerText=formatPercent((parseFloat(numValue)).toFixed(2));;
        }
        else{
            td.innerText=inputValue;
        }
    }
    saveValueInJson(tdId, numValue);
}

//PARA EL IVA VA A TENER QUE SER DISTINTO, ESTO SIRVE PARA BONIFICACION Y CANTIDAD NOMAS
function makeEditable(event){
    elementId=event.target.id;
    element= document.getElementById(elementId);
    if (selectedNow!=null){
        selectedAnt=selectedNow;
    }
    selectedNow=elementId;
    if (selectedAnt!=null && selectedNow!=selectedAnt){
        saveTd(selectedAnt);
    }
    val = element.innerText;
    var input = document.createElement("input");
    input.type = "text";
    input.className = "form-control"; // set the CSS class
    input.value=val;
    input.setAttribute("size",3);
    input.id ="input"+elementId;
    input.setAttribute("onchange", "saveValue(id);");
    element.innerText="";
    element.appendChild(input); 
    // element.setAttribute('contenteditable', true);
}