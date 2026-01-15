<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}

$tipo_usuario = $_SESSION['nivel'];
require_once 'modelo/registroventa.php';
$objventa = new Registroventa();

$registro = $objventa->consultar();
$producto = $objventa->obtenerProducto();
$dolar = $objventa->obtenerDolar();  
$metodo = $objventa->obtenerMetodopago();  

if (isset($_POST['registrar'])) {

    if (!empty($_POST['fecha']) && !empty($_POST['total_dl'])) {

        $objventa->set_Total_Dl($_POST['total_dl']);
        $objventa->set_Total_Bs($_POST['total_bs']);
        $objventa->set_Fecha($_POST['fecha']);

        // Arrays de productos
        $id_producto   = $_POST['id_producto'];
        $cantidad      = $_POST['cantidad'];
        $precio        = $_POST['precio_unitario'];
        $maode         = $_POST['maode'];

        // Arrays de pagos
        $id_metodopago = $_POST['id_metodopago'];
        $monto_dolares = $_POST['monto_dolares'];
        $monto_bs      = $_POST['monto_bs'];
        $referencia    = $_POST['referencias'];

        // Registrar
        $result = $objventa->registrar(
            $id_producto,
            $cantidad,
            $precio,
            $maode,
            $id_metodopago,
            $monto_dolares,
            $monto_bs,
            $referencia
        );

        echo json_encode($result);

    } else {
        $_SESSION['message'] = [
            'title' => 'VACÍO',
            'text'  => 'Faltan datos obligatorios',
            'icon'  => 'info'
        ];
        header('Location: ?pagina=venta');
    }
}
 else{
  require_once 'vista/registroventa2.php';
}


?>