var items=[];
var tipoTransaccion;
var idPersona;
function addItems(bienesTransacciones, tipo, id) {
    items = bienesTransacciones;
    tipoTransaccion = tipo; 
    idPersona=id;
    console.log(idPersona);
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
        tr.setAttribute("onclick","selectItem(event,id)");
        console.log(item);
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
            if ((col[j] == "Descuento") || (col[j]=="IVA")){value = formatPercent((parseFloat(value)).toFixed(2));}
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
