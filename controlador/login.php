<?php
session_start();
//--------
require_once 'modelo/login.php';
$objlogin = new Login();
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
      
        foreach ($blacklist as $prohibida) {
            $pattern = '/\b' . preg_quote($prohibida, '/') . '\b/i'; 
            if (preg_match($pattern, $input)) {
                return false;
            }
        }
        return true;
    }
//----- 
if (isset($_POST['ingresar'])) {
    if (empty($_SESSION['id'])) { /* V1 */
        if (!empty($_POST['usuario']) && !empty($_POST['clave'])) {
        
            $clave = strtolower($_POST['clave']); $usuario = $_POST['usuario'];  $dolar = $_POST['dolar'];
        
                $campos = [
                    'dolar' => $dolar,
                    'usuario' => $usuario,
                    'clave' => $clave
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'ingresar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    //VALIDACION DE DATOS
                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{6,16}$/', $usuario)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'ingresar', 'text' => "Usuario inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[a-z0-9]{8,16}$/', $clave)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'ingresar', 'text' => "Clave inválida"]);
                        exit;
                    }

                        $datosLogin = [
                            'operacion' => 'verificar',
                                'datos' => [
                                    'usuario' => $usuario,
                                    'clave' => $clave
                                ]
                        ];
    
                         $resultado = $objlogin->procesarLogin(json_encode($datosLogin));
                        
                         if (is_array($resultado)) {
                            $msg = $resultado['text'] ?? 'Usuario y/o clave Incorrecta';
                            echo json_encode(['respuesta' => 0, 'accion' => 'ingresar', 'text' => $msg]);
                            exit;
                        }   
                        
                        if ($resultado) {
                                // Aquí también usamos flecha porque es un objeto stdClass
                                if ($resultado->estatus == 1) {
                                        $_SESSION["id"] = $resultado->id_usuario;
                                        $_SESSION["nombre"] = $resultado->nombre;
                                        $_SESSION["apellido"] = $resultado->apellido;
                                        $_SESSION["usuario"] = $resultado->usuario;
                                        $_SESSION['id_tipo'] = $resultado->id_tipo;
                                        $_SESSION['nombre_tipo'] = $resultado->nombre_tipo;
                                        $_SESSION['nivel'] = $resultado->nivel;
                                    
                                        if($dolar === 'no'){

                                            echo json_encode(['respuesta' => 1, 'accion' => 'ingresar']); // no se actualizado dolar y pasa a home
                                            exit;
                                        } else if(floatval($dolar) != 0.00){

                                            if (!preg_match('/^(?=(?:\D*\d){3,10}\D*$)\d+(?:[.,]\d+)?$/', $dolar)) {
                                                echo json_encode(['respuesta' => 0, 'accion' => 'ingresar', 'text' => "Precio del Dolar inválido"]);
                                                exit;
                                            }
                                                            
                                            $datosLogin = [
                                                    'operacion' => 'dolar',
                                                        'datos' => [
                                                            'dolar' => $dolar
                                                        ]
                                                ];
                            
                                            $resultado = $objlogin->procesarLogin(json_encode($datosLogin));

                                            if($resultado == 1){
                                                echo json_encode(['respuesta' => 2, 'accion' => 'ingresar']); // actualizo el dolar y pasa a home
                                                exit;
                                            }else{
                                                echo json_encode(['respuesta' => 1, 'accion' => 'ingresar']); // error para actualizar dolar no actualiza y pasa a home
                                                exit;
                                            }
                                        }              

                                    echo json_encode(['respuesta' => 1, 'accion' => 'ingresar']); // no hay internet para actualizar dolar y pasa a home
                                    exit;

                                }else if (isset($resultado->noactiva)) {
                                    echo json_encode(['respuesta' => 0,'accion' => 'ingresar','text' => 'Lo sentimos, su cuenta está suspendida. Por favor, póngase en contacto con el administrador.']);
                                    exit;
                                }

                            } else {               
                                echo json_encode(['respuesta' => 0,'accion' => 'ingresar','text' => 'Usuario y/o Constraseña Incorrecta' ]);
                                exit;
                            } 
        } else{
            echo json_encode(['respuesta' => 0, 'accion' => 'ingresar', 'text' => 'Datos Vacios']);
            exit;
        }  
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'ingresar', 'text' => 'Error en Session']);
        exit;
    }  
//----------
} else if (isset($_POST['cerrar'])) {
//----------    
    session_destroy(); // Se Cierra la Session
    header('Location: ?pagina=login');
    exit;
//---------
} else if(!empty($_SESSION['id'])){
//----------
    session_destroy();
     header('Location: ?pagina=login');
    exit;
//----------    
}else {
    require_once 'vista/login.php';
}