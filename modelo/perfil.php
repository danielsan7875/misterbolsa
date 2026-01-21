<?php

require_once 'conexion.php';

class Perfil extends Conexion{

    private $conex;
    private $id_usuario;
    private $nombres;
    private $apellidos;
    private $usuario;
    private $clave;
    

    function __construct()
    {
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }


   
        public function consulta($id_usuario){
    
        $registro="SELECT nombre, apellido, usuario FROM usuario WHERE id_usuario = :id_usuario";
        $consulta = $this->conex->prepare($registro);
           $consulta->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $resul = $consulta->execute();

        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
            if ($resul){
                return $datos;
            } else{
                return $res = 0;
            }
    
    } //fin consultar

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
            $registro = "UPDATE usuario SET nombre = :nombres, apellido = :apellidos, usuario = :usuario WHERE id_usuario = :id_usuario";
    
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_usuario', $this->id_usuario);    
            $strExec->bindParam(':nombres', $this->nombres);
            $strExec->bindParam(':apellidos', $this->apellidos);
            $strExec->bindParam(':usuario', $this->usuario);
    
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

}
