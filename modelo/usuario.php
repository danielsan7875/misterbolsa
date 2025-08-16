<?php

require_once 'conexion.php';

class Usuario extends Conexion{

    private $conex;
    private $id_usuario;
    private $nombres;
    private $apellidos;
    private $usuario;
    private $clave;
    private $tipo_usuario;
    private $estatus;

    function __construct()
    {
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }

    public function registrar(){
    
        $registro ="INSERT INTO usuario(nombre, apellido, usuario, clave, estatus,id_tipo)
        VALUES(:nombres, :apellidos, :usuario, :clave, 1,:tipo_usuario)";

        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombres', $this->nombres);
        $strExec->bindParam(':apellidos', $this->apellidos);
        $strExec->bindParam(':usuario', $this->usuario);
        $strExec->bindParam(':clave', $this->clave);
        $strExec->bindParam(':tipo_usuario', $this->tipo_usuario);
        $resul=$strExec->execute();
        if($resul){
            $res=array('respuesta'=>1,'accion'=>'registrar');
        }else{
            $res=array('respuesta'=>0,'accion'=>'registrar');
        }

        return $res;
    } //fin registrar

    
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

    public function consultar(){
    
        $registro="SELECT u.*,tu.id_tipo, tu.nombre AS nombre_tipo
        FROM usuario u 
        INNER JOIN  tipo_usuario tu ON u.id_tipo = tu.id_tipo";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();

        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
            if ($resul){
                return $datos;
            } else{
                return $res = 0;
            }
    
    } //fin consultar

    // Metodo para llamar las Tipos existentes
    public function obtenerTipo() {
        $query = "SELECT * FROM tipo_usuario WHERE estatus = 1";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } 

        public function eliminar(){
            try {
                $registro = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
                $strExec = $this->conex->prepare($registro);
                $strExec->bindParam(':id_usuario', $this->id_usuario);
                $result = $strExec->execute();
                if ($result){
                    $res=array('respuesta'=>1,'accion'=>'eliminar');
                } else{
                    $res=array('respuesta'=>0,'accion'=>'eliminar');
                }

                return $res;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        public function actualizar(){
            $registro = "UPDATE usuario SET id_usuario = :id_usuario, nombre = :nombres, apellido = :apellidos,
            usuario = :usuario, clave = :clave, estatus = :estatus, id_tipo = :tipo_usuario
            WHERE id_usuario = :id_usuario";
    
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_usuario', $this->id_usuario);    
            $strExec->bindParam(':nombres', $this->nombres);
            $strExec->bindParam(':apellidos', $this->apellidos);
            $strExec->bindParam(':usuario', $this->usuario);
            $strExec->bindParam(':clave', $this->clave);
            $strExec->bindParam(':tipo_usuario', $this->tipo_usuario);
            $strExec->bindParam(':estatus', $this->estatus);
    
            $resul= $strExec->execute();
                if ($resul) {
                    $res=array('respuesta'=>1,'accion'=>'actualizar');
                }else{
                    $res=array('respuesta'=>0,'accion'=>'actualizar');
                 }
            return $res;
        }// fin actulizar
    
    public function get_Id_Usuario()
    {
        return $this->id_usuario;
    }

    public function set_Id_Usuario($id_usuario){
        $this->id_usuario = $id_usuario;
    }

    public function get_Nombres(){
        return $this->nombres;
    }

    public function set_Nombres($nombres){
        return $this->nombres = ucfirst(strtolower($nombres));
    }

    public function get_Apellidos() {
        return $this->apellidos;
    }
    
    public function set_Apellidos($apellidos) {
        $this->apellidos = ucfirst(strtolower($apellidos));
    }

   public function get_Usuario(){
    return $this->usuario;
   }

   public function set_Usuario($usuario){
    $this->usuario = strtolower($usuario);
   }

    public function get_Clave() {
        return $this->clave;
    }
    public function set_Clave($clave) {
        $this->clave = strtolower($clave);
    }

    public function get_Estatus(){
        return $this->estatus;
    }

    public function set_Estatus($estatus){
        $this->estatus = $estatus;
    }

    public function get_Tipo_usuario(){
        return $this->tipo_usuario;
    }
    public function set_Tipo_usuario($tipo_usuario){
        $this->tipo_usuario = $tipo_usuario;
    }
}
