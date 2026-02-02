<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}

$tipo_usuario = $_SESSION['nivel'];
//----------
require_once 'modelo/registroventa.php';
$objventa = new Registroventa();
//-------------
$registro = $objventa->consultarVenta();
//---------
$cierre = $objventa->obtenerCierreActivo();
$NroVenta = $objventa->obtenerNroVenta();
//-------------
$dolar = $objventa->obtenerDolar();  
$metodo = $objventa->obtenerMetodopago();  
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
if (isset($_POST['buscarProducto'])) { ///---------------------------------------- BUSCARDOR DE PRODUCTO
//------
   if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */ 

            $buscar = trim($_POST['buscarProducto']);
            $campos = [
                    'buscar' => $buscar
                        ];
                            /// Sanitización de Entradas
                            foreach ($campos as $nombree => $valor) {  // V5
                                if (!validarEntradaSQL($valor)) {
                                    echo json_encode(['respuesta' => 1, 'accion' => 'buscarProducto', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                                    exit;
                                }
                            } 
  
            
                            $productos = $objventa->buscarProducto($buscar, 50);
                            echo json_encode(['accion' => 'buscarProducto','respuesta' => $productos]);
                            exit;
  
         } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Session no encontrada']);
        exit;
    }
//--------
} else if (isset($_POST['registrar'])) { ///---------------------------------------- REGISTRAR VENTA
//-----------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */ 
            if (!$cierre) { // V3
                echo json_encode([ 'respuesta' => 0, 'accion' => 'registrar', 'text' => 'No esta activo un cierre']);
                exit; 
            }

                if (!empty($_POST['fecha']) && !empty($_POST['total_dl'])  && !empty($_POST['total_bs']) && !empty($_POST['id_cierre']) && !empty($_POST['hora']) &&
                    !empty($_POST['id_producto']) && !empty($_POST['cantidad'])  && !empty($_POST['precio_unitario'])  && !empty($_POST['maode'])  &&
                    !empty($_POST['id_metodopago'])  && !empty($_POST['monto_dolares'])  && !empty($_POST['monto_bs'])) { // V4

                    $total_Dl = $_POST['total_dl'];   $total_Bs = $_POST['total_bs'];   $fecha = $_POST['fecha'];
                    $id_Cierre = $_POST['id_cierre'];  $hora_Cierre = $_POST['hora'];

                     // Arrays de productos
                    $id_producto   = $_POST['id_producto'];
                    $cantidad      = $_POST['cantidad'];
                    $precio        = $_POST['precio_unitario'];
                    $maode         = $_POST['maode'];

                     // Arrays de pagos
                    $id_metodopago = $_POST['id_metodopago'];
                    $monto_dolares = $_POST['monto_dolares'];
                    $monto_bs      = $_POST['monto_bs'];
                    $referencia    = $_POST['referencias'];

                    $campos = [
                    'total_Dl' => $total_Dl,
                    'total_Bs' => $total_Bs,      
                    'fecha' => $fecha,
                    'id_Cierre' => $id_Cierre,
                    'hora_Cierre' => $hora_Cierre,      
                    'fecha' => $fecha,
                    'id_producto' => $id_producto,
                    'cantidad' => $cantidad,      
                    'precio' => $precio,
                    'maode' => $maode,
                    'id_metodopago' => $id_metodopago,      
                    'monto_dolares' => $monto_dolares,
                    'monto_bs' => $monto_bs,
                    'referencia' => $referencia
                        ];
                            /// Sanitización de Entradas
                            foreach ($campos as $nombree => $valor) {  // V5
                                if (!validarEntradaSQL($valor)) {
                                    echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                                    exit;
                                }
                            } 
       
                            //// Validar Datos  V6
                            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
                                echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Fecha inválida"]);
                                exit;
                            }

                            if (!preg_match('/^\d+(?:[.,]\d{1,2})?$/', $total_Dl)) {
                                echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Monto Total DL inválido"]);
                                exit;
                            }

                            if (!preg_match('/^\d+(?:[.,]\d{1,2})?$/', $total_Bs)) {
                                echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Monto Total Bs inválido"]);
                                exit;
                            }

                            if (!preg_match('/^[0-9]{1,3}$/', $id_Cierre)) {
                                echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Cantidad mayor inválido"]);
                                exit;
                            }

                            if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/', $hora_Cierre)) {
                                echo json_encode(['respuesta' => 0, 'accion' => 'registrar','text' => "Hora inválida" ]);
                                exit;
                            }

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

                            if (!is_array($precio)) {
                                echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Formato inválido de cantidad"]);
                                exit;
                            }
                                foreach ($precio as $prec) {
                                    if (!preg_match('/^\d+(?:[.,]\d{1,2})?$/', $prec)|| $prec <= 0) {
                                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Precio venta inválida"]);
                                        exit;
                                    }
                                }

                            if (!is_array($maode)) {
                                echo json_encode([ 'respuesta' => 0, 'accion' => 'registrar', 'text' => "Formato inválido de modo de venta"]);
                                exit;
                            }
                                foreach ($maode as $modo) {
                                    if (!in_array($modo, ['Detal', 'Mayor'], true)) {
                                        echo json_encode(['respuesta' => 0,'accion' => 'registrar','text' => "Modo de venta inválido (solo Detal o Mayor)"]);
                                        exit;
                                    }
                                }
                                
                            if (!is_array($id_metodopago)) {
                                echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Formato inválido de productos"]);
                                exit;
                            }
                                foreach ($id_metodopago as $id) {
                                    if (!preg_match('/^[0-9]{1,5}$/', $id)) {
                                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Metodo de pago inválido"]);
                                        exit;
                                    }
                                }    

                            if (!is_array($monto_dolares)) {
                                echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Formato inválido de Monto USD"]);
                                exit;
                            }
                                foreach ($monto_dolares as $monto) {
                                    if (!preg_match('/^\d+(?:[.,]\d{1,2})?$/', $monto) || $monto <= 0) {
                                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Monto USD inválida"]);
                                        exit;
                                    }
                                }

                            if (!is_array($monto_bs)) {
                                echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Formato inválido de Monto Bs"]);
                                exit;
                            }
                                foreach ($monto_bs as $bs) {
                                    if (!preg_match('/^\d+(?:[.,]\d{1,2})?$/', $bs) || $bs <= 0) {
                                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Monto Bs inválida"]);
                                        exit;
                                    }
                                }
                                
                            if (!is_array($referencia)) {
                                echo json_encode(['respuesta' => 0,'accion' => 'registrar', 'text' => "Formato inválido de referencia"]);
                                exit;
                            }
                                foreach ($referencia as $refe) {
                                    // Si está vacío → no validar, continuar
                                    if (trim($refe) === '') {
                                        continue;
                                    }
                                    // Si tiene valor → validar formato
                                    if (!preg_match('/^[0-9]{4,6}$/', $refe)) {
                                        echo json_encode([
                                            'respuesta' => 0,'accion' => 'registrar','text' => "Referencia inválida (debe contener entre 4 y 6 dígitos)"]);
                                        exit;
                                    }
                                }

                                $datosVenta = [
                                'operacion' => 'registrar',
                                    'datos' => [
                                       'total_Dl' => $total_Dl,
                                        'total_Bs' => $total_Bs,      
                                        'fecha' => $fecha,
                                        'id_Cierre' => $id_Cierre,
                                        'hora_Cierre' => $hora_Cierre,      
                                        'fecha' => $fecha,
                                        'id_producto' => $id_producto,
                                        'cantidad' => $cantidad,      
                                        'precio' => $precio,
                                        'maode' => $maode,
                                        'id_metodopago' => $id_metodopago,      
                                        'monto_dolares' => $monto_dolares,
                                        'monto_bs' => $monto_bs,
                                        'referencia' => $referencia
                                    ]
                            ];


                            $resultadoRegistro = $objventa->procesarVenta(json_encode($datosVenta));
        
                            echo json_encode($resultadoRegistro); /// RESULTADO DE LA REGISTRO
                            exit;

            } else { // V4
                echo json_encode([ 'respuesta' => 0, 'accion' => 'registrar', 'text' => 'Datos Vacios']);
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
//------    
}else if(isset($_POST['salir'])){
    header('Location: ?pagina=home');
} else { /// ---------------------------- VISTA
  require_once 'vista/registroventa2.php';
}


?>