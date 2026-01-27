<?php
    require_once 'conexion.php';

class Compra extends Conexion{

    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }
//----
    public function procesarCompra($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                case 'registrar':
                    if (!$this->verificarExistenciaProducto($datosProcesar['id_producto'])) {
                        return ['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Uno o más productos no existen'];
                    }
                    return $this->ejecutarRegistroCompra($datosProcesar);
                    
                case 'verificar':
                    return $this->verificarExistenciaProducto($datosProcesar['id_producto']);
                
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (\Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
//-------------------------------------------------------
    public function consultar(){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

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
//-------------------------------------------------------
    private function verificarExistenciaProducto($id_producto) {
        try {
            // Si es array → validar todos
            if (is_array($id_producto)) {
                if (empty($id_producto)) return false;

                $placeholders = implode(',', array_fill(0, count($id_producto), '?'));
                $sql = "SELECT COUNT(*) 
                        FROM producto 
                        WHERE id_producto IN ($placeholders)";

                $stmt = $this->conex->prepare($sql);
                $stmt->execute($id_producto);
                // Si existen todos → count == total enviados
                return $stmt->fetchColumn() == count($id_producto);
            }
            $sql = "SELECT COUNT(*) 
                    FROM producto 
                    WHERE id_producto = ?";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute([$id_producto]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw $e;
        }
    }
//-------------------------------------------------------
    private function ejecutarRegistroCompra($datos) {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            // 1) Registrar compra
            $sqlCompra = "INSERT INTO compra (fecha)
                        VALUES (:fecha)";

            $paramCompra = [
                'fecha' => $datos['fecha']
            ];

            $stmtCompra = $conex->prepare($sqlCompra);
            $stmtCompra->execute($paramCompra);

            $id_compra = $conex->lastInsertId();

            // 2) Preparar detalle
            $sqlDetalle = "INSERT INTO detalle_compra
                        (id_compra, id_producto, cantidad)
                        VALUES (:id_compra, :id_producto, :cantidad)";

            $stmtDetalle = $conex->prepare($sqlDetalle);

            // 3) Preparar actualización stock
            $sqlStock = "UPDATE producto
                        SET stock = stock + :cantidad
                        WHERE id_producto = :id_producto";

            $stmtStock = $conex->prepare($sqlStock);

            // 4) Registrar detalle + actualizar stock
            foreach ($datos['id_producto'] as $i => $producto) {

                $paramDetalle = [
                    'id_compra'   => $id_compra,
                    'id_producto'=> $producto,
                    'cantidad'   => $datos['cantidad'][$i]
                ];

                $stmtDetalle->execute($paramDetalle);

                $paramStock = [
                    'cantidad'    => $datos['cantidad'][$i],
                    'id_producto'=> $producto
                ];

                $stmtStock->execute($paramStock);
            }

            $conex->commit();
            $conex = null;

            return ['respuesta' => 1, 'accion' => 'registrar'];

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
            return ['respuesta' => 0, 'accion' => 'registrar', 'text' => $e->getMessage()];
        }
    }
//-------------------------------------------------------
    public function buscarProducto($buscar) {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();
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
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex->commit();
            $conex = null;
            return $resultado;

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
//-------------------------------------------------------
    public function ultimosProductos() {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();
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
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            $conex->commit();
            $conex = null;
            return $resultados;
        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
//-------------------------------------------------------
    public function consultarFiltrado($filtro = null, $desde = null, $hasta = null) {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

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
//----    
}
?>        