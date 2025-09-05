
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
        validarCampo($("#cantidad"), /^[0-9]{1,4}$/, $("#textocantidad"), "Debe haber como mínimo 1 caracter hasta 9999"),
     validarCampo($("#fecha"),/^(19|20)\d{2}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/, $("#textofecha"), "Debe ingresar una fecha válida en formato dd/mm/yyyy"),
        validarCampo($("#idproducto"), null, $("#textoproducto"), "Por favor, seleccione un producto válido.")
    ];

    return resultados.every(r => r === true);
}

$(document).ready(function() {

    var hoy = moment().format('YYYY-MM-DD'); // Formato de fecha para el input tipo date
    $('#fecha').val(hoy);

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





  let productoSeleccionado = {};

// Al hacer clic en el botón agregar
$('#btn-agregar-producto').click(function () {
  const productoSelect = $('#idproducto');
  const productoValue = productoSelect.val();
  const productoText = productoSelect.find('option:selected').text();
  const cantidadInput = $('#cantidadpro');
  const cantidadValue = parseInt(cantidadInput.val());

  let valid = true;

  // Validar selección de producto
  if (!productoValue) {
    $('#textoproducto').text('Por favor seleccione un producto.');
    valid = false;
  } else {
    $('#textoproducto').text('');
  }

  // Validar cantidad
  if (!cantidadValue || cantidadValue < 1 || cantidadValue > 200) {
    $('#textocantidad').text('entre 1 y 200.');
    valid = false;
  } else {
    $('#textocantidad').text('');
  }

  if (!valid) return;

  // Verificar si ya existe el producto en la tabla
  const yaExiste = $('input[name="id_producto[]"]').filter(function () {
    return $(this).val() === productoValue;
  }).length > 0;

  if (yaExiste) {
    $('#textoproducto').text('Este producto ya fue agregado.');
     Swal.fire({
      icon: 'warning',
      title: 'Este producto ya fue agregado.',
      text: '',
      confirmButtonColor: '#007bff'
    });
    return;
  }

  // Agregar fila a la tabla
  const nuevaFila = `
    <tr>
      <td class="mb-2 bg-s">
        <input type="hidden" name="id_producto[]" value="${productoValue}">
        ${productoText}
      </td>
      <td class="mb-2 bg-s">
        <input type="hidden" name="cantidad[]" value="${cantidadValue}">
        ${cantidadValue}
      </td>
      <td class="mb-2 bg-s">
        <button type="button" class="btn btn-sm btn-danger text-dark eliminar-fila"> - Quitar </button>
      </td>
    </tr>
  `;

    $('#tbody-productos').append(nuevaFila);
    $('#idproducto').val('');
    $('#cantidadpro').val('');
    $('#idproducto').val(null).trigger('change');
});


  // Eliminar fila
  $('#tabla-productos').on('click', '.eliminar-fila', function() {
    $(this).closest('tr').remove();
  });



    $("#cantidadpro").on("keypresss", function(e) {
     validarkeypress(/^[0-9\b]*$/, e);
    });
        
    $("#cantidadpro").on("keyup", function() {
       validarCampo($(this),/^[0-9]{1,4}$/, 
       $("#textocantidad"), "1 Al 200");
    });


$('#limpiardatos').on('click', function() {
    $('#idproducto').prop('selectedIndex', 0); 
    $('#cantidadpro').val(''); 
    // Actualizar Select2
    $('#idproducto').val(null).trigger('change');
});

$('#btn-limpiar-lista').click(function () {
  const filas = $('#tbody-productos tr').length;

  if (filas === 0) {
    Swal.fire({
      icon: 'info',
      title: 'Lista vacía',
      text: 'No hay productos para eliminar.',
      confirmButtonColor: '#007bff'
    });
    return;
  }

  Swal.fire({
    title: '¿Estás seguro?',
    text: 'Se eliminarán todos los productos de la lista.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#2b8f28ff',
    cancelButtonColor: '#c9280cff',
    confirmButtonText: 'Sí, limpiar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      $('#tbody-productos').empty();
      Swal.fire({
        icon: 'success',
        title: 'Lista limpiada',
        text: 'Todos los productos fueron eliminados.',
        confirmButtonColor: '#007bff'
      });
    }
  });
});


$('#registrar').on("click", function () {
 // Validar que haya al menos una fila en el tbody
  const filas = $('#tbody-productos tr').length;

  if (filas === 0) {
    Swal.fire({
      icon: 'warning',
      title: 'Lista vacía',
      text: 'Debes agregar al menos un producto antes de registrar la compra.',
      confirmButtonColor: '#007bff'
    });
    return; // Detener el envío
  }

    activarLoaderBoton('#registrar', "Guardando...");
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
                location = '?pagina=compra';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "ERROR");
                desactivarLoaderBoton('#registrar'); 
            }
          } else if (lee.accion == 'eliminar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha eliminado con éxito", "Los datos se han borrado correctamente ");
              setTimeout(function () {
                location = '?pagina=compra';
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
          desactivarLoaderBoton('#registrar'); 
        } else {
          muestraMensaje("error", 2000, "Error", "ERROR: <br/>" + request + status + err);
          desactivarLoaderBoton('#registrar'); 
        }
      },
      complete: function () {
      }
    });
  }