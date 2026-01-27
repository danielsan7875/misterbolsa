<?php
    session_start();

    if (empty($_SESSION["id"])){
        header("location:?pagina=login");
    } else if ($_SESSION["nivel"] == 3){
        header("location:?pagina=registroventa");
    }
    $tipo_usuario = $_SESSION['nivel']; $vista="Perdidas - Inventario"; $activo="5";

    require_once 'modelo/perdidas.php';
    $objperdidas = new Perdidas();
//---
    $registro = $objperdidas->consultar();
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
if (isset($_POST['ajaxProducto'])) { //-------------------------------- BUSQUEDA DE PRODUCTO AJAX
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
                    $producto = $objperdidas->ultimosProductos();
                } else {
                    // Búsqueda normal
                    $producto = $objperdidas->buscarProducto($buscar);
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
//---
} else if (isset($_POST['consultafiltro'])) { // -------------------------- FILTRO DE CONSULTA
//----
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

            $filtradofecha = $objperdidas->consultarFiltrado($filtro, $desde, $hasta);

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

            require_once("vista/complementos/consulta/perdidas.php");

        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Session no encontrada']);
        exit;
    } 
//----
} else if (isset($_POST['registrar'])) {
//----          
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */

        if ($_SESSION["nivel"] == 1) { /* V2 */ 

            if (!empty($_POST['id_producto']) && !empty($_POST['cantidad']) && !empty($_POST['fecha']) && !empty($_POST['nota'])) {

                $id_producto=$_POST['id_producto'];  $cantidad=$_POST['cantidad']; $fecha = $_POST['fecha']; $nota = $_POST['nota'];

                $campos = [
                    'id_producto' => $id_producto,
                    'cantidad' => $cantidad,      
                    'fecha' => $fecha,
                    'nota' => $nota
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }
                
                    if (!preg_match('/^[0-9]{1,3}$/', $id_producto)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Producto inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $cantidad)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Cantidad inválido"]);
                        exit;
                    }

                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Fecha inválida"]);
                        exit;
                    }

                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{4,100}$/', $nota)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Nota inválida"]);
                        exit;
                    }

                    $datosPerdidas = [
                                'operacion' => 'registrar',
                                    'datos' => [
                                        'id_producto' => $id_producto,
                                        'cantidad' => $cantidad,
                                        'fecha' => $fecha,
                                        'nota' => $nota
                                    ]
                    ];
            
                    $resultadoRegistro = $objperdidas->procesarPerdida(json_encode($datosPerdidas));
                    
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
} else {
   require_once 'vista/perdidas.php';
 }
?>