<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];
 $vista="Tipo de Usuario"; $activo="13";

if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

    require_once 'modelo/tipousuario.php';
    $objtipousuario = new Tipousuario();

    // 200 por defecto
    if (!isset($_SESSION['limite_tipo'])) {
        $_SESSION['limite_tipo'] = 100;
    }

    //  "ver más"
    if (isset($_POST['ver_mas'])) {
        $_SESSION['limite_tipo'] += 100; // Aumenta 100 registros
        header("location:?pagina=tipousuario");
        exit;
    }

    $total_registros = $objtipousuario->contarTotal(); 

    if (isset($_POST['consultar'])) {
        $registro = $objtipousuario->consultar($_SESSION['limite_tipo']);
        echo json_encode($registro);

    } else if(isset($_POST['registrar'])){
        if( !empty($_POST['nombre']) && !empty($_POST['nivel'])){

            $objtipousuario->set_Nombre($_POST['nombre']);
            $objtipousuario->set_Nivel($_POST['nivel']);
            
            $result=$objtipousuario->registrar();
            echo json_encode($result);

        }
        
    }else if(isset($_POST['modificar'])){
        $modificar = $objtipousuario->buscar($_POST['modificar']);
        require_once ("vista/complementos/actualizar/tipousuario.php");
    
    }else if(isset($_POST['actualizar'])){
            $id_tipo = $_POST['id_tipo'];
            $nombre = $_POST['nombre'];
            $nivel = $_POST['nivel'];
            $estatus = $_POST['estatus'];

            $objtipousuario->set_Id_Tipo($id_tipo); 
            $objtipousuario->set_Nombre($nombre);
            $objtipousuario->set_Nivel($nivel);
            $objtipousuario->set_Estatus($estatus);

            $result=$objtipousuario->actualizar();
            echo json_encode($result);
    
    } else if(isset($_POST['eliminar'])){
        $id_tipo = $_POST['eliminar'];
        
        if ($id_tipo == 1) {
            $res = array('respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No se puede eliminar el registro con ID 1');
            echo json_encode($res);
        }  else {

        $objtipousuario->set_Id_Tipo($id_tipo); 
       $result = $objtipousuario->eliminar();
       echo json_encode($result);
    }
  
  } else if ($tipo_usuario == 1) { // Validacion si es administrador entra
         require_once 'vista/tipousuario.php';
  } else {
         require_once 'vista/complementos/error/privilegio.php';
  }

?>