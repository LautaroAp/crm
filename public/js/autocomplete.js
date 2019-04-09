
function setJsonLabel(json_array, label_field){
    for (i = 0; i < json_array.length; i++) {
        json_array[i]["label"] = json_array[i][label_field]; 
    }
    return json_array;
};

$(document).ready(function() {
    
    // Autocompletado para input "CODIGO"
    $('#item_codigo').autocomplete({

        source: setJsonLabel(json_items_codigos, "codigo"),
        
        select: function (event, ui) {
            $('#item_id').val(ui.item.value); // save selected id to input
            $('#item_nombre').val(ui.item.nombre); // display the selected text
            $('#item_codigo').val(ui.item.codigo); // display the selected text
            $('#item_stock').val(ui.item.stock);
            $('#item_cantidad').val(1);
            $('#item_descripcion').html(ui.item.descripcion);
            $('#item_categoria').html(ui.item.categoria);
            $('#item_precio').html(ui.item.precio);
            $('#item_descuento').html(ui.item.descuento);
            $('#item_descuento_precio').html(ui.item.descuento_precio);
            $('#item_iva').html(ui.item.iva);
            $('#item_iva_precio').html(ui.item.iva_precio);
            $('#item_subtotal').html(ui.item.subtotal);

            return false;
        }
    });

    // Autocompletado para input "NOMBRE"
    $('#item_nombre').autocomplete({

        source: setJsonLabel(json_items_nombres, "nombre"),

        select: function (event, ui) {
            $('#item_id').val(ui.item.value); // save selected id to input
            $('#item_nombre').val(ui.item.nombre); // display the selected text
            $('#item_codigo').val(ui.item.codigo); //display the selected text
            $('#item_stock').val(ui.item.stock);
            $('#item_cantidad').val(1);
            $('#item_descripcion').html(ui.item.descripcion);
            $('#item_categoria').html(ui.item.categoria);
            $('#item_precio').html(ui.item.precio);
            $('#item_descuento').html(ui.item.descuento);
            $('#item_descuento_precio').html(ui.item.descuento_precio);
            $('#item_iva').html(ui.item.iva);
            $('#item_iva_precio').html(ui.item.iva_precio);
            $('#item_subtotal').html(ui.item.subtotal);
            return false;
        }
    })

});
