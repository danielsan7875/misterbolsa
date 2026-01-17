<?php

    require_once 'conexion.php';

    class Marca extends Conexion{

    private $conex;
    private $id_marca;
    private $nombre;
    private $estatus;

    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }



    public function buscar($data)
	{
		$this->id_marca = $data;
		$registro= "SELECT * from marca WHERE id_marca='".$this->id_marca."'";
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

        $registro ="INSERT INTO marca(nombre, estatus)
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

    /*
    public function consultar(){

        $registro ="SELECT * FROM marca WHERE estatus <5
        ORDER BY id_marca DESC";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
            if ($resul){
                return $datos;
            } else{
                return $res = 0;
            }
    
    } //fin consultar
*/

public function consultar($limite = 200){

    $registro ="SELECT * 
                FROM marca 
                WHERE estatus < 5
                ORDER BY id_marca DESC
                LIMIT :limite";

    $consulta = $this->conex->prepare($registro);
    $consulta->bindParam(':limite', $limite, PDO::PARAM_INT);
    $consulta->execute();

    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

    return $datos ?: 0;
}

    public function contarTotal(){
    $sql = "SELECT COUNT(*) AS total FROM marca WHERE estatus < 5";
    $consulta = $this->conex->prepare($sql);
    $consulta->execute();
    $fila = $consulta->fetch(PDO::FETCH_ASSOC);
    return $fila['total'];
}


    public function actualizar(){
		$registro = "UPDATE marca SET id_marca = :id_marca, nombre = :nombre, estatus = :estatus WHERE id_marca = :id_marca";

		$strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_marca', $this->id_marca);    
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

    


    public function eliminar2(){ // Eliminado Fisico
        try {
            $registro = "DELETE FROM marca WHERE id_marca = :id_marca";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_marca', $this->id_marca);
            $result = $strExec->execute();
            if ($result){
                return $res = 1;
            } else{
                return $res = 0;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    
    public function eliminar(){ // Elimanado Logico
        try {

            $registro = "UPDATE marca SET estatus = 5 WHERE id_marca = :id_marca";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_marca', $this->id_marca);
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
    
    public function get_Id_marca(){
        return $this->id_presentacion;
    }

    public function set_Id_marca($id_marca){
        $this->id_marca =$id_marca;
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