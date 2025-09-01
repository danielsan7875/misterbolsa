

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

    $('#tasadolarre').on("click", function () {
        var datos = new FormData($('#t')[0]);
        datos.append('tasadolar', 'tasadolar');
        enviaAjax(datos);
    }); // envio del formulario para la tasa

    $('#exampleModal').on('shown.bs.modal', function () {
        $('#id_producto').select2({
            dropdownParent: $('#exampleModal'),
            width: '93%',
            language: {
                noResults: function() {
                     return "No se encontraron resultados, intenta con otro término.";
                }, 
            }
        });
        
        $('#id_producto').on('select2:open', function() {
            $('input.select2-search__field').attr('placeholder', 'Buscador');
        });
    });

    $('#registrarventa').on("click", function () {
        if (!verificarListaDeVenta()) {
            
        } else {
            // Obtén los totales
        var totalDolares = $('#totaldolares').val(); // o .val() dependiendo de cómo esté configurado
        var totalBs = $('#totalBs').val(); // o .val() dependiendo de cómo esté configurado

        // Muestra la alerta de confirmación
        Swal.fire({
            title: '¿Confirmar venta?',
            text: `Total en Dólares: ${totalDolares}\n | Total en Bs: ${totalBs}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#457B9D',
            confirmButtonText: 'Sí, realizar venta',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, envía el formulario
                var datos = new FormData($('#formularioventa')[0]);
                datos.append('registrar', 'registrar');
                enviaAjax(datos);
            }
        });
    } 
        
    }); //envio del formulario

  

    // Listen for changes on the select element
    $('#id_producto').change(function() {
        // Get the selected option's data-stock and data-precio attributes
        var stock = $(this).find('option:selected').data('stock');
        var precio = $(this).find('option:selected').data('precio');
        var preciomayor = $(this).find('option:selected').data('preciomayor');
        var Cantidadmayor = $(this).find('option:selected').data('cantidad');
        
        // Set the value of the input fields with the retrieved stock and precio
        $('#stock').val(stock);
        $('#precio').val(precio);
        $('#preciomayor').val(preciomayor);
        $('#cantidadomayor').val(Cantidadmayor);
        
        // Validate stock
        if (stock === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'El stock esta en 0. No hay disponibilidad.!',
                confirmButtonColor: '#EF233C',
                confirmButtonText: 'Aceptar'   
            });
            return;
        }
    });

    $("#cantidadproducto").on("keypress", function(e) {
    // Permitir solo números y la tecla de retroceso
     validarkeypress(/^[0-9\b]*$/, e);
    });
            
    $("#cantidadproducto").on("keyup", function() {
// Validar que el contenido tenga entre 4 y 100 caracteres y que contenga solo números
        validarkeyup(/^[0-9]{1,4}$/, $(this),
        $("#textocantidad"), "Debe haber como mínimo 1 caracter hasta 9999");
    });

    var today = moment().format('YYYY-MM-DD'); // Formato de fecha para el input tipo date
    $('#ventafecha').val(today);
    $('#fechaventa').val(today);

    $("#inputtasadolar").on("keypress", function(e) {
        // Permitir solo números y la tecla de retroceso
        validarkeypress(/^[0-9\.\b]*$/, e);
    });
    
    $("#inputtasadolar").on("keyup", function() {
        // Validar que el contenido tenga entre 4 y 100 caracteres y que contenga solo números
        validarkeyup(/^[0-9\.]{4,7}$/, $(this),
         $("#textotasadolar"), "Debe haber como mínimo 0.01");
    });
});


/*|||||||||||||||||||| CODIGO PARA LA LISTA DE VENTA ||||||||||||||||||||||||||||||||| */ 
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| */ 

$('#limpiardatos').on('click', function() {
    // Limpiar los inputs
    $('#id_producto').prop('selectedIndex', 0); // Restablecer el select a la opción por defecto
    $('#cantidadproducto').val(''); // Limpiar el input de cantidad
    $('#stock').val(''); // Limpiar el input de stock
    $('#precio').val(''); 
    $('#preciomayor').val(''); 
    $('#cantidadomayor').val(''); 

    // Actualizar Select2
    $('#id_producto').val(null).trigger('change');
});

$('#limpiarregistro').on('click', function() {
    // Mostrar alerta de confirmación
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Esto limpiará todos los datos del registro!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#457B9D',
        cancelButtonColor: '#EF233C',
        confirmButtonText: 'Sí, limpiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Limpiar los inputs
            $('#id_producto').prop('selectedIndex', 0); // Restablecer el select a la opción por defecto
            $('#cantidadproducto').val(''); // Limpiar el input de cantidad
            $('#stock').val(''); // Limpiar el input de stock
            $('#precio').val(''); 
            $('#preciomayor').val(''); 
            $('#cantidadomayor').val(''); 

             // Actualizar Select2
             $('#id_producto').val(null).trigger('change');

            // Limpiar la tabla de ventas
            var listaVenta = $('#listadeventa');
            while (listaVenta[0].rows.length > 0) {
                listaVenta[0].deleteRow(0); // Eliminar todas las filas de la tabla
            }
 
            // Actualizar totales
            $('#totalUSD').val(''); // Limpiar el input de precio (si existe)
            $('#totalBs').val(''); // Limpiar el input de precio (si existe)
            $('#totaldolares').val(''); // Limpiar el input de precio (si existe)
        }
    });
});

$('#agregar').on('click', function() {
  var productoSelect = $('#id_producto');
  var productoValue = productoSelect.val();

  if (productoValue) {
      var productoText = productoSelect.find('option:selected').text();
      var tasadolar = parseFloat($('#tasadolar').val());
      var cantidadInput = $('#cantidadproducto');
      var cantidad = parseInt(cantidadInput.val());
      var stock = parseInt($('#stock').val());
      var cantidadMayor = parseInt($('#cantidadomayor').val()); // Obtener la cantidad del mayor

      // Validar la cantidad ingresada
      if (!cantidad || cantidad < 1) {
          Swal.fire({
              icon: 'info',
              title: '¡Necesitas ingresar una cantidad válida!',
              confirmButtonColor: '#457B9D',
              confirmButtonText: 'Aceptar'   
          });
          return;
      }

      if (cantidad > stock) {
          Swal.fire({
              icon: 'error',
              title: 'La cantidad ingresada excede el stock disponible.',
              confirmButtonColor: '#457B9D',
              confirmButtonText: 'Aceptar'   
          });
          return;
      }

      // Validar si el producto ya está en la lista
      var productoYaAgregado = false;
      $('#listadeventa tr').each(function() {
          var existingProductId = $(this).find('input[name="id_producto[]"]').val();
          if (existingProductId === productoValue) {
              productoYaAgregado = true;
              return false; // Salir del bucle
          }
      });

      if (productoYaAgregado) {
          Swal.fire({
              icon: 'warning',
              title: 'Este producto ya ha sido agregado a la lista.',
              confirmButtonColor: '#457B9D',
              confirmButtonText: 'Aceptar'   
          });
          return;
      }

      // Verificar si la cantidad es menor que la cantidad mayor
      if (cantidad <= cantidadMayor) {
          // Si es menor o igual, asignar automáticamente el precio de "Detal"
          var precio = parseFloat(productoSelect.find('option:selected').data('precio'));
          var tipoPrecio = 'DETAL';

          // Proceder a agregar el producto a la lista
          agregarProductoALista(productoValue, productoText, cantidad, tasadolar, precio, tipoPrecio);
      } else {
          // Si es mayor, mostrar el SweetAlert para seleccionar el tipo de precio
          Swal.fire({
              title: 'Selecciona el tipo de precio',
              input: 'select',
              inputOptions: {
                  'MAYOR': 'MAYOR',
                  'DETAL': 'DETAL'
              },
              inputPlaceholder: 'Selecciona una opción',
              showCancelButton: true,
              confirmButtonText: 'Aceptar',
              cancelButtonText: 'Cancelar',
              preConfirm: (value) => {
                  if (!value) {
                      Swal.showValidationMessage('Debes seleccionar un tipo de precio');
                  }
                  return value;
              }
          }).then((result) => {
              if (result.isConfirmed) {
                  var tipoPrecio = result.value;
                  var precio;

                  // Determinar el precio según la selección
                  if (tipoPrecio === 'mayor') {
                      precio = parseFloat(productoSelect.find('option:selected').data('preciomayor'));
                  } else {
                      precio = parseFloat(productoSelect.find('option:selected').data('precio'));
                  }

                  // Proceder a agregar el producto a la lista
                  agregarProductoALista(productoValue, productoText, cantidad, tasadolar, precio, tipoPrecio);
              }
          });
      }
  } else {
      Swal.fire({
          icon: 'info',
          title: 'Por favor, seleccione un producto.',
          confirmButtonColor: '#457B9D',
          confirmButtonText: 'Aceptar'   
      });
      return;
  }
});

// Función para agregar el producto a la lista
function agregarProductoALista(productoValue, productoText, cantidad, tasadolar, precio, tipoPrecio) {
  var montoBs = (precio * tasadolar).toFixed(2); // Cálculo del monto en Bs
  var subtotaldorales = (cantidad * precio).toFixed(2); // Cálculo del subtotal en dólares
  var subtotalbs = (cantidad * montoBs).toFixed(2); // Cálculo del subtotal en Bs

  // Crear una nueva fila en la tabla
  var newRow = $('<tr>').html(`
      <td class="bg-s"><input type="hidden" name='id_producto[]' id='producto_id' value='${productoValue}' >${productoText}</td>
      <td class="bg-s"><input type="hidden" name='cantidad[]' value='${cantidad}'>${cantidad}</td>
      <td class="bg-s"><input type="hidden" name='maode[]' value='${tipoPrecio}' readonly>${tipoPrecio}</td>
      <td class="bg-s"><input type="hidden" name='precio[]' value='${precio}' readonly>${precio}</td>
      <td class="bg-s"><input type="hidden" name='montoBs[]' value='${montoBs}' readonly>${montoBs}</td>
      <td class="bg-s"><input type="hidden" name='subtotaldorales[]' value='${subtotaldorales}' readonly>${subtotaldorales}</td>
      <td class="bg-s"><input type="hidden" name='subtotalbs[]' value='${subtotalbs}' readonly>${subtotalbs}</td>
      <td class="bg-s"><button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">Eliminar</button></td>
  `);
  $('#listadeventa').append(newRow);

  // Actualizar el total
  actualizarTotal();
  actualizarTotalBs();

  // Limpiar el select y el input de cantidad
  $('#id_producto').prop('selectedIndex', 0).trigger('change');
  $('#cantidadproducto').val('');
  $('#stock').val(''); // Limpiar el input de stock
  $('#precio').val(''); // Limpiar el input de precio (si existe)
  $('#cantidadomayor').val(''); // Limpiar el input de precio (si existe)
  
}


function eliminarFila(button) {
    var row = $(button).closest('tr');
    row.remove();

    // Actualizar el total después de eliminar la fila
    actualizarTotal();
    actualizarTotalBs();
}


function actualizarTotal() {
    var totalDolares = 0;
    var subtotalInputs = $('[name="subtotaldorales[]"]');

    subtotalInputs.each(function() {
        var value = parseFloat($(this).val()) || 0;
        console.log('totalDolares:', value); // Debugging log
        totalDolares += value; // Sumar los subtotales
    });

    // Actualizar el campo total
    $('#totalUSD').val(totalDolares.toFixed(2)); // Mostrar el total en el campo correspondiente
    $('#totaldolares').val(totalDolares.toFixed(2)); // Mostrar el total en el campo correspondiente
}

function actualizarTotalBs() {
    var totalBS = 0;
    var subtotalInputs = $('[name="subtotalbs[]"]');

    subtotalInputs.each(function() {
        var value = parseFloat($(this).val()) || 0;
        console.log('Adding to totalBS:', value); // Debugging log
        totalBS += value; // Sumar los subtotales
    });

    // Actualizar el campo total
    $('#totalBs').val(totalBS.toFixed(2)); // Mostrar el total en el campo correspondiente
}


/*|||||||||||||||||||| EL FIN DEL CODIGO DE LA LISTA DE VENTA |||||||||||||||||||||||| */ 
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| */ 





// Función para verificar si la lista de venta tiene al menos un registro
function verificarListaDeVenta() {
    // Verificamos el número de filas en la tabla de la lista de venta
    var filas = document.getElementById('listadeventa').getElementsByTagName('tr');
    if (filas.length > 0) {
        return true;
    } else {
        Swal.fire({
            icon: 'info',
            title: 'Por favor, añardir un producto a la lista.',
            confirmButtonColor: '#457B9D',
            confirmButtonText: 'Aceptar'   
        });
        return false;
    }
}

  

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
              muestraMensaje("success", 1500, "Se ha registrado con éxito", "Su registro se ha completado exitosamente");
              setTimeout(function () {
                location = '?pagina=venta';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "ERROR");
            }
          } else if (lee.accion == 'tasadolar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1500, "Tasa Actualiza", "Se ha modificado la tasa correctamente");
              setTimeout(function () {
                location = '?pagina=venta';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "Intente Nuevamente O Mas Tarde");
            }
          } else if (lee.accion == 'eliminar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha eliminado con éxito", "Los datos se han borrado correctamente ");
              setTimeout(function () {
                location = '?pagina=venta';
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
