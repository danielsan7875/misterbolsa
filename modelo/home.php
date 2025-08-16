<?php

    require_once 'conexion.php';

    class Home extends Conexion{

    private $conex;

    private $id_dolar;
    private $precios;

    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }


     // Metodo para llamar las medidas existentes
     public function obtenerDolar() {
        $query = "SELECT * FROM dolar WHERE id_dolar = 1";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function contarProductos() {
        $query = "SELECT COUNT(*) as total FROM producto WHERE estatus <5";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function contarCompras() {
        $query = "SELECT COUNT(*) as total FROM compra";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
    
    public function contarVentas() {
        $query = "SELECT COUNT(*) as total FROM venta";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
    
    
    public function tasadolar(){
		$registro = "UPDATE dolar SET id_dolar = :id_dolar, precios = :precios WHERE id_dolar = :id_dolar";

		$strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_dolar', $this->id_dolar);    
        $strExec->bindParam(':precios', $this->precios);

		$resul= $strExec->execute();
			if ($resul) {
                $res['respuesta'] = 1;
                $res['accion'] = 'tasadolar';
			}else{
                $res['respuesta'] = 0;
                $res['accion'] = 'tasadolar';
			 }
		return $res;
	}// fin actulizar
 


    public function get_Id_dolar(){
        return $this->id_dolar;
    }

    public function set_Id_dolar($id_dolar){
        $this->id_dolar =$id_dolar;
    }

    public function get_Precios(){
        return $this->precios;
    }

    public function set_Precios($precios){
        $this->precios= $precios;
    }

    }


?>        