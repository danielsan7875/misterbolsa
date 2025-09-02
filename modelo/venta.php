<?php

    require_once 'conexion.php';

    class Venta extends Conexion{

    private $conex;
    private $id_venta;
    private $fecha;
    private $total;
    private $id_detalle;
    private $id_productos;
    private $cantidad;
    private $maode;
    private $precio;

    private $id_dolar;
    private $precios;


    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }

    public function registrar($id_producto, $cantidad, $precio, $maode) {
        try {
            // Iniciar transacción
            $this->conex->beginTransaction();

            // Registro en tabla venta
            $registro_venta = "INSERT INTO venta (fecha, total) VALUES (:fecha, :total)";
            $strExec_venta = $this->conex->prepare($registro_venta);
            $strExec_venta->bindParam(':fecha', $this->fecha);
            $strExec_venta->bindParam(':total', $this->total);
            $strExec_venta->execute();

            // Obtener el ID de la Venta recién creada
            $id_venta = $this->conex->lastInsertId();

            if (!empty($id_producto) && !empty($cantidad) && !empty($precio) && !empty($maode)) {
                $registro_detalle = "INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, maode)
                 VALUES (:id_venta, :id_producto, :cantidad, :precio, :maode)";
                $strExec_detalle = $this->conex->prepare($registro_detalle);

                foreach ($id_producto as $i => $producto) {
                    $strExec_detalle->bindParam(':id_venta', $id_venta);
                    $strExec_detalle->bindParam(':id_producto', $producto);
                    $strExec_detalle->bindParam(':cantidad', $cantidad[$i]);
                    $strExec_detalle->bindParam(':precio', $precio[$i]);
                    $strExec_detalle->bindParam(':maode', $maode[$i]);
                    $strExec_detalle->execute();

                    // Actualizar el stock del producto
                $actualizar_stock = "UPDATE producto SET stock = stock - :cantidad WHERE id_producto = :id_producto";
                $strExec_stock = $this->conex->prepare($actualizar_stock);
                $strExec_stock->bindParam(':cantidad', $cantidad[$i]);
                $strExec_stock->bindParam(':id_producto', $producto);
                $strExec_stock->execute();
                }
            }else{
                $this->conex->rollBack();
                $res = array('respuesta' => 0, 'accion' => 'registrar');
            }

            // Confirmar transacción
            $this->conex->commit();
            $res = array('respuesta' => 1, 'accion' => 'registrar');
        } catch (Exception $e) {
            // Revertir transacción si ocurre un error
            $this->conex->rollBack();
            echo "Error: " . $e->getMessage() . "<br>";
            $res = array('respuesta' => 0, 'accion' => 'registrar');
        }

        return $res;
    }
    

    public function consultar(){

        $registro ="SELECT v.*, dv.*, m.nombre AS nombre_marca, p.nombre AS nombre_presentacion, pro.nombre
        FROM venta v
        INNER JOIN detalle_venta dv ON v.id_venta = dv.id_venta
        INNER JOIN producto pro ON dv.id_producto = pro.id_producto
        INNER JOIN marca m ON pro.id_marca = m.id_marca
        INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
        ORDER BY v.id_venta DESC";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();

        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
            if ($resul){
                return $datos;
            } else{
                return $res = 0;
            }
    
    } //fin consultar

  // Metodo para llamar las medidas existentes
    public function obtenerProducto() {
    $query = "SELECT pro.*, m.nombre AS nombre_marca, p.nombre AS nombre_presentacion 
        FROM producto pro
        INNER JOIN marca m ON pro.id_marca = m.id_marca
        INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
        WHERE pro.estatus =1";
    $consulta = $this->conex->prepare($query);
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
    
    }

    public function obtenerDolar() {
        $query = "SELECT * FROM dolar WHERE id_dolar = 1";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }



    public function eliminar(){ // Eliminado Fisico
        try {
            $registro = "DELETE FROM venta WHERE id_venta = :id_venta";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_venta', $this->id_venta);
            $result = $strExec->execute();
            if ($result){
                $res = array('respuesta' => 1, 'accion' => 'eliminar');
            } else{
                $res = array('respuesta' => 0, 'accion' => 'eliminar');
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
                $res = array('respuesta' => 1, 'accion' => 'tasadolar');
			}else{
                $res = array('respuesta' => 0, 'accion' => 'tasadolar');
			 }
		return $res;
	}// fin actulizar
  
    
    public function get_Id_venta(){
        return $this->id_venta;
    }

    public function set_Id_venta($id_venta){
        $this->id_venta =$id_venta;
    }

    public function get_Id_producto(){
        return $this->id_producto;
    }

    public function set_Id_producto($id_producto){
        $this->id_producto = $id_producto;
    }

    public function get_Cantidad(){
        return $this->cantidad;
    }

    public function set_Cantidad($cantidad){
        $this->cantidad = $cantidad;
    }

    public function get_Fecha(){
        return $this->fecha;
    }

    public function set_Fecha($fecha){
        $this->fecha = $fecha;
    }

    public function get_Id_detalle(){
        return $this->id_detalle;
    }

    public function set_Id_detalle($id_detalle){
        $this->id_detalle = $id_detalle;
    }

    public function get_Total(){
        return $this->total;
    }

    public function set_Total($total){
        $this->total = $total;
    }

    public function get_Precio(){
        return $this->precio;
    }

    public function set_Precio($precio){
        $this->precio = $precio;
    }

    public function get_Maode(){
        return $this->maode;
    }

    public function set_Maode($maode){
        $this->maode = $maode;
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