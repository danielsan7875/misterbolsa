<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];
$vista="Compra"; $activo="3";

if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

require_once 'modelo/compra.php';
$objcompra = new Compra();

$registro = $objcompra->consultar();
 
if (isset($_POST['ajaxProducto'])) {

    $buscar = $_POST['buscarProducto'] ?? '';

    if ($buscar === '') {
        // Últimos 20 registrados
        $producto = $objcompra->ultimosProductos();
    } else {
        // Búsqueda normal
        $producto = $objcompra->buscarProducto($buscar);
    }

    echo json_encode($producto);
    exit;

} else if(isset($_POST['registrar'])){
  
if( !empty($_POST['id_producto'] && !empty($_POST['cantidad']) && !empty($_POST['fecha'])) ){

      $objcompra->set_Fecha($_POST['fecha']);

      $id_producto=$_POST['id_producto'];
      $cantidad=$_POST['cantidad'];

      $result=$objcompra->registrar($id_producto,$cantidad);
    
      echo json_encode($result);
     
  } else{
      echo json_encode(['respuesta' => 0, 'accion' => 'registrar']);
  }

} else if(isset($_POST['pdf'])){

    if (!empty($_POST['pdf'])) {
      $id_compra = $_POST['pdf'];
            if(!$id_compra){
                header('Location: ?pagina=compra');
            }
          require_once 'assets/pdf/reportecompra.php';
      }


} else if (isset($_POST['modificar'])) {

    $filtro = $_POST['filtro'] ?? null;
    $desde  = $_POST['desde'] ?? null;
    $hasta  = $_POST['hasta'] ?? null;

    $filtradofecha = $objcompra->consultarFiltrado($filtro, $desde, $hasta);

      // TEXTO DEL PERIODO
    if ($filtro == "15") {
        $periodo = "Últimos 15 días (Hoy y 14 días anteriores)";
    } elseif ($filtro == "mes") {
        $periodo = "Mes actual (" . date('F Y') . ")";
    } elseif (!empty($desde) && !empty($hasta)) {
        $periodo = "Desde " . date('d/m/Y', strtotime($desde)) .
                   " hasta " . date('d/m/Y', strtotime($hasta));
    } else {
        $periodo = "Todos los registros";
    }

    require_once("vista/complementos/consulta/compra.php");
} else if ($tipo_usuario == 1 || $tipo_usuario == 2) { // Validacion si es administrador entra
     require_once 'vista/compra.php';
} else {
     require_once 'vista/complementos/error/privilegio.php';
}
?>