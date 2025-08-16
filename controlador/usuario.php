<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];

require_once 'modelo/usuario.php';

    $objusuario = new Usuario ();
    
    $registro = $objusuario->consultar(); 
    $tipos = $objusuario->obtenerTipo(); 
    
    if(isset($_POST['registrar'])){
      if ( !empty($_POST['nombres']) && !empty($_POST['apellidos']) && !empty($_POST['usuario'])  && !empty($_POST['tipo_usuario'])  && !empty($_POST['clave'])){

          $objusuario->set_Nombres($_POST['nombres']);
          $objusuario->set_Apellidos($_POST['apellidos']);
          $objusuario->set_Usuario($_POST['usuario']);
          $objusuario->set_Clave($_POST['clave']);
          $objusuario->set_Tipo_usuario($_POST['tipo_usuario']);
        
          $result=$objusuario->registrar();
          echo json_encode($result);
      }
  }else if(isset($_POST['modificar'])){
      $modificar = $objusuario->buscar($_POST['modificar']);
      require_once ("vista/complementos/actualizar/usuario.php");

      }else if(isset($_POST['actualizar'])){
        $objusuario->set_Id_usuario($_POST['id_usuario']);
        $objusuario->set_Nombres($_POST['nombres']);
        $objusuario->set_Apellidos($_POST['apellidos']);
        $objusuario->set_Usuario($_POST['usuario']);
        $objusuario->set_Clave($_POST['clave']);
        $objusuario->set_Tipo_usuario($_POST['tipo_usuario']);
        $objusuario->set_Estatus($_POST['estatus']);

          $result=$objusuario->actualizar();
          echo json_encode($result);
  
  }else if(isset($_POST['eliminar'])){
      $id_usuario = $_POST['eliminar'];

      // Validar que no se intente eliminar el usuario que está en sesión
    if ($id_usuario == $_SESSION["id"]) {
        $res = array('respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No se puedes Eliminarte a ti mismo');
            echo json_encode($res);
    } else if ($id_usuario == 1) {
        $res = array('respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No se puedes Eliminar a este Usuario');
             echo json_encode($res);
    } else {

      $objusuario->set_Id_Usuario($id_usuario); 
      $result = $objusuario->eliminar();
      echo json_encode($result);
    }  
    
  } else if ($tipo_usuario == 1) { // Validacion si es administrador entra
        require_once 'vista/usuario.php';
  } else {
        require_once 'vista/complementos/error/privilegio.php';
  }
?>