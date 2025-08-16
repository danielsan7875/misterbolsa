<?php

    require_once 'conexion.php';

    class Presentacion extends Conexion{

    private $conex;
    private $id_presentacion;
    private $nombre;
    private $estatus;

    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }



    public function buscar($data)
	{
		$this->id_presentacion = $data;
		$registro= "SELECT * from presentacion WHERE id_presentacion='".$this->id_presentacion."'";
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
   
    public function registrar(){

        $registro ="INSERT INTO presentacion(nombre, estatus)
        VALUES(:nombre, 1)";

        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);

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

    public function consultar(){

        $registro ="SELECT * FROM presentacion WHERE estatus < 5 ";
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
		$registro = "UPDATE presentacion SET id_presentacion = :id_presentacion, nombre = :nombre, estatus = :estatus WHERE id_presentacion = :id_presentacion";

		$strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_presentacion', $this->id_presentacion);    
        $strExec->bindParam(':nombre', $this->nombre);
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

            $registro = "UPDATE presentacion SET estatus = 5 WHERE id_presentacion = :id_presentacion";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_presentacion', $this->id_presentacion);
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
    
    public function get_Id_presentacion(){
        return $this->id_presentacion;
    }

    public function set_Id_presentacion($id_presentacion){
        $this->id_presentacion =$id_presentacion;
    }

    public function get_Nombre(){
        return $this->nombre;
    }

    public function set_Nombre($nombre){
        $this->nombre=ucfirst(strtolower($nombre)); 
    }
    public function get_Estatus(){
        return $this->estatus;
    }

    public function set_Estatus($estatus){
        $this->estatus=$estatus;
    }

        

    }


?>        