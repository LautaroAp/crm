var tipoTransaccion;
function addDetallesNota(items, tipo, id){
    if (items==null){
        items = [];
    }
    $(document).ready(function () {
        $('#table_bienes').DataTable({
            destroy: true,
            paging: false,
            searching: false,
            aaSorting: [],
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

    var col = ["Transaccion", "Detalle", "Monto", ""];
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
        var detalle = items[i];
        tr = tbody.insertRow(-1);
        // tr.onclick= selectdetalle(detalle["id"]);
        tr.setAttribute("id", i);
        tr.setAttribute("class", "click");
        //    tr.setAttribute("onclick","selectdetalle(event,id)");
        for (var j = 0; j < col.length - 1; j++) {
            var tabCell = tr.insertCell(-1);
            tabCell.setAttribute("id", i + "_" + col[j]);
            tabCell.setAttribute("class", "click");
            //////////////////////////////////////////////////////////////////////////////////
            switch (col[j]) {
                case 'Transaccion':
                    value = detalle["Transaccion Previa"]["Numero Tipo Transaccion"];
                    break;
                case 'Detalle':
                    value = detalle[col[j]];
                    tabCell.setAttribute("ondblclick", "makeEditable(event)");
                    break;
                case 'Monto':
                    value = detalle["Transaccion Previa"]["Subtotal"];
                    tabCell.setAttribute("ondblclick", "makeEditable(event)");
                    break;
                default:
                    value = " ";
            }
            tabCell.innerHTML = value;
        }
        // Botones
        var btn = document.createElement('button');
        btn.setAttribute('type', 'button');
        btn.setAttribute('class', 'btn btn-default btn-sm glyphicon glyphicon-trash'); // set attributes ...
        btn.setAttribute('id', i);
        btn.setAttribute('value', 'Borrar');
        btn.setAttribute("onclick", "removerDetalle(id)");
        var tabCell = tr.insertCell(-1);
        tabCell.setAttribute("class", "click");
        tabCell.appendChild(btn);
    }
    table.appendChild(tbody);

    // var divContainer = document.getElementById("div_table_bienes");
    divContainer.innerHTML = "";
    divContainer.appendChild(table);
}


function removerDetalle(id) {
    //el id indica el inicio de donde se borra y el 1 la cantidad de elementos eliminados
    items.splice(id, 1);
    addDetallesNota(items, tipoTransaccion, idPersona);
    calcularTotal();
}

function addDetalleToTable() {
    var detalle = $("#nota_detalle").val();
    var monto = $("#nota_monto").val();

    output = {
        "Transaccion Previa": {
            "Id":"",
            "Numero Tipo Transaccion": "-",
            "Subtotal": formatMoney((parseFloat(monto)).toFixed(2))
        },
        "Detalle": detalle
    }
    items.push(output);
    console.log(items);
    $("#jsonitems").val(JSON.stringify(items));
    addDetallesNota(items, tipoTransaccion, idPersona);
    borrarCampos();
    calcularTotal();
}

function completeDetalles(id){ 
    
    if (id!=null){
        items=[];
        var transaccion_selected = transacciones[id];
        mensaje = "Anula Factura "+ transaccion_selected["Numero Tipo Transaccion"];
        output = {
            "Transaccion Previa":transaccion_selected,
            "Detalle": mensaje
        }
    }
    items.push(output);
    console.log(items);
    $("#jsonitems").val(JSON.stringify(items));
    addDetallesNota(items, tipoTransaccion, idPersona);
    borrarCampos();
    calcularTotal();
}

function borrarCampos(){
    $("#nota_detalle").val("");
    $("#nota_monto").val("");
}

function calcularTotal(){
    var table = document.getElementById("table_bienes");
    var sumTotal = 0;
    for (var i = 0; i < items.length; i++) {
        subtotal = items[i]["Transaccion Previa"]["Subtotal"];
        subtotal = getNumberValue(subtotal);
        sumTotal = sumTotal + parseFloat(subtotal);
    }
    $("#total_general").val(formatMoney((parseFloat(sumTotal)).toFixed(2)));

}
