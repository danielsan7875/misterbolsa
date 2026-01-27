<?php
require_once 'conexion.php';
class Marca extends Conexion{
//---
    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }
//----
    public function procesarMarca($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                
                case 'registrar':
                
                    return $this->ejecutarRegistrarMarca($datosProcesar);
                   
                case 'actualizar':
                    if (!$this->verificarExistenciaMarca($datosProcesar['id_marca'])) {
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'La marca no existe'];
                    }
                   
                    return $this->ejecutarActualizarMarca($datosProcesar);    
             
                case 'eliminar':

                    if (!$this->verificarExistenciaMarca($datosProcesar['id_marca'])) {
                        return ['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'La marca no existe'];
                    }
                   
                    return $this->ejecutarEliminarMarca($datosProcesar);
                       
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (\Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
//-----------
    private function verificarExistenciaMarca($id_marca) {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM marca 
                    WHERE id_marca = ?";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute([$id_marca]);

            return $stmt->fetchColumn() > 0;

        } catch (PDOException $e) {
            throw $e;
        }
    }
//-------------------    
    private function ejecutarRegistrarMarca($datos){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

                $registro ="INSERT INTO marca(nombre, estatus) VALUES(:nombre, 1)";

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
                return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => $e->getMessage()];
            }
            throw $e;
        }
    }
//-----------------------
    private function ejecutarActualizarMarca($datos){
         try {
            $conex = $this->conex;
            $conex->beginTransaction();

		    $registro = "UPDATE marca SET nombre = :nombre, estatus = :estatus WHERE id_marca = :id_marca";

            $stmt = $conex->prepare($registro);
            $stmt->execute([
                'id_marca' => $datos['id_marca'],
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
//----------------------    
    private function ejecutarEliminarMarca($datos){ // Elimanado Logico
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "UPDATE marca SET estatus = 5 WHERE id_marca = :id_marca";

            $param = [
                'id_marca' => $datos['id_marca']
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
//--------------------------  
    public function buscar($data){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $this->id_marca = $data;
            $registro= "SELECT * from marca WHERE id_marca='".$this->id_marca."'";
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
//-------------------------
    public function consultar($limite = 200){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();
            
            $registro ="SELECT *  FROM marca WHERE estatus < 5 ORDER BY id_marca DESC LIMIT :limite";

            $consulta = $this->conex->prepare($registro);
            $consulta->bindParam(':limite', $limite, PDO::PARAM_INT);
            $consulta->execute();
            $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
            
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
//------------------------
    public function contarTotal(){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "SELECT COUNT(*) AS total FROM marca WHERE estatus < 5";
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
//------------
}
?>        