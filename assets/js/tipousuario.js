function eliminar(boton){
    Swal.fire({
      title: '¿Desea eliminar los datos?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#1d994dff',
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
        validarCampo($("#nombre"), /^[a-zA-Z]{4,100}$/, $("#textonombre"), "Debe haber como mínimo 4 caracteres"),
        validarCampo($("#selectnivel"), null, $("#textonivel"), "Por favor, seleccione un nivel válido.")
    ];

    return resultados.every(r => r === true);
}


function consulta() {
    var datos = new FormData();
    datos.append('consultar', 'consultar');
    enviaAjax(datos);
  }

$(document).ready(function() {
    consulta();

   // VALIDACION DE DATOS  
   $("#nombre").on("keypress", function(e) {
    validarkeypress(/^[a-zA-Z\b]*$/, e);
    });

    $("#nombre").on("keyup", function() {
      validarCampo($(this),/^[a-zA-Z]{4,100}$/, 
      $("#textonombre"), "Debe haber como mínimo 4 caracteres");
    });

    $("#selectnivel").on("change", function () {
       validarCampo($(this), null, $("#textonivel"), "Por favor, seleccione un nivel válido.");
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
      if (result.isConfirmed) {
        var idUsuario = $('#id_tipo').val();
        if (idUsuario == 1) {
          Swal.fire({
            title: 'No puede editar',
            text: 'El Tipo de usuario con ID 1 no se puede editar.',
            icon: 'error',
            confirmButtonColor: '#457B9D',
            confirmButtonText: 'Aceptar'
          });

        } else  if (!validarFormulario()) {
          muestraMensaje("error", 2000, "Campos inválidos O Vacios", "Por favor, corrige los errores antes de continuar.");
          return;
       
        } else {
           activarLoaderBoton('#actualizar', "Guardando...");
             var datos = new FormData($('#m')[0]);
             datos.append('actualizar', 'actualizar');
             enviaAjax(datos);
        }
      }
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

  function crearConsulta(datos) {
    
    var tablaBody = $('#tablatipousuario');
    tablaBody.empty();
  
    datos.forEach(function (dato) {
        var fila = '<tr>' +
            '<td class="text-center td-oscuro">' + dato.id_tipo + '</td>' +
            '<td class="td-oscuro">' + dato.nombre + '</td>' +
            '<td class="td-oscuro">' + dato.nivel + '</td>' +
            '<td class="td-oscuro">' +
            '<form method="POST" action="?pagina=tipousuario">' +
            '<button name="modificar" class="btn btn-primary btn-sm modificar me-2" value="' + dato.id_tipo + '"><i class="fas fa-pencil-alt" title="Editar"></i> Modificar </button>' +
            '<button type="button" onclick="eliminar(this)" class="btn btn-danger btn-sm eliminar" value="' + dato.id_tipo + '"><i class="fas fa-trash-alt" title="Eliminar"> </i> Eliminar </button>' +
            '</form>' +
            '</td>' +
            '</tr>';
    
        tablaBody.append(fila);
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
  
            if (lee.accion == 'consultar') {
              crearConsulta(lee.datos);
            }else if (lee.accion == 'registrar') {
                if (lee.respuesta == 1) {
                     $('#selectnivel').prop('selectedIndex', 0); 
                     $('#nombre').val('');  
                      muestraMensaje("success", 1500, "Se ha registrado con éxito", "Tipo de usuario agregado");
                        desactivarLoaderBoton('#registrar');
                          setTimeout(function () {
                            consulta();
                          }, 2000);
                } else {
                  muestraMensaje("error", 4000, "Error", lee.text);
                    desactivarLoaderBoton('#registrar');
                }
            } else if (lee.accion == 'actualizar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1500, "Se ha Modificado con éxito", "Tipo de usuario modificado");
                       desactivarLoaderBoton('#actualizar');
                          setTimeout(function () {
                            location = '?pagina=tipousuario';
                          }, 2000);
                } else {
                  muestraMensaje("error", 4000, "Error", lee.text);
                   desactivarLoaderBoton('#actualizar');
                }
              } else if (lee.accion == 'eliminar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1500, "Se ha eliminado con éxito", "Tipo de usuario Borrado");
                  setTimeout(function () {
                    consulta();
                  }, 2000);
                } else {
                  muestraMensaje("error", 4000, "Error", lee.text);
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
  



