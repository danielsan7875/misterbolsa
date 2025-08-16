// Seleccionar todos los botones de eliminar con la clase "eliminar"
var deleteButtons = document.querySelectorAll('.eliminar');
// Recorre los botones de eliminar y añade un receptor de eventos a cada uno de ellos
deleteButtons.forEach(function (button) {
  button.addEventListener('click', function (e) {
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
        var form = this.closest('form');
        var datos = new FormData(form);
        enviaAjax(datos);
      }
    });
  });
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

    // Obtener el valor del dólar y convertirlo a número
var dolar = parseFloat($("#dolar").val());

// Iterar sobre cada fila de la tabla
$(".table tbody tr").each(function() {
    // Obtener el precio en dólares y convertirlo a número desde el data attribute
    var precioDolar = parseFloat($(this).data('precio-unitario'));
    var precioDolarM = parseFloat($(this).data('precio-mayor'));

    // Realizar el cálculo y actualizar el precio en bolívares con formato
    var precioBs = dolar * precioDolar;
    var precioBsM = dolar * precioDolarM;

    $(this).find("#precioBs").text("Bs " + precioBs.toFixed(2));
    $(this).find("#precioBss").text("Bs " + precioBsM.toFixed(2));
});

var alertMessages = [];

// Iterar sobre cada fila de la tabla para verificar el stock
$(".table tbody tr").each(function() {
    var stockActual = parseInt($(this).find("td:nth-child(7)").text());
    var stockMinimo = parseInt($(this).data('stock-minimo')); // Obtener el stock mínimo desde el data attribute
    var productoNombre = $(this).find("td:nth-child(2)").text(); // Obtener el nombre del producto
    var productoId = $(this).find("td:nth-child(1)").text(); // Obtener el ID del producto

    if (stockActual < stockMinimo) {
        $(this).find('td:eq(6)').append(' <i class="fas fa-exclamation-triangle" style="color:rgb(230, 38, 54);"></i>');
        // Agregar mensaje a la lista de alertas
        alertMessages.push(" • #: " + productoId + " - " + productoNombre);
    }
});

// Si hay mensajes de alerta, mostrarlos en el div
if (alertMessages.length > 0) {
    $("#alerta").append('<div class="alert alert-warning" role="alert"><b><i class="fas fa-exclamation-triangle" style="color:rgb(12, 10, 10);"></i> ¡Alerta de Stock!</b> Se ha detectado un nivel de inventario bajo en los siguientes productos:<br>' + alertMessages.join('<br>') + '</div>');
}

    
        // VALIDACION DE DATOS  
        $("#nombre").on("keypress", function(e) {
        // Permitir letras (mayúsculas y minúsculas), números, espacios y los caracteres especiales - # /
        validarkeypress(/^[a-zA-Z0-9\-#\/\s\b]*$/, e);
        });

        $("#nombre").on("keyup", function() {
        // Validar que el contenido tenga entre 4 y 100 caracteres y que contenga solo letras, números, espacios y los caracteres especiales - # /
        validarkeyup(/^[a-zA-Z0-9\-#\/\s]{4,100}$/, $(this),
         $("#textonombre"), "Debe haber como mínimo 4 caracteres"); 
        });

        $("#precio").on("keypress", function(e) {
            // Permitir solo números y la tecla de retroceso
            validarkeypress(/^[0-9\.\b]*$/, e);
        });
        
        $("#precio").on("keyup", function() {
            // Validar que el contenido tenga entre 4 y 100 caracteres y que contenga solo números
            validarkeyup(/^[0-9\.]{4,7}$/, $(this),
             $("#textoprecio"), "Debe haber como mínimo 0.01");
        });

    

        $("#stock").on("keypress", function(e) {
            // Permitir solo números y la tecla de retroceso
            validarkeypress(/^[0-9\b]*$/, e);
        });
        
        $("#stock").on("keyup", function() {
            // Validar que el contenido tenga entre 4 y 100 caracteres y que contenga solo números
            validarkeyup(/^[0-9]{1,4}$/, $(this),
             $("#textostock"), "Debe haber como mínimo 1 caracter");
        });

        $("#inputtasadolar").on("keypress", function(e) {
            // Permitir solo números y la tecla de retroceso
            validarkeypress(/^[0-9\.\b]*$/, e);
        });
        
        $("#inputtasadolar").on("keyup", function() {
            // Validar que el contenido tenga entre 4 y 100 caracteres y que contenga solo números
            validarkeyup(/^[0-9\.]{4,7}$/, $(this),
             $("#textotasadolar"), "Debe haber como mínimo 0.01");
        });

        $('#registrar').on("click", function () {
            var datos = new FormData($('#u')[0]);
            datos.append('registrar', 'registrar');
            enviaAjax(datos);
        });
        
        $('#tasadolar').on("click", function () {
            var datos = new FormData($('#t')[0]);
            datos.append('tasadolar', 'tasadolar');
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
                location = '?pagina=producto';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "ERROR");
            }
          } else if (lee.accion == 'actualizar') { // falta
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
              setTimeout(function () {
                location = '?pagina=producto';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "Intente Nuevamente O Mas Tarde");
            }
          }else if (lee.accion == 'tasadolar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Tasa Actualiza", "Se ha modificado la tasa correctamente");
              setTimeout(function () {
                location = '?pagina=producto';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "Intente Nuevamente O Mas Tarde");
            }
          } else if (lee.accion == 'eliminar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha eliminado con éxito", "Los datos se han borrado correctamente ");
              setTimeout(function () {
                location = '?pagina=producto';
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