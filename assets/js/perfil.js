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


function validarkeypress(er,e){
	key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if(!a){
		e.preventDefault();
    }
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
        validarCampo($("#nombre"), /^[a-zA-Z]{4,100}$/, $("#textonombre"), "Debe haber como mínimo 4 caracteres"),
        validarCampo($("#apellido"), /^[a-zA-Z]{4,100}$/, $("#textoapellido"), "Debe haber como mínimo 4 caracteres"),
        validarCampo($("#usuario"), /^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{6,16}$/, $("#textousuario"), "debe ser entre 6 y 16 caracteres, letras y números")
    ];

    return resultados.every(r => r === true);
}

 function validarFormulario2() {
    const resultados = [
        validarCampo($("#actual"), /^[a-z0-9]{8,16}$/, $("#textoactual"), "La contraseña debe tener entre 8 y 16 caracteres, minúsculas y números"),
        validarCampo($("#nueva"), /^[a-z0-9]{8,16}$/, $("#textonueva"), "La contraseña debe tener entre 8 y 16 caracteres, minúsculas y números"),
        validarCampo($("#confirmar"), /^[a-z0-9]{8,16}$/, $("#textoconfirmar"), "La contraseña debe tener entre 8 y 16 caracteres, minúsculas y números")
    ];

    return resultados.every(r => r === true);
}

$(document).ready(function() {

    $("#nombre").on("keypress", function(e) {
        // Permitir solo letras (mayúsculas y minúsculas) y la tecla de retroceso
        validarkeypress(/^[a-zA-Z\b]*$/, e);
    });

    $("#nombre").on("keyup", function() {
      validarCampo($(this),/^[a-zA-Z]{4,100}$/, 
      $("#textonombre"), "Debe haber como mínimo 4 caracteres");
    });

    $("#apellido").on("keypress", function(e) {
        // Permitir solo letras (mayúsculas y minúsculas) y la tecla de retroceso
        validarkeypress(/^[a-zA-Z\b]*$/, e);
    });
    
    $("#apellido").on("keyup", function() {
      validarCampo($(this),/^[a-zA-Z]{4,100}$/, 
      $("#textoapellido"), "Debe haber como mínimo 4 caracteres y solo letras");
    });

    $("#usuario").on("keypress", function(e) {
    // Permitir letras (a-z, A-Z), números (0-9) y la tecla de retroceso (backspace)
    validarkeypress(/^[a-zA-Z0-9-\b]*$/, e);
    });

    $("#usuario").on("keyup", function() {
      validarCampo($(this),/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{6,16}$/, 
      $("#textousuario"), "Debe ser entre 6 y 16 caracteres, letras y números");
    });

    $("#actual").on("keypress", function(e) {
      validarkeypress(/^[a-z0-9]+$/, e);
    });

    $("#actual").on("keyup", function() {
      validarCampo($(this),/^[a-z0-9]{8,16}$/, 
      $("#textoactual"), "La contraseña debe tener entre 8 y 16 caracteres, minúsculas y números");
    });

    $("#nueva").on("keypress", function(e) {
      validarkeypress(/^[a-z0-9]+$/, e);
    });

    $("#nueva").on("keyup", function() {
      validarCampo($(this),/^[a-z0-9]{8,16}$/, 
      $("#textonueva"), "La contraseña debe tener entre 8 y 16 caracteres, minúsculas y números");
    });


    $("#confirmar").on("keypress", function(e) {
      validarkeypress(/^[a-z0-9]+$/, e);
    });

    $("#confirmar").on("keyup", function() {
      validarCampo($(this),/^[a-z0-9]{8,16}$/, 
      $("#textoconfirmar"), "La contraseña debe tener entre 8 y 16 caracteres, minúsculas y números");
    });





    $('#actualizar').on("click", function () {
    Swal.fire({
      title: '¿Desea actualizar los datos?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#1d994dff',
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
        var datos = new FormData($('#forPerfil')[0]);
        datos.append('actualizar', 'actualizar');
        enviaAjax(datos);
      }
    });  
    });



      $('#clave').on("click", function () {
            Swal.fire({
              title: '¿Desea actualizar la clave?',
              text: '',
              icon: 'question',
              showCancelButton: true,
              color: "#00000",
              confirmButtonColor: '#1d994dff',
              cancelButtonColor: '#EF233C',
              confirmButtonText: ' SI ',
              cancelButtonText: 'NO'
            }).then((result) => {
              if (!validarFormulario2()) {
                muestraMensaje("error", 2000, "Campos inválidos O Vacios", "Por favor, corrige los errores antes de continuar.");
                return;
            }
            else if (result.isConfirmed) {
              activarLoaderBoton('#clave', "Guardando...");
                var datos = new FormData($('#forClave')[0]);
                datos.append('clave', 'clave');
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
  
          if (lee.accion == 'clave') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1500, "Se ha Actualizado con éxito", "");
                  desactivarLoaderBoton('#clave');
              setTimeout(function () {
                location = '?pagina=perfil';
              }, 2000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "ERROR");
                  desactivarLoaderBoton('#clave');
            }
          } else if (lee.accion == 'actualizar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1500, "Se ha Modificado con éxito", "Actualizado exitosamente");
                  desactivarLoaderBoton('#actualizar');
              setTimeout(function () {
                location = '?pagina=perfil';
              }, 2000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "Intente Nuevamente O Mas Tarde");
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