<?php

    require_once 'conexion.php';

    class Perdidas extends Conexion{

    private $conex;
    private $id_perdida;
    private $id_producto;
    private $cantidad;
    private $fecha;
    private $notas;

    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }


    public function registrar() {
        // Primero, validamos el stock disponible
        $stockDisponible = $this->obtenerStockDisponible($this->id_producto);
        
        if ($this->cantidad > $stockDisponible) {
            $res=array('respuesta'=>-1,'accion'=>'registrar');
            return $res; 
        }
    
        // Si hay suficiente stock, procedemos a registrar la pérdida
        $registro = "INSERT INTO perdida(fecha, id_producto, cantidad, nota)
                     VALUES(:fecha, :id_producto, :cantidad, :nota)";
    
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':fecha', $this->fecha);
        $strExec->bindParam(':id_producto', $this->id_producto);
        $strExec->bindParam(':cantidad', $this->cantidad);
        $strExec->bindParam(':nota', $this->nota);
    
        $resul = $strExec->execute();
        
        if ($resul) {
            // Restamos el stock
            $this->restarStock($this->id_producto, $this->cantidad);
            $res=array('respuesta'=>1,'accion'=>'registrar'); // Registro exitoso
        } else {
            $res=array('respuesta'=>0,'accion'=>'registrar'); // Error al registrar
        }
        return $res; 
    }
    
    private function obtenerStockDisponible($id_producto) {
        $query = "SELECT stock FROM producto WHERE id_producto = :id_producto";
        $stmt = $this->conex->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['stock'] : 0; // Retorna el stock disponible
    }
    
    private function restarStock($id_producto, $cantidad) {
        $query = "UPDATE producto SET stock = stock - :cantidad WHERE id_producto = :id_producto";
        $stmt = $this->conex->prepare($query);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
    }
    
    
   /* 
    public function consultar(){

        $registro ="SELECT per.*, m.nombre AS nombre_marca, p.nombre AS nombre_presentacion, pro.nombre
        FROM perdida per
        INNER JOIN producto pro ON per.id_producto = pro.id_producto
        INNER JOIN marca m ON pro.id_marca = m.id_marca
        INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
        ORDER BY per.id_perdida DESC";
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

public function consultar(){

    $registro ="SELECT 
                    per.*, 
                    m.nombre AS nombre_marca, 
                    p.nombre AS nombre_presentacion, 
                    pro.nombre AS nombre_producto
                FROM perdida per
                INNER JOIN producto pro ON per.id_producto = pro.id_producto
                INNER JOIN marca m ON pro.id_marca = m.id_marca
                INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
                WHERE per.fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                ORDER BY per.id_perdida DESC";

    $consulta = $this->conex->prepare($registro);
    $resul = $consulta->execute();

    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

    return $resul ? $datos : 0;
}


public function consultarFiltrado($filtro = null, $desde = null, $hasta = null) {

    $where = "";
    $params = [];

    // FILTRO: Últimos 15 días
    if ($filtro == "15") {
        $where = "WHERE per.fecha >= DATE_SUB(CURDATE(), INTERVAL 15 DAY)";
    }

    // FILTRO: Mes actual
    if ($filtro == "mes") {
        $where = "WHERE MONTH(per.fecha) = MONTH(CURDATE())
                  AND YEAR(per.fecha) = YEAR(CURDATE())";
    }

    // FILTRO: Rango personalizado
    if (!empty($desde) && !empty($hasta)) {
        $where = "WHERE per.fecha BETWEEN :desde AND :hasta";
        $params[':desde'] = $desde;
        $params[':hasta'] = $hasta;
    }

    // CONSULTA COMPLETA
    $sql = "SELECT 
                per.*, 
                m.nombre AS nombre_marca, 
                p.nombre AS nombre_presentacion, 
                pro.nombre AS nombre_producto
            FROM perdida per
            INNER JOIN producto pro ON per.id_producto = pro.id_producto
            INNER JOIN marca m ON pro.id_marca = m.id_marca
            INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
            $where
            ORDER BY per.id_perdida DESC";

    $consulta = $this->conex->prepare($sql);
    $consulta->execute($params);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
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

    public function eliminar(){ // Eliminado Fisico
        try {
            $registro = "DELETE FROM perdida WHERE id_perdida = :id_perdida";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_perdida', $this->id_perdida);
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

    
    
    public function get_Id_perdida(){
        return $this->id_perdida;
    }

    public function set_Id_perdida($id_perdida){
        $this->id_perdida =$id_perdida;
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
   
    public function get_Nota(){
        return $this->nota;
    }

    public function set_Nota($nota){
        $this->nota = $nota;
    }
   

    }


 
?>        