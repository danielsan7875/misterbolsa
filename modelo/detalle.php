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


public function obtenerRotacionBaja30Dias($max = 20, $dias = 30, $limite = 10) {

    $query = "
        SELECT 
            p.id_producto,
            p.nombre AS producto,
            m.nombre AS marca,
            pr.nombre AS presentacion,
            IFNULL(SUM(dv.cantidad), 0) AS total_vendido
        FROM producto p
        LEFT JOIN detalle_venta dv 
            ON p.id_producto = dv.id_producto
        LEFT JOIN venta v 
            ON dv.id_venta = v.id_venta 
            AND v.fecha >= DATE_SUB(CURDATE(), INTERVAL :dias DAY)
        LEFT JOIN marca m ON p.id_marca = m.id_marca
        LEFT JOIN presentacion pr ON p.id_presentacion = pr.id_presentacion
        WHERE p.estatus < 5
        GROUP BY p.id_producto, p.nombre, m.nombre, pr.nombre
        HAVING total_vendido <= :max
        ORDER BY total_vendido ASC
        LIMIT :limite
    ";

    $consulta = $this->conex->prepare($query);
    $consulta->bindValue(':max', $max, PDO::PARAM_INT);
    $consulta->bindValue(':dias', $dias, PDO::PARAM_INT);
    $consulta->bindValue(':limite', $limite, PDO::PARAM_INT);
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


public function obtenerStockIdealTop($dias = 30, $reposicion = 7, $limite = 10) {

    $query = "
        SELECT 
            p.id_producto,
            p.nombre AS producto,
            m.nombre AS marca,
            pr.nombre AS presentacion,
            IFNULL(SUM(dv.cantidad),0) AS total_vendido,
            ROUND(IFNULL(SUM(dv.cantidad),0) / :dias, 2) AS promedio_diario,
            CEILING((IFNULL(SUM(dv.cantidad),0) / :dias) * :reposicion) AS stock_ideal
        FROM detalle_venta dv
        INNER JOIN venta v ON dv.id_venta = v.id_venta
        INNER JOIN producto p ON dv.id_producto = p.id_producto
        LEFT JOIN marca m ON p.id_marca = m.id_marca
        LEFT JOIN presentacion pr ON p.id_presentacion = pr.id_presentacion
        WHERE 
            p.estatus < 5
            AND v.fecha >= DATE_SUB(CURDATE(), INTERVAL :dias DAY)
        GROUP BY p.id_producto, p.nombre, m.nombre, pr.nombre
        ORDER BY total_vendido DESC
        LIMIT :limite
    ";

    $consulta = $this->conex->prepare($query);
    $consulta->bindValue(':dias', $dias, PDO::PARAM_INT);
    $consulta->bindValue(':reposicion', $reposicion, PDO::PARAM_INT);
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
    

 
    public function VentasMesActual() {

    $query = "
        SELECT 
            DAY(fecha) AS dia,
            SUM(total_dl) AS total_dia
        FROM venta
        WHERE 
            YEAR(fecha) = YEAR(CURDATE())
            AND MONTH(fecha) = MONTH(CURDATE())
        GROUP BY DAY(fecha)
        ORDER BY dia ASC
    ";

    $consulta = $this->conex->prepare($query);
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


public function TotalMesActual() {

    $query = "
        SELECT 
            SUM(total_dl) AS total_mes
        FROM venta
        WHERE 
            YEAR(fecha) = YEAR(CURDATE())
            AND MONTH(fecha) = MONTH(CURDATE())
    ";

    $consulta = $this->conex->prepare($query);
    $consulta->execute();
    return $consulta->fetch(PDO::FETCH_ASSOC);
}





    }


?>        