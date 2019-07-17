tipoPersona="";
function getNumberValue(inputValue) {
    inputValue = inputValue.substring(2, inputValue.length);
    inputValue = inputValue.replace(".", "", "gi");
    inputValue = inputValue.replace(",", ".", "gi");
    return inputValue;
}


// function formatMoney(number) {
//     return '$ ' + number.toLocaleString('en-US');
// }


function calcularCuentaCorriente(tipo){
    tipoPersona= tipo;
    var table = document.getElementById("tabla_ventas");
    var sumRemitos = 0;
    var sumFacturados = 0;

    for (var i = 1; i<table.rows.length; i++) {        
        var row = table.rows[i];
        if (row.cells[1]){
            var tipo = row.cells[1].innerHTML;
            var monto = row.cells[3].innerHTML;
            if (tipo=="Remito"){
                sumRemitos = sumRemitos + parseFloat(getNumberValue(monto));
            }
            else{
                sumFacturados = sumFacturados + parseFloat(getNumberValue(monto));
            }
        }
    }
    $('#total_ventas').val((sumRemitos.toFixed(2)));
    $('#total_facturado').val((sumFacturados.toFixed(2)));
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    var table2 = document.getElementById("tabla_cobros");
    var sumCobros = 0;
    for (var j = 1; j<table2.rows.length; j++) {        
        var r = table2.rows[j];
        if (r.cells[1]){
            var tipo = r.cells[1].innerHTML;
            var monto = r.cells[3].innerHTML;
            sumCobros = sumCobros + parseFloat(getNumberValue(monto));
        }
       
    }

    $('#total_cobros').val((sumCobros.toFixed(2)));

    var saldoPendienteCobro = sumFacturados - sumCobros;
    $('#saldo_pendiente_cobro').val((saldoPendienteCobro.toFixed(2)));
    var prodSinFacturar = sumRemitos - sumFacturados;
    var cuentaCorrienteGlobal = saldoPendienteCobro + prodSinFacturar;
    $('#saldo_pendiente_factura').val((prodSinFacturar.toFixed(2)));
    $('#cuenta_corriente_global').val((cuentaCorrienteGlobal.toFixed(2)));

}


