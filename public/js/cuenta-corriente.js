tipoPersona="";
function getNumberValue(inputValue) {
    inputValue = inputValue.substring(2, inputValue.length);
    inputValue = inputValue.replace(".", "", "gi");
    inputValue = inputValue.replace(",", ".", "gi");
    return inputValue;
}


function formatMoney(number) {
    return '$ ' + number.toLocaleString('en-US');
}


function calcularCuentaCorriente(tipo){
    tipoPersona= tipo;
    var table = document.getElementById("tabla_ventas");
    var sumRemitos = 0;
    var sumFacturados = 0;

    // for (var i = 1, row; row = table.rows[i]; i++) {        
    for (var i = 1; i<table.rows.length; i++) {        
        var row = table.rows[i];
        if (row.cells[1]){
            var tipo = row.cells[1].innerHTML;
            var monto = row.cells[3].innerHTML;
            console.log(tipo);
            if (tipo=="Remito"){
                sumRemitos = sumRemitos + parseFloat(getNumberValue(monto));
            }
            else{
                sumFacturados = sumFacturados + parseFloat(getNumberValue(monto));
            }
        }
    }
    $('#total_ventas').val(formatMoney(sumRemitos.toFixed(2)));
    $('#total_facturado').val(formatMoney(sumFacturados.toFixed(2)));
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    var table2 = document.getElementById("tabla_cobros");
    var sumCobros = 0;
    // for (var j = 1, r; r = table.rows[j]; j++) {        
    for (var j = 1; j<table2.rows.length; j++) {        
        var r = table2.rows[j];
        if (r.cells[1]){
            var tipo = r.cells[1].innerHTML;
            var monto = r.cells[3].innerHTML;
            sumCobros = sumCobros + parseFloat(getNumberValue(monto));
        }
       
    }

    $('#total_cobros').val(formatMoney(sumCobros.toFixed(2)));

    var saldoPendienteCobro = sumFacturados - sumCobros;
    // if (saldoPendienteCobro<0){
    //     var saldoPendienteCobro2 = saldoPendienteCobro * -1;
    //     if(tipoPersona=="CLIENTE"){
    //         $('#titulo_saldo').text("Saldo del cliente a favor");
    //     }
    //     else{
    //         $('#titulo_saldo').text("Saldo a favor ");
    //     }
    //     $('#saldo_pendiente_cobro').val(formatMoney(saldoPendienteCobro2.toFixed(2)));

    // }else{
        $('#saldo_pendiente_cobro').val(formatMoney(saldoPendienteCobro.toFixed(2)));

    // }
    console.log(sumRemitos);
    console.log(sumFacturados);
    var prodSinFacturar = sumRemitos - sumFacturados;
    var cuentaCorrienteGlobal = saldoPendienteCobro + prodSinFacturar;
    $('#saldo_pendiente_factura').val(formatMoney(prodSinFacturar.toFixed(2)));
    // if (cuentaCorrienteGlobal<0){
    //     $('#titulo_gral').text("Cuenta Corriente Global a favor");
    //     cuentaCorrienteGlobal = cuentaCorrienteGlobal * -1;        
    // }
    $('#cuenta_corriente_global').val(formatMoney(cuentaCorrienteGlobal.toFixed(2)));

}


