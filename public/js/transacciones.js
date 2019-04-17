function completarTransacciones(transacciones){
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
        for (var j = 0; j < col.length - 1; j++) {
            var tabCell = tr.insertCell(-1);
            tabCell.setAttribute("id", i + "_" + col[j]);
            tabCell.setAttribute("class", "click");
            value = transaccion[col[j]];
            tabCell.innerHTML = value;
        }

        // Botones
        // var btn = document.createElement('button');
        // btn.setAttribute('type', 'button');
        // btn.setAttribute('class', 'btn btn-default btn-sm glyphicon glyphicon-remove'); // set attributes ...
        // btn.setAttribute('id', i);
        // btn.setAttribute('value', 'Seleccionar');
        // btn.setAttribute("onclick", "removerBien2(event,id)");
        // var tabCell = tr.insertCell(-1);
        // tabCell.setAttribute("class", "click");
        // tabCell.appendChild(btn);
    }
    table.appendChild(tbody);

    divContainer.innerHTML = "";
    divContainer.appendChild(table);

//     var mymodal = $('#modalTransacciones');
//     mymodal.find('.modal-body').innerHTML("hola");
//     mymodal.modal('show');
// 
}

