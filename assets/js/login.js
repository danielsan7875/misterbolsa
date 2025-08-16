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
    $("#usuario").on("keypress", function(e) {
      // Permitir letras (a-z, A-Z), números (0-9) y la tecla de retroceso (backspace)
      validarkeypress(/^[a-zA-Z0-9-\b]*$/, e);
    });
  
    $("#usuario").on("keyup", function() {
      // Validar que el contenido tenga entre 6 y 16 caracteres y que contenga letras y números
      validarkeyup(/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{6,16}$/, $(this),
      $("#textousuario"), "El formato debe ser entre 6 y 16 caracteres, incluyendo letras y números");
    });
  
    $("#password").on("keyup", function() {
      var password = $(this).val();
      // Convertir la contraseña a minúsculas
      password = password.toLowerCase();
      
      // Validar que la contraseña tenga entre 8 y 16 caracteres y solo contenga letras minúsculas y números
      if (password.length >= 8 && password.length <= 16 && /^[a-z0-9]+$/.test(password)) {
          $("#textopassword").text("");
      } else {
          $("#textopassword").text("La contraseña debe tener entre 8 y 16 caracteres, solo letras minúsculas y números");
      }
    });
  
    // Agregamos un evento de submit al formulario
    $("form").on("submit", function(event) {
      // Verificamos si el usuario y la contraseña cumplen con los formatos de validación
      if (!validarUsuario() || !validarPassword()) {
        event.preventDefault(); // Si no cumplen, prevenimos que se envíe el formulario
      }
    });
    
    // Función para validar el usuario
    function validarUsuario() {
      var usuario = $("#usuario").val();
      // Validar que el usuario tenga entre 6 y 16 caracteres y contenga letras y números
      var regex = /^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{6,16}$/;
      if (regex.test(usuario)) {
        return true;
      } else {
        $("#textousuario").text("El formato debe ser entre 6 y 16 caracteres, incluyendo letras y números");
        return false;
      }
    }
    
    // Función para validar la contraseña
    function validarPassword() {
      var password = $("#password").val();
      // Validar que la contraseña tenga entre 8 y 16 caracteres y solo contenga letras minúsculas y números
      var regex = /^[a-z0-9]{8,16}$/;
      if (regex.test(password)) {
        return true;
      } else {
        $("#textopassword").text("La contraseña debe tener entre 8 y 16 caracteres, solo letras minúsculas y números");
        return false;
      }
    }
  });


  