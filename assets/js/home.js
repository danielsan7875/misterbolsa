 // Mostrar el modal al cargar la página
 window.onload = function() {
    var myModal = new bootstrap.Modal(document.getElementById('miModal'));
    myModal.show();
};


//Función para validar por Keypress
function validarkeypress(er,e){
	key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if(!a){
		e.preventDefault();
    }
}
//Función para validar por keyup
function validarkeyup(er,etiqueta,etiquetamensaje,
mensaje){
	a = er.test(etiqueta.val());
	if(a){
		etiquetamensaje.text("");
		return 1;
	}
	else{
		etiquetamensaje.text(mensaje);
		return 0;
	}
}


$(document).ready(function() {

    $("#inputtasadolar").on("keypress", function(e) {
    // Permitir solo números y la tecla de retroceso
    validarkeypress(/^[0-9\.\b]*$/, e);
    });
        
    $("#inputtasadolar").on("keyup", function() {
    // Validar que el contenido tenga entre 4 y 100 caracteres y que contenga solo números
    validarkeyup(/^[0-9\.]{4,7}$/, $(this),
    $("#textotasadolar"), "Debe haber como mínimo 0.01");
    });

    $('#tasadolarre').on("click", function () {
        var datos = new FormData($('#t')[0]);
        datos.append('tasadolar', 'tasadolar');
        enviaAjax(datos);
    }); // envio del formulario para la tasa
});



function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
      icon: icono,
      timer: tiempo,
      title: titulo,
      html: mensaje,
      showConfirmButton: false,
    });
  }
  
  function enviaAjax(datos) {
    $.ajax({
      async: true,
      url: "",
      type: "POST",
      contentType: false,
      data: datos,
      processData: false,
      cache: false,
      beforeSend: function () { },
      timeout: 10000,
      success: function (respuesta) {
        console.log(respuesta);
        var lee = JSON.parse(respuesta);  
        try {
  
          if (lee.accion == 'tasadolar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Tasa Actualiza", "Se ha modificado la tasa correctamente");
              setTimeout(function () {
                location = '?pagina=home';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "Intente Nuevamente O Mas Tarde");
            }
          }
        } catch (e) {
          alert("Error en JSON " + e.name);
        }
      },
      error: function (request, status, err) {
        Swal.close();
        if (status == "timeout") {
          muestraMensaje("error", 2000, "Error", "Servidor ocupado, intente de nuevo");
        } else {
          muestraMensaje("error", 2000, "Error", "ERROR: <br/>" + request + status + err);
        }
      },
      complete: function () {
      }
    });
  }  