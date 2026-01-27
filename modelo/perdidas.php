<?php
    require_once 'conexion.php';
    class Perdidas extends Conexion{

    private $conex;

    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }
//-----
    public function procesarPerdida($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                case 'registrar':
                    if (!$this->verificarExistenciaProducto($datosProcesar['id_producto'])) {
                        return ['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Uno o más productos no existen'];
                    }
                    return $this->ejecutarRegistroPerdida($datosProcesar);
                    
                
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (\Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
//-------------------------------------
    private function ejecutarRegistroPerdida($datos) {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            // 1) Verificar stock disponible
            $stockDisponible = $this->obtenerStockDisponible($datos['id_producto']);

            if ($datos['cantidad'] > $stockDisponible) {
                $conex->rollBack();
                return ['respuesta' => -1, 'accion' => 'registrar', 'text' => 'Stock insuficiente'];
            }

            // 2) Registrar pérdida
            $sqlPerdida = "INSERT INTO perdida (fecha, id_producto, cantidad, nota)
                        VALUES (:fecha, :id_producto, :cantidad, :nota)";

            $paramPerdida = [
                'fecha'       => $datos['fecha'],
                'id_producto'=> $datos['id_producto'],
                'cantidad'   => $datos['cantidad'],
                'nota'       => $datos['nota']
            ];

            $stmtPerdida = $conex->prepare($sqlPerdida);
            $stmtPerdida->execute($paramPerdida);

            // 3) Actualizar stock
            $sqlStock = "UPDATE producto
                        SET stock = stock - :cantidad
                        WHERE id_producto = :id_producto";

            $paramStock = [
                'cantidad'    => $datos['cantidad'],
                'id_producto'=> $datos['id_producto']
            ];

            $stmtStock = $conex->prepare($sqlStock);
            $stmtStock->execute($paramStock);

            $conex->commit();
            $conex = null;

            return ['respuesta' => 1, 'accion' => 'registrar'];

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                return ['respuesta' => 0, 'accion' => 'registrar', 'text' => $e->getMessage()];
                $conex = null;
            }
            throw $e;
        }
    }
//-------------------------------------
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
//-------------------------------------
    private function obtenerStockDisponible($id_producto) { 
        $query = "SELECT stock FROM producto WHERE id_producto = :id_producto"; 
        $stmt = $this->conex->prepare($query); $stmt->bindParam(':id_producto', $id_producto); 
        $stmt->execute(); $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['stock'] : 0; // Retorna el stock disponible
    }
//-------------------------------------
    public function consultar(){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

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
//-------------------------------------
    public function consultarFiltrado($filtro = null, $desde = null, $hasta = null) {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

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
            $resul=$consulta->fetchAll(PDO::FETCH_ASSOC);

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
//-------------------------------------
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
//-------------------------------------
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
//----
}
?>        