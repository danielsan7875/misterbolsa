<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
} else if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}
//------
$tipo_usuario = $_SESSION['nivel']; $vista="Detalles"; $activo="2";
//------
require_once 'modelo/detalle.php';
$objdetalle = new Detalle();
//-----
  //------
    $totalProductos = $objdetalle->contarProductos();
  //------  
    $totalventas = $objdetalle->sumarVentas();
  //------  
    $totalperdidas = $objdetalle->cantidadPerdida();
  //------  
    $totalbajo = $objdetalle->contarProductosBajoStock();
  //------  
    $topProductos = $objdetalle->obtenerTopProductosVendidos();
  //------
    $RotacionBaja30 = $objdetalle->obtenerRotacionBaja30Dias(20, 30, 10);
  //------
    $StockIdealTop = $objdetalle->obtenerStockIdealTop(30, 7, 10);
  //------
    $DatosGrafica = $objdetalle->VentasMesActual();
  //------  
    $TotalMes = $objdetalle->TotalMesActual();
  //------
//-----  
require_once 'vista/detalle.php';
?>