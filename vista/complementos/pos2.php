<div id="loading-screen">
<div id="wifi-loader">
    <svg class="circle-outer" viewBox="0 0 86 86">
        <circle class="back" cx="43" cy="43" r="40"></circle>
        <circle class="front" cx="43" cy="43" r="40"></circle>
        <circle class="new" cx="43" cy="43" r="40"></circle>
    </svg>
    <svg class="circle-middle" viewBox="0 0 60 60">
        <circle class="back" cx="30" cy="30" r="27"></circle>
        <circle class="front" cx="30" cy="30" r="27"></circle>
    </svg>
    <svg class="circle-inner" viewBox="0 0 34 34">
        <circle class="back" cx="17" cy="17" r="14"></circle>
        <circle class="front" cx="17" cy="17" r="14"></circle>
    </svg>
    <div class="text" data-text="Cargando"></div>
</div>
</div>

 <script>
  const idUsuario = <?php echo json_encode($_SESSION['id']); ?>;
</script>

<!-- 1. Navbar Superior -->
    <nav class="top-nav d-flex justify-content-between align-items-center sticky-top DivOscuro2">
        <div class="d-flex align-items-center gap-3">
            <form action="?pagina=registroventa" method="POST">
                <button type="submit" class="nav-btn active" name="salir" title="Inicio">
                    <i class="fa-solid fa-reply"></i>
                </button>
            </form>
            <button class="nav-btn" title="Historial" data-bs-toggle="modal" data-bs-target="#modalhistorial">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </button>
            <button class="nav-btn" title="Configuración" data-bs-toggle="modal" data-bs-target="#modalconfiguracion">
              <i class="fa-solid fa-gear"></i>
            </button>
            <?php 
            if (!$cierre) {
            echo'<button class="nav-btn" title="No Activo el Cierre">
              <i class="fa-solid fa-xmark text-danger"></i>
            </button>';
            }else{
           echo'<button class="nav-btn active" title="Cierre Activo">
              <i class="fa-solid fa-check text-success"></i>
            </button>';
            }
            ?>
        </div>

        <div class="sale-badge">
            <i class="fa-solid fa-cart-shopping" style="width: 16px; margin-right: 6px;"></i>
            Venta # <?php if (is_array($NroVenta)): ?>
                        <?php $NuevoNro = $NroVenta['id_venta'] + 1; ?> 
                            <?php echo $NuevoNro; ?>
                    <?php endif; ?>
            </div>

        <button class="nav-btn text-danger border-danger-subtle" title="Cerrar Sesión" data-bs-toggle="modal" data-bs-target="#modalcerrar">
            <i class="fa-solid fa-right-from-bracket"></i>
        </button>
    </nav>