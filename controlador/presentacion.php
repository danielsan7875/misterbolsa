<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
} else if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

$tipo_usuario = $_SESSION['nivel'];  $vista="Presentacion";  $activo="8";

require_once 'modelo/presentacion.php';
$objpresentacion = new Presentacion();
//----
if (!isset($_SESSION['limite_presentacion'])) { // 200 por defecto
    $_SESSION['limite_presentacion'] = 200;
}
//----
if (isset($_POST['ver_mas'])) { //  "ver más"
    $_SESSION['limite_presentacion'] += 100; 
     header("location:?pagina=presentacion");
    exit;
}
//-----------
 $registro = $objpresentacion->consultar($_SESSION['limite_presentacion']);
 $total_registros = $objpresentacion->contarTotal(); 
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
if(isset($_POST['registrar'])){ //---------------------------------- REGISTRAR
//----    
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */    
            if(!empty($_POST['nombre'])){
            
             $nombre = ucfirst(strtolower($_POST['nombre']));

                 $campos = [
                    'nombre' => $nombre
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

                    $datosPresentacion = [
                                'operacion' => 'registrar',
                                    'datos' => [
                                        'nombre' => $nombre
                                    ]
                        ];

                         $resultadoRegistro = $objpresentacion->procesarPresentacion(json_encode($datosPresentacion));
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
} else if(isset($_POST['modificar'])){ //--------------- BUSCAR EL ID PARA MODIFICAR
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
                
                    $modificar = $objpresentacion->buscar($editar);
                    require_once ("vista/complementos/actualizar/presentacion.php");

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
//------------      
} else if(isset($_POST['actualizar'])){ //----------------------------- ACTUALIZAR
//------------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */    
            if(!empty($_POST['id_presentacion']) && !empty($_POST['nombre']) && !empty($_POST['estatus'])){ // V3

            $id_presentacion = $_POST['id_presentacion'];  $nombre = ucfirst(strtolower($_POST['nombre']));    $estatus = $_POST['estatus'];
            
            $campos = [
                    'id_presentacion' => $id_presentacion,
                    'nombre' => $nombre,
                    'estatus' => $estatus
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    // -- Validacion de datos V5
                    if (!preg_match('/^[0-9]{1,3}$/', $id_presentacion)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Marca inválido"]);
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

                        $datosPresentacion = [
                                'operacion' => 'actualizar',
                                    'datos' => [
                                        'id_presentacion' => $id_presentacion,
                                        'nombre' => $nombre,
                                        'estatus' => $estatus
                                    ]
                        ];

                         $resultadoRegistro = $objpresentacion->procesarPresentacion(json_encode($datosPresentacion));
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
//------------   
} else if(isset($_POST['eliminar'])){ //------------------------------- ELIMINAR
//---------------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */    
            if(!empty($_POST['eliminar'])){ // V3
            
            $id_presentacion = $_POST['eliminar'];
    
                $campos = [
                    'id_presentacion' => $id_presentacion
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    // -- Validacion de datos V5
                    if (!preg_match('/^[0-9]{1,3}$/', $id_presentacion)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => "Marca inválido"]);
                        exit;
                    }
                
                        $datosPresentacion = [
                                'operacion' => 'eliminar',
                                    'datos' => [
                                        'id_presentacion' => $id_presentacion
                                    ]
                        ];

                         $resultadoRegistro = $objpresentacion->procesarPresentacion(json_encode($datosPresentacion));
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
//------------   
} else if ($tipo_usuario == 1 || $tipo_usuario == 2) {
        require_once 'vista/presentacion.php';  // ----------------- VISTA
} else {
        require_once 'vista/complementos/error/privilegio.php';
}

?>