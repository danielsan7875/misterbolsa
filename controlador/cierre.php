<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
} else if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}
$tipo_usuario = $_SESSION['nivel'];   $vista="Cierre Diario";   $activo="11";
//------
require_once 'modelo/cierre.php';
$objcierre = new Cierre();
//---------
if (!isset($_SESSION['limite_cierre'])) {  // 100 por defecto
    $_SESSION['limite_cierre'] = 100;
}
//--------
if (isset($_POST['ver_mas'])) { //  "ver más"
    $_SESSION['limite_cierre'] += 100; 
    header("location:?pagina=cierre");
    exit;
}
//-------------
$registro = $objcierre->consultar($_SESSION['limite_cierre']);
$total_registros = $objcierre->contarTotal();
//-------------
$dolar = $objcierre->obtenerDolar(); 
$cantidad = $objcierre->contarVentas();
$cierre = $objcierre->obtenerCierreActivo();
//---------------
$contarcierre = $objcierre->contarCierrePorFecha();
//------------------
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
//-----------
if(isset($_POST['iniciar'])){ //------------------------------- INICIAR EL CIERRE
//---------------
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */   
            if(!empty($_POST['fecha'] && !empty($_POST['tasa'])) ){ // V3

                $fecha = $_POST['fecha'];     $tasa = $_POST['tasa'];

               $campos = [
                    'fecha' => $fecha,
                    'tasa' => $tasa,      
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'iniciar', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    } 
        
                       if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])\s([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/',$fecha )) {
                                echo json_encode(['respuesta' => 0,'accion' => 'iniciar','text' => 'Fecha inválida' ]);
                                exit;
                            }


                       if (!preg_match('/^(?=(?:\D*\d){3,10}\D*$)\d+(?:[.,]\d+)?$/', $tasa)) {
                          echo json_encode(['respuesta' => 0, 'accion' => 'iniciar', 'text' => "tasa inválido"]);
                          exit;
                       }
                       $datosCierre = [
                                'operacion' => 'iniciar',
                                    'datos' => [
                                        'fecha' => $fecha,
                                        'tasa' => $tasa
                                    ]
                            ];
            
                        $resultadoRegistro = $objcierre->procesarCierre(json_encode($datosCierre));
                        
                        echo json_encode($resultadoRegistro); /// RESULTADO DE LA REGISTRO
                        exit;
           
            } else{
                echo json_encode(['respuesta' => 0, 'accion' => 'iniciar', 'text' => 'Datos Vacios']);
                exit;
            }
       } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'iniciar', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'iniciar', 'text' => 'Session no encontrada']);
        exit;
    } 

} else if(isset($_POST['cierre'])){
//----------
   if (isset($_SESSION['id']) && !empty($_SESSION['id'])) { /* V1 */
        if ($_SESSION["nivel"] == 1) { /* V2 */   
            if(!empty($_POST['fecha_cierre'] && !empty($_POST['id_cierre'])) ){ // V3  
            
              $fecha_cierre = $_POST['fecha_cierre'];    $id_cierre = $_POST['id_cierre'];

              $campos = [
                    'fecha' => $fecha_cierre,
                    'id_cierre' => $id_cierre,      
                ];
                     /// Sanitización de Entradas
                    foreach ($campos as $nombree => $valor) {  /* V4 */ 
                        if (!validarEntradaSQL($valor)) {
                            echo json_encode(['respuesta' => 0, 'accion' => 'cierre', 'text' => "Entrada inválida detectada en el campo: $nombree"]);
                            exit;
                        }
                    } 
                      
                    // -- Validacion de datos V5
                      if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])\s([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/',$fecha_cierre )) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'cierre', 'text' => "Fecha inválida"]);
                        exit;
                      }
                    
                      if (!preg_match('/^[0-9]{1,3}$/', $id_cierre)) {
                          echo json_encode(['respuesta' => 0, 'accion' => 'cierre', 'text' => "producto inválido"]);
                          exit;
                      }

                      $datosCierre = [
                                'operacion' => 'cierre',
                                    'datos' => [
                                        'fecha' => $fecha_cierre,
                                        'id_cierre' => $id_cierre
                                    ]
                            ];
            
                        $resultadoRegistro = $objcierre->procesarCierre(json_encode($datosCierre));
                        
                        echo json_encode($resultadoRegistro); /// RESULTADO DE LA REGISTRO
                        exit;


            } else{
                echo json_encode(['respuesta' => 0, 'accion' => 'cierre', 'text' => 'Datos Vacios']);
                exit;
            }
        } else{  /* 2 */ 
            echo json_encode(['respuesta' => 0, 'accion' => 'cierre', 'text' => 'No Tiene Permiso para realizar esta operacion']);
            exit;
        }      
    } else{ /* V1 */ 
        echo json_encode(['respuesta' => 0, 'accion' => 'cierre', 'text' => 'Session no encontrada']);
        exit;
    } 
//-------
} else if(isset($_POST['pdf'])){

      if (!empty($_POST['pdf'])) {
        $id_cierre = $_POST['pdf'];
     
      if(!$id_cierre){
        header('Location: ?pagina=cierre');
      }
          require_once 'assets/pdf/reportecierre.php';
          echo json_encode($res);
      }

} else {
  require_once 'vista/cierre.php';
}

?>