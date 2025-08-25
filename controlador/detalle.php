<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];


require_once 'modelo/detalle.php';
$objdetalle = new Detalle();

$totalProductos = $objdetalle->contarProductos();
$totalventas = $objdetalle->sumarVentas();
$totalperdidas = $objdetalle->cantidadPerdida();
$totalbajo = $objdetalle->contarProductosBajoStock();
$topProductos = $objdetalle->obtenerTopProductosVendidos();


require_once 'vista/detalle.php';
?>