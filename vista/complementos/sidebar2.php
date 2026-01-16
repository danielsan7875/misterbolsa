<!-- BARRA LATERAL -->
    <aside class="sidebar" id="sidebar">
        <div class="nav-category">Inicio</div>
        <nav class="nav flex-column">
            <a href="?pagina=home" class="nav-link <?php echo ($activo === "1") ? 'active' : ''; ?>"><i class="fa-solid fa-house"></i> Inicio</a>
              <a href="?pagina=detalle" class="nav-link <?php echo ($activo === "2") ? 'active' : ''; ?>"><i class="fa-solid fa-chart-pie"></i> Finanzas</a>
        </nav>

        <div class="nav-category">Gestión Comercial</div>
        <nav class="nav flex-column">
          <a href="?pagina=compra" class="nav-link <?php echo ($activo === "3") ? 'active' : ''; ?>"><i class="fa-solid fa-receipt"></i> Compra</a>
            <a href="?pagina=venta" class="nav-link <?php echo ($activo === "4") ? 'active' : ''; ?>"><i class="fa-solid fa-cash-register"></i> Venta</a>
             <a href="?pagina=perdidas" class="nav-link <?php echo ($activo === "5") ? 'active' : ''; ?>"><i class="fas fa-chart-line"></i> Perdidas</a>  
        </nav>

        <div class="nav-category">Inventario</div>
             <nav class="nav flex-column">
                 <a href="?pagina=producto" class="nav-link <?php echo ($activo === "6") ? 'active' : ''; ?>"><i class="fa-solid fa-boxes-stacked"></i> Producto</a>
                    <a href="?pagina=marca" class="nav-link <?php echo ($activo === "7") ? 'active' : ''; ?>"><i class="fa-solid fa-tag"></i> marca</a>
                      <a href="?pagina=presentacion" class="nav-link <?php echo ($activo === "8") ? 'active' : ''; ?>"><i class="fa-solid fa-shapes"></i> Presentación</a>
        </nav>

        
        <div class="nav-category">Finanza</div>
             <nav class="nav flex-column">
                      <a href="?pagina=metodopago" class="nav-link <?php echo ($activo === "9") ? 'active' : ''; ?>"><i class="fa-solid fa-credit-card"></i> Metódo Pago</a>
                 <a href="?pagina=tasa" class="nav-link <?php echo ($activo === "10") ? 'active' : ''; ?>"><i class="fa-solid fa-money-bill-trend-up"></i> Tasa de Cambio</a>
             <a href="?pagina=cierre" class="nav-link <?php echo ($activo === "11") ? 'active' : ''; ?>"><i class="fa-solid fa-file-invoice-dollar"></i> Cierre Diario</a>
        </nav>

           <div class="nav-category">Administrar Usuario</div>
        <nav class="nav flex-column mb-5">
        
            <a href="?pagina=usuario" class="nav-link <?php echo ($activo === "12") ? 'active' : ''; ?>"><i class="fa-solid fa-user-group"></i> Usuario</a>
            <a href="?pagina=tipousuario" class="nav-link <?php echo ($activo === "13") ? 'active' : ''; ?>"><i class="fa-solid fa-user-tag"></i> Tipo de Usurario</a>
        </nav>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="main-content ">
        <div class="content-wrapper shadow-sm DivOscuro2">