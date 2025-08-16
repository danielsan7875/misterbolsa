<?php

    require_once 'conexion.php';

    class Tipousuario extends Conexion{

    private $conex;
    private $id_tipo;
    private $nombre;
    private $nivel;
    private $estatus;
 
    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }

   
    public function registrar(){

        $registro ="INSERT INTO tipo_usuario(nombre, nivel, estatus)
        VALUES(:nombre,:nivel, 1)";

        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':nivel', $this->nivel);
        $resul=$strExec->execute();
        if ($resul) {
            $res['respuesta'] = 1;
            $res['accion'] = 'incluir';
        } else {
            $res['respuesta'] = 0;
            $res['accion'] = 'incluir';
        }

        return $res;
    } //fin registrar

    public function buscar($data){
		$this->id_tipo = $data;
		$registro= "SELECT * from tipo_usuario WHERE id_tipo='".$this->id_tipo."'";
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

        $registro ="SELECT * FROM tipo_usuario WHERE estatus <5";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();

        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            $dato['datos'] = $datos;
            $dato['accion'] = 'consultar';
            return $dato;
        } else {
            return $res = 0;
        }
    
    } //fin consultar


    
    public function actualizar(){
		$registro = "UPDATE tipo_usuario SET id_tipo = :id_tipo, nombre = :nombre, nivel = :nivel, estatus = :estatus WHERE id_tipo = :id_tipo";

		$strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_tipo', $this->id_tipo);    
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':nivel', $this->nivel);
        $strExec->bindParam(':estatus', $this->estatus);

		$resul= $strExec->execute();
			if ($resul) {
				$res['respuesta'] = 1;
                $res['accion'] = 'actualizar';
			}else{
				$res['respuesta'] = 0;
                $res['accion'] = 'actualizar';
			 }
		return $res;
	}// fin actulizar
  

    public function eliminar(){ // Elimanado Logico
        try {

            $registro = "UPDATE tipo_usuario SET estatus = 5 WHERE id_tipo = :id_tipo";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_tipo', $this->id_tipo);
            $result = $strExec->execute();
            if ($result) {
                $res['respuesta'] = 1;
                $res['accion'] = 'eliminar';
            } else {
                $res['respuesta'] = 0;
                $res['accion'] = 'eliminar';
            }
            return $res;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

   
    
    public function get_Id_tipo(){
        return $this->id_tipo;
    }

    public function set_Id_tipo($id_tipo){
        $this->id_tipo =$id_tipo;
    }

    public function get_Nombre(){
        return $this->nombre;
    }
    public function set_Nombre($nombre){
        $this->nombre=ucfirst(strtolower($nombre));
    }

    public function get_Nivel(){
        return $this->nivel;
    }

    public function set_Nivel($nivel){
        $this->nivel=$nivel;
    }

    public function get_Estatus(){
        return $this->estatus;
    }

    public function set_Estatus($estatus){
        $this->estatus=$estatus;
    }
        

    }
?>