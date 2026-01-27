<?php
    session_start();
    if (empty($_SESSION["id"])){
    header("location:?pagina=login");
    } else if ($_SESSION["nivel"] == 3){
    header("location:?pagina=registroventa");
    }
    $tipo_usuario = $_SESSION['nivel'];  $vista="Compra"; $activo="3";
//---
require_once 'modelo/compra.php';
$objcompra = new Compra();
//---
$registro = $objcompra->consultar();
//---
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
//---
    function validarIdCompra($id_compra, $registro) {

        if (empty($registro)) return false;

        foreach ($registro as $fila) {
            if ($fila['id_compra'] == $id_compra) {
                return true;
            }
        }

        return false;
    }
//---
if (isset($_POST['ajaxProducto'])) {  // -------------------------- BUSCAR AJAX
//---
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */

        if ($_SESSION["nivel"] == 1) { /* V2 */ 

                $buscar = $_POST['buscarProducto'] ?? '';

                 $campos = [
                    'buscar' => $buscar
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    } 

                if ($buscar === '') {
                    // Últimos 20 registrados
                    $producto = $objcompra->ultimosProductos();
                } else {
                    // Búsqueda normal
                    $producto = $objcompra->buscarProducto($buscar);
                }

                echo json_encode($producto);
                exit;

        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Session no encontrada']);
        exit;
    } 
//--
} else if(isset($_POST['registrar'])){ // ------------------------------ Registrar
//---
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */

         if ($_SESSION["nivel"] == 1) { /* V2 */ 

            if(!empty($_POST['id_producto'] && !empty($_POST['cantidad']) && !empty($_POST['fecha'])) ){ // V3
                
                $id_producto=$_POST['id_producto'];  $cantidad=$_POST['cantidad']; $fecha = $_POST['fecha'];

                $campos = [
                    'id_producto' => $id_producto,
                    'cantidad' => $cantidad,      
                    'fecha' => $fecha
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    } 
                    
                     //// Validar Datos  V5
                    if (!is_array($id_producto)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Formato inválido de productos"]);
                        exit;
                    }

                    foreach ($id_producto as $id) {
                        if (!preg_match('/^[0-9]{1,5}$/', $id)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Producto inválido"]);
                            exit;
                        }
                    }

                    // Validar cantidad (array de cantidades)
                    if (!is_array($cantidad)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Formato inválido de cantidad"]);
                        exit;
                    }

                    foreach ($cantidad as $cant) {
                        if (!preg_match('/^[0-9]{1,5}$/', $cant) || $cant <= 0) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Cantidad inválida"]);
                            exit;
                        }
                    }

                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Fecha inválida"]);
                        exit;
                    }

                    $datosCompra = [
                                'operacion' => 'registrar',
                                    'datos' => [
                                        'id_producto' => $id_producto,
                                        'cantidad' => $cantidad,
                                        'fecha' => $fecha
                                    ]
                            ];
            
                    $resultadoRegistro = $objcompra->procesarCompra(json_encode($datosCompra));
                    
                    echo json_encode($resultadoRegistro); /// RESULTADO DE LA REGISTRO
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
//--
} else if(isset($_POST['pdf'])){ // ------------------------------ PDF
//---
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */

         if ($_SESSION["nivel"] == 1) { /* V2 */

            if (!empty($_POST['pdf'])) { // V3

                $id_compra = $_POST['pdf'];

                $campos = [
                    'id_producto' => $id_compra
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    } 
                    // Validacion datos  V5
                    if (!preg_match('/^[0-9]{1,5}$/', $id_compra)) { 
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Nro de compra inválido"]);
                        exit; 
                    }
                    
                    if (!validarIdCompra($id_compra, $registro)) {
                        echo json_encode(['respuesta' => 0,'accion' => 'registrar','text' => 'La compra no existe']);
                        exit;
                    }

                    require_once 'assets/pdf/reportecompra.php'; // Generar PDF
          
            } else{ //V3
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
//--
} else if (isset($_POST['modificar'])) { // -------------------------- FILTRO DE CONSULTA
//--
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */

        if ($_SESSION["nivel"] == 1) { /* V2 */    

            $filtro = $_POST['filtro'] ?? null;
            $desde  = $_POST['desde'] ?? null;
            $hasta  = $_POST['hasta'] ?? null;

            $campos = [
                    'filtro' => $filtro,
                    'desde' => $desde,
                    'hasta' => $hasta
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    } 

            $filtradofecha = $objcompra->consultarFiltrado($filtro, $desde, $hasta);

            // TEXTO DEL PERIODO
            if ($filtro == "15") {
                $periodo = "Últimos 15 días (Hoy y 14 días anteriores)";
            } elseif ($filtro == "mes") {
                $periodo = "Mes actual (" . date('F Y') . ")";
            } elseif (!empty($desde) && !empty($hasta)) {
                $periodo = "Desde " . date('d/m/Y', strtotime($desde)) .
                        " hasta " . date('d/m/Y', strtotime($hasta));
            } else {
                $periodo = "Todos los registros";
            }

            require_once("vista/complementos/consulta/compra.php");

        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Session no encontrada']);
        exit;
    } 
//---
} else if ($tipo_usuario == 1) { // Validacion si es administrador entra
     require_once 'vista/compra.php';
} else {
     require_once 'vista/complementos/error/privilegio.php';
}
?>