<?php

require_once 'conexion.php';

class Login extends Conexion {

    private $conex;
    private $id_usuario;
    private $usuario;
    private $clave;
    private $nombres;
    private $apellidos;
    private $tipo_usuario;

    function __construct(){ // Metodo para BD
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }

    public function verificarUsuario() {
        $consulta = "SELECT u.*,tu.id_tipo, tu.nombre AS nombre_tipo, tu.nivel
        FROM usuario u 
        INNER JOIN  tipo_usuario tu ON u.id_tipo = tu.id_tipo
        WHERE u.usuario = :usuario 
        AND u.clave = :clave";
        $strExec = $this->conex->prepare($consulta);
        $strExec->bindParam(':usuario', $this->usuario);
        $strExec->bindParam(':clave', $this->clave);
        $strExec->execute();
        $resultado = $strExec->fetchObject();
        if ($resultado) {
            if ($resultado->estatus != 1) {
                $resultado->noactiva = true; // para indicar que la cuenta está suspendida o inactiva
            }
        }
        return $resultado;
    }
   
    public function get_IdUsuario() {
        return $this->id_usuario;
    }

    public function set_IdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function get_Usuario() {
        return $this->usuario;
    }

    public function set_Usuario($usuario) {
        $this->usuario = strtolower($usuario);
    }

    public function get_Clave() {
        return $this->clave;
    }

    public function set_Clave($clave) { 
        $this->clave = strtolower($clave);
    } //strtolower letras minusculas

    public function get_Nombres() {
        return $this->nombres;
    }

    public function set_Nombres($nombres) {
        $this->nombres = $nombres;
    }

    public function get_Apellidos() {
        return $this->apellidos;
    }

    public function set_Apellidos($apellidos) {
        $this->apellidos = $apellidos;
    }

    public function get_TipoUsuario() {
        return $this->tipo_usuario;
    }

    public function set_TipoUsuario($tipo_usuario) {
        $this->tipo_usuario = $tipo_usuario;
    }
}