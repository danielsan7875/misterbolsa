<?php

require_once 'conexion.php';
class Usuario extends Conexion{
//----------
    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }
//-----------
    public function procesarUsuario($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                
                case 'registrar':
                    if (!$this->verificarExistenciaUser($datosProcesar['usuario'])) {
                        return ['respuesta' => 0, 'accion' => 'registrar', 'text' => 'El usuario ya está registrado'];
                    }
                    if (!$this->verificarExistenciaTipo($datosProcesar['id_tipo'])) {
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'La marca no existe'];
                    }

                    return $this->ejecutarRegistrar($datosProcesar);
                   
                case 'actualizar':
                    if (!$this->verificarExistenciaUsuario($datosProcesar['id_usuario'])) {
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'el  no existe'];
                    }
                    if (!$this->verificarExistenciaTipo($datosProcesar['id_tipo'])) {
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'La marca no existe'];
                    }

                    if ($datosProcesar['usuario'] !== $datosProcesar['usuario_actual']) {
                        if (!$this->verificarExistenciaUser($datosProcesar['usuario'])) {
                            return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'El usuario ya está registrado'];
                        }
                    }

                    return $this->ejecutarActualizar($datosProcesar);    
             
                case 'eliminar':

                    if (!$this->verificarExistenciaUsuario($datosProcesar['id_usuario'])) {
                        return ['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'La marca no existe'];
                    }
                   
                    return $this->ejecutarEliminar($datosProcesar);
                       
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (\Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
//-----------
    private function verificarExistenciaUsuario($id_usuario) {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM usuario 
                    WHERE id_usuario = ?";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute([$id_usuario]);

            return $stmt->fetchColumn() > 0;

        } catch (PDOException $e) {
            throw $e;
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
//-----------------
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
//--------------------
    private function ejecutarRegistrar($datos){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

                $registro ="INSERT INTO usuario(nombre, apellido, usuario, clave, estatus,id_tipo)
                            VALUES(:nombres, :apellidos, :usuario, :clave, 1,:id_tipo)";

                $param = [
                    'nombres' => $datos['nombre'],
                    'apellidos' => $datos['apellido'],
                    'usuario' => $datos['usuario'],
                    'clave' =>$datos['clave'],
                    'id_tipo' => $datos['id_tipo']
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
//-----------------------
    private function ejecutarActualizar($datos){
         try {
            $conex = $this->conex;
            $conex->beginTransaction();

		    $registro = "UPDATE usuario SET nombre = :nombre, apellido = :apellido,
                        usuario = :usuario, clave = :clave, estatus = :estatus, id_tipo = :id_tipo
                        WHERE id_usuario = :id_usuario";

            $stmt = $conex->prepare($registro);
            $stmt->execute([
                'id_usuario' => $datos['id_usuario'],
                'nombre' => $datos['nombre'],
                'apellido' => $datos['apellido'],
                'usuario' => $datos['usuario'],
                'clave' => $datos['clave'],
                'estatus' => $datos['estatus'],
                'id_tipo' => $datos['id_tipo']
            ]);

            $conex->commit();
            $conex = null;
            return [ 'respuesta' => 1, 'accion' => 'actualizar'];


        } catch (PDOException $e) {
            if ($conex) $conex->rollBack(); $conex = null;
            return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => $e->getMessage()];
        }
	}
//---------------------
    private function ejecutarEliminar($datos){ // Elimanado Logico
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "DELETE FROM usuario WHERE id_usuario = :id_usuario";

            $param = [
                'id_usuario' => $datos['id_usuario']
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
//-------------    
    public function buscar($data){
		$this->id_usuario = $data;
		$registro= "SELECT u.*,tu.id_tipo, tu.nombre AS nombre_tipo
        FROM usuario u 
        INNER JOIN  tipo_usuario tu ON u.id_tipo = tu.id_tipo
        WHERE u.id_usuario='".$this->id_usuario."'";
		$resultado = "";
            $dato = $this->conex->prepare($registro);
            $resul = $dato->execute();
            $resultado=$dato->fetch(PDO::FETCH_ASSOC);
             if ($resul) {
	             return $resultado;
	        }else{
	            return $res = 0;
	        }		   
	}
//----------
    public function consultar($limite = 200){
        $registro="SELECT u.*,tu.id_tipo, tu.nombre AS nombre_tipo
        FROM usuario u 
        INNER JOIN  tipo_usuario tu ON u.id_tipo = tu.id_tipo
        ORDER BY u.id_usuario DESC  LIMIT :limite";
        $consulta = $this->conex->prepare($registro);
           $consulta->bindParam(':limite', $limite, PDO::PARAM_INT);
        $resul = $consulta->execute();

        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
            if ($resul){
                return $datos;
            } else{
                return $res = 0;
            }
    }
//----------------
    public function obtenerTipo() {
        $query = "SELECT * FROM tipo_usuario WHERE estatus = 1";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
//----------------        
    public function contarTotal(){
        $sql = "SELECT COUNT(*) AS total FROM usuario WHERE estatus < 5";
        $consulta = $this->conex->prepare($sql);
        $consulta->execute();
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);
        return $fila['total'];
    }   
//-------------   
}
