<?php
  session_start();
  if (empty($_SESSION["id"])){
    header("location:?pagina=login");
  }
//---
$tipo_usuario = $_SESSION['nivel'];  $vista="Venta"; $activo="4";
require_once 'modelo/venta.php';
$objventa = new Venta();
//---
$registro = $objventa->consultar();
$dolar = $objventa->obtenerDolar();  
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
    function validarIdventa($id_venta, $registro) {

        if (empty($registro)) return false;

        foreach ($registro as $fila) {
            if ($fila['id_venta'] == $id_venta) {
                return true;
            }
        }

        return false;
    }
//---
if(isset($_POST['pdf'])){ //---------------------- GENERAR PDF
//--- 
  if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */

    if ($_SESSION["nivel"] == 1) { /* V2 */ 

       if (!empty($_POST['pdf'])) {
        
          $id_venta = $_POST['pdf'];

                $campos = [
                    'id_venta' => $id_venta
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    } 
                    // Validacion datos  V5
                    if (!preg_match('/^[0-9]{1,5}$/', $id_venta)) { 
                        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => "Nro de venta inválido"]);
                        exit; 
                    }
                    
                    if (!validarIdventa($id_venta, $registro)) {
                        echo json_encode(['respuesta' => 0,'accion' => 'registrar','text' => 'La venta no existe']);
                        exit;
                    }
     
                    require_once 'assets/pdf/reporteventa.php';

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
//----
} else if (isset($_POST['consultafiltro'])) { //--------------------- CONSULTAR FILTRADO
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

          $filtradofecha = $objventa->consultarFiltrado($filtro, $desde, $hasta);

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

          require_once("vista/complementos/consulta/venta.php");

    } else{  /* 2 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
        exit;
    }      
  } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Session no encontrada']);
        exit;
  } 
//---   
} else{
//----  
  if ($_SESSION["nivel"] == 3){
    header("location:?pagina=registroventa");
  }
  require_once 'vista/venta.php';
//---  
}

?>