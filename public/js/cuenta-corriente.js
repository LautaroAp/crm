tipoPersona="";
function getNumberValue(inputValue) {
    inputValue = inputValue.substring(2, inputValue.length);
    // Puntos
    inputValue = inputValue.replace(".", "", "gi");
    inputValue = inputValue.replace(".", "", "gi");
    inputValue = inputValue.replace(".", "", "gi");
    // Coma
    inputValue = inputValue.replace(",", ".", "gi");
    return inputValue;
}



function calcularCuentaCorriente(persona){
    tipoPersona= persona;
    var table = document.getElementById("tabla_ventas");
    var sumRemitos = 0;
    var sumRemitosNoOficial = 0;
    var sumFacturados = 0;
    var sumFacturadosNoOficial = 0;

    // VENTAS
    for (var i = 1; i<table.rows.length; i++) {        
        var row = table.rows[i];
        if (row.cells[1]){
            // [fecha, persona, tipo, nro, oficial, importe]
            var tipo = row.cells[2].innerHTML;
            var oficial = row.cells[4].innerHTML.trim();
            var monto = row.cells[5].innerHTML; 

            if (tipo=="Remito"){
                switch (oficial) {
                    case "Si":
                        sumRemitos = sumRemitos + parseFloat(getNumberValue(monto)); 
                        break;
                    case "No":
                        sumRemitosNoOficial = sumRemitosNoOficial + parseFloat(getNumberValue(monto)); 
                        break;
                    default:
                        break;
                }
            }
            else{
                switch (oficial) {
                    case "Si":
                        sumFacturados = sumFacturados + parseFloat(getNumberValue(monto));
                        break;
                    case "No":
                        sumFacturadosNoOficial = sumFacturadosNoOficial + parseFloat(getNumberValue(monto));
                        break;
                    default:
                        break;
                }              
            }
        }
    }
    $('#total_ventas').val((sumRemitos.toFixed(2)));
    $('#total_ventas_no_oficial').val((sumRemitosNoOficial.toFixed(2)));

    $('#total_facturado').val((sumFacturados.toFixed(2)));
    $('#total_facturado_no_oficial').val((sumFacturadosNoOficial.toFixed(2)));
    
    var table2 = document.getElementById("tabla_cobros");
    var sumCobros = 0;

    // COBROS
    for (var j = 1; j<table2.rows.length; j++) {        
        var r = table2.rows[j];
        if (r.cells[1]){
            // [fecha, persona, tipo, nro, importe]
            var tipo = r.cells[2].innerHTML;
            var monto = r.cells[4].innerHTML;
            sumCobros = sumCobros + parseFloat(getNumberValue(monto));
        }
       
    }
    $('#total_cobros').val((sumCobros.toFixed(2)));

    var saldoPendienteCobro = (sumFacturados + sumFacturadosNoOficial) - sumCobros;
    var prodSinFacturar = (sumRemitos + sumRemitosNoOficial) - (sumFacturados + sumFacturadosNoOficial);
    var cuentaCorrienteGlobal = saldoPendienteCobro + prodSinFacturar;

    $('#saldo_pendiente_cobro').val((saldoPendienteCobro.toFixed(2)));
    $('#saldo_pendiente_factura').val((prodSinFacturar.toFixed(2)));
    $('#cuenta_corriente_global').val((cuentaCorrienteGlobal.toFixed(2)));

}


