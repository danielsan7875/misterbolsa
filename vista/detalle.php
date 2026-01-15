<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head2.php'; ?>
    <title>Detalle de Producto | Mister Bolsa C.A</title>
</head>

<body>
  
  <?php require_once 'vista/complementos/nav2.php'; ?>
  <?php require_once 'vista/complementos/sidebar2.php'; ?>
 
  
   
    <div class="container-fluid">
  <!--|||||||||||||||   Fin de Sidebar y Nav  |||||||||||||||||||||||||||-->


<div class="container my-4">
  <div class="row g-4">

    <!-- Card 1 -->
    <div class="col-md-6 col-xl-3">
      <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center">
          <div class="me-3">
            <i class="fas fa-box fa-2x text-primary"></i>
          </div>
          <div>
            <h6 class="card-title mb-1 texto-principal">Productos</h6>
              <?php 
              $total = $totalProductos['total']; 
              ?>
            <h4 class="fw-bold mb-0 texto-secundario"> <?php echo $total; ?></h4>
          
          </div>
        </div>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="col-md-6 col-xl-3">
      <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center">
          <div class="me-3">
            <i class="fa-solid fa-clipboard-list fa-2x text-warning"></i>
          </div>
          <div>
              <?php 
              $totalstock = $totalbajo['total']; 
              ?>
            <h6 class="card-title mb-1 texto-principal">Bajo Stock</h6>
            <h4 class="fw-bold mb-0 texto-secundario"><?php echo $totalstock; ?></h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="col-md-6 col-xl-3">
      <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center">
          <div class="me-3">
            <i class="fas fa-dollar-sign fa-2x text-success"></i>
          </div>
          <div>
             <?php 
              $totalven = number_format($totalventas['total'], 2, '.', '');
              ?>
            <h6 class="card-title mb-1 texto-principal">Ventas Totales</h6>
            <h4 class="fw-bold mb-0 texto-secundario">$ <?php echo $totalven; ?></h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 4 -->
    <div class="col-md-6 col-xl-3">
      <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center">
          <div class="me-3">
          
            <i class="fa-solid fa-file-arrow-down fa-2x text-danger"></i>
          </div>
          <div>
            <?php 
              $totalper = $totalperdidas['total']; 
              ?>
            <h6 class="card-title mb-1 texto-principal">Perdidas</h6>
            <h4 class="fw-bold mb-0 texto-secundario"><?php echo $totalper; ?></h4>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>


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
            <th scope="col" class="text-end">Cantidad Vendida</th>
            <th scope="col" class="text-end">Total $</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($topProductos as $index => $producto): ?>
            <tr>
              <td class="bg-s"><span class="badge bg-secondary"><?= $index + 1 ?></span></td>
              <td class="bg-s">
                <strong><?= htmlspecialchars($producto['producto']) ?></strong><br>
                <small class="text-muted">ID: <?= $producto['id_producto'] ?></small>
              </td>
              <td class="bg-s"><?= htmlspecialchars($producto['marca']) ?></td>
              <td class="bg-s"><?= htmlspecialchars($producto['presentacion']) ?></td>
              <td class="text-end bg-s">
                <span class="text-dark texto-secundario"><?= number_format($producto['total_vendido'], 0) ?></span>
              </td>
              <td class="text-end bg-s">
                <span class="badge rounded-pill bg-success text-dark fw-bold">$ <?= number_format($producto['total_dinero'], 2) ?></span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


        
 
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <?php require_once 'vista/complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    
</body>
</html>