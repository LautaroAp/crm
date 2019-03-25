var eventos = [];
// obtengo ID de eventos
function seleccionarFila(e) {
    elementId = e.target.id;
    $('#' + elementId).toggleClass('table-seleccion');
    if ($('#' + elementId).hasClass('table-seleccion')) {
        // Guardo id de evento
        eventos.push(elementId);
        console.log(eventos);
    } else {
        // Elimino id de evento
        eventos.splice($.inArray(elementId, eventos), 1);
        console.log(eventos);
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
    alert(document.body.getElementsByTagName("td")[0]);

    $('#' + elementId).toggleClass('table-seleccion');
    if ($('#' + elementId).hasClass('table-seleccion')) {
        item_ant=item;
        if (item_ant){
            $('#' + item_ant).toggleClass('table-seleccion');
        }
        // Guardo id de item seleccionado
        item = elementId;
        console.log(item);
    } else {
        // Reseteo el item
        item = null;
    }
}

function mostrarTransacciones(id_persona){
    $.post('/clientes/ajax/mostrarTransacciones/' + id_persona, function (data) {
        $('#eventos').html(data);
    });
}

function mostrarAccionesComerciales(id_persona){
    $.post('/clientes/ajax/mostrarAccionesComerciales/' + id_persona, function (data) {
        $('#eventos').html(data);
    });
}

// $(function(){
//     $(".click").dblclick(function (e){
//         e.preventDefault();
//         var data = $(this).attr("data-valor");
//         alert(data);
//     });
// });