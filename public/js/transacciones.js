var transacciones;
function completarTransacciones(transacciones_anteriores){
    transacciones= transacciones_anteriores;
    $(document).ready(function () {
        $('#table_transacciones').DataTable({
            "order": [0, 'desc'],

            "language": {
                "url": "/json/spanish.json"
            }
        });
    });
    var divContainer = document.getElementById("div_table_transacciones");
    var table = document.createElement("table");
    table.setAttribute("id", "table_transacciones");
    table.setAttribute("class", "display");

    var thead = document.createElement("thead");
    var col = ["Numero", "Fecha Transaccion", "Monto", ""];

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
    for (var i = 0; i < transacciones.length; i++) {
        var transaccion = transacciones[i];
        tr = tbody.insertRow(-1);
        // tr.onclick= selectItem(item["id"]);
        tr.setAttribute("id", i);
        tr.setAttribute("class", "click");
        //    tr.setAttribute("onclick","selectItem(event,id)");
        for (var j = 0; j < col.length-1; j++) {
            var tabCell = tr.insertCell(-1);
            tabCell.setAttribute("id", i);
            tabCell.setAttribute("class", "click");
            // tabCell.setAttribute("onclick", "selectTransaccion(id)");
            value = transaccion[col[j]];
            tabCell.innerHTML = value;
        }

        // Botones
        var btn = document.createElement('button');
        btn.setAttribute('type', 'button');
        btn.setAttribute('class', 'btn btn-default btn-sm  glyphicon glyphicon-unchecked ');
        btn.setAttribute('id', i+"button");
        btn.setAttribute("onclick", "selectTransaccion(id)");
        var tabCell = tr.insertCell(-1);
        tabCell.setAttribute("class", "click");
        tabCell.appendChild(btn);
    }
    table.appendChild(tbody);

    divContainer.innerHTML = "";
    divContainer.appendChild(table);

//     var mymodal = $('#modalTransacciones');
//     mymodal.find('.modal-body').innerHTML("hola");
//     mymodal.modal('show');
// 
}

function getTransaccion(buttonId){
    return  buttonId.substring(0, buttonId.indexOf('b'));
}

function selectTransaccion(id){
    if ($('#' + id).hasClass('glyphicon-unchecked')){
        $('#' + id).removeClass('glyphicon-unchecked');
        $('#' + id).addClass('glyphicon-check');
        var indexTransaccion = getTransaccion(id);
        $('#transaccion_base').val(indexTransaccion);
        transaccion_ant = transaccion;
        if (transaccion_ant) {
            $('#' + transaccion_ant).removeClass('glyphicon-check');
            $('#' + transaccion_ant).addClass('glyphicon-unchecked');
        }
        // Guardo id de la fila seleccionada
        transaccion = id;
        completeItems(indexTransaccion);
    }
}

function processData(data){
    console.log(data);
}

function removeItemsAnteriores(){
    items = $(items).not(itemsAnteriores).get();
    console.log(items);
}
function addTransaccionItems(){
    console.log(itemsAnteriores);
    removeItemsAnteriores();
    items = (items.concat(itemsTransaccion));
    addItems(items, tipoTransaccion, idPersona);
    // persistItemsInSession(items);
}

function completeItems(id){
    // if (itemsTransaccion!==null){
    //     itemsAnteriores = itemsTransaccion;
    // }
    if (id!=null){
        var transaccion_selected = transacciones[id];
        var idTransaccion = transaccion_selected["Id"];
        $.post( '/' + tipoTransaccion + '/ajax/addItemsTransaccion/' + idTransaccion, function (data) {
            $('#div_transaccion').html(data);
        });
    }
}