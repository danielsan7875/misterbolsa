<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'vista/complementos/head2.php'; ?>
    <title>Error 404</title>
   
</head>
<body>
    <?php require_once 'vista/complementos/nav2.php'; ?>
<?php require_once 'vista/complementos/sidebar2.php'; ?>

    <div class="transaction-list shadow-sm-0 DivOscuro1">

    <div class="error-container">
                <!-- Ilustración SVG Minimalista -->
                <div class="error-illustration">
                    <svg viewBox="0 0 500 350" xmlns="http://www.w3.org/2000/svg">
                        <rect x="50" y="50" width="400" height="250" rx="30" fill="#eef2ff" />
                        <circle cx="250" cy="150" r="60" fill="white" />
                        <path d="M220 180 Q250 140 280 180" stroke="#2D5BFF" stroke-width="8" fill="none" stroke-linecap="round" />
                        <circle cx="225" cy="130" r="5" fill="#2D5BFF" />
                        <circle cx="275" cy="130" r="5" fill="#2D5BFF" />
                        <path d="M100 100 L150 100 M350 100 L400 100" stroke="#d1d9e6" stroke-width="4" stroke-linecap="round" />
                        <path d="M100 250 L400 250" stroke="#d1d9e6" stroke-width="4" stroke-linecap="round" />
                    </svg>
                </div>

                <div class="error-code">404</div>
                <h1 class="error-title TextOscuroTitel">¡Vaya! Página no encontrada</h1>
                <p class="error-message">
                    Lo sentimos, la página que estás buscando parece haber sido movida o no existe. 
                    Verifica la URL o utiliza el botón inferior para volver al panel principal.
                </p>
                <a href="?pagina=home" class="btn-back-home">
                    <i class="fa-solid fa-house"></i> Volver al Inicio
                </a>
            </div>

        </div>      
<?php require_once 'vista/complementos/footer2.php'; ?>
</body>
</html>