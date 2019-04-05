
// Autocompletado con "NOMBRE" de Producto & Servicio
function autocompleteCode(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    if(inp){
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        if (!val) {
            return false;
        }
        currentFocus = -1;                        
        output = [];
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i]["value"].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                // agregar cada elemento a un nuevo JSON (output)
                // output.push(arr[i]);
                output.push({
                    id: arr[i]["value"],
                    value: arr[i]["label"],
                    label: arr[i]["value"],
                    descripcion: arr[i]["descripcion"]
                });
            }
        }
    });
    };
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function(e) {
        $("#item_codigo").autocomplete({
            source: function(request, response ) {
                data = output;
                response(data);
            },
            select: function (event, ui) {
                $('#item_id').val(ui.item.id); // display the selected text
                $('#item_nombre').val(ui.item.value); // display the selected text
                $('#item_codigo').val(ui.item.label); // save selected id to input
                $('#item_descripcion').val(ui.item.descripcion); // save selected id to input
                return false;
            }
        });
    });
}

autocompleteCode(document.getElementById("item_codigo"), json_items);

// Autocompletado con "CODIGO" de Producto & Servicio
function autocompleteName(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    if(inp){
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        if (!val) {
            return false;
        }
        currentFocus = -1;                        
        output = [];
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i]["label"].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                // agregar cada elemento a un nuevo JSON (output)
                output.push(arr[i]);
            }
        }
    });
    };
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function(e) {
        $("#item_nombre").autocomplete({
            source: function(request, response ) {
                data = output;
                response(data);
            },
            select: function (event, ui) {
                $('#item_id').val(ui.item.id); // display the selected text
                $('#item_nombre').val(ui.item.label); // display the selected text
                $('#item_codigo').val(ui.item.value); // save selected id to input
                $('#item_descripcion').val(ui.item.descripcion); // save selected id to input
                return false;
            }
        });
    });
}

autocompleteName(document.getElementById("item_nombre"), json_items);