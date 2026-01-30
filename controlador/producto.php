<?php
    session_start();
    if (empty($_SESSION["id"])){
    header("location:?pagina=login");
    } else if ($_SESSION["nivel"] == 3){
    header("location:?pagina=registroventa");
    }
    $vista="Producto - Inventario"; $activo="6"; $tipo_usuario = $_SESSION['nivel'];
    
require_once 'modelo/producto.php';
$objproducto = new Producto();
//---------
    if (!isset($_SESSION['limite_producto'])) {
        $_SESSION['limite_producto'] = 200;
    }
//---------------
    if (isset($_POST['ver_mas'])) {
        $_SESSION['limite_producto'] += 100; // Aumenta 100 registros
        header("location:?pagina=producto");
        exit;
    }
//--------------------
    $registro = $objproducto->consultar($_SESSION['limite_producto']);
//--------------------    
    $total_registros = $objproducto->contarTotal(); 
//--------------------   
    $dolar = $objproducto->obtenerDolar();
//-------------------    
    $StockMenor = $objproducto->StockMenor();
//---------------------
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
    function validarIdpresentacion($id_presentacion, $presentacion) {
        if (empty($presentacion)) return false;
            foreach ($presentacion as $fila) {
                if ($fila['id_presentacion'] == $id_presentacion) {
                    return true;
                }
            }
        return false;
    }
//---
 function validarIdmarca($id_marca, $marca) {
        if (empty($marca)) return false;
            foreach ($marca as $fila) {
                if ($fila['id_marca'] == $id_marca) {
                    return true;
                }
            }
        return false;
    }    
//---
if (isset($_POST['ajaxMarca'])) { //------------------------------ BUSCARDOR AJAX MARCA
//---
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */ 
            $buscar = $_POST['buscarMarca'] ?? '';

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
                $marca = $objproducto->ultimasMarcas();
            } else {
                $marca = $objproducto->buscarMarca($buscar);
            }
            echo json_encode($marca);
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
} else if (isset($_POST['ajaxPresentacion'])) {  //------------------------ BUSCAR AJAX PRESENTACION
//-------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */ 
             $buscar2 = $_POST['buscarPresentacion'] ?? '';

                 $campos = [
                    'buscar' => $buscar2
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    } 

            if ($buscar2 === '') {
                $presentacion = $objproducto->ultimasPresentacion();
            } else {
                $presentacion = $objproducto->buscarPresentacion($buscar2);
            }
            echo json_encode($presentacion);
            exit;

        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Session no encontrada']);
        exit;
    }    
//------------------
}else if(isset($_POST['registrar'])){ //----------------------------------- REGISTRAR PRODUCTO
//------------    
     if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */ 
            if (!empty($_POST['nombre']) && !empty($_POST['marca']) && !empty($_POST['presentacion']) && !empty($_POST['stock_min']) &&
                !empty($_POST['precio']) && !empty($_POST['preciomayor']) && !empty($_POST['cantidadmayor']) ){  //V3

                $nombre = $_POST['nombre']; $id_marca = $_POST['marca']; $id_presentacion = $_POST['presentacion'];
                $stockMin = $_POST['stock_min']; $precio = $_POST['precio']; $precioMayor = $_POST['preciomayor']; $cantidadMayor = $_POST['cantidadmayor'];

                 $campos = [
                    'nombre' => $nombre,
                    'id_marca' => $id_marca,
                    'id_presentacion' => $id_presentacion,
                    'stockMin' => $stockMin,
                    'precio' => $precio,
                    'precioMayor' => $precioMayor,
                    'cantidadMayor' => $cantidadMayor
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    // -- Validacion de datos V5
                    if (!preg_match('/^[0-9]{1,3}$/', $id_marca)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Marca inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $id_presentacion)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Presentacion inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $stockMin)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Stock Minimo inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $cantidadMayor)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Cantidad mayor inválido"]);
                        exit;
                    }

                    if (!preg_match('/^(?=(?:\D*\d){3,10}\D*$)\d+(?:[.,]\d+)?$/', $precio)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Precio inválido"]);
                        exit;
                    }

                    if (!preg_match('/^(?=(?:\D*\d){3,10}\D*$)\d+(?:[.,]\d+)?$/', $precioMayor)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Precio mayor inválido"]);
                        exit;
                    }
                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{4,100}$/', $nombre)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Nombre inválido"]);
                        exit;
                    }

                        $datosProductos = [
                                'operacion' => 'registrar',
                                    'datos' => [
                                        'nombre' => $nombre,
                                        'id_marca' => $id_marca,
                                        'id_presentacion' => $id_presentacion,
                                        'stockMin' => $stockMin,
                                        'precio' => $precio,
                                        'precioMayor' => $precioMayor,
                                        'cantidadMayor' => $cantidadMayor
                                    ]
                        ];

                         $resultadoRegistro = $objproducto->procesarProducto(json_encode($datosProductos));
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
//--------------   
}else if(isset($_POST['modificar'])){ //--------------------------------------- BUSCAR DATOS PARA EDITAR
//--------------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */    
            if(!empty($_POST['modificar'])){ 
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

                    $modificar = $objproducto->buscar($editar);
                    require_once ("vista/complementos/actualizar/producto.php");

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
}else if(isset($_POST['actualizar'])){ //-------------------------------------- MODIFICAR DATOS DE PRODUCTOS
//-----------------  
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */ 
            if (!empty($_POST['id_producto']) && !empty($_POST['nombre']) && !empty($_POST['marca']) && !empty($_POST['presentacion']) && !empty($_POST['stock_min']) &&
                !empty($_POST['precio']) && !empty($_POST['preciomayor']) && !empty($_POST['cantidadmayor']) ){  //V3

                $id_producto = $_POST['id_producto']; $nombre = $_POST['nombre']; $id_marca = $_POST['marca']; $id_presentacion = $_POST['presentacion'];
                $stockMin = $_POST['stock_min']; $precio = $_POST['precio']; $precioMayor = $_POST['preciomayor']; $cantidadMayor = $_POST['cantidadmayor'];

                $campos = [
                    'id_producto' => $id_producto,
                    'nombre' => $nombre,
                    'id_marca' => $id_marca,
                    'id_presentacion' => $id_presentacion,
                    'stockMin' => $stockMin,
                    'precio' => $precio,
                    'precioMayor' => $precioMayor,
                    'cantidadMayor' => $cantidadMayor
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                      // -- Validacion de datos V5
                    if (!preg_match('/^[0-9]{1,3}$/', $id_producto)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Producto inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $id_marca)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Marca inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $id_presentacion)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Presentacion inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $stockMin)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Stock Minimo inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $cantidadMayor)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Cantidad mayor inválido"]);
                        exit;
                    }

                    if (!preg_match('/^(?=(?:\D*\d){3,10}\D*$)\d+(?:[.,]\d+)?$/', $precio)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Precio inválido"]);
                        exit;
                    }

                    if (!preg_match('/^(?=(?:\D*\d){3,10}\D*$)\d+(?:[.,]\d+)?$/', $precioMayor)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Precio mayor inválido"]);
                        exit;
                    }
                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{4,100}$/', $nombre)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => "Nombre inválido"]);
                        exit;
                    }

                        $datosProductos = [
                                'operacion' => 'modificar',
                                    'datos' => [
                                        'id_producto' => $id_producto,
                                        'nombre' => $nombre,
                                        'id_marca' => $id_marca,
                                        'id_presentacion' => $id_presentacion,
                                        'stockMin' => $stockMin,
                                        'precio' => $precio,
                                        'precioMayor' => $precioMayor,
                                        'cantidadMayor' => $cantidadMayor
                                    ]
                        ];

                         $resultadoRegistro = $objproducto->procesarProducto(json_encode($datosProductos));
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
//------
}else if(isset($_POST['eliminar'])){  //---------------------------------------------- ELIMINADO LOGICO
//-----   
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */ 
            if (!empty($_POST['eliminar'])){  //V3

                $id_producto = $_POST['eliminar']; $stock = $_POST['stock'];
                
                $campos = [
                    'id_producto' => $id_producto,
                    'stock' => $stock
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }
                    
                    // -- Validacion de datos V5
                    if (!preg_match('/^[0-9]{1,3}$/', $id_producto)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => "Producto inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $stock)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => "Stock inválido"]);
                        exit;
                    }

                    if ($stock == 0){
                            $datosProductos = [
                                    'operacion' => 'eliminar',
                                        'datos' => [
                                            'id_producto' => $id_producto
                                        ]
                            ];
                
                            $resultadoRegistro = $objproducto->procesarProducto(json_encode($datosProductos));
                            echo json_encode($resultadoRegistro);
                            exit; 
                    
                    } else{
                            echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No puedes Eliminar un producto con stock disponible']);
                            exit;  
                    }

            } else{
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
}else if(isset($_POST['accion_estatus'])){//------------------------------------- ACTIVAR/DESACTIVAR PRODUCTO
//-----------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */ 
            if (!empty($_POST['id_producto']) && !empty($_POST['accion_estatus'])){  //V3
  
                $id_producto = $_POST['id_producto']; $accion = $_POST['accion_estatus'];
                
                $campos = [
                    'id_producto' => $id_producto,
                    'accion' => $accion
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'estatus', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    } 
                    // -- Validacion de datos V5
                    if (!preg_match('/^[a-zA-Z0-9\-#\/\s]{4,100}$/', $accion)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'estatus', 'text' => "accion inválida"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $id_producto)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'estatus', 'text' => "Producto inválido"]);
                        exit;
                    }

                        if ($accion === "activar") { 
                            $datosProductos = [
                                'operacion' => 'activar',
                                    'datos' => [
                                        'id_producto' => $id_producto
                                    ]
                            ];
            
                            $resultadoRegistro = $objproducto->procesarProducto(json_encode($datosProductos));
                          //  $result = $objproducto->activar(); 
                        } else {
                            $datosProductos = [
                                'operacion' => 'desactivar',
                                   'datos' => [
                                        'id_producto' => $id_producto
                                    ]
                            ];

                            $resultadoRegistro = $objproducto->procesarProducto(json_encode($datosProductos));
                            //$result = $objproducto->desactivar();
                        }
                        echo json_encode($resultadoRegistro);
                        exit; 

             } else{
                echo json_encode(['respuesta' => 0, 'accion' => 'estatus', 'text' => 'Datos Vacios']);
                exit;
            }
        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'estatus', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'estatus', 'text' => 'Session no encontrada']);
        exit;
    } 
//----------
} else if(isset($_POST['pdfcompleto'])){ //---------------------- GENERAR PDF
//--
    require_once 'assets/pdf/reporteproducto.php';
//--
}else{ //---------------------------- VISTA
//----------    
    require_once 'vista/producto.php';
//---------    
}
   
?>