<?php

session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
} else  if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

$tipo_usuario = $_SESSION['nivel'];  $vista="Usuario"; $activo="12";

require_once 'modelo/usuario.php';
$objusuario = new Usuario ();
//-----
if (!isset($_SESSION['limite_usuario'])) {
    $_SESSION['limite_usuario'] = 100;
}
//--------
if (isset($_POST['ver_mas'])) {
    $_SESSION['limite_usuario'] += 100;
    header("location:?pagina=usuario");
    exit;
}
//------------
$registro = $objusuario->consultar($_SESSION['limite_usuario']);
$total_registros = $objusuario->contarTotal(); 
//-----------    
$tipos = $objusuario->obtenerTipo(); 
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
if(isset($_POST['registrar'])){ //---------------------- REGISTRAR USUARIO
//----
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */    
            if(!empty($_POST['nombres']) && !empty($_POST['apellidos']) && !empty($_POST['usuario']) && !empty($_POST['clave']) && !empty($_POST['tipo_usuario'])){
            
             $nombre = ucfirst(strtolower($_POST['nombres']));  $apellido = ucfirst(strtolower($_POST['apellidos'])); $usuario = $_POST['usuario'];
             $id_tipo = $_POST['tipo_usuario'];  $clave = $_POST['clave'];
 
                $campos = [
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'usuario' => $usuario,
                    'clave' => $clave,
                    'id_tipo' => $id_tipo
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    //VALIDACION DE DATOS
                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{4,100}$/', $nombre)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Nombre inválido"]);
                        exit;
                    }
                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{4,100}$/', $apellido)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Apellido inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{6,16}$/', $usuario)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Usuario inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $id_tipo)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Tipo Usuario inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[a-z0-9]{8,16}$/', $clave)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Clave inválida"]);
                        exit;
                    }

                        $datosUsuario = [
                                'operacion' => 'registrar',
                                    'datos' => [
                                        'nombre' => $nombre,
                                        'apellido' => $apellido,
                                        'usuario' => $usuario,
                                        'clave' => $clave,
                                        'id_tipo' => $id_tipo
                                    ]
                        ];

                         $resultadoRegistro = $objusuario->procesarUsuario(json_encode($datosUsuario));
                         echo json_encode($resultadoRegistro);
                         exit; 
        
            } else{
                echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Datos Vacios']);
                exit;
            }
        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Session no encontrada']);
        exit;
    }
//---------------      
}else if(isset($_POST['modificar'])){ //-------------------- PARA BUSCAR LOS DATOS EL USUARIO
//---------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */    
            if(!empty($_POST['modificar'])){ // V3
            
            $editar = $_POST['modificar'];

                $campos = [
                    'nombre' => $editar
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    // -- Validacion de datos V5
                    if (!preg_match('/^[0-9]{1,3}$/', $editar)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "producto inválido"]);
                        exit;
                    }
                
                    $modificar = $objusuario->buscar($editar);
                    require_once ("vista/complementos/actualizar/usuario.php");

            }else{
                echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Datos Vacios']);
                exit;
            }
        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Session no encontrada']);
        exit;
    }
//----------------      
}else if(isset($_POST['actualizar'])){ //------------------------- ACTUALIZAR DATOS DEL USAURIO
//---------------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */    
            if(!empty($_POST['id_usuario']) &&!empty($_POST['nombres']) && !empty($_POST['apellidos']) && !empty($_POST['usuario']) && !empty($_POST['usuario_actual'])
               && !empty($_POST['clave']) && !empty($_POST['tipo_usuario']) && !empty($_POST['estatus'])){
            
             $nombre = ucfirst(strtolower($_POST['nombres']));  $apellido = ucfirst(strtolower($_POST['apellidos'])); $usuario = $_POST['usuario'];
             $id_tipo = $_POST['tipo_usuario'];  $clave = $_POST['clave']; $estatus = $_POST['estatus']; $id_usuario = $_POST['id_usuario']; $usuario_actual = $_POST['usuario_actual'];
 
                 $campos = [
                    'id_usuario' => $id_usuario,
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'usuario' => $usuario,
                    'clave' => $clave,
                    'id_tipo' => $id_tipo,
                    'estatus' => $estatus,
                    'usuario_actual' => $usuario_actual
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    //VALIDACION DE DATOS
                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{4,100}$/', $nombre)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Nombre inválido"]);
                        exit;
                    }
                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{4,100}$/', $apellido)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Apellido inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{6,16}$/', $usuario)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Usuario inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{6,16}$/', $usuario_actual)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Usuario inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $id_tipo)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Tipo Usuario inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $id_usuario)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Tipo Usuario inválido"]);
                        exit;
                    }

                     if (!preg_match('/^[0-9]{1}$/', $estatus)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Estatus inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[a-z0-9]{8,16}$/', $clave)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Clave inválida"]);
                        exit;
                    }

                        $datosUsuario = [
                                'operacion' => 'actualizar',
                                    'datos' => [
                                        'id_usuario' => $id_usuario,
                                        'nombre' => $nombre,
                                        'apellido' => $apellido,
                                        'usuario' => $usuario,
                                        'clave' => $clave,
                                        'id_tipo' => $id_tipo,
                                        'estatus' => $estatus,
                                        'usuario_actual' => $usuario_actual
                                    ]
                        ];

                         $resultadoRegistro = $objusuario->procesarUsuario(json_encode($datosUsuario));
                         echo json_encode($resultadoRegistro);
                         exit; 
        
            } else{
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
}else if(isset($_POST['eliminar'])){ //----------------------------- ELIMINAR USUARIO (FISICO)
//---------------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */    
            if(!empty($_POST['eliminar'])){ // V3
            
            $id_usuario = $_POST['eliminar'];
    
                $campos = [
                    'id_usuario' => $id_usuario
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    // -- Validacion de datos V5
                    if (!preg_match('/^[0-9]{1,3}$/', $id_usuario)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => "Marca inválido"]);
                        exit;
                    }
                      // Validar que no se intente eliminar el usuario que está en sesión
                      if ($id_usuario == $_SESSION["id"]) {
                         echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No se puedes Eliminar a ti mismo']);
                         exit;
                      } else if ($id_usuario == 1 || $id_usuario == 2) {
                          echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No se puedes Eliminar a este Usuario']);
                          exit;
                      } else {
                
                        $datosUsuario = [
                                'operacion' => 'eliminar',
                                    'datos' => [
                                        'id_usuario' => $id_usuario
                                    ]
                        ];

                         $resultadoRegistro = $objusuario->procesarUsuario(json_encode($datosUsuario));
                         echo json_encode($resultadoRegistro);
                         exit; 
                      }  
            }else{
                echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'Datos Vacios']);
                exit;
            }
        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'Session no encontrada']);
        exit;
    }
//------------     
} else if ($tipo_usuario == 1) { // Validacion si es administrador entra
        require_once 'vista/usuario.php'; //------------------------- VISTA
} else {
        require_once 'vista/complementos/error/privilegio.php';
}
?>