<?php
  session_start();
  if (empty($_SESSION["id"])){
    header("location:?pagina=login");
  } else if ($_SESSION["nivel"] == 3){
    header("location:?pagina=registroventa");
  }

  $vista="Inicio - Dashboard";  $activo="1"; $tipo_usuario = $_SESSION['nivel'];
//------
  require_once 'modelo/home.php';
  $objhome = new Home();
//------
  $dolar = $objhome->obtenerDolar(); 
//------  
  $totalProductos = $objhome->contarProductos();
//------  
  $totalCompra = $objhome->contarCompras();
//------  
  $totalVenta = $objhome->contarVentas();
//------  
  $registro = $objhome->consultarUltimasVentas();
//------  
  $DatosGrafica = $objhome->SemanasVentas();
//------  
  $TotalSemana = $objhome->TotalSemana();
//------  
  $TotalHoy = $objhome->TotalHoy();
//------  
  $TotalCantidadHoy = $objhome->contarVentasHoy();
//------
  require_once 'vista/home.php';
//------
?>