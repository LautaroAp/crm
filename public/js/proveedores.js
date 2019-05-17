$(document).ready(function() {
  
  /* * * * * * * * * * * * * PLACE HOLDER EN SELECT * * * * * * * * * * * */
  
    jQuery(".dropdown").change(function () {
        jQuery(this).removeClass("place_holder");
    });

/* * * * * * * * * * * * * ELIMINAR EVENTO * * * * * * * * * * * */

  $("#eliminar_evento").click(function(){
    if(eventos.length == 0){
      // <p>
      $("#eliminar_evento_1").attr("hidden","");
      $("#eliminar_evento_0").removeAttr("hidden");
      // <buttons>
      $("#eliminar_evento_aceptar").addClass("hidden");
      $("#eliminar_evento_cerrar").html("Aceptar");
    } else {
      // <p>
      $("#eliminar_evento_1").removeAttr("hidden");
      $("#eliminar_evento_0").attr("hidden","");
      // <buttons>
      $("#eliminar_evento_aceptar").removeClass("hidden");
      $("#eliminar_evento_cerrar").html("Cancelar");
    }
    
  });
  
});
  

