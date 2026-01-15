<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head2.php'; ?>
    <title>Inicio | Mister Bolsa C.A</title>

</head> 

<body id="body">

<?php require_once 'complementos/nav2.php'; ?>
<?php require_once 'complementos/sidebar2.php'; ?>

    <div class="row">
                <!-- COLUMNA IZQUIERDA -->
                <div class="col-xl-9 col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold m-0">Dashboard</h4> 
                       
                    </div>

                    <!-- Resumen -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="summary-card card-received active2">
                                <div class="icon-box"><i class="fa-solid fa-cash-register"></i></div>
                                <div class="small">Cantidad de Registros Venta</div>
                                    <?php $total1 = $totalVenta['total']; ?>
                                <div class="h5 fw-bold mb-0"> <?php echo $total1; ?> </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="summary-card card-spend active3">
                                <div class="icon-box"><i class="fa-solid fa-receipt"></i></div>
                                <div class="text-dark small">Cantidad de Registros Compra</div>
                                 
                                     <?php $total2 = $totalCompra['total']; ?>
                                <div class="h5 fw-bold mb-0"> <?php echo $total2; ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="summary-card active shadow-lg">
                                <div class="icon-box bg-white text-primary"><i class="fa-solid fa-boxes-stacked"></i></div>
                                 <?php  $total3 = $totalProductos['total'];  ?>
                            
                                <div class="opacity-75 small">Cantidad de Registros Producto</div>
                                <div class="h5 fw-bold mb-0"><?php echo $total3; ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico -->
                    <div class="chart-container shadow-sm border-0">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold m-0">Movimientos de la semana</h6>
                            <div class="tooltip-custom">
                                    Últimos 7 días: $<?= number_format($TotalSemana['total_semana'], 2) ?>
                            </div>
                        </div>
                        <div class="position-relative">
                            <canvas id="graficaSemana" height="120"></canvas>
                        </div>
                    </div>

                    <!-- Transacciones -->
                    <div class="transaction-list shadow-sm border-0">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold m-0">Ultimas Ventas</h6>
                            <a href="?pagina=venta" class="text-primary small text-decoration-none">Ver Todos</a>
                        </div>
                        <?php if (!empty($registro)): ?>
                            <?php foreach ($registro as $row): ?>
                                <div class="tr-item">
                                    <div class="tr-icon" style="color:#0066ff; background:#f0f6ff;">
                                        V
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="fw-bold small">
                                            <?php echo htmlspecialchars($row['productos']); ?>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            #<?php echo $row['id_venta']; ?> |
                                            <?php echo date('d-m-Y', strtotime($row['fecha'])); ?>
                                        </div>
                                    </div>

                                    <div class="fw-bold text-success small">
                                        $<?php echo number_format($row['total_dl'], 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-muted small text-center">
                                No hay ventas registradas hoy
                            </div>
                        <?php endif; ?>


                      
                    </div>
                </div>

                <!-- COLUMNA DERECHA -->
                <div class="col-xl-3 col-lg-4">
                    <h6 class="fw-bold mb-3">Mister Bolsa C.A</h6>
                    <div class="credit-card shadow">
                     
                    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                            <img src="assets/images/carrusel/001.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                            <img src="assets/images/carrusel/002.jpg" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                            <img src="assets/images/carrusel/003.jpg" class="d-block w-100" alt="...">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    </div>

                    <div class="mb-4 bg-white p-3 rounded-4 shadow-sm">
                        <span class="text-muted small">Tasa de cambio (Guardada en el Sistema)</span>
                         <?php foreach ($dolar as $dolar){  ?>
                        <div class="h4 fw-bold text-dark m-0"> 1,00 $ = <?php echo $dolar['precios']?> Bs </div>
                          <?php } ?>
                    </div>

                    <h6 class="fw-bold mb-3">Acceso Directo</h6>
                    <div class="mb-4">

                            <div class="card access-card shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-box me-3">
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                    </div>
                                    <div>
                                    <h5 class="card-title mb-0">Inventario</h5>
                                    <small class="text-muted">Gestionar productos</small>
                                    </div>
                                    <a href="?pagina=producto" class="stretched-link"></a>
                                </div>
                            </div>

                              <div class="card access-card shadow-sm mt-2">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-box me-3">
                                    <i class="fa-solid fa-cash-register"></i>
                                    </div>
                                    <div>
                                    <h5 class="card-title mb-0">Venta</h5>
                                    <small class="text-muted">Registro Venta</small>
                                    </div>
                                    <a href="?pagina=registroventa" class="stretched-link"></a>
                                </div>
                            </div>

                              <div class="card access-card shadow-sm mt-2">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon-box me-3">
                                    <i class="fa-solid fa-file-invoice-dollar"></i>
                                    </div>
                                    <div>
                                    <h5 class="card-title mb-0">Cierre Diario</h5>
                                    <small class="text-muted">Gestionar Cierre</small>
                                    </div>
                                    <a href="?pagina=cierre" class="stretched-link"></a>
                                </div>
                            </div>                      
                    </div>

                    <div class="wallet-promo bg-white p-4 shadow-sm border-0" style="border-radius: 20px;">
                        <h6 class="fw-bold text-primary mb-2">NeoPay Premium</h6>
                        <p class="text-secondary small mb-3">Get detailed reports and lower fees on all your operations.</p>
                        <button class="btn btn-primary w-100 btn-sm rounded-pill">Upgrade Now</button>
                    </div>
                </div>
            </div>





  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <?php require_once 'complementos/footer2.php'; ?>
  <!--<script src="assets/js/home.js"></script>
  |||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||--> 


<script>
    // Datos desde PHP
    const labelsSemana = [
        <?php foreach ($DatosGrafica as $d) { echo "'" . $d['dia'] . "',"; } ?>
    ];

    const datosSemana = [
        <?php foreach ($DatosGrafica as $d) { echo $d['total_dia'] . ","; } ?>
    ];
</script>



  <script>
const ctx = document.getElementById('graficaSemana');

setTimeout(() => {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labelsSemana,
            datasets: [{
                label: 'Ventas (USD)',
                data: datosSemana,
                borderColor: '#2D5BFF',
                backgroundColor: 'rgba(45, 91, 255, 0.2)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#2D5BFF'
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}, 500);


</script>



</body>
</html>