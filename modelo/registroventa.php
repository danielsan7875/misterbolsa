<?php

    require_once 'conexion.php';

    class Registroventa extends Conexion{

    private $conex;
    private $id_venta;
    private $fecha;
    private $total_bs;
    private $total_dl;
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

   public function registrar($id_producto, $cantidad, $precio, $maode, $id_metodopago, $monto_dolares, $monto_bs, $referencia) {
    try {
        // Iniciar transacción
        $this->conex->beginTransaction();

        // 1️⃣ Registrar venta
        $sql_venta = "INSERT INTO venta (fecha, total_dl, total_bs) 
                      VALUES (:fecha, :total_dl, :total_bs)";
        $stmt_venta = $this->conex->prepare($sql_venta);
        $stmt_venta->bindParam(':fecha', $this->fecha);
        $stmt_venta->bindParam(':total_dl', $this->total_dl);
        $stmt_venta->bindParam(':total_bs', $this->total_bs);
        $stmt_venta->execute();

        // ID de la venta recién creada
        $id_venta = $this->conex->lastInsertId();

        // Validación mínima
        if (empty($id_producto) || empty($cantidad) || empty($precio) || empty($maode)) {
            $this->conex->rollBack();
            return ['respuesta' => 0, 'accion' => 'registrar'];
        }

        // 2️⃣ Registrar detalle de venta
        $sql_detalle = "INSERT INTO detalle_venta 
                        (id_venta, id_producto, cantidad, precio_unitario, maode)
                        VALUES (:id_venta, :id_producto, :cantidad, :precio, :maode)";
        $stmt_detalle = $this->conex->prepare($sql_detalle);

        // 3️⃣ Actualizar stock
        $sql_stock = "UPDATE producto SET stock = stock - :cantidad 
                      WHERE id_producto = :id_producto";
        $stmt_stock = $this->conex->prepare($sql_stock);

        foreach ($id_producto as $i => $producto) {

            // Insertar detalle
            $stmt_detalle->bindParam(':id_venta', $id_venta);
            $stmt_detalle->bindParam(':id_producto', $producto);
            $stmt_detalle->bindParam(':cantidad', $cantidad[$i]);
            $stmt_detalle->bindParam(':precio', $precio[$i]);
            $stmt_detalle->bindParam(':maode', $maode[$i]);
            $stmt_detalle->execute();

            // Actualizar stock
            $stmt_stock->bindParam(':cantidad', $cantidad[$i]);
            $stmt_stock->bindParam(':id_producto', $producto);
            $stmt_stock->execute();
        }

        // 4️⃣ Registrar detalle de pago
        $sql_pago = "INSERT INTO detalle_pago 
                     (id_venta, id_metodopago, monto_dolares, monto_bs, referencia)
                     VALUES (:id_venta, :id_metodopago, :monto_dolares, :monto_bs, :referencia)";
        $stmt_pago = $this->conex->prepare($sql_pago);

        foreach ($id_metodopago as $i => $metodo) {
            $stmt_pago->bindParam(':id_venta', $id_venta);
            $stmt_pago->bindParam(':id_metodopago', $metodo);
            $stmt_pago->bindParam(':monto_dolares', $monto_dolares[$i]);
            $stmt_pago->bindParam(':monto_bs', $monto_bs[$i]);
            $stmt_pago->bindParam(':referencia', $referencia[$i]);
            $stmt_pago->execute();
        }

        // Confirmar transacción
        $this->conex->commit();
        return ['respuesta' => 1, 'accion' => 'registrar'];

    } catch (Exception $e) {
        $this->conex->rollBack();
        return ['respuesta' => 0, 'accion' => 'registrar', 'error' => $e->getMessage()];
    }
}



  // Metodo para llamar las medidas existentes
     public function obtenerProducto(){

        $registro ="SELECT pro.*, m.nombre AS nombre_marca, p.nombre AS nombre_presentacion 
        FROM producto pro
        INNER JOIN marca m ON pro.id_marca = m.id_marca
        INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
        WHERE pro.estatus <5
        ORDER BY pro.id_producto DESC";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();

        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
            if ($resul){
                return $datos;
            } else{
                return $res = 0;
            }
    
    } //fin consultar

    public function obtenerDolar() {
        $query = "SELECT * FROM dolar WHERE id_dolar = 1";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

     public function obtenerMetodopago() {
        $query = "SELECT * FROM metodopago WHERE estatus =1";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } 


    public function consultar() {
    $registro = "SELECT v.*, dv.*, m.nombre AS nombre_marca, p.nombre AS nombre_presentacion, pro.nombre
        FROM venta v
        INNER JOIN detalle_venta dv ON v.id_venta = dv.id_venta
        INNER JOIN producto pro ON dv.id_producto = pro.id_producto
        INNER JOIN marca m ON pro.id_marca = m.id_marca
        INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
        WHERE DATE(v.fecha) = CURDATE()
        ORDER BY v.id_venta DESC";

    $consulta = $this->conex->prepare($registro);
    $resul = $consulta->execute();

    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    return $resul ? $datos : 0;
}

    
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

    public function get_Total_Dl(){
        return $this->total_dl;
    }

    public function set_Total_Dl($total_dl){
        $this->total_dl = $total_dl;
    }

     public function get_Total_Bs(){
        return $this->total_bs;
    }

    public function set_Total_Bs($total_bs){
        $this->total_bs = $total_bs;
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