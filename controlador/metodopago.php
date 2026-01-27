<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
} else if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

$tipo_usuario = $_SESSION['nivel']; $vista="Metodo de Pago"; $activo="9";

require_once 'modelo/metodopago.php';
$objmetodopago = new Metodopago();
//----------
    function validarEntradaSQL($input) {
        // Si es array → validar cada elemento
        if (is_array($input)) {
            foreach ($input as $valor) {
                if (!validarEntradaSQL($valor)) {
                    return false;
                }
            }
            return true;
        }
        // Convertir a string por seguridad
        $input = (string)$input;

        $blacklist = [
            'SELECT', 'INSERT', 'UPDATE', 'DELETE', 'DROP', 'TRUNCATE', 'ALTER',
            'CREATE', 'RENAME', 'REPLACE', 'UNION', 'JOIN', 'WHERE', 'HAVING',
            'FROM', 'TABLE', 'DATABASE', 'SCHEMA', 'GRANT', 'REVOKE',
            '--', ';', '#', '/*', '*/', '@@', '@', 'CHAR', 'CAST', 'CONVERT',
            'EXEC', 'EXECUTE', 'xp_', 'sp_', 'OR', 'AND'
        ];
        $inputUpper = strtoupper($input);
        foreach ($blacklist as $prohibida) {
            if (strpos($inputUpper, $prohibida) !== false) {
                return false;
            }
        }
        return true;
    }
//-----
if (isset($_POST['consultar'])) {
//----    
    $registro = $objmetodopago->consultar();
    echo json_encode($registro);
//----
} else if(isset($_POST['activar'])){ //----------------------------- ACTIVAR
//----
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { // V1
        if ($_SESSION["nivel"] == 1) { // V2
            if( !empty($_POST['activar'])){ // V3  

            $id_metodopago = $_POST['activar'];
                
                $campos = [
                    'id_metodopago' => $id_metodopago
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'activar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    //VALIDACION DE DATOS
                     if (!preg_match('/^[0-9]{1,3}$/', $id_metodopago)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'activar', 'text' => "producto inválido"]);
                        exit;
                    }

                        $datosPago = [
                                'operacion' => 'activar',
                                    'datos' => [
                                        'id_metodopago' => $id_metodopago
                                    ]
                        ];

                         $resultadoRegistro = $objmetodopago->procesarPago(json_encode($datosPago));
                         echo json_encode($resultadoRegistro);
                         exit; 
    
            } else{
                echo json_encode(['respuesta' => 0, 'accion' => 'activar', 'text' => 'Datos Vacios']);
                exit;
            }
        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'activar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'activar', 'text' => 'Session no encontrada']);
        exit;
    }
//-----
}else if(isset($_POST['desactivar'])){//------------------------------ DESACTIVAR
//----   
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { // V1
        if ($_SESSION["nivel"] == 1) { // V2
            if( !empty($_POST['desactivar'])){ // V3  

            $id_metodopago = $_POST['desactivar'];
                
                $campos = [
                    'id_metodopago' => $id_metodopago
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'desactivar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    //VALIDACION DE DATOS
                     if (!preg_match('/^[0-9]{1,3}$/', $id_metodopago)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'desactivar', 'text' => "producto inválido"]);
                        exit;
                    }

                        $datosPago = [
                                'operacion' => 'desactivar',
                                    'datos' => [
                                        'id_metodopago' => $id_metodopago
                                    ]
                        ];

                         $resultadoRegistro = $objmetodopago->procesarPago(json_encode($datosPago));
                         echo json_encode($resultadoRegistro);
                         exit; 
    
            } else{
                echo json_encode(['respuesta' => 0, 'accion' => 'desactivar', 'text' => 'Datos Vacios']);
                exit;
            }
        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'desactivar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'desactivar', 'text' => 'Session no encontrada']);
        exit;
    }
//---
} else if ($tipo_usuario == 1 || $tipo_usuario == 2) {
//----
    require_once 'vista/metodopago.php';
//----
} else {
//------    
    require_once 'vista/complementos/error/privilegio.php';
//------
}
?>