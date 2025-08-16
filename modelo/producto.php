<?php

    require_once 'conexion.php';

    class Producto extends Conexion{

    private $conex;
    private $id_producto;
    private $nombre;
    private $marca;
    private $presentacion;
    private $stock_min;
    private $stock;
    private $precio;
    private $preciomayor;
    private $cantidadmayor;
    private $estatus;


    private $id_dolar;
    private $precios;

    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }


    public function buscar($data)
	{
		$this->id_producto = $data;
		$registro= "SELECT pro.*, m.nombre AS nombre_marca, p.nombre AS nombre_presentacion 
        FROM producto pro
        INNER JOIN marca m ON pro.id_marca = m.id_marca
        INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
        WHERE pro.id_producto='".$this->id_producto."'";
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

        $registro ="INSERT INTO producto(nombre, id_marca, id_presentacion, stock, stock_minimo, precio_unitario,precio_mayor, cantidad_mayor, estatus)
        VALUES(:nombre,:marca, :presentacion,0,:stock_min,:precio, :preciomayor, :cantidadmayor, 1)";

        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':marca', $this->marca);
        $strExec->bindParam(':presentacion', $this->presentacion);
        $strExec->bindParam(':stock_min', $this->stock_min);
        $strExec->bindParam(':precio', $this->precio);
        $strExec->bindParam(':preciomayor', $this->preciomayor);
        $strExec->bindParam(':cantidadmayor', $this->cantidadmayor);
        $resul=$strExec->execute();
        if($resul){
            $res = array('respuesta' => 1, 'accion' => 'registrar');
        }else{
            $res = array('respuesta' => 0, 'accion' => 'registrar');
        }

        return $res;
    } //fin registrar

    public function consultar(){

        $registro ="SELECT pro.*, m.nombre AS nombre_marca, p.nombre AS nombre_presentacion 
        FROM producto pro
        INNER JOIN marca m ON pro.id_marca = m.id_marca
        INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
        WHERE pro.estatus <5";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();

        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
            if ($resul){
                return $datos;
            } else{
                return $res = 0;
            }
    
    } //fin consultar

    // Metodo para llamar las Presentaciones existentes
    public function obtenerPresentacion() {
        $query = "SELECT * FROM presentacion WHERE estatus = 1";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    
     // Metodo para llamar las medidas existentes
     public function obtenerMarca() {
        $query = "SELECT * FROM marca WHERE estatus =1";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } 

     // Metodo para llamar las medidas existentes
     public function obtenerDolar() {
        $query = "SELECT * FROM dolar WHERE id_dolar = 1";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } 
    


    public function actualizar(){
		$registro = "UPDATE producto SET id_producto = :id_producto, nombre = :nombre, id_marca = :marca,
        id_presentacion = :presentacion, stock = :stock, stock_minimo = :stock_min, precio_unitario = :precio,
        precio_mayor = :preciomayor, cantidad_mayor = :cantidadmayor, estatus = :estatus
        WHERE id_producto = :id_producto";

		$strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_producto', $this->id_producto);    
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':marca', $this->marca);
        $strExec->bindParam(':presentacion', $this->presentacion);
        $strExec->bindParam(':stock_min', $this->stock_min);
        $strExec->bindParam(':precio', $this->precio);
        $strExec->bindParam(':preciomayor', $this->preciomayor);
        $strExec->bindParam(':cantidadmayor', $this->cantidadmayor);
        $strExec->bindParam(':stock', $this->stock);
        $strExec->bindParam(':estatus', $this->estatus);

		$resul= $strExec->execute();
			if ($resul) {
				$res = array('respuesta' => 1, 'accion' => 'actualizar');
			}else{
				$res = array('respuesta' => 0, 'accion' => 'actualizar');
			 }
		return $res;
	}// fin actulizar


    
    public function eliminar(){ // Elimanado Logico
        try {

            $registro = "UPDATE producto SET estatus = 5 WHERE id_producto = :id_producto";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_producto', $this->id_producto);
            $result = $strExec->execute();
    
            if ($result) {
                $res=array('respuesta'=>1,'accion'=>'eliminar');
            } else {
                $res=array('respuesta'=>0,'accion'=>'eliminar');
            }
            return $res;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function tasadolar(){
		$registro = "UPDATE dolar SET id_dolar = :id_dolar, precios = :precios WHERE id_dolar = :id_dolar";

		$strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_dolar', $this->id_dolar);    
        $strExec->bindParam(':precios', $this->precios);

		$resul= $strExec->execute();
			if ($resul) {
                $res=array('respuesta'=>1,'accion'=>'tasadolar');
			}else{
                $res=array('respuesta'=>0,'accion'=>'tasadolar');
			 }
		return $res;
	}// fin actulizar
 

    public function get_Id_producto(){
        return $this->id_producto;
    }

    public function set_Id_producto($id_producto){
        $this->id_producto =$id_producto;
    }

    public function get_Nombre(){
        return $this->nombre;
    }

    public function set_Nombre($nombre){
        $this->nombre=strtolower($nombre);
    }

    public function get_Marca(){
        return $this->id_producto;
    }

    public function set_Marca($marca){
        $this->marca = $marca;
    }

    public function get_Presentacion(){
        return $this->presentacion;
    }

    public function set_Presentacion($presentacion){
        $this->presentacion = $presentacion;
    }

    public function get_Stock(){
        return $this->stock;
    }

    public function set_Stock($stock){
        $this->stock = $stock;
    }

    public function get_stock_min(){
        return $this->stock_min;
    }

    public function set_Stock_min($stock_min){
        $this->stock_min = $stock_min;
    }

    public function get_Precio(){
        return $this->precio;
    }

    public function set_Precio($precio){
        $this->precio = $precio;
    }

    public function get_Cantidamayor(){
        return $this->cantidadmayor;
    }

    public function set_Cantidadmayor($cantidadmayor){
        $this->cantidadmayor = $cantidadmayor;
    }

    public function get_Preciomayor(){
        return $this->preciomayor;
    }

    public function set_Preciomayor($preciomayor){
        $this->preciomayor = $preciomayor;
    }

    public function get_Estatus(){
        return $this->estatus;
    }
    public function set_Estatus($estatus){
        $this->estatus=$estatus;
    }



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