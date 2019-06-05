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
    var col = ["Nombre", "Descripcion", "Cantidad", "Precio", "Dto", "ImpDto", "IVA", "ImpIVA", "Totales", ""];

    // var col = ["Transaccion", "Detalle", "Monto", "Eliminar"];
    var tr = thead.insertRow(-1);
    for (var i = 0; i < col.length; i++) {
        var th = document.createElement("th");
        switch (col[i]) {
            case 'Nombre':
                th.innerHTML = "Código"; 
                break;
            case 'Descripcion':
                th.innerHTML = "Producto/Servicio"; 
                break;
            case 'Cantidad':
                th.innerHTML = "Cant."; 
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
    for (var i = 0; i < items.length; i++) {
        var item = items[i];
        tr = tbody.insertRow(-1);
        // tr.onclick= selectdetalle(detalle["id"]);
        tr.setAttribute("id", i);
        tr.setAttribute("class", "click");
        //    tr.setAttribute("onclick","selectdetalle(event,id)");
        for (var j = 0; j < col.length - 1; j++) {
            var tabCell = tr.insertCell(-1);
            tabCell.setAttribute("id", i + "_" + col[j]);
            tabCell.setAttribute("class", "click");
            var dto, cantidad, dtoPeso, precioDto, iva, precio;
            precio = item["Subtotal"];
            dto = item["Dto"];
            cantidad = item["Cantidad"];
            dtoPeso = (precio * dto / 100) * cantidad;
            precioDto = (cantidad * precio) - dtoPeso;  
            iva = item["IVA"];
            //////////////////////////////////////////////////////////////////////////////////
            switch (col[j]) {
                case 'Nombre':
                    value = item["Codigo"];
                    break;
                case 'Descripcion':
                    value = item["Detalle"];
                    break;
                case 'Cantidad':
                    value =item["Cantidad"];
                    tabCell.setAttribute("ondblclick", "makeEditable(event)");
                    break;
                case 'Dto':
                    value =item["Dto"];
                    value = formatPercent((parseFloat(value)).toFixed(2));
                    tabCell.setAttribute("ondblclick", "makeEditable(event)");
                    break;
                case 'ImpDto':
                    value =item["ImpDto"];
                    value = formatMoney((parseFloat(dtoPeso)).toFixed(2));
                    break;
                case 'IVA':
                    value = item["IVA"];
                    if (!value){value=0;}
                    value = formatPercent((parseFloat(value)).toFixed(2));
                    tabCell.setAttribute("ondblclick", "makeEditable(event)");
                    break;
                case 'ImpIVA':
                    value =(precioDto * (iva / 100));
                    if (!value){ value=0};
                    value = formatMoney((parseFloat(value)).toFixed(2));
                    break;
                case 'Precio':
                    value = precio;
                    if(!value){value = 0;}
                    value = formatMoney((parseFloat(value)).toFixed(2));
                    break;
                case 'Totales':
                    if (!iva){iva = 0;}
                    var subtotal = precioDto + precioDto * iva / 100;
                    value = subtotal;
                    if (!value){value=0;}
                    value = formatMoney((parseFloat(value)).toFixed(2));
                    break
                default:
                    value = item[col[j]];
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
    var item = items[id];
    var indexItem = item["Index"];
    var tipoItem = item["Tipo"];
    var toDelete ="Selected";
    if (tipoItem=="factura"){
        delete jsonFacturas[indexItem][toDelete];
        completarTransacciones(jsonFacturas);
        console.log("DESPUES DE BORRAR UNA FACTURA QUEDA ");
        console.log(jsonFacturas);
    }
    if (tipoItem=="remito"){
        delete jsonRemitos[indexItem][toDelete];
        completarTransacciones(jsonRemitos);
    }
    items.splice(id, 1);
    $("#jsonitems").val(JSON.stringify(items));
    addDetallesNota(items, tipoTransaccion, idPersona);
    calcularTotal();
}

function addDetalleToTable() {
    var detalle = $("#nota_detalle").val();
    if (detalle==""){
        alert("Debe completar el detalle ");
        return;
    }
    var monto = $("#nota_monto").val();
    if (monto==""){
        alert("Ingrese un monto");
        return;
    }
    var cantidad= $("#cantidad").val();
    if (cantidad==""){cantidad=1;}
    var indexiva = $("#nota_iva").val();
    var dto = $("#nota_dto").val();
    if(dto==""){dto=0;}
    if(dto==""){dto=0;}
    // var impDto= monto*cantidad - (monto*cantidad)*dto/100;
    //PASARLE IVAS
    // var iva = ivas[indexiva]["Valor"];
    // var impIva, impDto= 0;

    output = {
        "Id":"",
        "Codigo":"-",
        "Cantidad":cantidad,
        "Numero Tipo Transaccion": "-",
        "Subtotal": monto,
        "Tipo": "indefinido",
        "Index" : "-1",
        "Dto": dto,
        "IVA": 0,
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
        console.log("Id es :"+ id);
        var tipoT = $("#tipoTransaccion").val();
        if (tipoT=="Factura"){
            var transaccion_selected = jsonFacturas[id];
            mensaje = "Anula Factura "+ transaccion_selected["Numero Tipo Transaccion"];
            output = {
                "Id":"",
                "Codigo":"-",
                "Cantidad":1,
                "Numero Tipo Transaccion": "-",
                "Subtotal": transaccion_selected["Importe Total"],
                "Tipo":"factura",
                "Index": id,
                "Dto": transaccion_selected["Bonificacion general"],
                "IVA": 0,
                "Detalle": mensaje,
                "Transaccion Previa":transaccion_selected
            }
           
        }else{
            var transaccion_selected = jsonRemitos[id];
            mensaje = "Anula Remito "+ transaccion_selected["Numero Tipo Transaccion"];
            output = {
                "Id":"",
                "Codigo":"-",
                "Cantidad":1,
                "Numero Tipo Transaccion": "-",
                "Subtotal":  transaccion_selected["Importe Total"],
                "Tipo":"remito",
                "Index": id,
                "Dto": transaccion_selected["Bonificacion general"],
                "IVA": 0,
                "Detalle": mensaje,
                "Transaccion Previa":transaccion_selected
            }
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
    $("#nota_dto").val("");
    $("#cantidad").val("");
}

function calcularTotal(){
    var sumTotal = 0;
    for (var i = 0; i < items.length; i++) {
        subtotal = items[i]["Subtotal"];
        sumTotal = sumTotal + parseFloat(subtotal);
    }
    $("#total_general").val(formatMoney((parseFloat(sumTotal)).toFixed(2)));
    // $("#total_letras").val(intToChar(sumTotal));

}




///////////////////////////////////////////////////////////////

$(document).ready(function(){
    
    $("#btn-modal").click(function(){
        optionSelected= $("#tipoTransaccion option:selected").val();
        console.log(optionSelected);
        jsonModalTransaccion=[];
        switch (optionSelected) {
            case 'Remito':
                completarTransacciones(jsonRemitos);    
                break;
            case 'Factura':
                completarTransacciones(jsonFacturas);
                break;
            default:
            completarTransacciones(jsonFacturas);
        }
    });
    
});


function completarTransacciones(transacciones){
    $(document).ready(function () {
        $('#table_transacciones').DataTable({
            destroy: true,
            paging: true,
            lengthMenu: [[5,10,25,50,-1],[5,10,25,50,"Todos"]],
            searching: true,
            
            "order": [0, 'asc'],

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
        var transac = transacciones[i];
        tr = tbody.insertRow(-1);
        // tr.onclick= selectItem(item["id"]);
        tr.setAttribute("id", i);
        tr.setAttribute("class", "click");
        //    tr.setAttribute("onclick","selectItem(event,id)");
        for (var j = 0; j < col.length-1; j++) {
            var tabCell = tr.insertCell(-1);
            tabCell.setAttribute("id", i);
            tabCell.setAttribute("class", "click");
            if (col[j]=="Numero"){
                value = transac["Numero Tipo Transaccion"];
            }
            else{
                value = transac[col[j]];
            }
            tabCell.innerHTML = value;
        }

        // Botones
        var btn = document.createElement('button');
        btn.setAttribute('type', 'button');

        if ("Selected" in transac){
            console.log("lo pone como chequeado");
            btn.setAttribute('class', 'btn btn-default btn-sm  glyphicon glyphicon-check ');
        }
        else{
            btn.setAttribute('class', 'btn btn-default btn-sm  glyphicon glyphicon-unchecked ');
        }
        btn.setAttribute('id', i+"button");
        btn.setAttribute("onclick", "selectTransaccion(id)");
        var tabCell = tr.insertCell(-1);
        tabCell.setAttribute("class", "click");
        tabCell.appendChild(btn);
    }
    table.appendChild(tbody);
    divContainer.innerHTML = "";
    divContainer.appendChild(table);
}

function getTransaccion(buttonId){
    return  buttonId.substring(0, buttonId.indexOf('b'));
}

function selectTransaccion(id){
    if ($('#' + id).hasClass('glyphicon-unchecked')){
        // console.log("uncheked");
        $('#' + id).removeClass('glyphicon-unchecked');
        $('#' + id).addClass('glyphicon-check');
        // console.log("cheked");
        var tipoT = $('#tipoTransaccion').val();
        var indexTransaccion = getTransaccion(id);
        if (tipoT=="Factura"){
            jsonFacturas[indexTransaccion]["Selected"]= true;
            completeDetalles(indexTransaccion);
        }
        else{
            jsonRemitos[indexTransaccion]["Selected"]= true;
            completeDetalles(indexTransaccion);
        }

    }
}
