<?php

require_once 'conexion.php';

class Perfil extends Conexion{

    private $conex;
    private $id_usuario;
    private $nombres;
    private $apellidos;
    private $usuario;
    private $clave;
    private $nueva;
    private $confirmar;
    

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

        public function actualizarClave($id_usuario, $nuevaClave){
            $registro = "UPDATE usuario SET clave = :nueva WHERE id_usuario = :id_usuario";
    
            $stmt = $this->conex->prepare($registro);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':nueva', $nuevaClave);
    
            $resul= $stmt->execute();
                if ($resul) {
                    $res=array('respuesta'=>1,'accion'=>'clave');
                }else{
                    $res=array('respuesta'=>0,'accion'=>'clave');
                 }
            return $res;
        }// fin actulizar



       public function consultarClaveActual($id_usuario){
            $sql = "SELECT clave FROM usuario WHERE id_usuario = :id_usuario";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }




    
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

     public function get_Nueva() {
        return $this->clave;
    }
    public function set_Nueva($clave) {
        $this->clave = strtolower($clave);
    }

     public function get_Confirmar() {
        return $this->clave;
    }
    public function set_confimar($clave) {
        $this->clave = strtolower($clave);
    }

}
