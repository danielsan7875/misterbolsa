    
    <!-- |||||||||||||||| MODALES ||||||||||||||||| -->

    <!-- Modal CONFIGURACION -->
    <div class="modal fade" id="modalconfiguracion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modal-producto ">
            <div class="modal-header" >
                <h5 class="modal-title" id="staticBackdropLabel">Configuración</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                        
                <div class="transaction-list shadow-sm border-0 mt-3 DivOscuro2">
                    <div class="row">
                        <div class="col">
                            <h5>Modo Oscuro</h5>
                        </div>
                        <div class="col">
                            <div class="form-check form-switch">
                                <input class="form-check-input" id="toggleModo" type="checkbox" id="flexSwitchCheckDefault">
                               
                            </div>
                        </div>
                    </div>
                    
                </div>
                  <div class="transaction-list shadow-sm border-0 DivOscuro2">
                    <h5>Estado del Cierre Diario: 
                         <?php 
                        if (!$cierre) {
                        echo'<span class="text-danger"> No activo </span>';
                        }else{
                           echo'<span class="text-success"> Activo Nro. '.$cierre["id_cierre"].' </span>';
                        }
                        ?>
                    </h5>
                    </div>
                     <div class="transaction-list shadow-sm border-0 DivOscuro2">
                         <h5>Tasa de Cambio (Guardada en el Sistema)
                            <span class="text-success" id="tasacofing">  </span>
                         </h5>
                    </div>
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>


      <!-- Modal HISTORIAL -->
    <div class="modal fade" id="modalhistorial"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content modal-producto">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"> <i class="fa-solid fa-clock-rotate-left me-2"></i> Historial de Venta de Hoy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                

                             <div class="transaction-list shadow-sm border-0 DivOscuro2">

      <div class="table-responsive text-dark TextOscuro">
        <table id="myTable" class="table table-hover" style="width:100%">
          <thead class="table-color">
            <tr>
              <th>Nro y Fecha</th>
              <th>Producto</th>
         
              <th>Cant. / Modo</th>
              <th>Precio Uni. $</th>
              <th>TOTAL $</th>
              <?php if( $tipo_usuario == 1) { ?>
              <th>Accion</th>
              <?php } ?>
            </tr>
          </thead>
            <tbody>
              <?php
            $ventas = [];
            foreach ($registro as $dato) {
                $id_venta = $dato['id_venta'];

                if (!isset($ventas[$id_venta])) {
                    $ventas[$id_venta] = [
                        'fecha' => $dato['fecha'],
                        'total' => $dato['total_dl'],
                        'totalBs' => $dato['total_bs'],
                        'detalles' => [],
                        'pagos' => []
                    ];
                }

                // Agregar productos
                 // Agregar productos SIN DUPLICAR
                $productoKey = $dato['id_producto'];

                if (!isset($ventas[$id_venta]['detalles'][$productoKey])) {
                    $ventas[$id_venta]['detalles'][$productoKey] = [
                        'nombre' => $dato['nombre_producto'],
                        'cantidad' => $dato['cantidad'],
                        'marca' => $dato['nombre_marca'],
                        'presentacion' => $dato['nombre_presentacion'],
                        'precio_unitario' => $dato['precio_unitario'],
                        'maode' => $dato['maode']
                    ];
                }

            }

              ?>
              <?php foreach ($ventas as $id_venta => $info): ?>
              <tr>

                   <td class="td-oscuro">
                  <div class="d-flex align-items-center">
                      <div class="me-3">
                          <i class="fa-solid fa-cash-register fa-2x" style="color: #E63946;"></i>
                      </div>
                      <div>
                          <div style="font-size: 1.1rem;">
                                 Nro: <?php echo $id_venta; ?>
                          </div>
                          <div class="small text-primary">
                            <strong>
                            <?php echo date("d/m/Y", strtotime($info['fecha'])); ?>
                            </strong>
                          </div>
                      </div>
                  </div>
              </td> 

               <td class="td-oscuro">
                    <ul class="mb-0 list-unstyled">
                        <?php foreach ($info['detalles'] as $detalle): ?>
                            <li>
                                <i class="fa-solid fa-boxes-stacked me-2 text-primary"></i>
                                <?php echo $detalle['nombre'].' - '.$detalle['marca'].' - '.$detalle['presentacion']; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </td>

               <td class="td-oscuro">
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($info['detalles'] as $detalle): ?>
                            <li>
                                <?php echo $detalle['cantidad'] . ' - ' . strtoupper($detalle['maode']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </td>

                  <td class="td-oscuro">
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($info['detalles'] as $detalle): ?>
                                <li><?php echo number_format($detalle['precio_unitario'], 2, ',', '.') . ' $'; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>


                  <td class="td-oscuro align-middle">
                    <?php echo  '$ '.$info['total']; ?>
                            <p class="text-muted">Bs. <?php echo $info['totalBs'] ?></p>
                  </td>

                  <?php if ($tipo_usuario == 1): ?>
                  <td class="td-oscuro align-middle">
                      <form action="?pagina=venta" method="POST" >
                          <button type="submit"  class="btn btn-primary btn-sm" name="pdf" value="<?php echo $id_venta; ?>"> <i class="fa-regular fa-file-pdf me-2"></i>Descargar</button>
                      </form>
                  </td>
                  <?php endif; ?>
              </tr>
              <?php endforeach; ?>
            </tbody>
        </table>
      </div>

    </div>




            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal CERRAR SESSION -->
    <div class="modal fade" id="modalcerrar"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

        <div class="modal-body text-center p-4">

            <i class="fa-solid fa-circle-question text-dark mb-4"
              style="font-size: 7rem;"></i>

            <h4 class="fw-bold mb-4">
                ¿Confirmas que deseas cerrar sesión?
            </h4>

            <div class="d-flex justify-content-center gap-3">

                <form action="?pagina=login" method="POST" autocomplete="off">
                    <button type="submit" class="btn btn-success btn-lg px-4" name="cerrar">
                        Sí
                    </button>
                </form>

                <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">
                    No
                </button>
            </div>
        </div>
      
    </div>
  </div>
</div>


<!-- MODAL DE PAGOS MULTIPLES -->
<div class="modal fade" id="ModalPago"  data-bs-backdrop="static" data-bs-keyboard="false"tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content body-modal">
            <div class="modal-body text-center p-4">

            
        <div id="main-app" class="container py-4 py-md-5 divcards DivOscuro2">
        <div class="row max-width-lg mx-auto">
            
            <!-- Header -->
            <div class="col-12 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h1 class="header-title mb-1"> <i class="fa-solid fa-receipt me-2 text-primary"></i> Finalizar Venta</h1>
                    <p class="text-secondary mb-0">Seleccione el método de pago para finalizar.</p>
                </div>
                <div class="px-4 py-2 d-flex align-items-center gap-3">
                     <button type="button" class="btn btn-primary btn-sm px-4">
                             <i class="fa-solid fa-circle-question me-2"></i>
                                Ayuda
                        </button>
                    
                        <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                    
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-8 mb-4 ">
                <div class="card card-custom p-4 p-md-5 DivOscuro1">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold m-0"><i class="fa-solid fa-credit-card text-primary me-2"></i> Métodos de Pago</h5>
                        <a href="#" class="btn btn-primary" id="btn-add-method">
                            <i class="fa-solid fa-plus me-2"></i> Añadir otro
                        </a>
                    </div>

                    <div id="payments-container" class="d-flex flex-column gap-3">
                        <!-- Las filas de pago se insertan aquí vía JS -->
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 2rem; z-index: 10;">
                    <div class="summary-card mb-4 shadow-lg">
                        <div class="form-label-xs text-white-50 mb-3">Resumen de Venta</div>
                        
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <span class="text-white-50 small">Total a cobrar:</span>
                            <div class="text-end">
                                <h2 class="fw-black mb-0" id="summary-total-usd">$0.00</h2>
                                <span class="text-white fw-bold" id="summary-total-bs">0 BS</span>
                            </div>
                        </div>

                        <hr class="border-secondary opacity-25">

                        <div class="d-flex justify-content-between align-items-center mb-4 small">
                            <span class="text-white">Pagado hasta ahora:</span>
                            <span class="text-white fw-bold" id="summary-paid-usd">$0.00</span>
                        </div>

                        <div class="bg-white bg-opacity-10 rounded-4 p-4 border border-white border-opacity-10">
                            <div class="form-label-xs text-white-50 mb-2">Restante por pagar</div>
                            <div class="d-flex justify-content-between align-items-end">
                                <h3 class="fw-bold mb-0" id="remaining-usd">$0.00</h3>
                                <span class="text-white-50 small" id="remaining-bs">0 BS</span>
                            </div>
                            
                            <div class="progress mt-3">
                                <div id="payment-progress" class="progress-bar bg-primary" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <button id="btn-process" class="btn btn-secondary btn-process w-full w-100 mb-3 disabled">
                        <span id="btn-text"> <i class="fa-solid fa-circle-info me-2"></i> Falta completar pago</span>
                    </button>

                    <p class="text-center text-secondary small px-3">
                        <i class="fa-solid fa-circle-info me-1"></i> Verifique los montos antes de confirmar.
                    </p>
                </div>
            </div>
        </div>
    </div>

<!-- Pantalla de Éxito -->
<div id="success-screen" class="container py-5 text-center divcards DivOscuro2">
    <div class="row justify-content-center">
        <div class="col-md-5">
      
            <div class="card card-custom p-5 position-relative overflow-hidden DivOscuro1">

                <!-- Icono decorativo izquierdo -->
                <i class="fa-solid fa-check-circle fa-5x text-success position-absolute" 
                   style="top: 50%; left: 0; transform: translate(-50%, -50%); opacity: 0.08; pointer-events: none;"></i>

                <!-- Icono decorativo derecho -->
                <i class="fa-solid fa-cart-shopping fa-5x text-primary position-absolute" 
                   style="top: 50%; right: 0; transform: translate(50%, -50%); opacity: 0.08; pointer-events: none;"></i>

                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-circle-check fa-beat fa-2x"></i>
                </div>

                <h2 class="fw-bold mb-2">¡Venta Completada!</h2>
                <p class="text-secondary mb-4">Hemos registrado la operación correctamente. El comprobante ya está disponible para imprimir o enviar.</p>
                <a href="?pagina=registroventa" class="btn btn-primary btn-process w-100">Nueva Venta</a>
            </div>
        </div>
    </div>
</div>



    <script>
        const EXCHANGE_RATE = <?php echo $precio; ?>;
        const MAX_METHODS = 4;

        let payments = [
            { id: Date.now(), method: '', currency: 'USD', amount: 0, ref: '' }
        ];

        $(document).ready(function () {
            syncTotalsUI();
            renderPayments();
            updateTotals();

            formatearDecimalAutomatico('.monto-input');
        });

        /* ==================
        SINCRONIZAR TOTALES EN LA UI
        ================== */
        function syncTotalsUI() {
            const totalUSD = parseFloat($('#total_dl').val()) || 0;
            const totalBS  = parseFloat($('#total_bs').val()) || 0;

            $('#summary-total-usd').text(`$${totalUSD.toFixed(2)}`);
            $('#summary-total-bs').text(`${totalBS.toFixed(2)} BS`);
        }

        /* ==================
        RENDER ORIGINAL UI
        ================== */
        function renderPayments() {

            const container = $('#payments-container');
            container.html('');

            payments.forEach((p) => {

                const row = `
                <div class="payment-row DivOscuro2">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-4">
                            <label class="form-label-xs TitelOscuro">Método</label>
                            <select class="form-select metodo-select w-100" data-id="${p.id}">
                                <option value="">Seleccione</option>
                                <?php foreach ($metodo as $m): ?>
                                    <option value="<?= $m['id_metodopago']; ?>"><?= htmlspecialchars($m['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>

                            <input type="text" class="form-control mt-2 referencia-input ${p.method == 1 || p.method == 2 ? '' : 'd-none'}"
                                placeholder="Referencia" data-id="${p.id}" value="${p.ref || ''}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label-xs">Moneda</label>
                            <div class="btn-group w-100 p-1 bg-white border rounded-3  w-100">
                                <button type="button" class="btn btn-currency ${p.currency === 'USD' ? 'active' : ''}" data-id="${p.id}" data-cur="USD">USD</button>
                                <button type="button" class="btn btn-currency ${p.currency === 'BS' ? 'active' : ''}" data-id="${p.id}" data-cur="BS">BS</button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-xs">Monto a pagar</label>
                            <div class="currency-input-group">
                                <span class="currency-symbol">${p.currency === 'USD' ? '$' : 'Bs'}</span>
                                <input type="text" step="0.01" class="form-control monto-input  w-100" data-id="${p.id}" value="${p.amount || ''}">
                            </div>
                        </div>

                        <div class="col-md-1 text-end">
                            <button class="btn border-0 text-secondary p-2 btn-remove" ${payments.length === 1 ? 'disabled style="opacity:0"' : ''} data-id="${p.id}">
                                <i class="fa-solid fa-trash-can fs-5 text-danger"></i>
                            </button>
                        </div>

                    </div>
                </div>
                `;

                container.append(row);
                container.find(`select[data-id="${p.id}"]`).val(p.method);
            });

            filtrarMetodos();
            generarHidden();
        }

        /* ==================
        INPUTS OCULTOS LIMPIOS
        ================== */
        function generarHidden() {

            const div = $('#MetodoPagoDiv');
            div.html('');

            payments.forEach(p => {
                // Solo generar si hay método y monto válido
                if (!p.method || p.amount <= 0) return;

                let usd = 0;
                let bs  = 0;

                if (p.currency === 'USD') {
                    usd = p.amount;
                    bs  = p.amount * EXCHANGE_RATE;
                } else {
                    bs  = p.amount;
                    usd = p.amount / EXCHANGE_RATE;
                }

                div.append(`
                    <input type="hidden" name="monto_dolares[]" value="${usd.toFixed(2)}">
                    <input type="hidden" name="monto_bs[]" value="${bs.toFixed(2)}">
                    <input type="hidden" name="id_metodopago[]" value="${p.method}">
                    <input type="hidden" name="referencias[]" value="${p.ref || ''}">
                `);
            });
        }

        /* ==================
        EVENTOS
        ================== */
        $(document).on('change', '.metodo-select', function () {
            const id = $(this).data('id');
            payments.find(p => p.id === id).method = $(this).val();

            // Mostrar input de referencia solo si es metodo 1 o 2
            const refInput = $(`.referencia-input[data-id="${id}"]`);
            if ($(this).val() == '1' || $(this).val() == '2') {
                refInput.removeClass('d-none');
            } else {
                refInput.addClass('d-none').val('');
                payments.find(p => p.id === id).ref = '';
            }

            renderPayments();
        });

        $(document).on('click', '.btn-currency', function () {
            const id = $(this).data('id');
            payments.find(p => p.id === id).currency = $(this).data('cur');
            renderPayments();
            updateTotals();
        });


        $(document).on('input', '.referencia-input', function() {
            let val = $(this).val();

            // eliminar cualquier carácter que no sea número
            val = val.replace(/\D/g, '');

            // limitar entre 4 y 6 dígitos
            if (val.length > 6) val = val.slice(0,6);

            $(this).val(val);

            // guardar en payments
            const id = $(this).data('id');
            payments.find(p => p.id === id).ref = val;
            generarHidden();
        });


        $(document).on('click', '.btn-remove', function () {
            const id = $(this).data('id');
            payments = payments.filter(p => p.id !== id);
            renderPayments();
            updateTotals();
        });

        /* ==================
        AÑADIR MÉTODO
        ================== */
        $('#btn-add-method').click(function (e) {
            e.preventDefault();
            if (payments.length >= MAX_METHODS) {
                muestraMensaje("error", 1500, "Máximo 4 métodos permitidos", "");
                return;
            }
            payments.push({
                id: Date.now(),
                method: '',
                currency: 'USD',
                amount: 0,
                ref: ''
            });
            renderPayments();
        });

        /* ==================
        FILTRAR MÉTODOS
        ================== */
        function filtrarMetodos() {
            const usados = payments.map(p => p.method).filter(x => x !== '');
            $('.metodo-select').each(function () {
                const actual = $(this).val();
                $(this).find('option').each(function () {
                    const val = $(this).val();
                    if (val && usados.includes(val) && val !== actual) $(this).hide();
                    else $(this).show();
                });
            });
        }

        /* ==================
        CARD DE PROGRESO + BOTÓN
        ================== */
        function updateTotals() {

            const totalUSD = parseFloat($('#total_dl').val()) || 0;
            if (totalUSD <= 0) return;

            const paidUSD = payments.reduce((acc, p) => {
                const val = p.amount || 0;
                return acc + (p.currency === 'USD' ? val : val / EXCHANGE_RATE);
            }, 0);

            const remainingUSD = Math.max(0, totalUSD - paidUSD);
            const remainingBS = remainingUSD * EXCHANGE_RATE;
            const progress = Math.min(100, (paidUSD / totalUSD) * 100);

            $('#summary-paid-usd').text(`$${paidUSD.toFixed(2)}`);
            $('#remaining-usd').text(`$${remainingUSD.toFixed(2)}`);
            $('#remaining-bs').text(`${remainingBS.toLocaleString('es-VE')} BS`);
            $('#payment-progress').css('width', `${progress}%`);

            const btn = $('#btn-process');
            const btnText = $('#btn-text');

            if (remainingUSD <= 0.01 && paidUSD > 0) {
                btn.prop('disabled', false)
                .removeClass('disabled btn-secondary')
                .addClass('btn-primary');
                btnText.html('<i class="bi bi-check-circle me-2"></i> Procesar Pago');
            } else {
                btn.prop('disabled', true)
                .addClass('disabled btn-secondary')
                .removeClass('btn-primary');
                btnText.html('<i class="bi bi-exclamation-circle me-2"></i> Falta completar pago');
            }
        }


        function formatearDecimalAutomatico(selector) {
            $(document).on("input", selector, function () {
                let id = $(this).data('id');
                let pObj = payments.find(p => p.id === id);
                if (!pObj) return;

                // Solo dígitos
                let valor = $(this).val().replace(/\D/g, "");

                if (valor.length === 0) {
                    $(this).val("");
                    pObj.amount = 0;
                    generarHidden();
                    updateTotals();
                    return;
                }

                // Asegura al menos 3 dígitos (2 decimales)
                while (valor.length < 3) valor = "0" + valor;

                const parteEntera = valor.slice(0, valor.length - 2);
                const parteDecimal = valor.slice(-2);

                // Formato con separador decimal
                let resultado = `${parseInt(parteEntera)}.${parteDecimal}`;

                let totalUSD = parseFloat($('#total_dl').val()) || 0;

                // Validar que no supere el total
                const pagado = payments.reduce((a, p) => {
                    if (p.id === id) return a;
                    return a + (p.currency === 'USD' ? p.amount : p.amount / EXCHANGE_RATE);
                }, 0);

                let restante = totalUSD - pagado;
                if (restante < 0) restante = 0;
                if (pObj.currency === 'BS') restante *= EXCHANGE_RATE;

                let valorNumerico = parseFloat(resultado);
                if (valorNumerico > restante) valorNumerico = restante;

                // Guardar en payments
                pObj.amount = valorNumerico;

                // Mostrar en input
                $(this).val(valorNumerico.toFixed(2));

                generarHidden();
                updateTotals();
            });
        }

        function resetVenta() {
    
    document.getElementById('main-app').style.display = 'none';
    document.getElementById('success-screen').style.display = 'block';

    payments = [
        { id: Date.now(), method: '', currency: 'USD', amount: 0, ref: '' }
    ];

    renderPayments();
    updateTotals();
}

function verificarPagosCompletos() {
    for (let p of payments) {
        // Si no hay método seleccionado
        if (!p.method || p.method === '') return false;
        // Si el monto es 0 o vacío
        if (!p.amount || p.amount <= 0) return false;
    }
    return true;
}


    </script>







            </div>
        </div>
    </div>
</div>



    
    
    
    
    <!-- Scripts -->
    <script src="assets/js/moment.js"></script>

    <script src="assets/js/datetable/data.js"></script>

    <script src="assets/js/sweetalert2.js"></script>
    
    <script src="assets/librerias/bootstrap5/js/bootstrap.min.js"></script>
    
    <script src="assets/librerias/bootstrap5/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/SystemVentaPos.js"></script>

    <script src="assets/librerias/driver.js/dist/driver.js.iife.js"></script>
