<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];

if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

require_once 'modelo/producto.php';

$objproducto = new Producto();

$registro = $objproducto->consultar();
$marca = $objproducto->obtenerMarca();
$presentacion = $objproducto->obtenerPresentacion();
$dolar = $objproducto->obtenerDolar();

if(isset($_POST['registrar'])){
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
   

  }else if(isset($_POST['tasadolar'])){
    if(!empty($_POST['id_dolar']) && !empty($_POST['precios'])){

        $objproducto->set_Id_dolar($_POST['id_dolar']); 
        $objproducto->set_precios($_POST['precios']); 
 
        $result = $objproducto->tasadolar();
        echo json_encode($result);
    }
  } else {
    require_once 'vista/producto.php';
  }
    
?>