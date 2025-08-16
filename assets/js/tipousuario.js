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

function consulta() {
    var datos = new FormData();
    datos.append('consultar', 'consultar');
    enviaAjax(datos);
  }

$(document).ready(function() {
    consulta();

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
    
    $('#registrar').on("click", function () {
         
            var datos = new FormData($('#u')[0]);
            datos.append('registrar', 'registrar');
            enviaAjax(datos);
         
    });
 
  });

  $('#actualizar').on("click", function () {
    // Mostrar el mensaje de confirmación
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
        // Obtener el valor del input oculto
        var idUsuario = $('#id_tipo').val(); // Asegúrate de que el ID del input oculto sea correcto
  
        // Verificar si el id_usuario es igual a 1
        if (idUsuario == 1) {
          Swal.fire({
            title: 'No puede editar',
            text: 'El Tipo de usuario con ID 1 no se puede editar.',
            icon: 'error',
            confirmButtonColor: '#457B9D',
            confirmButtonText: 'Aceptar'
          });

        } else {
             var datos = new FormData($('#m')[0]);
             datos.append('actualizar', 'actualizar');
             enviaAjax(datos);
        }
      }
    });
  });

function validarenvio() {
	if (validarkeyup(/^[a-zA-Z\s]{1,50}$/,
		$("#nombre"), $("#textonombre"), "Debe haber como mínimo 4 caracteres y solo letras")) {
		muestraMensaje("error", 2000, "Error", "Tiene que ingresar un nombre valido");
		return false;
	}

	return true;
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

  function crearConsulta(datos) {
    
    var tablaBody = $('#tablatipousuario');
    tablaBody.empty();
  
    datos.forEach(function (dato) {
        var fila = '<tr>' +
            '<td class="text-center">' + dato.id_tipo + '</td>' +
            '<td>' + dato.nombre + '</td>' +
            '<td>' + dato.nivel + '</td>' +
            '<td>' +
            '<form method="POST" action="?pagina=tipousuario">' +
            '<button name="modificar" class="btn btn-primary btn-sm modificar" value="' + dato.id_tipo + '"><i class="fas fa-pencil-alt" title="Editar"></i> Modificar </button>' +
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
            }else if (lee.accion == 'incluir') {
                if (lee.respuesta == 1) {
                     $('#selectnivel').prop('selectedIndex', 0); 
                     $('#nombre').val('');  
                  muestraMensaje("success", 1000, "Se ha registrado con éxito", "Su registro se ha completado exitosamente");
                  setTimeout(function () {
                    consulta();
                  }, 1000);
                } else {
                  muestraMensaje("error", 1000, "ERROR", "ERROR");
                }
              } else if (lee.accion == 'actualizar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1000, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
                  setTimeout(function () {
                    location = '?pagina=tipousuario';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, "ERROR", lee.text);
                }
              } else if (lee.accion == 'eliminar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1000, "Se ha eliminado con éxito", "Los datos se han borrado correctamente");
                  setTimeout(function () {
                    consulta();
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
  



