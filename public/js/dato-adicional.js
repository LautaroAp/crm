$(document).ready(function() {

/* * * * * * * * * * * * * ADD / EDIT * * * * * * * * * * * */

    // Selecciona Tipo de Dato Adicional
    $("#tipo_dato_adicional").change(function () {
      var tipo = $("#tipo_dato_adicional option:selected").val();
      // Dato
      if(tipo == "1"){
        $("#tipo_dato").attr("hidden", false);
        $("#referencia_cliente").attr("hidden", true);
        $("#referencia_proveedor").attr("hidden", true);
      } 
      // Cliente
      else if(tipo == "2"){
        $("#tipo_dato").attr("hidden", true);
        $("#referencia_cliente").attr("hidden", false);
        $("#referencia_proveedor").attr("hidden", true);
      } 
      // Proveedor
      else if(tipo == "3"){
        $("#tipo_dato").attr("hidden", true);
        $("#referencia_cliente").attr("hidden", true);
        $("#referencia_proveedor").attr("hidden", false);
      }
    });


    // Autocompletado para "NOMBRE CLIENTE"
    $('#nombre_cliente').autocomplete({

      source: json_clientes,
      
      select: function (event, ui) {
          $('#persona_id').val(ui.item.value); // save selected id to input
          $('#nro_cliente').val(ui.item.value); // save selected id to input
          $('#nombre_cliente').val(ui.item.label); // display the selected text
          return false;
      }
    });

    // Autocompletado para "NOMBRE PROVEEDOR"
    $('#nombre_proveedor').autocomplete({

      source: json_proveedores,
      
      select: function (event, ui) {
          $('#persona_id').val(ui.item.value); // save selected id to input
          $('#nro_proveedor').val(ui.item.value); // save selected id to input
          $('#nombre_proveedor').val(ui.item.label); // display the selected text
          return false;
      }
    });

});