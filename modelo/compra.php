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



   public function registrar($id_producto, $cantidad) {
    try {
        // Iniciar transacción
        $this->conex->beginTransaction();

        if (!empty($id_producto) && !empty($cantidad)) {
            // Insertar en tabla compra (solo fecha)
            $registro_compra = "INSERT INTO compra (fecha) VALUES (:fecha)";
            $strExec_compra = $this->conex->prepare($registro_compra);
            $strExec_compra->bindParam(':fecha', $this->fecha);
            $strExec_compra->execute();

            // Obtener el ID de la compra recién creada
            $id_compra = $this->conex->lastInsertId();

            // Preparar inserción en detalle_compra
            $registro_detalle = "INSERT INTO detalle_compra (id_compra, id_producto, cantidad)
                                 VALUES (:id_compra, :id_producto, :cantidad)";
            $strExec_detalle = $this->conex->prepare($registro_detalle);

            // Preparar actualización de stock
            $actualizar_stock = "UPDATE producto SET stock = stock + :cantidad
                                 WHERE id_producto = :id_producto";
            $strExec_stock = $this->conex->prepare($actualizar_stock);

            foreach ($id_producto as $i => $producto) {
                // Insertar detalle
                $strExec_detalle->bindParam(':id_compra', $id_compra);
                $strExec_detalle->bindParam(':id_producto', $producto);
                $strExec_detalle->bindParam(':cantidad', $cantidad[$i]);
                $strExec_detalle->execute();

                // Actualizar stock
                $strExec_stock->bindParam(':cantidad', $cantidad[$i]);
                $strExec_stock->bindParam(':id_producto', $producto);
                $strExec_stock->execute();
            }

            // Confirmar transacción
            $this->conex->commit();
            $res = array('respuesta' => 1, 'accion' => 'registrar');
        } else {
            // Datos incompletos
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


    public function consultar(){

    $registro ="SELECT 
                    c.*, 
                    dc.*, 
                    m.nombre AS nombre_marca, 
                    p.nombre AS nombre_presentacion, 
                    pro.nombre
                FROM compra c
                INNER JOIN detalle_compra dc ON c.id_compra = dc.id_compra
                INNER JOIN producto pro ON dc.id_producto = pro.id_producto
                INNER JOIN marca m ON pro.id_marca = m.id_marca
                INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
                WHERE c.fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                ORDER BY c.id_compra DESC";

    $consulta = $this->conex->prepare($registro);
    $resul = $consulta->execute();

    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

    return $resul ? $datos : 0;
}



  // Metodo para llamar las medidas existentes
    public function buscarProducto($buscar) {

        $sql = "SELECT 
                    pro.id_producto,
                    pro.nombre AS nombre_producto,
                    m.nombre AS nombre_marca,
                    p.nombre AS nombre_presentacion
                FROM producto pro
                INNER JOIN marca m ON pro.id_marca = m.id_marca
                INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
                WHERE pro.estatus = 1
                AND (
                        pro.nombre LIKE :buscar
                    OR m.nombre LIKE :buscar
                    OR p.nombre LIKE :buscar
                )
                ORDER BY pro.nombre
                LIMIT 20";

        $stmt = $this->conex->prepare($sql);
        $stmt->bindValue(':buscar', "%$buscar%", PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ultimosProductos() {

        $sql = "SELECT 
                    pro.id_producto,
                    pro.nombre AS nombre_producto,
                    m.nombre AS nombre_marca,
                    p.nombre AS nombre_presentacion
                FROM producto pro
                INNER JOIN marca m ON pro.id_marca = m.id_marca
                INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
                WHERE pro.estatus = 1
                ORDER BY pro.id_producto DESC
                LIMIT 20";

        $stmt = $this->conex->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function consultarFiltrado($filtro = null, $desde = null, $hasta = null) {

    $where = "";
    $params = [];

    if ($filtro == "15") {
        $where = "WHERE c.fecha >= DATE_SUB(CURDATE(), INTERVAL 15 DAY)";
    }

    if ($filtro == "mes") {
        $where = "WHERE MONTH(c.fecha) = MONTH(CURDATE())
                  AND YEAR(c.fecha) = YEAR(CURDATE())";
    }

    if (!empty($desde) && !empty($hasta)) {
        $where = "WHERE c.fecha BETWEEN :desde AND :hasta";
        $params[':desde'] = $desde;
        $params[':hasta'] = $hasta;
    }

    $sql = "SELECT c.*, dc.*, m.nombre AS nombre_marca,
                   p.nombre AS nombre_presentacion, pro.nombre
            FROM compra c
            INNER JOIN detalle_compra dc ON c.id_compra = dc.id_compra
            INNER JOIN producto pro ON dc.id_producto = pro.id_producto
            INNER JOIN marca m ON pro.id_marca = m.id_marca
            INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
            $where
            ORDER BY c.id_compra DESC";

    $consulta = $this->conex->prepare($sql);
    $consulta->execute($params);

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