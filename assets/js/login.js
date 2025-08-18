const passwordInput = document.getElementById('password');
const showPasswordButton = document.getElementById('show-password');

showPasswordButton.addEventListener('click', () => {
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    showPasswordButton.classList.remove('fa-eye');
    showPasswordButton.classList.add('fa-eye-slash');
  } else {
    passwordInput.type = 'password';
    showPasswordButton.classList.remove('fa-eye-slash');
    showPasswordButton.classList.add('fa-eye'); 
  }
});

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
    } else {
      campo.removeClass("is-invalidd").addClass("is-validd");
      textoError.text("");
    }
  } else {
   
    if (regex.test(valor)) {
      campo.removeClass("is-invalidd").addClass("is-validd");
      textoError.text("");
    } else {
      campo.removeClass("is-validd").addClass("is-invalidd");
      textoError.text(mensaje);
    }
  }
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


$(document).ready(function() {
  
      $("#usuario").on("keypress", function(e) {
        validarkeypress(/^[a-z0-9]$/, e);
      });
    
      $("#usuario").on("keyup", function () {
       validarCampo($(this),/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{6,16}$/,
      $("#textousuario"),"El formato debe ser entre 6 y 16 caracteres, incluyendo letras y números");
      });

      $("#password").on("keypress", function(e) {
        validarkeyup(/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z0-9]{8,16}$/, e);
      });
      
      $("#password").on("keyup", function() {
        validarCampo($(this),/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z0-9]{8,16}$/, 
        $("#textopassword"), "El formato debe ser entre 8 y 16 caracteres");
      });



    $('#ingresar').on("click", function(event) {
        event.preventDefault();
        if (validarFormularioLogin()) {
          activarLoaderBoton('#ingresar', "Iniciando...");
          const datos = new FormData($('#credenciales')[0]);
          datos.append('ingresar', 'ingresar');
          enviaAjax(datos);
        } else {
            muestraMensajetost("error","Validación fallida", "Corrige los campos antes de continuar.", "1500");
        }
    });
    
 

  });

function validarFormularioLogin() {
  let valido = true;

  // Validar usuario: letras + números, 6 a 16 caracteres
  const usuario = $("#usuario").val();
  const regexUsuario = /^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{6,16}$/;
  if (!regexUsuario.test(usuario)) {
    $("#usuario").removeClass("is-validd ").addClass("is-invalidd");
    $("#textousuario").text("Formato incorrecto. Debe tener entre 6 y 16 caracteres, incluyendo letras y números.");
    valido = false;
  } else {
    $("#usuario").removeClass("is-invalidd").addClass("is-validd ");
    $("#textousuario").text("");
  }

  // Validar password: letras + números, 8 a 16 caracteres
  const password = $("#password").val();
  const regexPassword = /^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z0-9]{8,16}$/;
  if (!regexPassword.test(password)) {
    $("#password").removeClass("is-validd").addClass("is-invalidd");
    $("#textopassword").text("Formato incorrecto. Debe tener entre 8 y 16 caracteres, incluyendo letras y números.");
    valido = false;
  } else {
    $("#password").removeClass("is-invalidd").addClass("is-validd");
    $("#textopassword").text("");
  }

  return valido;
}

function muestraMensajetost(icono, titulo, mensaje, tiempo) {
  Swal.fire({
    icon: icono,
    title: titulo,
    text: mensaje,
    toast: true,
    position: "top",
    showConfirmButton: false,
    timer: tiempo,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
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
  
          if (lee.accion == 'ingresar') {
            if (lee.respuesta == 1) {
            
              desactivarLoaderBoton('#ingresar'); 
              setTimeout(function () {
                location = '?pagina=home';
              }, 1000);
            } if (lee.respuesta == 2) {
              muestraMensajetost("success","Inicio de Session", "Exitosamente | Dolar Actualizado", "1000");
              desactivarLoaderBoton('#ingresar'); 
              setTimeout(function () {
                location = '?pagina=home';
              }, 1000);
            }else {
              desactivarLoaderBoton('#ingresar');
              muestraMensaje("error", 2000, lee.text, "");
             
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


  async function obtenerTasaDolarApi() {
    try {
        const respuesta = await fetch('https://ve.dolarapi.com/v1/dolares/oficial');
        if (!respuesta.ok) {
            throw new Error(`Error HTTP: ${respuesta.status}`);
        }

        const datos = await respuesta.json();
        const tasaBCV = datos.promedio.toFixed(2); // Redondea la tasa a 2 decimales

        document.getElementById("bcvfor").value = tasaBCV;
    } catch (error) {
        document.getElementById("bcvfor").value = "0.00";
    }
}

document.addEventListener("DOMContentLoaded", obtenerTasaDolarApi);


  