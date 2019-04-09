var items=[];
var tipoTransaccion;
var idPersona;

function addItems(bienesTransacciones, tipo, id) {
   items = bienesTransacciones;
   console.log(items);
   tipoTransaccion = tipo;
   idPersona=id;
   var table = document.createElement("table");
   table.setAttribute("id", "table_bienes");
   table.setAttribute("class", "display");

   var thead = document.createElement("thead");
   var col = ["Nombre", "Descripcion", "Cantidad", "Precio","Descuento","IVA", "Subtotal", ""];

   var tr = thead.insertRow(-1);                  
   for (var i = 0; i < col.length; i++) {
       var th = document.createElement("th");     
       th.innerHTML = col[i];
       tr.appendChild(th);
   }
   thead.appendChild(tr);
   table.appendChild(thead);

   var tbody = document.createElement("tbody");
   tbody.setAttribute("role", "button");
   var value = null;
   for (var i = 0; i < items.length; i++) {
       var item = items[i]
       tr = tbody.insertRow(-1);
       // tr.onclick= selectItem(item["id"]);
       tr.setAttribute("id", i);
       tr.setAttribute("class", "click");
    //    tr.setAttribute("onclick","selectItem(event,id)");
       console.log(item);
       for (var j = 0; j < col.length -1; j++) {
           var tabCell = tr.insertCell(-1);
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

       // Botones
       var btn = document.createElement('button');
       btn.setAttribute('type','button');
       btn.setAttribute('class','btn btn-default btn-sm glyphicon glyphicon-remove'); // set attributes ...
       btn.setAttribute('id',i);
       btn.setAttribute('value','Borrar');
       btn.setAttribute("onclick","removerBien2(event,id)");
       var tabCell = tr.insertCell(-1);
       tabCell.setAttribute("class", "click");
       tabCell.appendChild(btn);
       //   tr.cells[-1].appendChild(btn);
   }
   table.appendChild(tbody);

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
        cantidad = table.rows[i].cells[2].innerHTML;
        precio_unitario = table.rows[i].cells[3].innerHTML;
        precio_unitario = parseFloat(precio_unitario.substring(2, precio_unitario.length));
        descuento = table.rows[i].cells[4].innerHTML;
        descuento = descuento.substring(0, descuento.length-2);
        descuento=  (parseFloat(descuento) * precio_unitario /100 );
        precio_unitario_dto= precio_unitario-descuento;
        sumBonificacion = sumBonificacion + descuento*cantidad;
        console.log("SUM BONIFICACION "+sumBonificacion);

        iva = table.rows[i].cells[5].innerHTML;
        iva = iva.substring(0, iva.length-2);
        sumIva = sumIva + (parseFloat(iva) * precio_unitario_dto/ 100)*cantidad ;

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
    $("#bonificacion_total").val(formatMoney(parseFloat(sumBonificacion).toFixed(2)));
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

/////////////////////////// TODO LO QUE SIGUE ES PARA EDITAR LA TABLA DINAMICAMENTE ////////////////////////////
var selectedAnt=null;
var selectedNow =null;

function isFormatDescuento(inputValue){
    if (inputValue!=null){
        return inputValue.substr(inputValue.length - 1) == "%";
    }
    return null;
}

function isFormatMoney(inputValue){
    if (inputValue!=null){
        return inputValue.substring(0,1) == "$";
    }
    return null;
}

function getNumberValue(inputValue){
    if (isFormatDescuento(inputValue)){
        return inputValue.substring(0,inputValue.length-2);
    }
    else if (isFormatMoney(inputValue)){
        return inputValue.substring(2,inputValue.length);
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
    //ES NECESARIO GUARDAR LOS CAMBIOS EN EL INPUT HIDDEN DE HTML PARA OBTENER EL JSON CON DATA
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
    saveValueInJson(tdId, inputValue);
}

function actualizarFila(tdId){
    index = getIndex(tdId);
    attribute = getAttribute(tdId);
    // if (attribute=="Cantidad"){
    //     tdSubtotal = document.getElementById(index+"_Subtotal");
    //     subtotal= tdSubtotal.innerText;
    //     subtotal = getNumberValue(subtotal);
    //     cant = document.getElementById(tdId).innerText;
    //     console.log("SUBTOTAL "+ subtotal);
    //     console.log("CANTIDAD " + cant)
    //     tdSubtotal.innerText= formatMoney(parseFloat(cant*subtotal).toFixed(2));
    // }
    // if (attribute=="Descuento"){
        cant = document.getElementById(index+"_Cantidad").innerText;
        precio = document.getElementById(index+"_Precio").innerText;
        precio = getNumberValue(precio);
        descuento = document.getElementById(index+"_Descuento").innerText;
        descuento = getNumberValue(descuento);
        iva = document.getElementById(index+"_IVA").innerText;
        iva = getNumberValue(iva);
        cantxprecio = cant * precio;
        cantxprecioydto = cantxprecio - cantxprecio*descuento/100;
        subtotal = cantxprecioydto + cantxprecioydto * iva/100;
        document.getElementById(index+"_Subtotal").innerText = formatMoney(parseFloat(subtotal).toFixed(2));
    // }
    calcularSubcampos();
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
    actualizarFila(tdId);
    saveValueInJson(tdId, numValue);
}

function pulsar(e){
    tecla = (document.all) ? e.keyCode :e.which;
    return (tecla!=13); 
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
    input.setAttribute("onkeypress", "return pulsar(event)");
    element.innerText="";
    element.appendChild(input); 
    // element.setAttribute('contenteditable', true);
}

// * * * * * * * * * PARA AGREGAR ITEM DEL AUTOCOMPLETE * * * * * * * * * 

function addItemToTable(){
    // Compara el Stock Disponible con la Cantidad ingresada
    if(verificaStockDisponible()){
        // Elimina items sobrantes del json "output" y deja solo el seleccionado
        updateOutputSelect();

        // busco BIEN segun ID
        // * * * (Ya lo tengo, esta completo en "output")...
    
        // le concateno el BIEN a "ITEMS" (agregar a un json)
        items.push(output[0]);

        // luego:
            //ES NECESARIO GUARDAR LOS CAMBIOS EN EL INPUT HIDDEN DE HTML PARA OBTENER EL JSON CON DATA
            // $("#jsonitems").val(JSON.stringify(items));
        $("#jsonitems").val(JSON.stringify(items));
        // Si no actualiza, volver a llamar agregar item
        // addItems(obj, "pedido", idPersona); // Se rompe * * * ¿tengo que adaptar output con los campos de bienesTransaccion?

    };
}

function updateOutputSelect(){
    // Elimina items sobrantes del json "output" y deja solo el seleccionado
    var result;
    for (i = 0; i < output.length; i++) {
        if (output[i]["value"] == $('#item_id').val()) {
            result = output.splice(i, 1);
            break;
        } 
    }
    
    result[0]["cantidad"] = $('#item_cantidad').val();
    output = result;
    // output= null;
    // cantidad = ('#item_cantidad').val();
    // descuento = result[0]["descuento"];
    // iva = getIvaByValue(result[0]["iva"]);
    // output = {"Bien" : result[0], "Cantidad" : cantidad, "Descuento" : descuento, "IVA"=  }
    clearAddItem();
}

function clearAddItem(){
    $('#item_id').val(null);
    $('#item_codigo').val(null);
    $('#item_nombre').val(null);
    $('#item_stock').val(null);
    $('#item_cantidad').val(null);
}

function verificaStockDisponible(){
    if($('#item_cantidad').val() > 0){
        if($('#item_cantidad').val() > $('#item_stock').val()){
            if(confirm("La cantidad ingresada sobrepasa el Stock disponible. ¿Desea continuar?")){
                return true
            } else {
                return false;
            }
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function addItemToJsonTable(){

}