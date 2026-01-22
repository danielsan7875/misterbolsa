<?php

session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$vista="Producto - Inventario"; $activo="6";
$tipo_usuario = $_SESSION['nivel'];

if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

require_once 'modelo/producto.php';

$objproducto = new Producto();

 // 200 por defecto
    if (!isset($_SESSION['limite_producto'])) {
        $_SESSION['limite_producto'] = 200;
    }

    //  "ver más"
    if (isset($_POST['ver_mas'])) {
        $_SESSION['limite_producto'] += 100; // Aumenta 100 registros
        header("location:?pagina=usuario");
        exit;
    }

    $registro = $objproducto->consultar($_SESSION['limite_producto']);
    $total_registros = $objproducto->contarTotal(); 

   
    $presentacion = $objproducto->obtenerPresentacion();

    $dolar = $objproducto->obtenerDolar();

    $StockMenor = $objproducto->StockMenor();

if (isset($_POST['ajaxMarca'])) {

    $buscar = $_POST['buscarMarca'] ?? '';

    if ($buscar === '') {
        // 🔹 Últimos 20 registrados
        $marca = $objproducto->ultimasMarcas();
    } else {
        // 🔹 Búsqueda normal
        $marca = $objproducto->buscarMarca($buscar);
    }

    echo json_encode($marca);
    exit;

} else if (isset($_POST['ajaxPresentacion'])) {

    $buscar2 = $_POST['buscarPresentacion'] ?? '';

    if ($buscar2 === '') {
        // 🔹 Últimos 20 registrados
        $presentacion = $objproducto->ultimasPresentacion();
    } else {
        // 🔹 Búsqueda normal
        $presentacion = $objproducto->buscarPresentacion($buscar2);
    }

    echo json_encode($presentacion);
    exit;

}else if(isset($_POST['registrar'])){
   if(!empty($_POST['nombre'])){

       $objproducto->set_Nombre($_POST['nombre']);
       $objproducto->set_Marca($_POST['marca']);
       $objproducto->set_Presentacion($_POST['presentacion']);
       $objproducto->set_Stock_min($_POST['stock_min']);
       $objproducto->set_Precio($_POST['precio']);
       $objproducto->set_Preciomayor($_POST['preciomayor']);
       $objproducto->set_Cantidadmayor($_POST['cantidadmayor']);
   
       $result=$objproducto->registrar();
       echo json_encode($result);

   }
}else if(isset($_POST['modificar'])){
   $modificar = $objproducto->buscar($_POST['modificar']);
   require_once ("vista/complementos/actualizar/producto.php");

   }else if(isset($_POST['actualizar'])){
    $objproducto->set_Id_producto($_POST['id_producto']);
    $objproducto->set_Nombre($_POST['nombre']);
    $objproducto->set_Marca($_POST['marca']);
    $objproducto->set_Presentacion($_POST['presentacion']);
    $objproducto->set_Stock_min($_POST['stock_min']);
    $objproducto->set_Precio($_POST['precio']);
    $objproducto->set_Preciomayor($_POST['preciomayor']);
    $objproducto->set_Cantidadmayor($_POST['cantidadmayor']);
    $objproducto->set_Stock($_POST['stock']);
    $objproducto->set_Estatus($_POST['estatus']);

    $result=$objproducto->actualizar();
    echo json_encode($result);

}else if(isset($_POST['eliminar'])){
   $id_producto = $_POST['eliminar'];
   $stock = $_POST['stock'];

   if ($stock == 0){
        $objproducto->set_Id_producto($id_producto); 

        $result = $objproducto->eliminar();
        echo json_encode($result);
   
   } else{
        $res = array('respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No puedes Eliminar un producto con stock disponible');
        echo json_encode($res);
        
  }
   

  }else if(isset($_POST['accion_estatus'])){


    $id_producto = $_POST['id_producto'];
    $accion = $_POST['accion_estatus'];

    $objproducto->set_Id_producto($id_producto);

    if ($accion === "activar") {
        $result = $objproducto->activar(); // Debes tener este método en tu clase
    } else {
        $result = $objproducto->desactivar();
    }

    echo json_encode($result);
    exit; // Importante para que no se filtre basura de la vista

  } else if(isset($_POST['pdfcompleto'])){
      require_once 'assets/pdf/reporteproducto.php';
  } else{
    require_once 'vista/producto.php';
  }
    
?>