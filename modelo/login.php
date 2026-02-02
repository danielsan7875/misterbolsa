<?php
require_once 'conexion.php';
class Login extends Conexion {
//--------------
    function __construct(){ // Metodo para BD
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }
//-------------
    public function procesarLogin($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                
                case 'verificar':
                    if ($this->verificarExistenciaUser($datosProcesar['usuario'])) {
                        return ['respuesta' => 3, 'accion' => 'ingresar', 'text' => 'Usuario y/o clave incorrecto'];
                    }
                
                    return $this->ejecutarverificarUsuario($datosProcesar);
                   
                case 'dolar':

                    return $this->ejecutarTasa($datosProcesar);    
             
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (\Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
//-------------------    
    private function verificarExistenciaUser($usuario) {
        try {
            $sql = "SELECT COUNT(1) 
                    FROM usuario 
                    WHERE usuario = ?";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute([$usuario]);

            return $stmt->fetchColumn() == 0;

        } catch (PDOException $e) {
            throw $e;
        }
    }
//----------------
    private function ejecutarverificarUsuario($datos) {
        try {
            $conex = $this->conex;

            $consulta = "SELECT u.*,tu.id_tipo, tu.nombre AS nombre_tipo, tu.nivel
                        FROM usuario u 
                        INNER JOIN  tipo_usuario tu ON u.id_tipo = tu.id_tipo
                        WHERE u.usuario = :usuario 
                        AND u.clave = :clave";
                        
            $param = [
                'usuario' => $datos['usuario'],
                'clave' => $datos['clave']
            ];
            
            $strExec = $this->conex->prepare($consulta);
            $strExec->execute($param);
            $resultado = $strExec->fetchObject();
            if ($resultado) {
                if ($resultado->estatus != 1) {
                    $resultado->noactiva = true; // para indicar que la cuenta está suspendida o inactiva
                }
            }

            $conex = null;
            return $resultado;

         } catch (\PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }  
    }
//------------------
    private function ejecutarTasa($datos){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

		    $registro = "UPDATE dolar SET id_dolar = 1, precios = :dolar WHERE id_dolar = 1";

            $param = [
                'dolar' => $datos['dolar']
            ];

            $strExec = $this->conex->prepare($registro);  
		    $resul= $strExec->execute($param);

            $conex->commit();
            $conex = null;
        
                if ($resul) {
                    $res= "1";
                }else{
                    $res= "0";
                }
		        return $res;

          } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }  
	}
//--------------
   
}