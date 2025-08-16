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

$(document).ready(function() {
    // VALIDACION DE DATOS  
    $("#nota").on("keypress", function(e) {
    // Permitir letras (mayúsculas y minúsculas), números, espacios y los caracteres especiales - # /
     validarkeypress(/^[a-zA-Z0-9\-#\/\s\b]*$/, e);
    });
    
    $("#nota").on("keyup", function() {
    // Validar que el contenido tenga entre 4 y 100 caracteres y que contenga solo letras, números, espacios y los caracteres especiales - # /
     validarkeyup(/^[a-zA-Z0-9\-#\/\s]{4,100}$/, $(this),
     $("#textonota"), "Debe haber como mínimo 4 caracteres"); 
    });  
    
    $("#cantidad").on("keypress", function(e) {
    // Permitir solo números y la tecla de retroceso
    validarkeypress(/^[0-9\b]*$/, e);
    });
            
    $("#cantidad").on("keyup", function() {
     // Validar que el contenido tenga entre 4 y 100 caracteres y que contenga solo números
    validarkeyup(/^[0-9]{1,4}$/, $(this),
     $("#textocantidad"), "Debe haber como mínimo 1 caracter");
    });

    var today = moment().format('YYYY-MM-DD'); // Formato de fecha para el input tipo date
    $('#fechaperdida').val(today);
    

    $('#exampleModal').on('shown.bs.modal', function () {
        $('#idproducto').select2({
            dropdownParent: $('#exampleModal'),
            width: '93%',
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
        var datos = new FormData($('#u')[0]);
        datos.append('registrar', 'registrar');
        enviaAjax(datos);
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
                        location = '?pagina=perdidas';
                    }, 1000);
                } else if (lee.respuesta == -1) {
                    muestraMensaje("error", 2000, "Cantidad no disponible", "La cantidad solicitada es mayor a la disponible.");
                } else {
                    muestraMensaje("error", 2000, "ERROR", "Ocurrió un error inesperado.");
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