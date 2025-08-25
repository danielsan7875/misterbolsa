// Seleccionar todos los botones de eliminar con la clase "eliminar"
var deleteButtons = document.querySelectorAll('.eliminar');

deleteButtons.forEach(function (button) {
  button.addEventListener('click', function (e) {
    e.preventDefault();

    // Obtener el formulario y la fila (tr) correspondiente
    var form = this.closest('form');
    var row = this.closest('tr');

    // Extraer el ID y el nombre del producto desde las celdas
    var idProducto = row.querySelector('td:nth-child(1)').textContent.trim();
    var nombreProducto = row.querySelector('td:nth-child(2)').textContent.trim();

    // Mostrar la alerta con los datos del producto
    Swal.fire({
      title: '¿Desea eliminar este producto?',
      html: `<strong>ID:</strong> ${idProducto}<br><strong>Nombre:</strong> ${nombreProducto}`,
      icon: 'question',
      showCancelButton: true,
      color: "#000000",
      confirmButtonColor: '#1d994d',
      cancelButtonColor: '#EF233C',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        var datos = new FormData(form);
        enviaAjax(datos);
      }
    });
  });
});



function formatearDecimalAutomatico(input) {
    input.on("keyup", function () {
        let valor = $(this).val().replace(/\D/g, ""); // Elimina todo lo que no sea dígito

        if (valor.length === 0) {
            $(this).val("");
            return;
        }

        // Asegura al menos dos dígitos para los decimales
        while (valor.length < 3) {
            valor = "0" + valor;
        }

        const parteEntera = valor.slice(0, valor.length - 2);
        const parteDecimal = valor.slice(-2);

        const resultado = `${parseInt(parteEntera)}.${parteDecimal}`;
        $(this).val(resultado);
    });
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

// ✅ Actualizar precios en el modal
$(".precio-bs-detal").each(function() {
    var precio = parseFloat($(this).data("precio"));
    if (!isNaN(precio)) {
        var precioBs = dolar * precio;
        $(this).text("" + precioBs.toFixed(2));
    }
});

$(".precio-bs-mayor").each(function() {
    var precio = parseFloat($(this).data("precio"));
    if (!isNaN(precio)) {
        var precioBsM = dolar * precio;
        $(this).text("" + precioBsM.toFixed(2));
    }
});


var alertMessages = [];

// Iterar sobre cada fila de la tabla para verificar el stock
$(".table tbody tr").each(function() {
    var stockActual = parseInt($(this).find("td:nth-child(6)").text());
    var stockMinimo = parseInt($(this).data('stock-minimo'));
    var productoNombre = $(this).find("td:nth-child(2)").text();
    var productoId = $(this).find("td:nth-child(1)").text();

    if (stockActual < stockMinimo) {
        $(this).find('td:eq(5)').append(' <i class="fas fa-exclamation-triangle" style="color:rgb(230, 38, 54);"></i>');
        alertMessages.push(`
            <tr>
                <td class="text-center text-dark fw-bold">#${productoId}</td>
                <td class="text-dark">${productoNombre}</td>
            </tr>
        `);
    }
});

if (alertMessages.length > 0) {
    $("#alerta").html(`
        <div class="alert alert-warning d-flex justify-content-between align-items-center" role="alert">
            <div class="text-dark">
                <i class="fas fa-exclamation-triangle me-2" style="color:#ff0015ff;"></i>
                <strong>¡Alerta de Stock!</strong> Algunos productos tienen inventario bajo. (Haz clic en "ver detalles")
            </div>
            <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#stockModal">
                Ver detalles
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="stockModalLabel"><i class="fas fa-box-open me-2"></i>Productos con bajo stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                <p>Se han detectado los siguientes productos con niveles de inventario por debajo del mínimo:</p>
                <div class="table-responsive">
                  <table class="table table-bordered table-sm table-hover">
                    <thead class="table-dark">
                      <tr>
                        <th class="text-center">#</th>
                        <th>Nombre del producto</th>
                      </tr>
                    </thead>
                    <tbody>
                      ${alertMessages.join('')}
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
    `);
}


              // Aplica a ambos campos
        formatearDecimalAutomatico($("#precio"));
        formatearDecimalAutomatico($("#preciomayor"));

        // VALIDACION DE DATOS  
        $("#nombre").on("keypress", function(e) {
         validarkeypress(/^[a-zA-Z0-9\-#\/\s\b]*$/, e);
        });

         $("#nombre").on("keyup", function() {
            validarCampo($(this),/^[a-zA-Z0-9\-#\/\s]{4,100}$/, 
            $("#textonombre"), "Debe haber como mínimo 4 caracteres");
         });

        $("#precio").on("keypress", function(e) {
            validarkeypress(/^[0-9\.\b]*$/, e);
        });
      
         $("#precio").on("keyup", function() {
            validarCampo($(this),/^[0-9\.]{4,7}$/, 
            $("#textoprecio"), "Debe haber como mínimo 0.01");
         });

        $("#stock").on("keypress", function(e) {
            validarkeypress(/^[0-9\b]*$/, e);
        });

         $("#stock").on("keyup", function() {
            validarCampo($(this),/^[0-9]{1,4}$/, 
            $("#textostock"), "Debe haber como mínimo 1 caracter");
         });

         $("#preciomayor").on("keypress", function(e) {
            validarkeypress(/^[0-9\.\b]*$/, e);
        });
      
         $("#preciomayor").on("keyup", function() {
            validarCampo($(this),/^[0-9\.]{4,7}$/, 
            $("#textopreciomayor"), "Debe haber como mínimo 0.01");
         });

         $("#cantidadmayor").on("keypress", function(e) {
            validarkeypress(/^[0-9\b]*$/, e);
        });

         $("#cantidadmayor").on("keyup", function() {
            validarCampo($(this),/^[0-9]{1,4}$/, 
            $("#textocantidadmayor"), "Debe haber como mínimo 1 caracter");
         });

        $("#marcaselect").on("change", function () {
            validarCampo($(this), null, $("#textomarca"), "Por favor, seleccione una marca válida.");
        });

          $("#presenselect").on("change", function () {
          validarCampo($(this), null, $("#textopresentacion"), "Por favor, seleccione una presentanción válida.");
        });
        
        $("#preciomayor").on("keyup", function () {
          const precio = parseFloat($("#precio").val());
          const preciomayor = parseFloat($(this).val());

          validarCampo($(this), /^[0-9\.]{4,7}$/, $("#textopreciomayor"), "Debe haber como mínimo 0.01");

          if (!isNaN(precio) && !isNaN(preciomayor)) {
              if (preciomayor > precio) {
                  $(this).val(""); // Borra el valor
                  $("#textopreciomayor").text("El precio mayor no puede ser mayor al precio detal.");
                  $(this).removeClass("is-validd").addClass("is-invalidd");
              }
          }
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

function validarFormulario() {
    const resultados = [
        validarCampo($("#nombre"), /^[a-zA-Z0-9\-#\/\s]{4,100}$/, $("#textonombre"), "Debe haber como mínimo 4 caracteres"),
        validarCampo($("#precio"), /^[0-9\.]{4,7}$/, $("#textoprecio"), "Debe haber como mínimo 0.01"),
        validarCampo($("#stock"), /^[0-9]{1,4}$/, $("#textostock"), "Debe haber como mínimo 1 caracter"),
        validarCampo($("#preciomayor"), /^[0-9\.]{4,7}$/, $("#textopreciomayor"), "Debe haber como mínimo 0.01"),
        validarCampo($("#cantidadmayor"), /^[0-9]{1,4}$/, $("#textocantidadmayor"), "Debe haber como mínimo 1 caracter"),
        validarCampo($("#marcaselect"), null, $("#textomarca"), "Por favor, seleccione un rol válido."),
        validarCampo($("#presenselect"), null, $("#textopresentacion"), "Por favor, seleccione un rol válido.")
    ];

    return resultados.every(r => r === true);
}


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
              desactivarLoaderBoton('#registrar'); 
              setTimeout(function () {
                location = '?pagina=producto';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "ERROR");
              desactivarLoaderBoton('#registrar'); 
            }
          } else if (lee.accion == 'actualizar') { // falta
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
              desactivarLoaderBoton('#actualizar');
              setTimeout(function () {
                location = '?pagina=producto';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "Intente Nuevamente O Mas Tarde");
              desactivarLoaderBoton('#actualizar');
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