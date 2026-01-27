<?php
require_once 'conexion.php';

class Venta extends Conexion{

    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }
//----
    public function consultar(){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();
            $registro ="SELECT 
                        v.*, 
                        dv.*, 
                        m.nombre AS nombre_marca, 
                        p.nombre AS nombre_presentacion, 
                        pro.nombre AS nombre_producto,
                        dp.id_detallepago,
                        dp.id_metodopago,
                        dp.monto_dolares,
                        dp.monto_bs,
                        dp.referencia,
                        mp.nombre AS metodo_pago
                    FROM venta v
                    INNER JOIN detalle_venta dv ON v.id_venta = dv.id_venta
                    INNER JOIN producto pro ON dv.id_producto = pro.id_producto
                    INNER JOIN marca m ON pro.id_marca = m.id_marca
                    INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
                    LEFT JOIN detalle_pago dp ON v.id_venta = dp.id_venta
                    LEFT JOIN metodopago mp ON dp.id_metodopago = mp.id_metodopago
                    WHERE v.fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                    ORDER BY v.id_venta DESC";

            $consulta = $this->conex->prepare($registro);
            $resul = $consulta->execute();

            $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $conex->commit();
            $conex = null;
            return $resul ? $datos : 0;

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
//----
    public function consultarFiltrado($filtro = null, $desde = null, $hasta = null) {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $where = "";
            $params = [];

            // FILTRO: Últimos 15 días
            if ($filtro == "15") {
                $where = "WHERE v.fecha >= DATE_SUB(CURDATE(), INTERVAL 15 DAY)";
            }

            // FILTRO: Mes actual
            if ($filtro == "mes") {
                $where = "WHERE MONTH(v.fecha) = MONTH(CURDATE())
                        AND YEAR(v.fecha) = YEAR(CURDATE())";
            }

            // FILTRO: Rango personalizado
            if (!empty($desde) && !empty($hasta)) {
                $where = "WHERE v.fecha BETWEEN :desde AND :hasta";
                $params[':desde'] = $desde;
                $params[':hasta'] = $hasta;
            }

            // CONSULTA COMPLETA
            $sql = "SELECT 
                        v.*, 
                        dv.*, 
                        m.nombre AS nombre_marca, 
                        p.nombre AS nombre_presentacion, 
                        pro.nombre AS nombre_producto,
                        dp.id_detallepago,
                        dp.id_metodopago,
                        dp.monto_dolares,
                        dp.monto_bs,
                        dp.referencia,
                        mp.nombre AS metodo_pago
                    FROM venta v
                    INNER JOIN detalle_venta dv ON v.id_venta = dv.id_venta
                    INNER JOIN producto pro ON dv.id_producto = pro.id_producto
                    INNER JOIN marca m ON pro.id_marca = m.id_marca
                    INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
                    LEFT JOIN detalle_pago dp ON v.id_venta = dp.id_venta
                    LEFT JOIN metodopago mp ON dp.id_metodopago = mp.id_metodopago
                    $where
                    ORDER BY v.id_venta DESC";

            $consulta = $this->conex->prepare($sql);
            $consulta->execute($params);
            $resul = $consulta->fetchAll(PDO::FETCH_ASSOC);

                $conex->commit();
                $conex = null;
                return $resul;
        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
//--
    public function obtenerDolar() {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $query = "SELECT * FROM dolar WHERE id_dolar = 1";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            $resul = $consulta->fetchAll(PDO::FETCH_ASSOC);

                $conex->commit();
                $conex = null;
                return $resul;
        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }    
//---    
}

?>        