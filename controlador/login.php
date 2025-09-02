<?php

session_start();

require_once 'modelo/login.php';

$objlogin = new Login();

if (isset($_POST['ingresar'])) {
    if (!empty($_POST['usuario']) && !empty($_POST['clave'])) {
        $usuario = $_POST['usuario'];
        $clave = $_POST['clave'];
         $dolar = $_POST['dolar'];

        $objlogin->set_Usuario($_POST['usuario']);
        $objlogin->set_Clave($_POST['clave']);
        
        $resultado = $objlogin->verificarUsuario();
        if ($resultado) {
            if ($resultado->estatus == 1) { 
                $_SESSION["id"] = $resultado->id_usuario;
                $_SESSION["nombre"] = $resultado->nombre;
                $_SESSION["apellido"] = $resultado->apellido;
                $_SESSION['id_tipo'] = $resultado->id_tipo;
                $_SESSION['nombre_tipo'] = $resultado->nombre_tipo;
                $_SESSION['nivel'] = $resultado->nivel;


                if(floatval($dolar) != 0.00){
                                      
                    $objlogin->set_Dolar($dolar);
                    $resul = $objlogin->Tasa();

                    if($resul == 1){
                        echo json_encode(['respuesta' => 2, 'accion' => 'ingresar']);
                        exit;
                    }else{
                        echo json_encode(['respuesta' => 1, 'accion' => 'ingresar']);
                        exit;
                    }
                }              

                 echo json_encode(['respuesta' => 1, 'accion' => 'ingresar']);
                 exit;

            }else if (isset($resultado->noactiva)) {
                
                    echo json_encode([
                    'respuesta' => 0,
                    'accion' => 'ingresar',
                    'text' => 'Lo sentimos, su cuenta está suspendida. Por favor, póngase en contacto con el administrador.'
                ]);
                exit;
            }
        } else {
                              
            echo json_encode([
                    'respuesta' => 0,
                    'accion' => 'ingresar',
                    'text' => 'Usuario y/o Constraseña Incorrecta'
                ]);
                exit;
        } 
    } 

}else if (isset($_POST['cerrar'])) {
    session_destroy(); // Se Cierra la Session
    header('Location: ?pagina=login');
    exit;

} else {
    require_once 'vista/login.php';
}