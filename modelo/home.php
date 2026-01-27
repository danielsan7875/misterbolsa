<?php

    require_once 'conexion.php';
    class Home extends Conexion{

    private $conex;
    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }
//--------------
     public function obtenerDolar() {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $query = "SELECT * FROM dolar WHERE id_dolar = 1";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $conex->commit();
            $conex = null;
            return $datos;
        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
//----------------------    
    public function contarProductos() {
         try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $query = "SELECT COUNT(*) as total FROM producto WHERE estatus <5";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            $Datos = $consulta->fetch(PDO::FETCH_ASSOC);

            $conex->commit();
            $conex = null;
            return $Datos;
        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
//-----------------------
    public function contarCompras() {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $query = "SELECT COUNT(*) as total FROM compra";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            $datos = $consulta->fetch(PDO::FETCH_ASSOC);

            $conex->commit();
            $conex = null;
            return $datos;
        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
//----------------------    
    public function contarVentas() {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();
            $query = "SELECT COUNT(*) as total FROM venta";

            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            $Datos = $consulta->fetch(PDO::FETCH_ASSOC);

            $conex->commit();
            $conex = null;
            return $Datos;
        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
//----------------------
    public function contarVentasHoy() {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $query = "SELECT COUNT(*) as total FROM venta WHERE fecha = CURDATE()";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            $datos = $consulta->fetch(PDO::FETCH_ASSOC);

            $conex->commit();
            $conex = null;
            return $datos;

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    } 
//-------------------
    public function consultarUltimasVentas() {
      try {
        $conex = $this->conex;
        $conex->beginTransaction();
        $sql = "
            SELECT 
                v.id_venta,
                v.fecha,
                v.total_dl,
                GROUP_CONCAT(pro.nombre SEPARATOR ', ') AS productos
            FROM venta v
            INNER JOIN detalle_venta dv ON v.id_venta = dv.id_venta
            INNER JOIN producto pro ON dv.id_producto = pro.id_producto
            GROUP BY v.id_venta, v.fecha, v.total_dl
            ORDER BY v.id_venta DESC
            LIMIT 3
        ";

        $stmt = $this->conex->prepare($sql);
        $stmt->execute();
        $Datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $conex->commit();
            $conex = null;
            return $Datos;

    } catch (\PDOException $e) {
        if ($conex) {
            $conex->rollBack();
            $conex = null;
        }
        throw $e;
    }
}
//------------------
public function TotalSemana() {
    try {
        $conex = $this->conex;
        $conex->beginTransaction();
        $query = "
            SELECT 
                SUM(total_dl) AS total_semana
            FROM venta
            WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        ";

        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        $datos = $consulta->fetch(PDO::FETCH_ASSOC);

            $conex->commit();
            $conex = null;
            return $datos;

    } catch (\PDOException $e) {
        if ($conex) {
            $conex->rollBack();
            $conex = null;
        }
        throw $e;
    }
}
//-----------------
public function TotalHoy() {
    try {
        $conex = $this->conex;
        $conex->beginTransaction();

        $query = "
            SELECT 
                SUM(total_dl) AS total_hoy
            FROM venta
            WHERE fecha = CURDATE()
        ";

        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        $Datos = $consulta->fetch(PDO::FETCH_ASSOC);

            $conex->commit();
            $conex = null;
            return $Datos;

    } catch (\PDOException $e) {
        if ($conex) {
            $conex->rollBack();
            $conex = null;
        }
        throw $e;
    }
}
//-----------
public function SemanasVentas() {
    try {
        $conex = $this->conex;
        $conex->beginTransaction();
        $query = "
            SELECT 
                DATE(fecha) AS dia,
                SUM(total_dl) AS total_dia
            FROM venta
            WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
            GROUP BY DATE(fecha)
            ORDER BY dia ASC
        ";

        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $conex->commit();
            $conex = null;
            return $datos;

    } catch (\PDOException $e) {
        if ($conex) {
            $conex->rollBack();
            $conex = null;
        }
        throw $e;
    }
}
//-----------
}
?>        