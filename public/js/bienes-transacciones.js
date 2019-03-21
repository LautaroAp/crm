var items=[];
function addItems(bienesTransacciones) {
    items = bienesTransacciones;
    console.log(items);
    var col = ["Nombre", "Descripcion", "Cantidad", "Precio","Bonificacion","IVA", "Subtotal"];
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
        // console.log(item);
        for (var j = 0; j < col.length; j++) {
            var tabCell = tr.insertCell(-1);
            tabCell.setAttribute("id", i);
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
            if ((col[j] == "Bonificacion") || (col[j]=="IVA")){value = formatPercent(value);}
            if (col[j] == "Precio"){value = formatMoney(value);}

            tabCell.innerHTML = value;
        }
    }
    var divContainer = document.getElementById("contenido_bienes");
    divContainer.innerHTML = "";
    divContainer.appendChild(table);


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

    for(var i = 1; i < table.rows.length; i++)
    {
        bonificacion = table.rows[i].cells[4].innerHTML;
        var bonificacion = bonificacion.substring(0, bonificacion.length-2);
        sumBonificacion = sumBonificacion + parseFloat(bonificacion);
        console.log(sumBonificacion);
        
        console.log(sumIva);
        iva = table.rows[i].cells[5].innerHTML;
        var iva = iva.substring(0, iva.length-2);
        sumIva = sumIva + parseFloat(iva);
        console.log(sumIva);

        subtotal = table.rows[i].cells[6].innerHTML;
        sumSubtotal = sumSubtotal + parseFloat(subtotal);
        console.log(sumSubtotal);

    }
    $("#bonificacion_total").val(formatPercent(parseFloat(sumBonificacion)));
    $("#iva_total").val(formatPercent(parseFloat(sumIva)));
    $("#total_general").val(formatMoney(sumSubtotal));

 

}

var item = null;
var item_ant=null;
// obtengo ID de eventos
function selectItem(e,pos) {
    console.log(items);

    $('#' + pos).toggleClass('item-seleccion');
    if ($('#' + pos).hasClass('item-seleccion')) {
        item_ant=item;
        if (item_ant){
            $('#' + item_ant).toggleClass('item-seleccion');
        }
        
        // Guardo id de la fila seleccionada
        item = pos;
        $("#item_precio").val(items[pos]["Precio"]);
        $("#item_dto").val(items[pos]["Descuento"]);
        $("#cantidad").val(1);
        $("#bonificacion").val(0);
        $("#iva option:selected").html(items[pos]["Iva"]);
        $("#subtotal").val(items[pos]["Precio"]);
        $("#idbien").val(items[pos]["Id"]);
        console.log(item);
        calculaSubtotal();
    } else {
        // Reseteo el item
        item = null;
        $("#item_precio").val(0);
        $("#item_dto").val(0);
        $("#cantidad").val(0);
        $("#bonificacion").val(0);
        $("#iva option:selected").html(0);
        $("#subtotal").val(0);
        $("#idbien").val(0);
    }
   
   
}
