var tipoTransaccion;
var idPersona;
var ivas;

var transaccion = null;
var transaccion_ant = null;

function setIvas(arrayIvas) {
    ivas = arrayIvas;
}

function addItems(bienesTransacciones, tipo, id) {
    if (bienesTransacciones==null){
        bienesTransacciones = [];
    }
    $(document).ready(function () {
        $('#table_bienes').DataTable({
            "order": [0, 'desc'],

            "language": {
                "url": "/json/spanish.json"
            }
        });
    });
    tipoTransaccion = tipo;
    idPersona = id;
    var divContainer = document.getElementById("div_table_bienes");
    if (divContainer != null) {
        divContainer.parentNode.removeChild(divContainer);
    }
    divContainer = document.createElement("div");
    divContainer.setAttribute("id", "div_table_bienes");

    var parentDiv = document.getElementById("contenido_bienes");
    parentDiv.innerHTML = "";
    parentDiv.appendChild(divContainer);

    table = document.createElement("table");
    table.setAttribute("id", "table_bienes");
    table.setAttribute("class", "display");

    var thead = document.createElement("thead");
    var col = ["Nombre", "Descripcion", "Cantidad", "Precio", "Dto", "ImpDto", "IVA", "ImpIVA", "Totales", ""];

    var tr = thead.insertRow(-1);
    for (var i = 0; i < col.length; i++) {
        var th = document.createElement("th");
        switch (col[i]) {
            case 'Nombre':
                th.innerHTML = "Producto / Servicio"; 
                break;
            case 'Dto':
                th.innerHTML = "Dto (%)";
                break;
            case 'ImpDto':
                th.innerHTML = "Dto ($)";
                break;
            case 'IVA':
                th.innerHTML = "IVA (%)"; 
                break;
            case 'ImpIVA':
                th.innerHTML = "IVA ($)";
                break;
            default:
                th.innerHTML = col[i];
          }
        tr.appendChild(th);
    }
    thead.appendChild(tr);
    table.appendChild(thead);

    var tbody = document.createElement("tbody");
    tbody.setAttribute("role", "button");
    var value = null;


    for (var i = 0; i < bienesTransacciones.length; i++) {
        var item = bienesTransacciones[i];
        tr = tbody.insertRow(-1);
        // tr.onclick= selectItem(item["id"]);
        tr.setAttribute("id", i);
        tr.setAttribute("class", "click");
        //    tr.setAttribute("onclick","selectItem(event,id)");
        for (var j = 0; j < col.length - 1; j++) {
            var tabCell = tr.insertCell(-1);
            tabCell.setAttribute("id", i + "_" + col[j]);
            tabCell.setAttribute("class", "click");
            var precio = item["Bien"]["Precio"];
            var dto = item["Bien"]["Dto"];
            var cantidad = item["Cantidad"];
            var dtoPeso = (precio * dto / 100) * cantidad;
            var precioDto = precio - dtoPeso;     
            var iva = item["IVA"]["Valor"];
            if (col[j] == "Nombre" || col[j] == "Descripcion" || col[j] == "Precio") {
                value = item["Bien"][col[j]];
            } else if (col[j] == "IVA") {
                value = iva;
            } else {
                value = item[col[j]];
            }
            if (col[j] == "Cantidad") {
                tabCell.setAttribute("ondblclick", "makeEditable(event)");
            }
            if ((col[j] == "Dto") || (col[j] == "IVA")) {
                value = formatPercent((parseFloat(value)).toFixed(2));
                tabCell.setAttribute("ondblclick", "makeEditable(event)");
            }
            if ((col[j] == "Precio") || (col[j] == "Totales")) {
                if (value) {
                    value = formatMoney(value);
                }
            }
            if (col[j] == "ImpDto") {
                value = formatMoney((parseFloat(dtoPeso)).toFixed(2));
            }
            if ((col[j] == "ImpIVA")) {
                value = precioDto * iva / 100;
                value = formatMoney((parseFloat(value)).toFixed(2));
            }

            tabCell.innerHTML = value;
        }

        // Botones
        var btn = document.createElement('button');
        btn.setAttribute('type', 'button');
        btn.setAttribute('class', 'btn btn-default btn-sm glyphicon glyphicon-trash'); // set attributes ...
        btn.setAttribute('id', i);
        btn.setAttribute('value', 'Borrar');
        btn.setAttribute("onclick", "removerBien2(event,id)");
        var tabCell = tr.insertCell(-1);
        tabCell.setAttribute("class", "click");
        tabCell.appendChild(btn);
    }
    table.appendChild(tbody);

    // var divContainer = document.getElementById("div_table_bienes");
    divContainer.innerHTML = "";
    divContainer.appendChild(table);
    calcularSubcampos();


}

function getItems() {
    return items;
}

function formatMoney(number) {
    return '$ ' + number.toLocaleString('en-US');
}

function formatPercent(number) {
    return number.toLocaleString('en-US') + ' %';
}


// function calcularSubcampos() {
//     var table = document.getElementById("table_bienes");
//     var sumBonificacion = 0;
//     var sumSubtotal = 0;
//     var sumIva = 0;
//     var bonificacion_general = 0;
//     var descuento = 0;
//     var cantidad = 0;
//     var precio_unitario_dto = 0;
//     var iva = 0;
//     var subtotal = 0;
//     var sumVentaBruta = 0;
//     for (var i = 1; i < table.rows.length; i++) {
//         //CANTIDAD ES LA COLUMNA 2
//         cantidad = table.rows[i].cells[2].innerHTML;
//         //PRECIO UNITARIO ES LA COLUMNA 3
//         precio_unitario = table.rows[i].cells[3].innerHTML;
//         precio_unitario = parseFloat(precio_unitario.substring(2, precio_unitario.length));
//         //DESCUENTO ES LA COLUMNA 4
//         descuento = table.rows[i].cells[4].innerHTML;
//         descuento = descuento.substring(0, descuento.length - 2);
//         descuento = (parseFloat(descuento) * precio_unitario / 100);
//         precio_unitario_dto = precio_unitario - descuento;
//         sumBonificacion = sumBonificacion + descuento * cantidad;
//         //IVA ES LA COLUMNA 
//         iva = table.rows[i].cells[6].innerHTML;
//         iva = iva.substring(0, iva.length - 2);
//         sumIva = sumIva + (parseFloat(iva) * precio_unitario_dto / 100) * cantidad;

//         //SUBTOTAL ES LA COLUMNA 8
//         subtotal = table.rows[i].cells[8].innerHTML;
//         subtotal = parseFloat(subtotal.substring(2, subtotal.length));
//         sumSubtotal = sumSubtotal + parseFloat(subtotal);
//         sumVentaBruta = sumVentaBruta + cantidad * precio_unitario;

//     }

//     bonificacion_general = $("#bonificacion_general").val();
//     recargo_general = $("#recargo_general").val();
//     if (!recargo_general) {
//         recargo_general = 0;
//     }
//     var total_general = sumSubtotal - (sumSubtotal * bonificacion_general / 100) + (sumSubtotal * recargo_general / 100);
//     var bonificacion_importe = sumSubtotal * bonificacion_general / 100;
//     var recargo_importe = sumSubtotal * recargo_general / 100;
//     $("#subtotal_general").val(formatMoney(parseFloat(sumSubtotal).toFixed(2)));
//     $("#bonificacion_importe").val(formatMoney(parseFloat(bonificacion_importe).toFixed(2)));
//     $("#recargo_importe").val(formatMoney(parseFloat(recargo_importe).toFixed(2)));
//     $("#venta_bruta").val(formatMoney(parseFloat(sumVentaBruta).toFixed(2)));
//     $("#descuento_total").val(formatMoney(parseFloat(sumBonificacion).toFixed(2)));
//     $("#iva_total").val(formatMoney(parseFloat(sumIva).toFixed(2)));
//     $("#total_general").val(formatMoney(parseFloat(total_general).toFixed(2)));
//     $("#jsonitems").val(JSON.stringify(items));
// }


function calcularSubcampos() {
    var table = document.getElementById("table_bienes");
    var sumBonificacion = 0;
    var sumSubtotal = 0;
    var sumIva = 0;
    var bonificacion_general = 0;
    var descuento = 0;
    var cantidad = 0;
    var precio_unitario_dto = 0;
    var iva = 0;
    var subtotal = 0;
    var sumVentaBruta = 0;

    for (var i = 0; i < items.length; i++) {
        // CANTIDAD
        cantidad = items[i]["Cantidad"];
        //PRECIO UNITARIO
        precio_unitario = items[i]["Bien"]["Precio"];
        //DESCUENTO
        descuento = items[i]["Dto"];
        descuento = (parseFloat(descuento) * precio_unitario / 100);
        precio_unitario_dto = precio_unitario - descuento;
        sumBonificacion = sumBonificacion + descuento * cantidad;
        //IVA
        iva = items[i]["IVA"]["Valor"];
        sumIva = sumIva + (parseFloat(iva) * precio_unitario_dto / 100) * cantidad;
        //SUBTOTAL
        subtotal = items[i]["Totales"];
        sumSubtotal = sumSubtotal + parseFloat(subtotal);
        sumVentaBruta = sumVentaBruta + cantidad * precio_unitario;       
    }

    bonificacion_general = $("#bonificacion_general").val();
    recargo_general = $("#recargo_general").val();

    if (!recargo_general) {
        recargo_general = 0;
    }

    var total_general = sumSubtotal - (sumSubtotal * bonificacion_general / 100) + (sumSubtotal * recargo_general / 100);
    var bonificacion_importe = sumSubtotal * bonificacion_general / 100;
    var recargo_importe = sumSubtotal * recargo_general / 100;

    $("#subtotal_general").val(formatMoney(parseFloat(sumSubtotal).toFixed(2)));
    $("#bonificacion_importe").val(formatMoney(parseFloat(bonificacion_importe).toFixed(2)));
    $("#recargo_importe").val(formatMoney(parseFloat(recargo_importe).toFixed(2)));
    $("#venta_bruta").val(formatMoney(parseFloat(sumVentaBruta).toFixed(2)));
    $("#descuento_total").val(formatMoney(parseFloat(sumBonificacion).toFixed(2)));
    $("#iva_total").val(formatMoney(parseFloat(sumIva).toFixed(2)));
    $("#total_general").val(formatMoney(parseFloat(total_general).toFixed(2)));
    $("#jsonitems").val(JSON.stringify(items));
}

var item = null;
var item_ant = null;
// obtengo ID de eventos
function selectItem(e, pos) {
    $('#' + pos).toggleClass('item-seleccion');
    if ($('#' + pos).hasClass('item-seleccion')) {
        item_ant = item;
        if (item_ant) {
            $('#' + item_ant).toggleClass('item-seleccion');
        }
        // Guardo id de la fila seleccionada
        item = pos;
        $("#item_precio").val(items[pos]["Precio"]);
        $("#cantidad").val(1);
        $("#descuento").val(items[pos]["Dto"]);
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

function removerBien(event, id) {
    //el id indica el inicio de donde se borra y el 1 la cantidad de elementos eliminados
    
    items.splice(id, 1);
    addItems(items, tipoTransaccion, idPersona);
}

function removerBien2(event, id) {
    $.ajax({
        "dataType": "text",
        "type": "POST",
        "data": "temp",
        "url": '/' + tipoTransaccion + '/ajax/eliminarItem/' + id + '/' + idPersona,
        "success": function (msg) {
            document.location.reload();
        },
        "error": function (msg) {
            console.log("1-error!");
        }

    }).done(function () {
        console.log("1-done!");
    })
}

function seleccionaFormaPago() {

    if ($("#forma_pago option:selected").val() == '2') {
        $("#div_bonificacion").attr("hidden", "");
        $("#div_recargo").removeAttr("hidden");
    }

    if ($("#forma_pago option:selected").val() == '1') {
        $("#div_bonificacion").removeAttr("hidden");
        $("#div_recargo").attr("hidden", "");
    }

    reseteaFormaPago();
    calcularSubcampos();

}

function reseteaFormaPago() {
    $("#bonificacion_general").val('0.00');
    $("#recargo_general").val('0.00');
}

function toggleAttr(e, attr) {
    if ($("#" + e).attr(attr)) {
        $("#" + e).removeAttr(attr);
    } else {
        $("#" + e).attr(attr, "");
    }
}

function justNumbers(event) {
    var keynum = window.event ? window.event.keyCode : event.which;
    if ((keynum == 0) || (keynum == 8) || (keynum == 46)) {
        return true;
    }
    return /\d/.test(String.fromCharCode(keynum));
}

/////////////////////////// TODO LO QUE SIGUE ES PARA EDITAR LA TABLA DINAMICAMENTE ////////////////////
var selectedAnt = null;
var selectedNow = null;

function isFormatDescuento(inputValue) {
    if (inputValue != null) {
        return inputValue.substr(inputValue.length - 1) == "%";
    }
    return null;
}

function isFormatMoney(inputValue) {
    if (inputValue != null) {
        return inputValue.substring(0, 1) == "$";
    }
    return null;
}

function getNumberValue(inputValue) {
    if (isFormatDescuento(inputValue)) {
        return inputValue.substring(0, inputValue.length - 2);
    } else if (isFormatMoney(inputValue)) {
        return inputValue.substring(2, inputValue.length);
    }
    return inputValue;
}

function getIndex(tdId) {
    return index = tdId.substring(0, tdId.indexOf('_'));
}

function getAttribute(tdId) {
    return attribute = tdId.substring(tdId.indexOf('_') + 1, tdId.length);
}

function controlStock(attribute, inputValue){
    console.log(items[index]["Bien"]["Stock"]);
    console.log(inputValue);
    inputValue = parseFloat(inputValue);
    if (items[index]["Bien"]["Tipo"]=="PRODUCTO"){
        if (attribute=="Cantidad"){
            var stock = parseFloat(items[index]["Bien"]["Stock"]);
            if(stock<=inputValue){
                if (confirm("La cantidad ingresada sobrepasa el Stock disponible. ¿Desea continuar?")) {
                    return true
                } else {
                    return false;
                }
            }
        }
    }
    return true;
}
function saveValueInJson(tdId, inputValue) {
    //recibo "0_Cantidad" separo en indice 0 y atributo Cantidad
    index = tdId.substring(0, tdId.indexOf('_'));
    attribute = tdId.substring(tdId.indexOf('_') + 1, tdId.length);
    if (inputValue != null) {
        if (controlStock(attribute, inputValue)){
            if (attribute == "IVA") {
                inputValue = ivas[getIvaFromValue(inputValue)];
            }
            items[index][attribute] = inputValue;
        }
    }
     //ES NECESARIO GUARDAR LOS CAMBIOS EN EL INPUT HIDDEN DE HTML PARA OBTENER EL JSON CON DATA
    $("#jsonitems").val(JSON.stringify(items));
    // persistItemsInSession();
}
//esta funcion es llamada cuando se modifica el valor de un input
function saveValue(inputId) {
    tdId = inputId.substring(5);
    saveTd(tdId);
}

function actualizarJson(fila) {
    var col = ["Cantidad", "Dto", "IVA", "Totales"];
    var index = fila + "_";
    for (var j = 0; j < col.length; j++) {
        var subindex = index + col[j];
        if (col[j] != "IVA") {
            items[fila][col[j]] = getNumberValue(document.getElementById(subindex).innerText);
        } else {
            var valorIva = document.getElementById(subindex).innerText;
            var ivajson = ivas[getIvaFromValue(valorIva)];
            items[fila]["IVA"] = ivajson;
        }
    }
    console.log("despues de actualizar items queda ");
    console.log(items);
    $("#jsonitems").val(JSON.stringify(items));
    // persistItemsInSession();
}

function actualizarFila(tdId) {
    index = getIndex(tdId);
    attribute = getAttribute(tdId);
    cant = document.getElementById(index + "_Cantidad").innerText;
    if (controlStock(attribute,cant)){
        precio = document.getElementById(index + "_Precio").innerText;
        precio = getNumberValue(precio);
        descuento = document.getElementById(index + "_Dto").innerText;
        descuento = getNumberValue(descuento);
        iva = document.getElementById(index + "_IVA").innerText;
        iva = getNumberValue(iva);
        console.log(iva);
        cantxprecio = cant * precio;
        dto$ = cantxprecio  * descuento / 100;
        cantxprecioydto =cantxprecio -cantxprecio  * descuento / 100;
        document.getElementById(index + "_ImpDto").innerText = formatMoney(parseFloat(dto$).toFixed(2));
        subtotal = cantxprecioydto + cantxprecioydto * iva / 100;
        ivaEnPeso =   cantxprecioydto * iva / 100;
        document.getElementById(index + "_ImpIVA").innerText = formatMoney(parseFloat(ivaEnPeso).toFixed(2));
        document.getElementById(index + "_Totales").innerText = formatMoney(parseFloat(subtotal).toFixed(2));
        actualizarJson(index);
        calcularSubcampos();
    }
}

//esta funcion es llamada para guardar el input anterior al generarse un nuevo input
function saveTd(tdId) {
    inputId = "input" + tdId;
    var inputValue;
    if (getAttribute(tdId) == "IVA") {
        var select = document.getElementById(inputId);
        inputValue = select.options[select.selectedIndex].text;
    } else {
        inputValue = document.getElementById(inputId).value;
    }
    numValue = getNumberValue(inputValue);
    inputElement = document.getElementById(inputId);
    if (inputElement != null) {
        inputElement.remove();
        td = document.getElementById(tdId);
        attribute = getAttribute(tdId);
        if ((attribute == "Dto") || (attribute == "IVA")) {
            td.innerText = formatPercent((parseFloat(numValue)).toFixed(2));;
        } else {
            td.innerText = inputValue;
        }
    }
    console.log("items 2");
    console.log(items);
    actualizarFila(tdId);
}

function pulsar(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    return (tecla != 13);
}

function getIvaFromValue(valor) {
    var ivaValor = getNumberValue(valor);
    for (var i = 0; i < ivas.length; i++) {
        if (ivas[i]["Valor"] == ivaValor) {
            return i;
        }
    }
    return 0;
}

//PARA EL IVA VA A TENER QUE SER DISTINTO, ESTO SIRVE PARA BONIFICACION Y CANTIDAD NOMAS
function makeEditable(event) {
    elementId = event.target.id;
    element = document.getElementById(elementId);
    if (selectedNow != null) {
        selectedAnt = selectedNow;
    }
    selectedNow = elementId;
    if (selectedAnt != null && selectedNow != selectedAnt) {
        saveTd(selectedAnt);
    }
    val = element.innerText;
    if (getAttribute(elementId) == "IVA") {
        //Create and append select list
        var selectList = document.createElement("select");
        selectList.id = "input" + elementId;
        selectList.name = "IVA";
        selectList.setAttribute("class", "form-control");
        selectList.setAttribute("onchange", "saveValue(id);");
        selectList.setAttribute("onkeypress", "return pulsar(event)");
        valor = element.innerText;
        element.innerText = "";
        element.appendChild(selectList);
        //Create and append the options

        var option = document.createElement("option");
        iva = getIvaFromValue(valor);
        option.value = getIvaFromValue(iva['Id']);
        option.text = valor;
        option.setAttribute("hidden", "");
        selectList.appendChild(option);


        var option = document.createElement("option");
        // option.value = "-1";
        // option.text = "NO DEFINIDO";
        // selectList.appendChild(option);

        for (var i = 0; i < ivas.length; i++) {
            var option = document.createElement("option");
            option.value = ivas[i]['Id'];
            option.text = ivas[i]['Valor'];
            selectList.appendChild(option);
        }
    } else {
        var input = document.createElement("input");
        input.type = "text";
        input.className = "form-control"; // set the CSS class
        input.value = val;
        input.setAttribute("size", 3);
        input.id = "input" + elementId;
        input.setAttribute("onchange", "saveValue(id);");
        input.setAttribute("onkeypress", "return pulsar(event)");
        element.innerText = "";
        element.appendChild(input);
        // element.setAttribute('contenteditable', true);
    }
}

// * * * * * * * * * PARA AGREGAR ITEM DEL AUTOCOMPLETE * * * * * * * * * 
//agregar el item a la lista de la sesion 
function persistItemsInSession() {
    $.ajax({
        type: "POST",
        data: {
            json: JSON.stringify(items)
        },
        url: '/' + tipoTransaccion + '/ajax/setItems'
    }).done(function () {
        console.log("1-done!");
    })
}


function addItemToTable() {
    // Compara el Stock Disponible con la Cantidad ingresada
    updateOutputSelect();
    if (verificaStockDisponible(output)) {
        // Elimina items sobrantes del json "output" y deja solo el seleccionado
        // busco BIEN segun ID
        // * * * (Ya lo tengo, esta completo en "output")...
        // updateOutputSelect();
        // le concateno el BIEN a "ITEMS" (agregar a un json)

        items.push(output);
        // persistItemsInSession();
        // luego:
        //ES NECESARIO GUARDAR LOS CAMBIOS EN EL INPUT HIDDEN DE HTML PARA OBTENER EL JSON CON DATA
        // $("#jsonitems").val(JSON.stringify(items));
        $("#jsonitems").val(JSON.stringify(items));
        addItems(items, tipoTransaccion, idPersona); // Se rompe * * *

    };
}

function updateOutputSelect() {
    // Elimina items sobrantes del json "output" y deja solo el seleccionado
    var result;
    var aux = Array.from(json_items);
    console.log("aux");
    console.log(aux);
    for (i = 0; i < aux.length; i++) {
        if (aux[i]["value"] == $('#item_id').val()) {
            result = aux.splice(i, 1);

            // result = result.splice(i,1);
            break;
        }
    }
    var item_cantidad = $('#item_cantidad').val();
    var item_descuento = result[0]["descuento"];
    var item_iva = result[0]["iva"];
    var item_iva = ivas[getIvaFromValue(item_iva)];
    var item_subtotal = result[0]["totales"] * item_cantidad;

    item_subtotal = (parseFloat(item_subtotal).toFixed(2));

    output = null;
    output = {
        "Bien": {

            "Categoria": result[0]["categoria"],
            "Descripcion": result[0]["descripcion"],
            "Dto": result[0]["descuento"],
            "Id": result[0]["value"],
            "Iva": result[0]["iva"],
            "Nombre": result[0]["nombre"],
            "Precio": result[0]["precio"],
            "Totales": result[0]["totales"],
            "Tipo": result[0]['tipo'],
            "Stock": result[0]['stock']
        },
        "Cantidad": item_cantidad,
        "Dto": item_descuento,
        "IVA": item_iva,
        "Id": "",
        "Totales": item_subtotal
    }


    // Cambia el formato de "output" con "Bien: + IVA:"
    // output = {"Bien" : output[0], "Cantidad" : cantidad, "Dto (%)" : descuento, "IVA": iva  }
    clearAddItem();
}

function clearAddItem() {
    $('#item_id').val(null);
    $('#item_codigo').val(null);
    $('#item_nombre').val(null);
    $('#item_stock').val(null);
    $('#item_cantidad').val(null);

    // Tabla Detalles
    $('#item_descripcion').html(null);
    $('#item_categoria').html(null);
    $('#item_precio').html(null);
    $('#item_descuento').html(null);
    $('#item_descuento_precio').html(null);
    $('#item_iva').html(null);
    $('#item_iva_precio').html(null);
    $('#item_subtotal').html(null);

}

function verificaStockDisponible(output) {
   if (output["Bien"]["Tipo"]=="PRODUCTO"){
       var cant = parseFloat(output["Cantidad"]);
       if (cant > 0) {
            if (cant > parseFloat(output["Bien"]["Stock"])) {
                if (confirm("La cantidad ingresada sobrepasa el Stock disponible. ¿Desea continuar?")) {
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
   return true;
    
}