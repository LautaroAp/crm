var eventos = [];
// obtengo ID de eventos
function seleccionarFila(e) {
    elementId = e.target.id;
    $('#' + elementId).toggleClass('table-seleccion');
    if ($('#' + elementId).hasClass('table-seleccion')) {
        // Guardo id de evento
        eventos.push(elementId);
    } else {
        // Elimino id de evento
        eventos.splice($.inArray(elementId, eventos), 1);
    }
}

// funcion para eliminar los eventos guardados
function eliminaEventos() {
    // para cada elemento, envio id a la Action eliminaEvento
    for (i = 0; i < eventos.length; i++) {
        id_evento = eventos[i];
        $.ajax({
            "dataType": "text",
            "type": "POST",
            "data": "temp",
            "url": '/clientes/ajax/eliminaEventos/' + id_evento,

            "success": function (msg) {
                document.location.reload();
            },
            "error": function (msg) {
                console.log("1-error!");
            }

        }).done(function (msg) {
            console.log("1-done!");
        })
    }
}

//toma el pais al cambiar la opcion, lo envia a la accion getPovincias que devuelve la lista de provincias para elegir
function actualizarDatosProvincias(id_pais)
{
    $.post('/clientes/ajax/getProvincias/' + id_pais, function (data) {
        $('#div_provincias').html(data);
    });
}

function actualizarServicios(id_categoria)
{
    $.post('/clientes/ajax/getServicios/' + id_categoria, function (data) {
        $('#div_servicios').html(data);
    });
}

function actualizarDatosTipoEvento(tipo){
    if (tipo==-1)
        tipo="todos";
    $.post('/getTipos/' + tipo, function (data) {$('#div_tipo_eve').html(data);
        });
}


var item = null;
var item_ant=null;
// obtengo ID de eventos
function seleccionarItem(e) {
    elementId = e.target.id;
    // alert(document.body.getElementsByTagName("td")[0]);

    $('#' + elementId).toggleClass('table-seleccion');
    if ($('#' + elementId).hasClass('table-seleccion')) {
        item_ant=item;
        if (item_ant){
            $('#' + item_ant).toggleClass('table-seleccion');
        }
        // Guardo id de item seleccionado
        item = elementId;
    } else {
        // Reseteo el item
        item = null;
    }
}

// Llama a mostrarTodoAction en Controller
function mostrarTodo(id_persona){
    if (tipo_persona=="cliente"){
        $.post('/clientes/ajax/mostrarTodo/' + id_persona, function (data) {
        $('#eventos').html(data);
        });
    }
    else{
        $.post('/proveedores/ajax/mostrarTodo/' + id_persona, function (data) {
            $('#eventos').html(data);
            });
    }
   
}

// Llama a mostrarTransaccionesAction en Controller
function mostrarTransacciones(id_persona){
    if (tipo_persona=="cliente"){
        $.post('/clientes/ajax/mostrarTransacciones/' + id_persona, function (data) {
        $('#eventos').html(data);
        });
    }
    else{
        $.post('/proveedores/ajax/mostrarTransacciones/' + id_persona, function (data) {
            $('#eventos').html(data);
            });
    }
   
}

// Llama a mostrarAccionesComercialesAction en Controller
function mostrarAccionesComerciales(id_persona){
    if (tipo_persona=="cliente"){
        $.post('/clientes/ajax/mostrarAccionesComerciales/' + id_persona, function (data) {
        $('#eventos').html(data);
        });
    }else{
        $.post('/proveedores/ajax/mostrarAccionesComerciales/' + id_persona, function (data) {
            $('#eventos').html(data);
            });
    }
   
}

// Llama a mostrarAccionesComercialesAction en Controller
function mostrarCuentaCorriente(id_persona){
    if (tipo_persona=="cliente"){
        $.post('/clientes/ajax/mostrarCuentaCorriente/' + id_persona, function (data) {
        $('#eventos').html(data);
        });
    }else{
        $.post('/proveedores/ajax/mostrarCuentaCorriente/' + id_persona, function (data) {
            $('#eventos').html(data);
            });
    }
   
}

//Permite cambiar el estado de una transaccion
function cambiarEstado(estado, event, tipoTransaccion, idPersona){
    var idTransaccion = event.target.id;
    var edo = estado.replace("_", " ");
    switch (tipoTransaccion.toUpperCase()) {
        case "NOTA DE CREDITO":
            tipoTransaccion = "notaCredito";
            break;
    
        case "NOTA DE DEBITO":
            tipoTransaccion = "notaDebito";
            break;
        default:
            break;
    }
    if (confirm("Se cambiará el estado de la transacción a "+ edo +" ¿Desea continuar?")) {
        $.ajax({
            "dataType": "text",
            "type": "POST",
            "data": "temp",
            "url": '/'+tipoTransaccion+'/ajax/cambiarEstado/' + idTransaccion+ '/'+estado,
            "success": function (msg) {
                if (tipoTransaccion!="cobro"){
                    mostrarTransacciones(idPersona);
                }else{
                    document.location.reload();
                }
            },
            "error": function (msg) {
                console.log("1-error!");
            }
        }).done(function (msg) {
            console.log("1-done!");
        })
    }
    console.log("* * * * FIN -> CAMBIAR ESTADO * * * *");
}

// function cambiarEstado2(estado, event,tipoTransaccion,idPersona){
//     var idTransaccion = event.target.id;
//     var edo = estado.replace("_", " ");
//     if (confirm("Se cambiará el estado de la transacción a "+ edo +" ¿Desea continuar?")) {
//         $.post('/'+tipoTransaccion+'/ajax/cambiarEstado/' + idTransaccion+ '/'+estado);
//     }
//     mostrarTransacciones(idPersona);
// }

