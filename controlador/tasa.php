<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
} else if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

$tipo_usuario = $_SESSION['nivel']; $vista="Tasa de Cambio"; $activo="10";

require_once 'modelo/tasa.php';
$objtasa = new Tasa();
//------------
$consulta = $objtasa->obtenerDolar();
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
//--------- 
if(isset($_POST['tasadolar'])){ //----------------------------------- ACTUALIZAR TASA
//---------  
      if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */

        if ($_SESSION["nivel"] == 1) { /* V2 */ 

            if(!empty($_POST['id_dolar']) && !empty($_POST['precios'])){
            
            $precios = $_POST['precios']; $id_dolar = $_POST['id_dolar'];

                if ($precios === "0.00"){
                    echo json_encode(['respuesta' => 0, 'accion' => 'tasadolar', 'text' => 'Error con la conexion o el valdor es 0.00']);
                    exit;
                }

                $campos = [
                    'precios' => $precios,
                    'id_dolar' => $id_dolar
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'tasadolar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    }

                    if (!preg_match('/^(?=(?:\D*\d){3,10}\D*$)\d+(?:[.,]\d+)?$/', $precios)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'tasadolar', 'text' => "tasa de cambio inválido"]);
                        exit;
                    }

                    if (!preg_match('/^[0-9]{1,3}$/', $id_dolar)) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'tasadolar', 'text' => "tasa inválido"]);
                        exit;
                    }

                        $datosTasa = [
                                'operacion' => 'tasadolar',
                                    'datos' => [
                                        'id_dolar' => $id_dolar,
                                        'precios' => $precios
                                    ]
                        ];

                        $resultadoRegistro = $objtasa->procesarTasa(json_encode($datosTasa));
                        echo json_encode($resultadoRegistro);
                        exit;
               

            } else{
                echo json_encode(['respuesta' => 0, 'accion' => 'tasadolar', 'text' => 'Datos Vacios']);
                exit;
            }
        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'tasadolar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'tasadolar', 'text' => 'Session no encontrada']);
        exit;
    } 
  } else {
    require_once 'vista/tasa.php'; // ------------------------VISTA
  }
?>