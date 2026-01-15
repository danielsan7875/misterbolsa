<?php

    require_once 'conexion.php';

    class Detalle extends Conexion{

    private $conex;


    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }


 public function obtenerTopProductosVendidos($limite = 5) {
    $query = "
        SELECT 
            p.id_producto,
            p.nombre AS producto,
            m.nombre AS marca,
            pr.nombre AS presentacion,
            SUM(dv.cantidad) AS total_vendido,
            SUM(dv.cantidad * dv.precio_unitario) AS total_dinero
        FROM detalle_venta dv
        INNER JOIN producto p ON dv.id_producto = p.id_producto
        LEFT JOIN marca m ON p.id_marca = m.id_marca
        LEFT JOIN presentacion pr ON p.id_presentacion = pr.id_presentacion
        WHERE p.estatus < 5
        GROUP BY p.id_producto, p.nombre, m.nombre, pr.nombre
        ORDER BY total_vendido DESC
        LIMIT :limite
    ";
    $consulta = $this->conex->prepare($query);
    $consulta->bindValue(':limite', $limite, PDO::PARAM_INT);
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}



    public function contarProductos() {
        $query = "SELECT COUNT(*) as total FROM producto WHERE estatus <5";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function contarProductosBajoStock() {
    $query = "SELECT COUNT(*) as total FROM producto WHERE stock <= stock_minimo AND estatus < 5";
    $consulta = $this->conex->prepare($query);
    $consulta->execute();
    return $consulta->fetch(PDO::FETCH_ASSOC);
}


   public function sumarVentas() {
    $query = "SELECT SUM(total_dl) as total FROM venta";
    $consulta = $this->conex->prepare($query);
    $consulta->execute();
    return $consulta->fetch(PDO::FETCH_ASSOC);
    }

     public function cantidadPerdida() {
    $query = "SELECT SUM(cantidad) as total FROM perdida";
    $consulta = $this->conex->prepare($query);
    $consulta->execute();
    return $consulta->fetch(PDO::FETCH_ASSOC);
    }
    

 

    }


?>        