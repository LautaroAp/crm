var items=[];
function addItems(bienes) {
    items = bienes;
    console.log(items);
    var col = ["Nombre", "Descripcion", "Descuento", "Iva","Precio"];
    //TABLE HEADER
    var table = document.createElement("table");
    var tr = table.insertRow(-1);                   // TABLE ROW.
    for (var i = 0; i < col.length; i++) {
        var th = document.createElement("th");      // TABLE HEADER.
        th.innerHTML = col[i];
        tr.appendChild(th);
    }
    //TABLE BODY
    for (var i = 0; i < bienes.length; i++) {
        var item = bienes[i]
        tr = table.insertRow(-1);
        // tr.onclick= selectItem(item["id"]);
        tr.setAttribute("id", i);
        tr.setAttribute("class", "click");
        tr.setAttribute("onclick","selectItem(event,id)");
        // console.log(item);
        for (var j = 0; j < col.length; j++) {
            var tabCell = tr.insertCell(-1);
            tabCell.setAttribute("id", i);
            tabCell.setAttribute("class", "click");
            tabCell.innerHTML = item[col[j]];
        }
    }
    var divContainer = document.getElementById("contenido_bienes");
    divContainer.innerHTML = "";
    divContainer.appendChild(table);
}

var item = null;
var item_ant=null;
// obtengo ID de eventos
function selectItem(e,pos) {
    console.log(items);

    $('#' + pos).toggleClass('table-seleccion');
    if ($('#' + pos).hasClass('table-seleccion')) {
        item_ant=item;
        if (item_ant){
            $('#' + item_ant).toggleClass('table-seleccion');
        }
        // Guardo id de la fila seleccionada
        item = pos;
        $("#subtotal").val(items[pos]["Precio"]);
        $("#iva").val(items[pos]["Iva"]);
        $("#cantidad").val(1);
        $("#idbien").val(items[pos]["Id"]);
        console.log(item);
    } else {
        // Reseteo el item
        item = null;
        $("#subtotal").val(null);
        $("#iva").val(null);
        $("#cantidad").val(null);
        $("#idbien").val(null);
    }
   
   
}
