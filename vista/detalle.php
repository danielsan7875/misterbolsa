<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head2.php'; ?>
    <title>Detalle de Producto | Mister Bolsa C.A</title>
     <style>
    

    .dashboard-card{
      border-radius:12px;
      padding:20px;
      color:#fff;
      min-height:120px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      box-shadow:0 4px 12px rgba(0,0,0,.12);
      transition:.3s ease;
    }

    .dashboard-card:hover{
      transform:translateY(-4px);
      box-shadow:0 10px 20px rgba(0,0,0,.18);
    }

    .dashboard-card i{
      font-size:40px;
      opacity:.9;
    }

    .dashboard-card .title{
      font-size:14px;
      letter-spacing:.5px;
      text-transform:uppercase;
      opacity:.9;
    }

    .dashboard-card .value{
      font-size:36px;
      font-weight:700;
      line-height:1;
    }

    .bg-blue{
      background:linear-gradient(135deg,#5fa9ff,#3f7cff);
    }

    .bg-green{
      background:linear-gradient(135deg,#35d07f,#1fb56a);
    }

    .bg-orange{
      background:linear-gradient(135deg,#ffb44c,#ff8c00);
    }

    .bg-red{
      background:linear-gradient(135deg,#ff6a6a,#ff3d3d);
    }

    #graficaTorta{
    max-height: 390px !important;
}

  </style>
</head>

<body>
  
  <?php require_once 'vista/complementos/nav2.php'; ?>
  <?php require_once 'vista/complementos/sidebar2.php'; ?>

     <div class="row">
        <div class="d-sm-flex align-items-center justify-content-between mb-5">
          <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fa-solid fa-chart-pie me-2" style="color: #E63946;"></i>
            Finanzas</h1>
              
          <!-- Button que abre el Modal N1 Registro -->
            <button type="button" class="btn btn-primary">
                <i class="fa-solid fa-circle-question me-2"></i>
               Ayuda
          </button>
        </div>
    </div>

 
  
   <div class="transaction-list shadow-sm border-0 DivOscuro1">
<div class="container py-4">
  <div class="row g-4">

    <!-- Card 1 -->
    <div class="col-xl-3 col-md-6">
      <div class="dashboard-card bg-blue">
        <div>
          <div class="title"><strong>Productos</strong></div>
           <?php $total = $totalProductos['total']; ?>
          <div class="value"> <?php echo $total; ?> </div>
        </div>
        <i class="fas fa-box"></i>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="col-xl-3 col-md-6">
      <div class="dashboard-card bg-green">
        <div>
          <div class="title"><strong>Total de Ventas</strong></div>
          <?php $totalven = number_format($totalventas['total'], 2, ',', '.');?>
          <div class="value">$ <?php echo $totalven; ?></div>
        </div>
        <i class="fas fa-dollar-sign"></i>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="col-xl-3 col-md-6">
      <div class="dashboard-card bg-orange">
        <div>
          <div class="title"><strong>Producto Bajo Stock</strong></div>
           <?php $totalstock = $totalbajo['total']; ?>
          <div class="value"><?php echo $totalstock; ?></div>
        </div>
        <i class="fa-solid fa-clipboard-list"></i>
      </div>
    </div>

    <!-- Card 4 -->
    <div class="col-xl-3 col-md-6">
      <div class="dashboard-card bg-red">
        <div>
          <div class="title"><strong>Cantidad de Perdidas</strong></div>
           <?php  $totalper = $totalperdidas['total'];  ?>
          <div class="value"> <?php echo $totalper; ?> </div>
        </div>
        <i class="fa-solid fa-file-arrow-down"></i>
      </div>
    </div>

  </div>
</div>


  </div>
   


<div class="container-fluid py-3">
 
  <!-- FILA 1 -->
  <div class="row g-4 mb-2">
      
  

    <!-- Tabla -->
    <div class="col-lg-6">
<div class="transaction-list shadow-sm border-0 DivOscuro1">

            <div class="card shadow-sm border-0 mb-4">
  <div class="card-header bg-gradient bg-primary text-white d-flex justify-content-between align-items-center">
    <h6 class="mb-0 text-white "><i class="fas fa-fire me-2"></i>Top 5 Productos Más Vendidos</h6>
    <span class="badge bg-light text-dark">Actualizado</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-borderless align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Producto</th>
            <th scope="col">Marca</th>
            <th scope="col">Presentación</th>
            <th scope="col" class="text-end">Vendidas</th>
            <th scope="col" class="text-end">Total $</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($topProductos as $index => $producto): ?>
            <tr>
              <td class="td-oscuro"><span class="badge bg-secondary"><?= $index + 1 ?></span></td>
              <td class="td-oscuro">
                <strong><?= htmlspecialchars($producto['producto']) ?></strong><br>
                <small class="text-muted">Codigo: <?= $producto['id_producto'] ?></small>
              </td>
              <td class="td-oscuro"><?= htmlspecialchars($producto['marca']) ?></td>
              <td class="td-oscuro"><?= htmlspecialchars($producto['presentacion']) ?></td>
              <td class="text-center td-oscuro">
                <span class="text-dark texto-secundario"><?= number_format($producto['total_vendido'], 0) ?></span>
              </td>
              <td class="text-end td-oscuro">
                <span class="badge rounded-pill bg-success fa-1x">$ <?= number_format($producto['total_dinero'], 2) ?></span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

   </div>

    </div>

    <!-- Gráfica -->
    <div class="col-lg-6">
          <div class="chart-container shadow-sm border-0 DivOscuro1">
                <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold m-0">Top 5 Productos Más Vendidos</h6>
            </div>

            <div class="position-relative">
                <canvas id="graficaTorta" height="80">

                </canvas>
            </div>
           </div>
    </div>

  

  </div>
 



      <!-- FILA 2 -->
      <!-- Gráfico -->
                    <div class="chart-container shadow-sm border-0 DivOscuro1">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                              <h6 class="fw-bold m-0">Movimientos del Mes</h6>
                              <div class="tooltip-custom fw-bold text-success" id="totalMes">
                                  Mes: $0
                              </div>
                          </div>

                          <div class="position-relative">
                              <canvas id="graficaMes" height="80"></canvas>
                          </div>

                    </div>



  <!-- FILA 3 -->
  <div class="row g-4 mb-2">

    <!-- Gráfica -->
    <div class="col-lg-6">
         <div class="chart-container shadow-sm border-0 DivOscuro1">
            
                <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold m-0">Producto con baja venta (Rotación lenta) ultimos 30 dias</h6>
            </div>

            <div class="position-relative">
               <table class="table table-hover mb-0">
            <thead class="table-color">
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Marca</th>
                    <th>Presentación</th>
                    <th>Vendidos</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($RotacionBaja30)): ?>
                    <?php $i=1; foreach($RotacionBaja30 as $p): ?>
                        <tr>
                            <td class="td-oscuro"><?= $i++ ?></td>
                            <td class="td-oscuro"><?= htmlspecialchars($p['producto']) ?></td>
                            <td class="td-oscuro"><?= htmlspecialchars($p['marca']) ?></td>
                            <td class="td-oscuro"><?= htmlspecialchars($p['presentacion']) ?></td>
                            <td class="td-oscuro">
                                <?php
                                  $color = $p['total_vendido'] == 0 ? 'danger fa-1x' : 
                                           ($p['total_vendido'] <= 10 ? 'warning text-dark fa-1x' : 'secondary fa-1x');
                                ?>
                                <span class="badge bg-<?= $color ?>">
                                    <?= $p['total_vendido'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">
                             No hay productos con baja rotación este mes
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </table>
            </div>
 
       </div>
    </div>

    <!-- Tabla -->
    <div class="col-lg-6">
      <div class="chart-container shadow-sm border-0 DivOscuro1">
          <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold m-0">Stock Recomendado para los 10 productos mas vendidos</h6>
                   
         </div>

        <div class="position-relative">


         <table class="table table-hover mb-0">
            <thead class="table-color">
                <tr>
                    <th>Cod.</th>
                    <th>Producto</th>
                    <th>Vendidos (30d)</th>
                    <th>Promedio / día</th>
                    <th>Stock Ideal</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($StockIdealTop)): ?>
                    <?php $i=1; foreach($StockIdealTop as $p): ?>
                        <tr>
                            <td class="td-oscuro"><?= $p['id_producto'] ?></td>
                            <td class="td-oscuro"><?= htmlspecialchars($p['producto']. ' - '.$p['marca'] ) ?></td>
                            <td class="text-center td-oscuro"><?= $p['total_vendido'] ?></td>
                            <td class="text-center td-oscuro"><?= round($p['promedio_diario']) ?></td>
                            <td  class="td-oscuro">
                                <span class="badge bg-success fa-1x">
                                    <?= $p['stock_ideal'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">
                            No hay datos disponibles
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
                     
         </div>         
    
       </div>
   </div>

  </div>






        
 
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <?php require_once 'vista/complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    


  <script>
const datos = <?php echo json_encode($DatosGrafica); ?>;
const totalMes = <?php echo json_encode($TotalMes['total_mes'] ?? 0); ?>;
const topProductos = <?php echo json_encode($topProductos); ?>;

</script>


<script>
const labels = [];
const valores = [];

datos.forEach(item => {
    labels.push(item.dia);
    valores.push(item.total_dia);
});

// Pintar total en la vista
document.getElementById('totalMes').innerHTML =
    'Mes: $' + Number(totalMes).toLocaleString();

new Chart(document.getElementById('graficaMes'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Ventas',
            data: valores,
              borderColor: '#2D5BFF',
                backgroundColor: 'rgba(45, 91, 255, 0.2)',
            tension: 0.45,
            fill: true,
            borderWidth: 2,
           pointRadius: 4,
                pointBackgroundColor: '#2D5BFF'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: ctx => '$ ' + ctx.parsed.y.toLocaleString()
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: value => '$' + value.toLocaleString()
                }
            }
        }
    }
});
</script>


<script>
const labelsTop = [];
const cantidadesTop = [];
const dineroTop = [];

topProductos.forEach(p => {
    labelsTop.push(`${p.producto} (${p.marca ?? ''} ${p.presentacion ?? ''})`);
    cantidadesTop.push(p.total_vendido);
    dineroTop.push(p.total_dinero);
});

new Chart(document.getElementById('graficaTorta'), {
    type: 'doughnut',
    data: {
        labels: labelsTop,
        datasets: [{
            data: cantidadesTop,
            borderWidth: 2,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        cutout: '30%',
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        const i = ctx.dataIndex;
                        return [
                            'Cantidad: ' + cantidadesTop[i],
                            'Total: $' + Number(dineroTop[i]).toLocaleString()
                        ];
                    }
                }
            }
        }
    }
});
</script>


</body>
</html>