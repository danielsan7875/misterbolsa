
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
   // VALIDACION DE DATOS  
    consulta();
  $("#nombre_prese").on("keypress", function(e) {
    // Permitir letras (mayúsculas y minúsculas), números, espacios y los caracteres especiales - # /
    validarkeypress(/^[a-zA-Z0-9\-.#\/\s\b]*$/, e);
    });

    $("#nombre_prese").on("keyup", function() {
    // Validar que el contenido tenga entre 4 y 100 caracteres y que contenga solo letras, números, espacios y los caracteres especiales - # /
    validarkeyup(/^[a-zA-Z0-9\-.#\/\s]{4,100}$/, $(this),
     $("#textopresentacion"), "Debe haber como mínimo 4 caracteres"); 
    });

    
  $('#registrar').on("click", function () {
         
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
    if (result.isConfirmed) {
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


  function crearConsulta(datos) {

    var tablaBody = $('#tablapresentacion');
    tablaBody.empty();
  
    datos.forEach(function (dato) {
        var estatus_texto = {
            1: 'Activo',
            2: 'No Activo',
            3: 'Inactivo'
          };
          var estatus_classes = {
            1: 'activo', // Activo
            2: 'no-activo', // No activo
            3: 'inactivo' // Inactivo
          };
      
  
      var fila = '<tr>' +
        '<td class="text-center">' + dato.id_presentacion + '</td>' +
        '<td>' + dato.nombre + '</td>' +
        '<td><span class="' + estatus_classes[dato.estatus] + '">' + estatus_texto[dato.estatus] + '</span></td>' +
        '<td>' +
        '<form method="POST" action="?pagina=presentacion">' +
            '<button name="modificar" class="btn btn-primary btn-sm modificar" value="' + dato.id_presentacion + '"><i class="fas fa-pencil-alt" title="Editar"></i> Modificar </button>' +
            '<button type="button" onclick="eliminar(this)" class="btn btn-danger btn-sm eliminar" value="' + dato.id_presentacion + '"><i class="fas fa-trash-alt" title="Eliminar"> </i> Eliminar </button>' +
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
              }
              else if (lee.accion == 'incluir') {
                if (lee.respuesta == 1) {
                     $('#nombre_prese').val('');  
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
                    location = '?pagina=presentacion';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, "ERROR", "ERROR");
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