<?php

require_once 'conexion.php';
class Presentacion extends Conexion{
//----
    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }
//----------
    public function procesarPresentacion($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                
                case 'registrar':
                
                    return $this->ejecutarRegistrarPrsentacion($datosProcesar);
                   
                case 'actualizar':
                    if (!$this->verificarExistenciaPresentacion($datosProcesar['id_presentacion'])) {
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'La presentacion no existe'];
                    }
                   
                    return $this->ejecutarActualizarPresentacion($datosProcesar);    
             
                case 'eliminar':

                    if (!$this->verificarExistenciaPresentacion($datosProcesar['id_presentacion'])) {
                        return ['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'La presentacion no existe'];
                    }
                   
                    return $this->ejecutarEliminarPresentacion($datosProcesar);
                       
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (\Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
//---------------------
    private function verificarExistenciaPresentacion($id_presentacion) {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM presentacion 
                    WHERE id_presentacion = ?";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute([$id_presentacion]);

            return $stmt->fetchColumn() > 0;

        } catch (PDOException $e) {
            throw $e;
        }
    }
//---------------------   
    private function ejecutarRegistrarPrsentacion($datos){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

                $registro ="INSERT INTO presentacion(nombre, estatus) VALUES(:nombre, 1)";

                $param = [
                    'nombre' => $datos['nombre']
                ];

                $stmt = $conex->prepare($registro);
                $stmt->execute($param);

            if ($stmt->rowCount() > 0) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'registrar'];
            }

            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Error al momento de ejecutar'];

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
                return ['respuesta' => 0, 'accion' => 'registrar', 'text' => $e->getMessage()];
            }
            throw $e;
        }
    } 
//--------------------
    private function ejecutarActualizarPresentacion($datos){
         try {
            $conex = $this->conex;
            $conex->beginTransaction();

		    $registro = "UPDATE presentacion SET nombre = :nombre, estatus = :estatus WHERE id_presentacion = :id_presentacion";

            $stmt = $conex->prepare($registro);
            $stmt->execute([
                'id_presentacion' => $datos['id_presentacion'],
                'nombre' => $datos['nombre'],
                'estatus' => $datos['estatus']
            ]);

            $conex->commit();

            return [ 'respuesta' => 1, 'accion' => 'actualizar'];


        } catch (PDOException $e) {
            if ($conex) $conex->rollBack();
            return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => $e->getMessage()];
        }
	}
//-----------------------
    private function ejecutarEliminarPresentacion($datos){ // Elimanado Logico
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "UPDATE presentacion SET estatus = 5 WHERE id_presentacion = :id_presentacion";

            $param = [
                'id_presentacion' => $datos['id_presentacion']
            ];

            $stmt = $conex->prepare($sql);
            $stmt->execute($param);
    
            if ($stmt->rowCount() > 0) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'eliminar'];
            }

            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'Error en el momento de ejecutar'];

         } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
                return ['respuesta' => 0, 'accion' => 'eliminar', 'text' => $e->getMessage()];
            }
            throw $e;
        }
    }
//-------------------                   
    public function consultar($limite = 200){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $registro ="SELECT * FROM presentacion WHERE estatus < 5 ORDER BY id_presentacion DESC LIMIT :limite";
            $consulta = $this->conex->prepare($registro);
            $consulta->bindParam(':limite', $limite, PDO::PARAM_INT);
            $resul = $consulta->execute();
            $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
            
            $conex->commit();
            $conex = null;
            return $datos;

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    } 
//---------------------
    public function contarTotal(){
         try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "SELECT COUNT(*) AS total FROM presentacion WHERE estatus < 5";
            $consulta = $this->conex->prepare($sql);
            $consulta->execute();
            $fila = $consulta->fetch(PDO::FETCH_ASSOC);
            
            $conex->commit();
            $conex = null;
            return $fila['total'];

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
//----------------------
    public function buscar($data){
         try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $this->id_presentacion = $data;
            $registro= "SELECT * from presentacion WHERE id_presentacion='".$this->id_presentacion."'";
            $resultado = "";

            $dato = $this->conex->prepare($registro);
            $resul = $dato->execute();
            $resultado=$dato->fetch(PDO::FETCH_ASSOC);
            
            $conex->commit();
            $conex = null;
            return $resul ? $resultado : 0;

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }  
	}
}
?>        