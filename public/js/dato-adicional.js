var limpiar_datos;

$(document).ready(function() {

/* * * * * * * * * * * * * ADD / EDIT * * * * * * * * * * * */

    // Selecciona Tipo de Dato Adicional
    $("#tipo_dato_adicional").change(function () {
      var tipo = $("#tipo_dato_adicional option:selected").val();
      // Dato
      if(tipo == "Dato"){
        $("#tipo_dato").attr("hidden", false);
        $("#referencia_cliente").attr("hidden", true);
        $("#referencia_proveedor").attr("hidden", true);
      } 
      // Cliente
      else if(tipo == "Cliente"){
        $("#tipo_dato").attr("hidden", true);
        $("#referencia_cliente").attr("hidden", false);
        $("#referencia_proveedor").attr("hidden", true);
      } 
      // Proveedor
      else if(tipo == "Proveedor"){
        $("#tipo_dato").attr("hidden", true);
        $("#referencia_cliente").attr("hidden", true);
        $("#referencia_proveedor").attr("hidden", false);
      }
      // Limpiar campos
      limpiarDatosAdicionales();
      limpiar_datos = true;
    });

    // Autocompletado para "NRO CLIENTE"
    $('#nro_cliente').autocomplete({

      source: setJsonLabel(json_nro_clientes, "nro"),
      
      select: function (event, ui) {
          $('#id_referencia_persona').val(ui.item.value); // save selected id to input
          $('#nro_cliente').val(ui.item.value); // save selected id to input
          $('#nombre_cliente').val(ui.item.nombre); // display the selected text
          $('#dato_general').val(ui.item.nombre);
          return false;
      }
    });

    // Autocompletado para "NOMBRE CLIENTE"
    $('#nombre_cliente').autocomplete({

      source: json_clientes,
      
      select: function (event, ui) {
          $('#id_referencia_persona').val(ui.item.value); // save selected id to input
          $('#nro_cliente').val(ui.item.value); // save selected id to input
          $('#nombre_cliente').val(ui.item.nombre); // display the selected text
          $('#dato_general').val(ui.item.nombre);
          return false;
      }
    });

    // Autocompletado para "NRO PROVEEDOR"
    $('#nro_proveedor').autocomplete({

      source: setJsonLabel(json_nro_proveedores, "nro"),
      
      select: function (event, ui) {
          $('#id_referencia_persona').val(ui.item.value); // save selected id to input
          $('#nro_proveedor').val(ui.item.value); // save selected id to input
          $('#nombre_proveedor').val(ui.item.nombre); // display the selected text
          $('#dato_general').val(ui.item.nombre);
          return false;
      }
    });

    // Autocompletado para "NOMBRE PROVEEDOR"
    $('#nombre_proveedor').autocomplete({

      source: json_proveedores,
      
      select: function (event, ui) {
          $('#id_referencia_persona').val(ui.item.value); // save selected id to input
          $('#nro_proveedor').val(ui.item.value); // save selected id to input
          $('#nombre_proveedor').val(ui.item.nombre); // display the selected text
          $('#dato_general').val(ui.item.nombre);
          return false;
      }
    });

});

function setJsonLabel(json_array, label_field){
  for (i = 0; i < json_array.length; i++) {
      json_array[i]["label"] = json_array[i][label_field]; 
  }
  return json_array;
};

function limpiarDatosAdicionales(){
  if(limpiar_datos){
    $('#id_referencia_persona').val(null);
    $('#dato_general').val(null);
    $('#nombre_cliente').val(null);
    $('#nro_cliente').val(null);
    $('#nombre_proveedor').val(null);
    $('#nro_proveedor').val(null);
    $('#descripcion').val(null);
  }
}
