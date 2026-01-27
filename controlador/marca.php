<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
} else if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}
//--
$tipo_usuario = $_SESSION['nivel'];  $vista="Marca";  $activo="7";
//-------
require_once 'modelo/marca.php';
$objmarca = new Marca();
//-------
    if (!isset($_SESSION['limite_marca'])) { // 200 por defecto
        $_SESSION['limite_marca'] = 200;
    }
//-------
    if (isset($_POST['ver_mas'])) { //  "ver más"
        $_SESSION['limite_marca'] += 100; 
        header("location:?pagina=marca");
        exit;
    }
//--------
$registro = $objmarca->consultar($_SESSION['limite_marca']);
$total_registros = $objmarca->contarTotal(); 
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
if(isset($_POST['registrar'])){ //------------------------------------------------ REGISTRO
//-----    
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { // V1
        if ($_SESSION["nivel"] == 1) { // V2
            if( !empty($_POST['nombre'])){ // V3

                $nombre = $_POST['nombre'];

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

                    $datosMarca = [
                                'operacion' => 'registrar',
                                    'datos' => [
                                        'nombre' => $nombre
                                    ]
                        ];

                         $resultadoRegistro = $objmarca->procesarMarca(json_encode($datosMarca));
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
}else if(isset($_POST['modificar'])){ //--------------------------------- BUSCAR EL ID PARA MODIFICAR DATOS
//--------------    
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
                    
                    $modificar = $objmarca->buscar($editar);
                    require_once ("vista/complementos/actualizar/marca.php");

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
//---------------
}else if(isset($_POST['actualizar'])){ //---------------------------------- ACTUALIZAR
//-------------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
            if ($_SESSION["nivel"] == 1) { /* V2 */    
                if(!empty($_POST['id_marca']) && !empty($_POST['nombre']) && !empty($_POST['estatus'])){ // V3    

                $id_marca = $_POST['id_marca']; $nombre = $_POST['nombre'];  $estatus = $_POST['estatus'];
       
                $campos = [
                    'id_marca' => $id_marca,
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
                    if (!preg_match('/^[0-9]{1,3}$/', $id_marca)) {
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

                    $datosMarca = [
                                'operacion' => 'actualizar',
                                    'datos' => [
                                        'id_marca' => $id_marca,
                                        'nombre' => $nombre,
                                        'estatus' => $estatus
                                    ]
                        ];

                         $resultadoRegistro = $objmarca->procesarMarca(json_encode($datosMarca));
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
}else if(isset($_POST['eliminar'])){ //--------------------------- ELIMINAR
//------------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
            if ($_SESSION["nivel"] == 1) { /* V2 */    
                if(!empty($_POST['eliminar'])){ // V3 

                $id_marca = $_POST['eliminar'];

                $campos = [
                    'id_marca' => $id_marca
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    // -- Validacion de datos V5
                    if (!preg_match('/^[0-9]{1,3}$/', $id_marca)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => "Marca inválido"]);
                        exit;
                    }
                
                     $datosMarca = [
                                'operacion' => 'eliminar',
                                    'datos' => [
                                        'id_marca' => $id_marca
                                    ]
                        ];

                         $resultadoRegistro = $objmarca->procesarMarca(json_encode($datosMarca));
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
//--------------
} else if ($tipo_usuario == 1 || $tipo_usuario == 2) {
//-------    
    require_once 'vista/marca.php';  //------------------------------ VISTA
//-------    
} else {
//------    
    require_once 'vista/complementos/error/privilegio.php';
//------    
}
?>