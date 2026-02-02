<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$vista="Mi Perfil";
//----------
require_once 'modelo/perfil.php';
$objperfil = new Perfil ();
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
if(isset($_POST["actualizar"])){ //---------------------------------- ACTUALIZAR DATOS 
//----------- 
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
            if ($_SESSION["nivel"] == 1) { /* V2 */    
                if(!empty($_POST['id_usuario']) && !empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['usuario'])){ // V3    

                $id_usuario = $_POST['id_usuario']; $nombre = ucfirst(strtolower($_POST['nombre']));  $apellido = ucfirst(strtolower($_POST['apellido']));  $usuario = $_POST['usuario'];

                $campos = [
                    'id_usuario' => $id_usuario,
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'usuario' => $usuario
                ];

                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    // -- Validacion de datos V5
                    if (!preg_match('/^[0-9]{1,3}$/', $id_usuario)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Usuario inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{4,100}$/', $apellido)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Nombre inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{4,100}$/', $nombre)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Nombre inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{6,16}$/', $usuario)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "usuario inválido"]);
                        exit;
                    }

                    $datosPerfil = [
                                'operacion' => 'actualizar',
                                    'datos' => [
                                        'id_usuario' => $id_usuario,
                                        'nombre' => $nombre,
                                        'apellido' => $apellido,
                                        'usuario' => $usuario
                                    ]
                        ];

                         $resultadoRegistro = $objperfil->procesarPerfil(json_encode($datosPerfil));
                            if ($resultadoRegistro['respuesta'] == 1) {
                                $consulta = $objperfil->consulta($_POST['id_usuario']);
                                  $_SESSION["nombre"]   = $consulta[0]['nombre'];
                                  $_SESSION["apellido"] = $consulta[0]['apellido'];
                                  $_SESSION["usuario"]  = $consulta[0]['usuario'];
                            }
                         echo json_encode($resultadoRegistro);
                         exit; 

            }else{
                echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'Datos Vacios']);
                exit;
            }
        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'Session no encontrada']);
        exit;
    }  
//-----------------    
} else if(isset($_POST["clave"])){
//----------- 
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
            if ($_SESSION["nivel"] == 1) { /* V2 */    
                if(!empty($_POST['id_usuario']) && !empty($_POST['actual']) && !empty($_POST['nueva']) && !empty($_POST['confirmar'])){ // V3    

                $id_usuario = $_POST['id_usuario'];  $claveActual = $_POST['actual'];  $nueva = $_POST['nueva'];   $confirmar = $_POST['confirmar'];
   
                $campos = [
                    'id_usuario' => $id_usuario,
                    'claveActual' => $claveActual,
                    'nueva' => $nueva,
                    'confirmar' => $confirmar
                ];

                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'clave', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    // -- Validacion de datos V5
                    if (!preg_match('/^[0-9]{1,3}$/', $id_usuario)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'clave', 'text' => "Usuario inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[a-z0-9]{8,16}$/', $nueva)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'clave', 'text' => "Clave nueva inválida"]);
                        exit;
                    }

                    if (!preg_match('/^[a-z0-9]{8,16}$/', $claveActual)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'clave', 'text' => "Clave Actual inválida"]);
                        exit;
                    }

                    if (!preg_match('/^[a-z0-9]{8,16}$/', $confirmar)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'clave', 'text' => "Clave confirmar inválida"]);
                        exit;
                    }

                    $datos = $objperfil->consultarClaveActual($id_usuario);

                     if (!$datos) {
                         echo json_encode(['respuesta' => 0, 'accion' => 'clave', 'text' => 'No se encuentra a usuario a modificar']);
                        exit;
                      }

                      if ($datos['clave'] !== $claveActual) {
                          echo json_encode(['respuesta' => 0, 'accion' => 'clave', 'text' => 'La clave actual es incorrecta']);
                          exit;
                      }

                      if ($nueva !== $confirmar) {
                          echo json_encode(['respuesta' => 0, 'accion' => 'clave', 'text' => 'La nueva nueva y confirmar no son iguales']);
                           exit;
                      }

                    $datosPerfil = [
                                'operacion' => 'clave',
                                    'datos' => [
                                        'id_usuario' => $id_usuario,
                                        'nueva' => $nueva
                                    ]
                        ];

                         $resultadoRegistro = $objperfil->procesarPerfil(json_encode($datosPerfil));
                         echo json_encode($resultadoRegistro);
                         exit; 

            }else{
                echo json_encode(['respuesta' => 0, 'accion' => 'clave', 'text' => 'Datos Vacios']);
                exit;
            }
        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'clave', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'clave', 'text' => 'Session no encontrada']);
        exit;
    }  
//-----------------  
} else {
  require_once 'vista/perfil.php';
}
?>