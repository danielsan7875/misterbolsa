$(document).ready(function () {
    const $tabla = $('#tabla-inventario');
  const $filasOriginales = $tabla.find('tbody tr');
  const $buscador = $('#buscador');
  const $paginacion = $('.pagination');
  const porPagina = 4;
  let paginaActual = 1;
  let filasFiltradas = $filasOriginales;

  function mostrarPagina(pagina) {
    const inicio = (pagina - 1) * porPagina;
    const fin = inicio + porPagina;

    filasFiltradas.hide().slice(inicio, fin).show();
    actualizarBotones(pagina);
  }

  function actualizarBotones(pagina) {
    const totalPaginas = Math.ceil(filasFiltradas.length / porPagina);
    $paginacion.empty();

    if (totalPaginas === 0) return;

    if (pagina > 1) {
      $paginacion.append(`<li class="page-item"><a class="page-link" href="#" data-page="${pagina - 1}">Atrás</a></li>`);
    }

    for (let i = 1; i <= totalPaginas; i++) {
      $paginacion.append(`<li class="page-item ${i === pagina ? 'active' : ''}">
        <a class="page-link" href="#" data-page="${i}">${i}</a></li>`);
    }

    if (pagina < totalPaginas) {
      $paginacion.append(`<li class="page-item"><a class="page-link" href="#" data-page="${pagina + 1}">Siguiente</a></li>`);
    }

    $paginacion.find('a').on('click', function (e) {
      e.preventDefault();
      paginaActual = parseInt($(this).data('page'));
      mostrarPagina(paginaActual);
    });
  }

 function filtrarFilas() {
  const filtro = $buscador.val().toLowerCase().trim();

  // Eliminar mensaje previo
  $tabla.find('.no-result').remove();

  if (filtro === '') {
    filasFiltradas = $filasOriginales;
  } else {
    filasFiltradas = $filasOriginales.filter(function () {
      const textoClave = $(this).find('td:eq(1)').text().toLowerCase();
      return textoClave.includes(filtro);
    });
  }

  if (filasFiltradas.length === 0) {
    $filasOriginales.hide();
    $tabla.find('tbody').append(`<tr class="no-result"><td colspan="4" class="text-center  bg-s text-muted">No se encontraron resultados</td></tr>`);
    $paginacion.empty();
  } else {
    paginaActual = 1;
    mostrarPagina(paginaActual);
  }
}
  $buscador.on('input', filtrarFilas);

  mostrarPagina(paginaActual);
    
/*----------------------*/
  const tasaDolar = parseFloat($('#tasadolar').val()); // Asegúrate que este input exista y tenga el valor

  $('.preciobsdetal').each(function () {
    const precioUSD = parseFloat($(this).data('usd'));
    const precioBS = (precioUSD * tasaDolar).toFixed(2);
    $(this).text(precioBS);
  });

  $('.preciobsmayor').each(function () {
    const precioUSD = parseFloat($(this).data('usd'));
    const precioBS = (precioUSD * tasaDolar).toFixed(2);
    $(this).text(precioBS);
  });

  /*------------------*/
  const tasa = parseFloat($('#tasadolar').val());

  $('.btn-agregar').on('click', function () {
  const btn = $(this);
  const productoValue = btn.data('id');
  const productoText = btn.data('text');
  const precioDetal = parseFloat(btn.data('precio-detal'));
  const precioMayor = parseFloat(btn.data('precio-mayor'));
  const cantidadMayor = parseInt(btn.data('cantidad-mayor'));
  const stock = parseInt(btn.data('stock'));

  if (stock === 0) {
    Swal.fire('Sin stock disponible', 'Este producto no tiene unidades disponibles', 'error');
    return;
  }

  const yaAgregado = $('#bodyventa input[name="id_producto[]"]').filter(function () {
    return $(this).val() == productoValue;
  }).length > 0;

  if (yaAgregado) {
    Swal.fire('Producto ya agregado', 'Este producto ya está en la lista de venta', 'warning');
    return;
  }

  const cantidad = 1;
  const precio = cantidad >= cantidadMayor ? precioMayor : precioDetal;
  const montoBs = (precio * tasa).toFixed(2);
  const subtotalDolares = (precio * cantidad).toFixed(2);
  const subtotalBs = (subtotalDolares * tasa).toFixed(2);
  const tipoPrecio = cantidad >= cantidadMayor ? 'Mayor' : 'Detal';

  const fila = `
    <tr>
      <td class="bg-s">
        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">Eliminar</button>
      </td>
      <td class="bg-s">
        <input type="hidden" name='id_producto[]' value='${productoValue}'>${productoText}
      </td>
      <td class="bg-s">
        <input type="number" name='cantidad[]' class="form-control border border-info cantidad" value='${cantidad}' min="1" max="${stock}">
      </td>
      <td class="bg-s">
        <span class="precio-bs">${montoBs} Bs</span>
        <input type="hidden" name='precio[]' value='${precio}'>
        <input type="hidden" name='montoBs[]' value='${montoBs}'>
      </td>
      <td class="bg-s">
        <input type="hidden" name='maode[]' value='${tipoPrecio}'>${tipoPrecio}
      </td>
      <td class="bg-s">
        <span class="subtotal-bs">${subtotalBs} Bs</span>
        <input type="hidden" name='subtotaldorales[]' value='${subtotalDolares}'>
        <input type="hidden" name='subtotalbs[]' value='${subtotalBs}'>
      </td>
    </tr>
  `;

  $('#bodyventa').append(fila);
  verificarFilaVacia();
  actualizarTotales();
});

function verificarFilaVacia() {
  const hayProductos = $('#bodyventa tr').not('.fila-vacia').length > 0;
  $('.fila-vacia').toggle(!hayProductos);
}


  // Actualizar subtotales al cambiar cantidad
$('#bodyventa').on('input', '.cantidad', function () {
  const $fila = $(this).closest('tr');
  const cantidad = parseInt($(this).val());
  const stock = parseInt($(this).attr('max'));
  const productoId = $fila.find("input[name='id_producto[]']").val();
  const btnRef = $('.btn-agregar[data-id="' + productoId + '"]');
  const cantidadMayor = parseInt(btnRef.data('cantidad-mayor'));
  const precioDetal = parseFloat(btnRef.data('precio-detal'));
  const precioMayor = parseFloat(btnRef.data('precio-mayor'));

  if (cantidad > stock) {
    Swal.fire('Stock insuficiente', `Solo hay ${stock} unidades disponibles`, 'warning');
    $(this).val(stock);
    return;
  }

  const precio = cantidad >= cantidadMayor ? precioMayor : precioDetal;
  const tipoPrecio = cantidad >= cantidadMayor ? 'Mayor' : 'Detal';
  const montoBs = (precio * tasa).toFixed(2);
  const subtotalDolares = (precio * cantidad).toFixed(2);
  const subtotalBs = (subtotalDolares * tasa).toFixed(2);

  $fila.find('.precio-bs').text(`${montoBs} Bs`);
  $fila.find("input[name='precio[]']").val(precio.toFixed(2));
  $fila.find("input[name='montoBs[]']").val(montoBs);
  $fila.find("input[name='maode[]']").val(tipoPrecio);
  $fila.find("td:eq(4)").html(`<input type="hidden" name='maode[]' value='${tipoPrecio}'>${tipoPrecio}`);
  $fila.find('.subtotal-bs').text(`${subtotalBs} Bs`);
  $fila.find("input[name='subtotaldorales[]']").val(subtotalDolares);
  $fila.find("input[name='subtotalbs[]']").val(subtotalBs);

  actualizarTotales();
});


  // Eliminar fila
  window.eliminarFila = function (btn) {
    $(btn).closest('tr').remove();
    verificarFilaVacia();
    actualizarTotales();
  };

  // Actualizar totales generales
  function actualizarTotales() {
    let totalDolares = 0;
    let totalBs = 0;

    $('#bodyventa tr').each(function () {
      const subtotalD = parseFloat($(this).find("input[name='subtotaldorales[]']").val()) || 0;
      const subtotalB = parseFloat($(this).find("input[name='subtotalbs[]']").val()) || 0;
      totalDolares += subtotalD;
      totalBs += subtotalB;
    });

    $('.texto-secundario strong:contains("$")').text(`${totalDolares.toFixed(2)} $`);
    $('.texto-secundario strong:contains("Bs")').last().text(`${totalBs.toFixed(2)} Bs`);
    $('#totaldolaresfor').val(totalDolares.toFixed(2)); 
  }

  const fechaActual = moment().format('YYYY-MM-DD');
 $('#ventafecha').val(fechaActual);


$('#registrarventa').on("click", function (e) {
  e.preventDefault(); // Evita el envío inmediato

  const filasVenta = $('#bodyventa tr').not('.fila-vacia');

  if (filasVenta.length === 0) {
    Swal.fire({
      icon: 'warning',
      title: 'Sin productos',
      text: 'No hay productos en la lista de venta',
      confirmButtonColor: '#0337c4ff'
    });
    return;
  }

  Swal.fire({
    title: '¿Confirmar venta?',
    text: '¿Deseas registrar esta venta?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Sí, registrar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#1d994dff',
    cancelButtonColor: '#EF233C',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
       activarLoaderBoton('#registrarventa', "Guardando...");
      const datos = new FormData($('#formularioventa')[0]);
      datos.append('registrar', 'registrar');
      enviaAjax(datos);
    }
  });
});



  });


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
            try {
        // Eliminar contenido HTML en caso de que aparezca
        var respuestaLimpiada = respuesta.split("<!DOCTYPE html>")[0].trim();
        var lee = JSON.parse(respuestaLimpiada);
        console.log("JSON parseado correctamente:", lee);
        } catch (error) {
            console.error("Error al parsear JSON:", error.message);
        }

        try {
  
          if (lee.accion == 'registrar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1500, "Se ha registrado con éxito", "Su registro se ha completado exitosamente");
              desactivarLoaderBoton('#registrarventa');
              setTimeout(function () {
                location = '?pagina=registroventa';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "Intente más Tarde");
              desactivarLoaderBoton('#registrarventa');
            }
          } 
          
        } catch (e) {
          alert("Error en JSON " + e.name);
            desactivarLoaderBoton('#registrarventa');
        }
      },
      error: function (request, status, err) {
        Swal.close();
        if (status == "timeout") {
          muestraMensaje("error", 2000, "Error", "Servidor ocupado, intente de nuevo");
            desactivarLoaderBoton('#registrarventa');
        } else {
          muestraMensaje("error", 2000, "Error", "ERROR: <br/>" + request + status + err);
            desactivarLoaderBoton('#registrarventa');
        }
      },
      complete: function () {
      }
    });
  }  
