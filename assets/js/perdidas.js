/*var deleteButtons = document.querySelectorAll('.eliminar');
// Recorre los botones de eliminar y añade un receptor de eventos a cada uno de ellos
deleteButtons.forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault(); // Evita que se envíe el formulario
        // Muestra la alerta de confirmación
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
                // Si se confirma, envía el formulario para eliminar el registro
                this.parentNode.submit();
            }
        });
    });
});*/

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
        validarCampo($("#cantidad"), /^[0-9]{1,4}$/, $("#textocantidad"), "Debe haber como mínimo 1 caracter"),
        validarCampo($("#nota"), /^[a-zA-Z0-9\-#\/\s]{4,100}$/, $("#textonota"), "Debe haber como mínimo 4 caracteres"),
        validarCampo($("#idproducto"), null, $("#textoproducto"), "Por favor, seleccione un producto válido.")
    ];

    return resultados.every(r => r === true);
}


$(document).ready(function() {
    // VALIDACION DE DATOS  
    $("#nota").on("keypress", function(e) {
     validarkeypress(/^[a-zA-Z0-9\-#\/\s\b]*$/, e);
    });
    
     $("#nota").on("keyup", function() {
      validarCampo($(this),/^[a-zA-Z0-9\-#\/\s]{4,100}$/, 
      $("#textonota"), "Debe haber como mínimo 4 caracteres");
    });
    
    $("#cantidad").on("keypress", function(e) {

    validarkeypress(/^[0-9\b]*$/, e);
    });
        

     $("#cantidad").on("keyup", function() {
      validarCampo($(this),/^[0-9]{1,4}$/, 
      $("#textocantidad"), "Debe haber como mínimo 1 caracter");
    });

    $("#idproducto").on("change", function () {
       validarCampo($(this), null, $("#textoproducto"), "Por favor, seleccione un producto válido.");
    });

    var today = moment().format('YYYY-MM-DD'); // Formato de fecha para el input tipo date
    $('#fechaperdida').val(today);
    

    $('#exampleModal').on('shown.bs.modal', function () {
        $('#idproducto').select2({
            dropdownParent: $('#exampleModal'),
            width: '90%',
            language: {
                 noResults: function() {
                 return "No se encontraron resultados, intenta con otro término.";
                 } 
            }
        });
        $('#idproducto').on('select2:open', function() {
            $('input.select2-search__field').attr('placeholder', 'Buscador');
        });
    });

    $('#limpiardatos').on('click', function() {
        // Limpiar los inputs
        $('#idproducto').prop('selectedIndex', 0); 
        $('#cantidad').val(''); 
        $('#nota').val('');
    
        // Actualizar Select2
        $('#idproducto').val(null).trigger('change');
    });

    $('#registrar').on("click", function () {
      if (!validarFormulario()) {
        muestraMensaje("error", 2000, "Campos inválidos O Vacios", "Por favor, corrige los errores antes de continuar.");
        return;
    }
    Swal.fire({
      title: '¿Desea Registrar estos datos?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#1d994dff',
      cancelButtonColor: '#EF233C',
      confirmButtonText: ' SI ',
      cancelButtonText: 'NO'
    }).then((result) => {
       if (result.isConfirmed) {
       activarLoaderBoton('#registrar', "Guardando...");
        var datos = new FormData($('#u')[0]);
        datos.append('registrar', 'registrar');
        enviaAjax(datos);
      }
    });  
});

 });


 
function eliminar(boton){
    Swal.fire({
      title: '¿Desea eliminar los datos?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#1d994dff',
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
                    desactivarLoaderBoton('#registrar');
                    setTimeout(function () {
                        location = '?pagina=perdidas';
                    }, 1000);
                } else if (lee.respuesta == -1) {
                    muestraMensaje("error", 2000, "Cantidad no disponible", "La cantidad solicitada es mayor a la disponible.");
                      desactivarLoaderBoton('#registrar');
                  } else {
                    muestraMensaje("error", 2000, "ERROR", "Ocurrió un error inesperado.");
                      desactivarLoaderBoton('#registrar');
                  }
            } else if (lee.accion == 'eliminar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha eliminado con éxito", "Los datos se han borrado correctamente ");
              setTimeout(function () {
                location = '?pagina=perdidas';
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