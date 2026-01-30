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
    if(!empty($_POST['id_dolar']) && !empty($_POST['precios'])){
      $precioss = $_POST['precios'];
     

        if ($precioss === "0.00"){
           $res = array('respuesta' => 0, 'accion' => 'tasadolar', 'text' => 'Error con la conexion o el valdor es 0.00');
            echo json_encode($res);
            exit;
        }

        $objtasa->set_Id_dolar($_POST['id_dolar']); 
        $objtasa->set_precios($_POST['precios']); 
 
        $result = $objtasa->tasadolar();
        echo json_encode($result);
    }
  } else {
    require_once 'vista/tasa.php'; // ------------------------VISTA
  }
?>