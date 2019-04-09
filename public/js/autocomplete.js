
// // Autocompletado con "CODIGO" de Producto & Servicio
// function autocompleteCode(inp, arr) {
//     /*the autocomplete function takes two arguments,
//     the text field element and an array of possible autocompleted values:*/
//     var currentFocus;
//     /*execute a function when someone writes in the text field:*/
//     if(inp){
//     inp.addEventListener("input", function(e) {
//         var a, b, i, val = this.value;
//         if (!val) {
//             return false;
//         }
//         currentFocus = -1;                        
//         output = [];
//         /*for each item in the array...*/
//         for (i = 0; i < arr.length; i++) {
//             /*check if the item starts with the same letters as the text field value:*/
//             if (arr[i]["codigo"].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
//                 // agregar cada elemento a un nuevo JSON (output)
//                 var item_label = arr[i]["label"];
//                 var item_codigo = arr[i]["codigo"];
//                 output.push(arr[i]);
//                 // invierto codigo y label para la funcion de autocompletado (para que se muestre el codigo y no el nombre al escribir)
//                 output[(output.length)-1]["label"] = item_codigo;
//                 output[(output.length)-1]["codigo"] = item_label;
//             }
//         }
//     });
//     };
//     /*execute a function when someone clicks in the document:*/
//     document.addEventListener("click", function(e) {
//         $("#item_codigo").autocomplete({
//             source: function(request, response ) {
//                 data = output;
//                 response(data);
//             },
//             select: function (event, ui) {
//                 $('#item_id').val(ui.item.vale); // display the selected text
//                 $('#item_nombre').val(ui.item.codigo); // display the selected text
//                 $('#item_codigo').val(ui.item.label); // save selected id to input
//                 $('#item_descripcion').val(ui.item.descripcion); // save selected id to input
//                 return false;
//             }
//         });
//     });
// }
// autocompleteCode(document.getElementById("item_codigo"), json_items);


// // Autocompletado con "NOMBRE" de Producto & Servicio
// function autocompleteName(inp, arr) {
//     /*the autocomplete function takes two arguments,
//     the text field element and an array of possible autocompleted values:*/
//     var currentFocus;
//     /*execute a function when someone writes in the text field:*/
//     if(inp){
//     inp.addEventListener("input", function(e) {
//         var a, b, i, val = this.value;
//         if (!val) {
//             return false;
//         }
//         currentFocus = -1;
//         output = [];
//         /*for each item in the array...*/
//         for (i = 0; i < arr.length; i++) {
//             /*check if the item starts with the same letters as the text field value:*/
//             if (arr[i]["label"].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
//                 // agregar cada elemento a un nuevo JSON (output)
//                 output.push(arr[i]);
//             }
//         }
//     });
//     };
//     /*execute a function when someone clicks in the document:*/
//     document.addEventListener("click", function(e) {
//         $("#item_nombre").autocomplete({
//             source: function(request, response ) {
//                 data = output;
//                 response(data);
//             },
//             select: function (event, ui) {
//                 $('#item_id').val(ui.item.value); // display the selected text
//                 $('#item_nombre').val(ui.item.label); // display the selected text
//                 $('#item_codigo').val(ui.item.codigo); // save selected id to input
//                 $('#item_stock').val(ui.item.stock); // save selected id to input
//                 $('#item_descripcion').html(ui.item.descripcion);
//                 $('#item_categoria').html(ui.item.categoria);
//                 $('#item_precio').html(ui.item.precio);
//                 $('#item_descuento').html(ui.item.descuento);
//                 $('#item_descuento_precio').html(ui.item.descuento_precio);
//                 $('#item_iva').html(ui.item.iva);
//                 $('#item_iva_precio').html(ui.item.iva_precio);
//                 $('#item_subtotal').html(ui.item.subtotal);
//                 return false;
//             }
//         });
//     });
// }
// autocompleteName(document.getElementById("item_nombre"), json_items);

$(document).ready(function() {
    
    // Autocompletado para input "CODIGO"
    $('#item_codigo').autocomplete({
        source: json_items,
        
        select: function (event, ui) {
            $('#item_nombre').val(ui.item.label); // display the selected text
            $('#item_id').val(ui.item.value); // display the selected text
            
            $('#item_codigo').val(ui.item.codigo); // save selected id to input
            $('#item_stock').val(ui.item.stock); // save selected id to input
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
        source: json_items,
        select: function (event, ui) {
            $('#item_id').val(ui.item.value); // display the selected text
            $('#item_nombre').val(ui.item.label); // display the selected text
            $('#item_codigo').val(ui.item.codigo); // save selected id to input
            $('#item_stock').val(ui.item.stock); // save selected id to input
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
