<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
} else if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

$tipo_usuario = $_SESSION['nivel'];  $vista="Tipo de Usuario"; $activo="13";

require_once 'modelo/tipousuario.php';
$objtipousuario = new Tipousuario();
//-----------   
if (!isset($_SESSION['limite_tipo'])) { // 200 por defecto
        $_SESSION['limite_tipo'] = 100;
}
//-----
if (isset($_POST['ver_mas'])) {
    $_SESSION['limite_tipo'] += 100; // Aumenta 100 registros
    header("location:?pagina=tipousuario");
    exit;
}
//--------
$total_registros = $objtipousuario->contarTotal(); 
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
if (isset($_POST['consultar'])) { //------------------------- CONSULTA DE DATOS DINAMICO
//-----------    
        $registro = $objtipousuario->consultar($_SESSION['limite_tipo']);
        echo json_encode($registro);
//-----------
} else if(isset($_POST['registrar'])){ //------------------------- REGISTRAR
//-----------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { // V1
        if ($_SESSION["nivel"] == 1) { // V2
            if( !empty($_POST['nombre']) && !empty($_POST['nivel'])){ // V3

                $nombre = ucfirst(strtolower($_POST['nombre'])); $nivel = $_POST['nivel'];

                $campos = [
                    'nombre' => $nombre,
                    'nivel' => $nivel
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

                    if (!preg_match('/^[1-3]{1}$/', $nivel)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "nivel inválido"]);
                        exit;
                    }

                    $datosTipo = [
                                'operacion' => 'registrar',
                                    'datos' => [
                                        'nombre' => $nombre,
                                        'nivel' => $nivel
                                    ]
                        ];

                         $resultadoRegistro = $objtipousuario->procesarTipo(json_encode($datosTipo));
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
//-----------        
}else if(isset($_POST['modificar'])){ //-------------------- PARA BUSCAR LOS DATOS PARA EDITAR
//-----------   
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */    
            if(!empty($_POST['modificar'])){ // V3
                $editar = $_POST['modificar'];

                $campos = [
                    'editar' => $editar
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
                    
                    $modificar = $objtipousuario->buscar($editar);
                    require_once ("vista/complementos/actualizar/tipousuario.php");

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
//-----------    
}else if(isset($_POST['actualizar'])){ //------------------------- ACTUALIZAR DATOS 
//----------- 
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
            if ($_SESSION["nivel"] == 1) { /* V2 */    
                if(!empty($_POST['id_tipo']) && !empty($_POST['nombre']) && !empty($_POST['estatus']) && !empty($_POST['nivel'])){ // V3    

                $id_tipo = $_POST['id_tipo']; $nombre = ucfirst(strtolower($_POST['nombre']));  $nivel = $_POST['nivel'];    $estatus = $_POST['estatus'];

                $campos = [
                    'id_tipo' => $id_tipo,
                    'nombre' => $nombre,
                    'estatus' => $estatus,
                    'nivel' => $nivel
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    // -- Validacion de datos V5
                    if (!preg_match('/^[0-9]{1,3}$/', $id_tipo)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Tipo usuario inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1}$/', $estatus)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "estatus inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{4,100}$/', $nombre)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Nombre inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[1-3]{1}$/', $nivel)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "nivel inválido"]);
                        exit;
                    }

                    $datosTipo = [
                                'operacion' => 'actualizar',
                                    'datos' => [
                                        'id_tipo' => $id_tipo,
                                        'nombre' => $nombre,
                                        'estatus' => $estatus,
                                        'nivel' => $nivel
                                    ]
                        ];

                         $resultadoRegistro = $objtipousuario->procesarTipo(json_encode($datosTipo));
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
} else if(isset($_POST['eliminar'])){ //----------------------- ELIMINAR DATOS 
//-----------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
            if ($_SESSION["nivel"] == 1) { /* V2 */    
                if(!empty($_POST['eliminar'])){ // V3 

                $id_tipo = $_POST['eliminar'];

                $campos = [
                    'id_tipo' => $id_tipo
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    // -- Validacion de datos V5
                    if (!preg_match('/^[0-9]{1,3}$/', $id_tipo)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => "Tipo usuario inválido"]);
                        exit;
                    }

                    if ($id_tipo == 1 || $id_tipo == 2) {
                        $res = array('respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No se puede eliminar, registro ADMIN');
                        echo json_encode($res);
                    } 
                
                     $datosTipo = [
                                'operacion' => 'eliminar',
                                    'datos' => [
                                        'id_tipo' => $id_tipo
                                    ]
                        ];

                         $resultadoRegistro = $objtipousuario->procesarTipo(json_encode($datosTipo));
                         echo json_encode($resultadoRegistro);
                         exit; 

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
//-----------  
} else if ($tipo_usuario == 1) { // Validacion si es administrador entra
         require_once 'vista/tipousuario.php'; //-------------------- VISTA
} else {
         require_once 'vista/complementos/error/privilegio.php';
}
?>