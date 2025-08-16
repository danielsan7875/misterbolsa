
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
   // VALIDACION DE DATOS  
   $("#nombre").on("keypress", function(e) {
    // Permitir solo letras (mayúsculas y minúsculas) y la tecla de retroceso
    validarkeypress(/^[a-zA-Z\b]*$/, e);
    });

    $("#nombre").on("keyup", function() {
    // Validar que el contenido tenga entre 4 y 100 caracteres y que contenga solo letras
    validarkeyup(/^[a-zA-Z]{4,100}$/, $(this),
     $("#textonombre"), "Debe haber como mínimo 4 caracteres y solo letras");
    });

    $("#apellido").on("keypress", function(e) {
    // Permitir solo letras (mayúsculas y minúsculas) y la tecla de retroceso
    validarkeypress(/^[a-zA-Z\b]*$/, e);
    });
    
    $("#apellido").on("keyup", function() {
    // Validar que el contenido tenga entre 4 y 100 caracteres y que contenga solo letras
    validarkeyup(/^[a-zA-Z]{4,100}$/, $(this),
    $("#textoapellido"), "Debe haber como mínimo 4 caracteres y solo letras");
    });

    $("#usuario").on("keypress", function(e) {
    // Permitir letras (a-z, A-Z), números (0-9) y la tecla de retroceso (backspace)
    validarkeypress(/^[a-zA-Z0-9-\b]*$/, e);
    });

    $("#usuario").on("keyup", function() {
    // Validar que el contenido tenga entre 6 y 16 caracteres y que contenga letras y números
    validarkeyup(/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{6,16}$/, $(this),
    $("#textousuario"), "El formato debe ser entre 6 y 16 caracteres, incluyendo letras y números");
    });

    $("#clave").on("keyup", function() {
        var password = $(this).val();
        // Convertir la contraseña a minúsculas
        password = password.toLowerCase();
        
        // Validar que la contraseña tenga entre 8 y 16 caracteres y solo contenga letras minúsculas y números
        if (password.length >= 8 && password.length <= 16 && /^[a-z0-9]+$/.test(password)) {
            $("#textoclave").text("");
        } else {
            $("#textoclave").text("La contraseña debe tener entre 8 y 16 caracteres, solo letras minúsculas y números");
        }
    });


    $('#registrar').on("click", function () {
        var datos = new FormData($('#u')[0]);
        datos.append('registrar', 'registrar');
        enviaAjax(datos);
      });
    

    
  });

  $('#actualizar').on("click", function () {
    Swal.fire({
      title: '¿Desea actualizar los datos?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#457B9D',
      cancelButtonColor: '#EF233C',
      confirmButtonText: ' SI ',
      cancelButtonText: 'NO'
    }).then((result) => {
      if (result.isConfirmed) {
        var datos = new FormData($('#m')[0]);
        datos.append('actualizar', 'actualizar');
        enviaAjax(datos);
      }
    });  
});


  function eliminar(boton){
    Swal.fire({
      title: '¿Desea eliminar los datos?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#457B9D',
      cancelButtonColor: '#EF233C',
      confirmButtonText: ' SI ',
      cancelButtonText: 'NO'
    }).then((result) => {
      if (result.isConfirmed) {
        var datos = new FormData();
        datos.append('eliminar', boton.value);
        enviaAjax(datos);
      }
    });
  }

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
  
          if (lee.accion == 'registrar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha registrado con éxito", "Su registro se ha completado exitosamente");
              setTimeout(function () {
                location = '?pagina=usuario';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "ERROR");
            }
          } else if (lee.accion == 'actualizar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
              setTimeout(function () {
                location = '?pagina=usuario';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "Intente Nuevamente O Mas Tarde");
            }
          } else if (lee.accion == 'eliminar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha eliminado con éxito", "Los datos se han borrado correctamente ");
              setTimeout(function () {
                location = '?pagina=usuario';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", lee.text);
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