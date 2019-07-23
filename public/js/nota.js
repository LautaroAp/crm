var tipoTransaccion;

// function addDetallesNota(items, tipo, id){
//     if (items==null){
//         items = [];
//     }
//     $(document).ready(function () {
//         $('#table_bienes').DataTable({
//             destroy: true,
//             paging: false,
//             searching: false,
//             aaSorting: [],
//             "language": {
//                 "url": "/json/spanish.json"
//             }
//         });
//     });
//     tipoTransaccion = tipo;
//     idPersona = id;

//     var divContainer = document.getElementById("div_table_bienes");
//     if (divContainer != null) {
//         divContainer.parentNode.removeChild(divContainer);
//     }
    
//     divContainer = document.createElement("div");
//     divContainer.setAttribute("id", "div_table_bienes");
//     var parentDiv = document.getElementById("contenido_bienes");
//     parentDiv.innerHTML = "";
//     parentDiv.appendChild(divContainer);
//     table = document.createElement("table");
//     table.setAttribute("id", "table_bienes");
//     table.setAttribute("class", "display");
//     var thead = document.createElement("thead");
//     var col = ["Nombre", "Descripcion", "Cantidad", "Precio", "Dto", "ImpDto", "IVA", "ImpIVA", "Totales", ""];

//     // var col = ["Transaccion", "Detalle", "Monto", "Eliminar"];
//     var tr = thead.insertRow(-1);
//     for (var i = 0; i < col.length; i++) {
//         var th = document.createElement("th");
//         switch (col[i]) {
//             case 'Nombre':
//                 th.innerHTML = "Código"; 
//                 break;
//             case 'Descripcion':
//                 th.innerHTML = "Producto/Servicio"; 
//                 break;
//             case 'Cantidad':
//                 th.innerHTML = "Cant."; 
//             break;
//             case 'Dto':
//                 th.innerHTML = "Dto (%)";
//                 break;
//             case 'ImpDto':
//                 th.innerHTML = "Dto ($)";
//                 break;
//             case 'IVA':
//                 th.innerHTML = "IVA (%)"; 
//                 break;
//             case 'ImpIVA':
//                 th.innerHTML = "IVA ($)";
//                 break;
//             default:
//                 th.innerHTML = col[i];
//           }
//         tr.appendChild(th);
//     }
//     thead.appendChild(tr);
//     table.appendChild(thead);
//     var tbody = document.createElement("tbody");
//     tbody.setAttribute("role", "button");
//     var value = null;
//     for (var i = 0; i < items.length; i++) {
//         var item = items[i];
//         tr = tbody.insertRow(-1);
//         // tr.onclick= selectdetalle(detalle["id"]);
//         tr.setAttribute("id", i);
//         tr.setAttribute("class", "click");
//         //    tr.setAttribute("onclick","selectdetalle(event,id)");
//         for (var j = 0; j < col.length - 1; j++) {
//             var tabCell = tr.insertCell(-1);
//             tabCell.setAttribute("id", i + "_" + col[j]);
//             tabCell.setAttribute("class", "click");
//             var dto, cantidad, dtoPeso, precioDto, iva, precio;
//             precio = item["Precio"];
//             dto = item["Dto"];
//             cantidad = item["Cantidad"];
//             dtoPeso = (precio * dto / 100) * cantidad;
//             precioDto = (cantidad * precio) - dtoPeso;  
//             iva = item["IVA"];
//             //////////////////////////////////////////////////////////////////////////////////
//             switch (col[j]) {
//                 case 'Nombre':
//                     value = item["Codigo"];
//                     break;
//                 case 'Descripcion':
//                     value = item["Detalle"];
//                     break;
//                 case 'Cantidad':
//                     value =item["Cantidad"];
//                     tabCell.setAttribute("ondblclick", "makeEditable(event)");
//                     break;
//                 case 'Dto':
//                     value =item["Dto"];
//                     value = ((parseFloat(value)).toFixed(2));
//                     tabCell.setAttribute("ondblclick", "makeEditable(event)");
//                     break;
//                 case 'ImpDto':
//                     value =item["ImpDto"];
//                     value = ((parseFloat(dtoPeso)).toFixed(2));
//                     break;
//                 case 'IVA':
//                     value = item["IVA"]["Valor"];
//                     if (!value){value=0;}
//                     value = ((parseFloat(value)).toFixed(2));
//                     tabCell.setAttribute("ondblclick", "makeEditable(event)");
//                     break;
//                 case 'ImpIVA':
//                     value =(precioDto * (iva / 100));
//                     if (!value){ value=0};
//                     value = ((parseFloat(value)).toFixed(2));
//                     break;
//                 case 'Precio':
//                     value = precio;
//                     if(!value){value = 0;}
//                     value = ((parseFloat(value)).toFixed(2));
//                     tabCell.setAttribute("ondblclick", "makeEditable(event)");
//                     break;
//                 case 'Totales':
//                     value = item["Totales"];
//                     value = ((parseFloat(value)).toFixed(2));
//                     break
//                 default:
//                     value = item[col[j]];
//             }
//             tabCell.innerHTML = value;
//         }
//         // Botones
//         var btn = document.createElement('button');
//         btn.setAttribute('type', 'button');
//         btn.setAttribute('class', 'btn btn-default btn-sm glyphicon glyphicon-trash'); // set attributes ...
//         btn.setAttribute('id', i);
//         btn.setAttribute('value', 'Borrar');
//         btn.setAttribute("onclick", "removerDetalle(id)");
//         var tabCell = tr.insertCell(-1);
//         tabCell.setAttribute("class", "click");
//         tabCell.appendChild(btn);
//     }
//     table.appendChild(tbody);

//     // var divContainer = document.getElementById("div_table_bienes");
//     divContainer.innerHTML = "";
//     divContainer.appendChild(table);
// }

function addDetallesNota(bienesTransacciones, tipo, id, actualizarPrecio) {
    console.log("--> addDetallesNota()");
    precioActualizado = actualizarPrecio;
    if (bienesTransacciones==null){
        bienesTransacciones = [];
    }
    $(document).ready(function () {
        // $("#table_bienes").dataTable().fnDestroy();
        $('#table_bienes').DataTable({
            destroy: true,
            paging: true,
            searching: true,
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
    for (var i = 0; i < bienesTransacciones.length; i++) {
        var item = bienesTransacciones[i];
        tr = tbody.insertRow(-1);
        tr.setAttribute("id", i);
        tr.setAttribute("class", "click");
        for (var j = 0; j < col.length - 1; j++) {
            var tabCell = tr.insertCell(-1);
            tabCell.setAttribute("id", i + "_" + col[j]);

            var dto, cantidad, dtoPeso, precioDto, iva, precio;
            if(precioActualizado){
                precio = item["Bien"]["Precio"];
            } else {
                precio = item["Precio Original"];
            }

            dto= item["Dto"];
            cantidad = item["Cantidad"];
            dtoPeso = (precio * dto / 100) * cantidad;
            precioDto = (cantidad * precio) - dtoPeso;  
            iva = item["IVA"]["Valor"];

            //////////////////////////////////////////////////////////////////////////////////

            var readonly = true;
            switch (col[j]) {
                case 'Nombre':
                    value = item["Codigo"];
                    break;
                case 'Descripcion':
                    value = item["Detalle"];
                    break;
                case 'Cantidad':
                    value =item["Cantidad"];
                    tabCell.setAttribute("class", "cant-width");
                    readonly = false;
                    break;
                case 'Dto':
                    value =dto;
                    tabCell.setAttribute("class", "dto-width");
                    if (!value){value=0;}
                    value = (parseFloat(value)).toFixed(2);
                    readonly = false;
                    break;
                case 'ImpDto':
                    value = (parseFloat(dtoPeso)).toFixed(2);
                    break;
                case 'IVA':
                    value = iva;
                    tabCell.setAttribute("class", "select-iva-width");
                    if (!value){value=0;}
                    value = (parseFloat(value)).toFixed(2);
                    readonly = false;
                    break;
                case 'ImpIVA':
                    value =(precioDto * (iva / 100));
                    if (!value){ value=0};
                    value = (parseFloat(value)).toFixed(2);
                    break;
                case 'Precio':
                    value = precio;
                    if(!value){value = 0;}
                    value = (parseFloat(value)).toFixed(2);
                    break;
                case 'Totales':
                    if (!iva){iva = 0;}
                    var subtotal = precioDto + precioDto * iva / 100;
                    value = subtotal;
                    if (!value){value=0;}
                    value = (parseFloat(value)).toFixed(2);
                    break
                default:
                    value = item[col[j]];
            }
            
            var myHtml = '<input type="text" role="button" class="form-control item-nota-update" name="" id="'+i+'_item_'+col[j]+'" value="'+ value+'" onkeypress="return justNumbersEnter(event);"';
            if(readonly){
                myHtml += ' readonly';
            };
            myHtml += '>';

            // Crear <select> IVAS
            if(col[j] == 'IVA'){
                myHtml = '<select role="button" id="'+i+'_item_'+col[j]+'" class="form-control item-nota-update">';
                for (var k = 0; k < ivas.length; k++) {
                    myHtml += '<option value="'+ivas[k]['Id']+'"';
                    if(value == ivas[k]['Valor']){
                        myHtml += ' selected';
                    };
                    myHtml += '>'+ivas[k]['Valor']+'</option>';
                }
                myHtml += '</select>';
            }

            if(!readonly){
                tabCell.innerHTML = myHtml;
            } else {
                tabCell.innerHTML = value;
            };            
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
    // calcularSubcampos();
    calcularTotal();
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
    console.log("--> addDetalleToTable()");

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
    var cantidad = $("#cantidad").val();
    if (cantidad == ""){
        cantidad = 1;
    }
    var indexiva = $("#select_ivas").val();
    if (!indexiva){
        indexiva = ivas.length -1;
    }
    if ((indexiva == "-1") || (indexiva == "")){
        indexiva = ivas.length-1;
    }
    var dto = $("#nota_dto").val();
    if(dto == ""){
        dto = 0;
    }
    var impDto = (monto*cantidad)*dto/100;
    var montoDto = (monto*cantidad) - impDto;
    //PASARLE IVAS
    var iva = ivas[indexiva]["Valor"];
    var impIva = montoDto * iva/100;
    var totales= montoDto + impIva;

    var item_gravado = "0";
    var item_no_gravado = "0";
    var item_exento = "0";
    switch (iva) {
        case "0.00":
            // EXENTO
            item_exento = montoDto;
            item_exento = (parseFloat(item_exento).toFixed(2));
            break;
        default:
            // GRAVADO
            item_gravado = montoDto;
            item_gravado = (parseFloat(item_gravado).toFixed(2));
            break;
    }

    output = {
        "Id":"",
        "Codigo":"-",
        "Cantidad":cantidad,
        "Numero Tipo Transaccion": "-",
        "Precio Original": parseFloat(monto).toFixed(2),
        "Subtotal": parseFloat(montoDto).toFixed(2),
        "Tipo": "indefinido",
        "Index" : "-1",
        "Dto": dto,
        "ImpDto": parseFloat(impDto).toFixed(2),
        "IVA":  ivas[indexiva],
        "ImpIVA": parseFloat(impIva).toFixed(2),
        "ImporteGravado": item_gravado,
        "ImporteNoGravado": item_no_gravado,
        "ImporteExento": item_exento,
        "Detalle": detalle,
        "Totales": parseFloat(totales).toFixed(2)
    }
    items.push(output);
    $("#jsonitems").val(JSON.stringify(items));
    addDetallesNota(items, tipoTransaccion, idPersona);
    borrarCampos();
    calcularTotal();
}

function completeDetalles(id){
    console.log("--> completeDetalles()");

    if (id!=null){
        var tipoT = $("#tipoTransaccion").val();
        if (tipoT=="Factura"){
            var transaccion_selected = jsonFacturas[id];
            mensaje = "Anula Factura "+ transaccion_selected["Numero Tipo Transaccion"];
            output = {
                "Id":"",
                "Codigo":"-",
                "Cantidad":1,
                "Precio Original": transaccion_selected["Importe Total"],
                "Numero Tipo Transaccion": "-",
                "Subtotal": transaccion_selected["Subtotal"],
                "Tipo":"factura",
                "Index": id,
                "Dto": "0",
                "IVA": ivas[ivas.length-1],
                "ImporteGravado": transaccion_selected["ImporteGravado"],
                "ImporteNoGravado": transaccion_selected["ImporteNoGravado"],
                "ImporteExento": transaccion_selected["ImporteExento"],
                "Detalle": mensaje,
                "Transaccion Previa":transaccion_selected,
                "Totales": transaccion_selected["Importe Total"]
            }
           
        } else {
            var transaccion_selected = jsonRemitos[id];
            mensaje = "Anula Remito "+ transaccion_selected["Numero Tipo Transaccion"];
            output = {
                "Id":"",
                "Codigo":"-",
                "Cantidad":1,
                "Precio Original": transaccion_selected["Importe Total"],
                "Numero Tipo Transaccion": "-",
                "Subtotal":  transaccion_selected["Subtotal"],
                "Tipo":"remito",
                "Index": id,
                "Dto": "0",
                "IVA":ivas[ivas.length-1],
                "ImporteGravado": transaccion_selected["ImporteGravado"],
                "ImporteNoGravado": transaccion_selected["ImporteNoGravado"],
                "ImporteExento": transaccion_selected["ImporteExento"],
                "Detalle": mensaje,
                "Transaccion Previa":transaccion_selected,
                "Totales": transaccion_selected["Importe Total"]
            }
        }
    }
    items.push(output);
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
    document.getElementById("select_ivas").selectedIndex = "-1"; 

}

function calcularTotal() {
    var bonificacion_general = 0;
    var recargo_general = 0;
    var sumTotal = 0;
    var total_letras = "";

    for (var i = 0; i < items.length; i++) {
        subtotal = items[i]["Totales"];
        sumTotal = sumTotal + parseFloat(subtotal);
    }

    bonificacion_general = $("#bonificacion_general").val();
    recargo_general = $("#recargo_general").val();

    var total_general = sumTotal - (sumTotal * bonificacion_general / 100) + (sumTotal * recargo_general / 100);
    var bonificacion_importe = sumTotal * bonificacion_general / 100;
    var recargo_importe = sumTotal * recargo_general / 100;

    $("#bonificacion_importe").val((parseFloat(bonificacion_importe).toFixed(2)));
    $("#recargo_importe").val((parseFloat(recargo_importe).toFixed(2)));
    $("#total_general").val((parseFloat(total_general).toFixed(2)));
    $("#jsonitems").val(JSON.stringify(items));

    // Calcular Importes Gravado/NoGravado/Exento + IVA
    actualizarImporteImpuesto();

    // Calcular Total en Letras
    total_letras = intToChar(parseFloat(total_general).toFixed(2));
    $("#total_letras").html(total_letras);
    $("#total_letras").val(total_letras);
}

// Calcula segun la TransaccionPrecia HACER *************
function actualizarImporteImpuestoNota(){
    var sumImpGravado = 0; 
    var sumImpNoGravado = 0;
    var sumImpExento = 0;

    var sumIva = {
        iva_27: 0,
        iva_21: 0,
        iva_10: 0,
        iva_5: 0,
        iva_2: 0,
        iva_0: 0
    };

    var bonificacion = parseFloat($("#bonificacion_general").val());
    if(!bonificacion){
        bonificacion = 0;
        parseFloat($("#bonificacion_general").val("0"));
    }
    var recargo = parseFloat($("#recargo_general").val());
    if(!recargo){
        recargo = 0;
        parseFloat($("#recargo_general").val("0"));
    }

    for(var i = 0; i < items.length; i++){
        if(items[i]["ImporteGravado"]){
            sumImpGravado = sumImpGravado + parseFloat(items[i]["ImporteGravado"]);
        }
        if(items[i]["ImporteNoGravado"]){
            sumImpNoGravado = sumImpNoGravado + parseFloat(items[i]["ImporteNoGravado"]);
        }
        if(items[i]["ImporteExento"]){
            sumImpExento = sumImpExento + parseFloat(items[i]["ImporteExento"]);
        }

        if(items[i]["Transaccion Previa"]){
                sumIva.iva_27 = sumIva.iva_27 + parseFloat(items[i]["Transaccion Previa"]["ImporteIVA27"]);
                sumIva.iva_21 = sumIva.iva_21 + parseFloat(items[i]["Transaccion Previa"]["ImporteIVA21"]);
                sumIva.iva_10 = sumIva.iva_10 + parseFloat(items[i]["Transaccion Previa"]["ImporteIVA10"]);
                sumIva.iva_5 = sumIva.iva_5 + parseFloat(items[i]["Transaccion Previa"]["ImporteIVA5"]);
                sumIva.iva_2 = sumIva.iva_2 + parseFloat(items[i]["Transaccion Previa"]["ImporteIVA2"]);
                sumIva.iva_0 = sumIva.iva_0 + parseFloat(items[i]["Transaccion Previa"]["ImporteIVA0"]);
        }
        else {
            switch (items[i]["IVA"]["Valor"]) {
                case "27.00":
                    sumIva.iva_27 = sumIva.iva_27 + parseFloat(items[i]["ImpIVA"]);
                    break;
                case "21.00":
                    sumIva.iva_21 = sumIva.iva_21 + parseFloat(items[i]["ImpIVA"]);
                    break;
                case "10.50":
                    sumIva.iva_10 = sumIva.iva_10 + parseFloat(items[i]["ImpIVA"]);
                    break;
                case "5.00":
                    sumIva.iva_5 = sumIva.iva_5 + parseFloat(items[i]["ImpIVA"]);
                    break;
                case "2.50":
                    sumIva.iva_2 = sumIva.iva_2 + parseFloat(items[i]["ImpIVA"]);
                    break;
                case "0.00":
                    sumIva.iva_0 = sumIva.iva_0 + parseFloat(items[i]["ImpIVA"]);
                    break;
                default:
                    break;
            }
        }
    }

    // GRAVADO
    sumImpGravado = sumImpGravado - (sumImpGravado * bonificacion/100);
    sumImpGravado = sumImpGravado + (sumImpGravado * recargo/100);
    // NO GRAVADO
    sumImpNoGravado = sumImpNoGravado - (sumImpNoGravado * bonificacion/100);
    sumImpNoGravado = sumImpNoGravado + (sumImpNoGravado * recargo/100);
    // EXCENTO
    sumImpExento = sumImpExento - (sumImpExento * bonificacion/100);
    sumImpExento = sumImpExento + (sumImpExento * recargo/100);

    $('#importe_gravado').val(parseFloat(sumImpGravado).toFixed(2));
    $('#importe_no_gravado').val(parseFloat(sumImpNoGravado).toFixed(2));
    $('#importe_exento').val(parseFloat(sumImpExento).toFixed(2));

    $('#importe_iva_27').val(parseFloat(sumIva.iva_27).toFixed(2));
    $('#importe_iva_21').val(parseFloat(sumIva.iva_21).toFixed(2));
    $('#importe_iva_10').val(parseFloat(sumIva.iva_10).toFixed(2));
    $('#importe_iva_5').val(parseFloat(sumIva.iva_5).toFixed(2));
    $('#importe_iva_2').val(parseFloat(sumIva.iva_2).toFixed(2));
    $('#importe_iva_0').val(parseFloat(sumIva.iva_0).toFixed(2));
};

///////////////////////////////////////////////////////////////

$(document).ready(function(){
    
    $("#btn-modal").click(function(){
        optionSelected= $("#tipoTransaccion option:selected").val();
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

    // ACTUALZIAR VALORES ITEM
    $("body").on("change", ".item-nota-update", function(e){
        console.log("--> item-nota-update");
        var pos = e.target.id.split("", 1);
        var item_precio = items[pos]["Precio Original"];
        var item_cantidad = $('#'+pos+'_item_Cantidad').val();
        var item_subtotal = item_precio * item_cantidad;
        var item_total = "0";

        var item_descuento = $('#'+pos+'_item_Dto').val();
        var item_importe_descuento = item_subtotal * (item_descuento/100);
        item_subtotal = item_subtotal - item_importe_descuento;

        var item_iva = $('#'+pos+'_item_IVA option:selected').text();
        var item_importe_iva = item_subtotal * (item_iva/100);
        item_total = item_subtotal + item_importe_iva;

        var item_gravado = "0";
        var item_no_gravado = "0";
        var item_exento = "0";
        switch (item_iva["Valor"]) {
            case "0.00":
                // EXENTO
                item_exento = item_subtotal;
                item_exento = (parseFloat(item_exento).toFixed(2));
                break;
            default:
                // GRAVADO
                item_gravado = item_subtotal;
                item_gravado = (parseFloat(item_gravado).toFixed(2));
                break;
        }

        item_importe_descuento = (parseFloat(item_importe_descuento).toFixed(2));
        item_importe_iva = (parseFloat(item_importe_iva).toFixed(2));
        item_subtotal = (parseFloat(item_subtotal).toFixed(2));
        item_total = (parseFloat(item_total).toFixed(2));
        
        // Actualizando item[pos]
        items[pos]["Cantidad"] = item_cantidad;
        items[pos]["Dto"] = item_descuento;
        items[pos]["IVA"] = ivas[getIvaFromValue(item_iva)];
        items[pos]["ImpIVA"] = item_importe_iva; //items[pos]["ImpIva"] = item_importe_iva;
        items[pos]["Subtotal"] = item_subtotal;
        items[pos]["ImpDto"] = item_importe_descuento;
        // "Precio Original": item_subtotal;
        items[pos]["ImporteGravado"] = item_gravado;
        items[pos]["ImporteNoGravado"] = item_no_gravado;
        items[pos]["ImporteExento"] = item_exento;
        items[pos]["Totales"] = item_total;

        // Actualizando valores de fila
        $('#'+pos+'_ImpDto').html(item_importe_descuento);
        $('#'+pos+'_ImpIVA').html(item_importe_iva);
        $('#'+pos+'_Totales').html(item_total);

        // Recalcular
        calcularTotal();

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
        tr.setAttribute("id", i);
        tr.setAttribute("class", "click");
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
        $('#' + id).removeClass('glyphicon-unchecked');
        $('#' + id).addClass('glyphicon-check');
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
