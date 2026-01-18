<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'vista/complementos/head2.php'; ?>
    <title>Mi Perfil | Mister Bolsa C.A</title>
   <style>
       
   </style>
</head>
<body>
    <?php require_once 'vista/complementos/nav2.php'; ?>
<?php require_once 'vista/complementos/sidebar2.php'; ?>


   <div class="transaction-list shadow-sm border-0 DivOscuro1">

<!-- 1. Banner Superior -->
<div class="profile-cover"></div>

<div class="container">
    <!-- 2. Encabezado con Avatar superpuesto -->
    <div class="profile-header-content px-3 px-md-0">
        <div class="avatar-wrapper">
            <!-- Icono de persona en círculo como pidió el diseño -->
            <div class="avatar-icon-placeholder rounded-circle shadow">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>
        <div class="user-info-text ">
            <h2 class="TextOscuro"><?php echo $_SESSION["nombre"].' '.$_SESSION["apellido"];?></h2>
            <p class="TextOscuro"><i class="bi bi-briefcase me-2"></i>Cargo: <?php echo $_SESSION["nombre_tipo"];?></p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card card1 mb-5 DivOscuro2">
                <div class="card-header card-header1 d-flex align-items-center DivOscuro2">
                    <i class="bi bi-gear-fill me-2 text-primary"></i> Infomacion Personal
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-4">Puede cambiar tu información personal aquí.</p>
                    
                    <form>
                        <!-- Campos: Nombre, Apellido y Usuario -->
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="first_name" value="<?php echo $_SESSION["nombre"];?>" placeholder="Tu nombre">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-control" name="last_name" value="<?php echo $_SESSION["apellido"];?>" placeholder="Tu apellido">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nombre de Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">@</span>
                                    <input type="text" class="form-control border-start-0 ps-0" name="username" value="<?php echo $_SESSION["usuario"];?>" placeholder="usuario">
                                </div>
                            </div>
                        
                        </div>

                          <!-- Botones de Acción -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary">Limpiar</button>
                            <button type="submit" class="btn btn-primary shadow-sm">Guardar Cambios</button>
                        </div>


                        <!-- 3. Sección de Cambio de Clave -->
                        <div class="password-section DivOscuro2">
                            <h5 class="fw-bold mb-3"><i class="bi bi-lock-fill me-2 text-primary"></i>Cambiar Contraseña</h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Contraseña Actual</label>
                                    <input type="password" class="form-control" placeholder="••••••••">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" placeholder="Nueva clave">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" placeholder="Repite la nueva clave">
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary">Limpiar</button>
                            <button type="submit" class="btn btn-primary shadow-sm">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
<?php require_once 'vista/complementos/footer2.php'; ?>
</body>
</html>