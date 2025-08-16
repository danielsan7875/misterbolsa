var deleteButtons = document.querySelectorAll('.eliminar');
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
});

  


/*|||||||||||||||||||| CODIGO PARA LA LISTA DE VENTA ||||||||||||||||||||||||||||||||| */ 
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| */ 

$('#limpiardatos').on('click', function() {
    // Limpiar los inputs
    $('#id_producto').prop('selectedIndex', 0); // Restablecer el select a la opción por defecto
    $('#cantidadproducto').val(''); // Limpiar el input de cantidad
    $('#stock').val(''); // Limpiar el input de stock
    $('#precio').val(''); // Limpiar el input de precio (si existe)
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
            $('#precio').val(''); // Limpiar el input de precio (si existe)

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
        var precio = parseFloat(productoSelect.find('option:selected').data('precio'));

        // Obtener la tasa de cambio desde el input oculto
        var tasadolar = parseFloat($('#tasadolar').val());

        // Obtener la cantidad ingresada
        var cantidadInput = $('#cantidadproducto');
        var cantidad = parseInt(cantidadInput.val());

        // Obtener el stock disponible
        var stock = parseInt($('#stock').val());

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

        var montoBs = (precio * tasadolar).toFixed(2); // Cálculo del monto en Bs
        var subtotaldorales = (cantidad * precio).toFixed(2); // Cálculo del subtotal en dólares
        var subtotalbs = (cantidad * montoBs).toFixed(2); // Cálculo del subtotal en Bs

        // Crear una nueva fila en la tabla
        var newRow = $('<tr>').html(`
            <td><input type="hidden" name='id_producto[]' id='producto_id' value='${productoValue}' >${productoText}</td>
            <td><input type="hidden" name='cantidad[]' value='${cantidad}'>${cantidad}</td>
            <td><input type="hidden" name='precio[]' value='${precio}' readonly>${precio}</td>
            <td><input type="hidden" name='montoBs[]' value='${montoBs}' readonly>${montoBs}</td>
            <td><input type="hidden" name='subtotaldorales[]' value='${subtotaldorales}' readonly>${subtotaldorales}</td>
            <td><input type="hidden" name='subtotalbs[]' value='${subtotalbs}' readonly>${subtotalbs}</td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">Eliminar</button></td>
        `);
        $('#listadeventa').append(newRow);

        // Actualizar el total
        actualizarTotal();
        actualizarTotalBs();

        // Limpiar el select y el input de cantidad
        productoSelect.prop('selectedIndex', 0);
        cantidadInput.val('');
        $('#stock').val(''); // Limpiar el input de stock
        $('#precio').val(''); // Limpiar el input de precio (si existe)

      

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
        console.log('Adding to totalDolares:', value); // Debugging log
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
