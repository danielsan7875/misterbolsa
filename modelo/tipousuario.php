<?php
require_once 'conexion.php';
class Tipousuario extends Conexion{
//---
    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }
//----
    public function procesarTipo($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                
                case 'registrar':
                
                    return $this->ejecutarRegistrarTipo($datosProcesar);
                   
                case 'actualizar':
                    if (!$this->verificarExistenciaTipo($datosProcesar['id_tipo'])) {
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'La marca no existe'];
                    }
                   
                    return $this->ejecutarActualizarTipo($datosProcesar);    
             
                case 'eliminar':

                    if (!$this->verificarExistenciaTipo($datosProcesar['id_tipo'])) {
                        return ['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'La marca no existe'];
                    }
                   
                    return $this->ejecutarEliminarTipo($datosProcesar);
                       
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (\Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
//-----------
    private function verificarExistenciaTipo($id_tipo) {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM tipo_usuario 
                    WHERE id_tipo = ?";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute([$id_tipo]);

            return $stmt->fetchColumn() > 0;

        } catch (PDOException $e) {
            throw $e;
        }
    }
//-------------------    
    private function ejecutarRegistrarTipo($datos){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

                $registro ="INSERT INTO tipo_usuario (nombre, nivel, estatus) VALUES(:nombre, :nivel, 1)";

                $param = [
                    'nombre' => $datos['nombre'],
                    'nivel' =>$datos['nivel']
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
    private function ejecutarActualizarTipo($datos){
         try {
            $conex = $this->conex;
            $conex->beginTransaction();

		    $registro = "UPDATE tipo_usuario SET nombre = :nombre, nivel = :nivel, estatus = :estatus WHERE id_tipo = :id_tipo";

            $stmt = $conex->prepare($registro);
            $stmt->execute([
                'id_tipo' => $datos['id_tipo'],
                'nombre' => $datos['nombre'],
                'nivel' => $datos['nivel'],
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
    private function ejecutarEliminarTipo($datos){ // Elimanado Logico
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "UPDATE tipo_usuario SET estatus = 5 WHERE id_tipo = :id_tipo";

            $param = [
                'id_tipo' => $datos['id_tipo']
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

            $this->id_tipo = $data;
            $registro= "SELECT * from tipo_usuario WHERE id_tipo='".$this->id_tipo."'";
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
            
            $registro ="SELECT *  FROM tipo_usuario WHERE estatus < 5 ORDER BY id_tipo DESC LIMIT :limite";

            $consulta = $this->conex->prepare($registro);
            $consulta->bindParam(':limite', $limite, PDO::PARAM_INT);
            $consulta->execute();
            $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
            
            $conex->commit();
            $conex = null;
            
            $dato['datos'] = $datos;
            $dato['accion'] = 'consultar';
            return $dato;

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

            $sql = "SELECT COUNT(*) AS total FROM tipo_usuario WHERE estatus < 5";
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