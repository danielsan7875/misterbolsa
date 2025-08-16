<?php

    require_once 'conexion.php';

    class Compra extends Conexion{

    private $conex;
    private $id_compra;
    private $id_producto;
    private $cantidad;
    private $fecha;

    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }



    public function registrar() {
        try {
            // Iniciar transacción
            $this->conex->beginTransaction();
    
            // Insertar registro en la tabla compra
            $registro = "INSERT INTO compra(id_producto, cantidad, fecha)
            VALUES(:id_producto, :cantidad, :fecha)";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_producto', $this->id_producto);
            $strExec->bindParam(':cantidad', $this->cantidad);
            $strExec->bindParam(':fecha', $this->fecha);
            
            // Ejecutar la inserción
            $resul = $strExec->execute();
            
            if ($resul) {
                // Actualizar stock en la tabla producto
                $this->actualizarStockProducto($this->id_producto, $this->cantidad);
                
                // Confirmar transacción
                $this->conex->commit();
                $res = array('respuesta' => 1, 'accion' => 'registrar');
            } else {
                // Revertir transacción si la inserción falla
                $this->conex->rollBack();
                $res = array('respuesta' => 0, 'accion' => 'registrar');
            }
        } catch (Exception $e) {
            // Revertir transacción si ocurre un error
            $this->conex->rollBack();
            echo "Error: " . $e->getMessage() . "<br>";
            $res = array('respuesta' => 0, 'accion' => 'registrar');
        }
    
        return $res;
    }
    
    private function actualizarStockProducto($id_producto, $cantidad_comprada) {
        // Obtener el stock actual del producto
        $stmt = $this->conex->prepare("SELECT stock FROM producto WHERE id_producto = :id_producto");
        $stmt->bindParam(":id_producto", $id_producto);
        $stmt->execute();
        $stock_actual = $stmt->fetchColumn();
    
        // Actualizar el stock disponible
        $nuevo_stock = $stock_actual + $cantidad_comprada;
    
        // Guardar el nuevo stock en la base de datos
        $stmt = $this->conex->prepare("UPDATE producto SET stock = :stock WHERE id_producto = :id_producto");
        $stmt->bindParam(":stock", $nuevo_stock);
        $stmt->bindParam(":id_producto", $id_producto);
        $stmt->execute();
    }

    public function consultar(){

        $registro ="SELECT c.*, m.nombre AS nombre_marca, p.nombre AS nombre_presentacion, pro.nombre
        FROM compra c
        INNER JOIN producto pro ON c.id_producto = pro.id_producto
        INNER JOIN marca m ON pro.id_marca = m.id_marca
        INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion";
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

    public function eliminar(){ // Eliminado Fisico
        try {
            $registro = "DELETE FROM compra WHERE id_compra = :id_compra";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_compra', $this->id_compra);
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

    
    
    public function get_Id_compra(){
        return $this->id_compra;
    }

    public function set_Id_compra($id_compra){
        $this->id_compra =$id_compra;
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
   

        

    }

/*

 $registro ="SELECT c.*, m.nombre AS nombre_marca, p.nombre AS nombre_presentacion, pro.nombre
        FROM compra c
        INNER JOIN producto pro ON c.id_compra = pro.id_producto
        INNER JOIN marca m ON pro.id_marca = m.id_marca
        INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion";
*/
 
?>        