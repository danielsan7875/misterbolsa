<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$vista="Mi Perfil";

require_once 'modelo/perfil.php';

  $objperfil = new Perfil ();


  if(isset($_POST["actualizar"])){

      $objperfil->set_Id_usuario($_POST['id_usuario']);
      $objperfil->set_Nombres($_POST['nombre']);
      $objperfil->set_Apellidos($_POST['apellido']);
      $objperfil->set_Usuario($_POST['usuario']);

      $result=$objperfil->actualizar();

      if ($result['respuesta'] == 1) {

          $consulta = $objperfil->consulta($_POST['id_usuario']);

         $_SESSION["nombre"]   = $consulta[0]['nombre'];
          $_SESSION["apellido"] = $consulta[0]['apellido'];
          $_SESSION["usuario"]  = $consulta[0]['usuario'];

      }


      echo json_encode($result);
      exit;

  } else if(isset($_POST["clave"])){

    $id_usuario = $_POST['id_usuario'];
    $claveActual = $_POST['actual'];
    $nueva = $_POST['nueva'];
    $confirmar = $_POST['confirmar'];

       $datos = $objperfil->consultarClaveActual($id_usuario);

        if (!$datos) {
              echo json_encode(['respuesta'=>0,'accion'=>'clave']);
            exit;
        }

    
        if ($datos['clave'] !== $claveActual) {
              echo json_encode(['respuesta'=>0,'accion'=>'clave']);
            exit;
        }

        
        if ($nueva !== $confirmar) {
            echo json_encode(['respuesta'=>0,'accion'=>'clave']);
            exit;
        }

      $actualizado = $objperfil->actualizarClave($id_usuario, $nueva);
      echo json_encode($actualizado);
      exit;



  } else{

  } 

require_once 'vista/perfil.php';
?>