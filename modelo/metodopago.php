<?php

require_once 'conexion.php';
class Metodopago extends Conexion{
//-------
    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }
//------
       public function procesarPago($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                     
               case 'desactivar':
                    if (!$this->verificarExistenciaPago($datosProcesar['id_metodopago'])) {
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'El metodo pago no existe'];
                    }
                   
                    return $this->ejecutarDesactivarMetodopago($datosProcesar);    
             
                case 'activar':

                    if (!$this->verificarExistenciaPago($datosProcesar['id_metodopago'])) {
                        return ['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'El metodo pago no existe'];
                    }
                   
                    return $this->ejecutarActivarMetodopago($datosProcesar);
                       
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (\Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
//---------------------
    private function verificarExistenciaPago($id_metodopago) {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM metodopago 
                    WHERE id_metodopago = ?";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute([$id_metodopago]);

            return $stmt->fetchColumn() > 0;

        } catch (PDOException $e) {
            throw $e;
        }
    }
//------------------
    public function consultar(){
            try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $registro ="SELECT * FROM metodopago WHERE estatus <5 ORDER BY id_metodopago DESC";
            $consulta = $this->conex->prepare($registro);
            $resul = $consulta->execute();

            $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
            $conex->commit();
            $conex = null;
            if ($resul) {
                $dato['datos'] = $datos;
                $dato['accion'] = 'consultar';
                return $dato;
            } else {
                return $res = 0;
            }
        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    } 
//---------------    
    private function ejecutarDesactivarMetodopago($datos){ // Elimanado Logico
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $registro = "UPDATE metodopago SET estatus = 2 WHERE id_metodopago = :id_metodopago";
            
            $param = [
                'id_metodopago' => $datos['id_metodopago']
            ];

            $stmt = $conex->prepare($registro);
            $stmt->execute($param);
    
            if ($stmt->rowCount() > 0) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'desactivar'];
            }

            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'desactivar', 'text' => 'Error en el momento de ejecutar'];

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
                return ['respuesta' => 0, 'accion' => 'desactivar', 'text' => $e->getMessage()];
            }
            throw $e;
        }
    }
//------------
     private function ejecutarActivarMetodopago($datos){ // Elimanado Logico
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $registro = "UPDATE metodopago SET estatus = 1 WHERE id_metodopago = :id_metodopago";
            
            $param = [
                'id_metodopago' => $datos['id_metodopago']
            ];

            $stmt = $conex->prepare($registro);
            $stmt->execute($param);
    
            if ($stmt->rowCount() > 0) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'activar'];
            }

            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'activar', 'text' => 'Error en el momento de ejecutar'];

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
                return ['respuesta' => 0, 'accion' => 'activar', 'text' => $e->getMessage()];
            }
            throw $e;
        }
    }
//---------        
}
?>        