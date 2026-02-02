<?php

require_once 'conexion.php';
class Perfil extends Conexion{
//-----------    
    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }
//----------------
    public function procesarPerfil($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                
                case 'actualizar':
                    if (!$this->verificarExistenciaPerfil($datosProcesar['id_usuario'])) {
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'Usuario no existe'];
                    }
                   
                    return $this->ejecutarActualizazPerfil($datosProcesar);    
             
                case 'clave':

                    if (!$this->verificarExistenciaPerfil($datosProcesar['id_usuario'])) {
                        return ['respuesta' => 0, 'accion' => 'clave', 'text' => 'Usuario no existe'];
                    }
                   
                    return $this->ejecutarActualizarClave($datosProcesar);
                       
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (\Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
//-----------
    private function verificarExistenciaPerfil($id_usuario) {
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
    private function ejecutarActualizazPerfil($datos){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

		    $registro = "UPDATE usuario SET nombre = :nombre, apellido = :apellido, usuario = :usuario WHERE id_usuario = :id_usuario";

            $stmt = $conex->prepare($registro);
            $stmt->execute([
                'id_usuario' => $datos['id_usuario'],
                'nombre' => $datos['nombre'],
                'apellido' => $datos['apellido'],
                'usuario' => $datos['usuario']
            ]);

            $conex->commit();
            $conex = null;
            return [ 'respuesta' => 1, 'accion' => 'actualizar'];

        } catch (PDOException $e) {
            if ($conex) $conex->rollBack(); $conex = null;
            return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => $e->getMessage()];
        }    
    }
//---------
     private function ejecutarActualizarClave($datos){
         try {
            $conex = $this->conex;
            $conex->beginTransaction();

		    $registro = "UPDATE usuario SET clave = :nueva WHERE id_usuario = :id_usuario";

            $stmt = $conex->prepare($registro);
            $stmt->execute([
                'id_usuario' => $datos['id_usuario'],
                'nueva' => $datos['nueva']
            ]);

            $conex->commit();
            $conex = null;
            return [ 'respuesta' => 1, 'accion' => 'clave'];

        } catch (PDOException $e) {
            if ($conex) $conex->rollBack(); $conex = null;
            return ['respuesta' => 0, 'accion' => 'clave', 'text' => $e->getMessage()];
        }       
    }
//---------
    public function consultarClaveActual($id_usuario){
        try{
            $conex = $this->conex;
            $conex->beginTransaction();
            
            $sql = "SELECT clave FROM usuario WHERE id_usuario = :id_usuario";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $Datos = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $conex ->commit();
            $conex = null;
            return $Datos;
        } catch(\PDOException $e){
            if($conex){
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }       
    }
//--------------
    public function consulta($id_usuario){
         try {
            $conex = $this->conex;
            $conex->beginTransaction();
        
            $registro="SELECT nombre, apellido, usuario FROM usuario WHERE id_usuario = :id_usuario";
            $consulta = $this->conex->prepare($registro);
            $consulta->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
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
//--------------
} 

