

function eliminar(boton){
    Swal.fire({
      title: '¿Desea eliminar los datos?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#1a7538',
      cancelButtonColor: '#EF233C',
      confirmButtonText: ' SI, ELIMINAR ',
      cancelButtonText: 'NO'
    }).then((result) => {
      if (result.isConfirmed) {
        var datos = new FormData();
        datos.append('eliminar', boton.value);
        enviaAjax(datos);
      }
    });
  }

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


function activarLoaderBoton(idBoton, texto) {
    const $boton = $(idBoton);
    const textoActual = $boton.html();
    $boton.data('texto-original', textoActual); // Guarda el texto original
    $boton.prop('disabled', true);
    $boton.html(`<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${texto}`);
}

function desactivarLoaderBoton(idBoton) {
    const $boton = $(idBoton);
    const textoOriginal = $boton.data('texto-original');
    $boton.prop('disabled', false);
    $boton.html(textoOriginal);
}

function validarCampo(campo, regex, textoError, mensaje) {
  const valor = campo.val();

  if (campo.is("select")) {
    if (valor === "") {
      campo.removeClass("is-validd").addClass("is-invalidd");
      textoError.text(mensaje);
      return false;
    } else {
      campo.removeClass("is-invalidd").addClass("is-validd");
      textoError.text("");
      return true;
    }
  } else {
    if (regex.test(valor)) {
      campo.removeClass("is-invalidd").addClass("is-validd");
      textoError.text("");
      return true;
    } else {
      campo.removeClass("is-validd").addClass("is-invalidd");
      textoError.text(mensaje);
      return false;
    }
  }
}

function validarFormulario() {
    const resultados = [
        validarCampo($("#nombre_marca"), /^(?=.*[a-zA-Z]|(?=.*[0-9]))[a-zA-Z0-9\s]{4,100}$/, $("#textomarca"), "Debe haber como mínimo de 4 caracteres"),
    ];

    return resultados.every(r => r === true);
}  

$(document).ready(function() {
   // VALIDACION DE DATOS  
  $("#nombre_marca").on("keypress", function(e) {
    validarkeypress(/^[a-zA-Z0-9-\s\b]*$/, e);
  });
  
   $("#nombre_marca").on("keyup", function() {
      validarCampo($(this),/^(?=.*[a-zA-Z]|(?=.*[0-9]))[a-zA-Z0-9\s]{4,100}$/, 
         $("#textomarca"), "Debe haber como mínimo de 4 caracteres");
   });

  $('#registrar').on("click", function () {
          if (!validarFormulario()) {
                muestraMensaje("error", 2000, "Campos inválidos O Vacios", "Por favor, corrige los errores antes de continuar.");
              return;
          }

          activarLoaderBoton('#registrar', "Guardando...");
          var datos = new FormData($('#u')[0]);
          datos.append('registrar', 'registrar');
          enviaAjax(datos);
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
      if (!validarFormulario()) {
        muestraMensaje("error", 2000, "Campos inválidos O Vacios", "Por favor, corrige los errores antes de continuar.");
        return;
    }
    else if (result.isConfirmed) {
      activarLoaderBoton('#actualizar', "Guardando...");
        var datos = new FormData($('#m')[0]);
        datos.append('actualizar', 'actualizar');
        enviaAjax(datos);
      }
    });      
 
 
  });

    
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
  
            if (lee.accion == 'incluir') {
                if (lee.respuesta == 1) { 
                  muestraMensaje("success", 1000, "Se ha registrado con éxito", "Su registro se ha completado exitosamente");
                   desactivarLoaderBoton('#registrar');
                    setTimeout(function () {
                      location = '?pagina=marca';
                    }, 1000);
                } else {
                  muestraMensaje("error", 1000, "ERROR", "ERROR");
                  desactivarLoaderBoton('#registrar');
                }
              } else if (lee.accion == 'actualizar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1000, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
                  desactivarLoaderBoton('#actualizar');
                  setTimeout(function () {
                    location = '?pagina=marca';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, "ERROR", "ERROR");
                  desactivarLoaderBoton('#actualizar');
                }
              } else if (lee.accion == 'eliminar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1000, "Se ha eliminado con éxito", "Los datos se han borrado correctamente ");
                   setTimeout(function () {
                    location = '?pagina=marca';
                  }, 1000);
                }else {
                  muestraMensaje("error", 2000, "ERROR", lee.text);
                  desactivarLoaderBoton('#actualizar');
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
  