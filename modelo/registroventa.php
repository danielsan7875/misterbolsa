<?php

require_once 'conexion.php';
class Registroventa extends Conexion{
//---------------------
    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }
//----------------------
    public function procesarVenta($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                
                case 'registrar':
                   if (!$this->verificarExistenciaProducto($datosProcesar['id_producto'])) {
                        return ['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Uno o más productos no existen'];
                    }
                    
                    if (!$this->verificarExistenciaMetodopago($datosProcesar['id_metodopago'])) {
                        return ['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Uno o más metodo de pago no existe'];
                    }

                    if (!$this->verificarStockProducto($datosProcesar['id_producto'], $datosProcesar['cantidad'])) {
                        return ['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Stock insuficiente en uno o más productos' ];
                    }

                    return $this->ejecutarRegistroVenta($datosProcesar);
                   
                case 'actualizar':
                    if (!$this->verificarExistenciaPresentacion($datosProcesar['id_presentacion'])) {
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'La presentacion no existe'];
                    }
                   
                    return $this->ejecutarActualizarPresentacion($datosProcesar);    
             
            
                       
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (\Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
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
    private function verificarExistenciaMetodopago($id_metodopago) {
        try {
            // Si es array → validar todos
            if (is_array($id_metodopago)) {
                if (empty($id_metodopago)) return false;

                $placeholders = implode(',', array_fill(0, count($id_metodopago), '?'));
                $sql = "SELECT COUNT(*) 
                        FROM metodopago 
                        WHERE id_metodopago IN ($placeholders)";

                $stmt = $this->conex->prepare($sql);
                $stmt->execute($id_metodopago);
                // Si existen todos → count == total enviados
                return $stmt->fetchColumn() == count($id_metodopago);
            }
            $sql = "SELECT COUNT(*) 
                    FROM metodopago 
                    WHERE id_metodopago = ?";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute([$id_metodopago]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw $e;
        }
    }  
//-------------------------------------------------------    
    private function verificarStockProducto($id_producto, $cantidad) {
        try {

            // Validación básica
            if (empty($id_producto) || empty($cantidad)) return false;

            // Si es un solo producto
            if (!is_array($id_producto)) {
                $sql = "SELECT stock FROM producto WHERE id_producto = ?";
                $stmt = $this->conex->prepare($sql);
                $stmt->execute([$id_producto]);
                $stock = $stmt->fetchColumn();

                return $stock !== false && $stock >= $cantidad;
            }

            // Si son múltiples productos
            foreach ($id_producto as $i => $id) {

                $cantSolicitada = $cantidad[$i] ?? 0;

                $sql = "SELECT stock FROM producto WHERE id_producto = ?";
                $stmt = $this->conex->prepare($sql);
                $stmt->execute([$id]);

                $stock = $stmt->fetchColumn();

                if ($stock === false || $stock < $cantSolicitada) {
                    return false;
                }
            }

            return true;

        } catch (PDOException $e) {
            throw $e;
        }
    }
//---------------
    private function ejecutarRegistroVenta($datos) {
        try {
        $conex = $this->conex;
        $conex->beginTransaction();

        $sqlVenta = "INSERT INTO venta (fecha, total_dl, total_bs) 
                     VALUES (:fecha, :total_dl, :total_bs)";
        $stmtVenta = $conex->prepare($sqlVenta);
        $stmtVenta->execute([
            'fecha'     => $datos['fecha'],
            'total_dl'  => $datos['total_Dl'],
            'total_bs'  => $datos['total_Bs']
        ]);

        $id_venta = $conex->lastInsertId();

        // Validación mínima
        if (empty($datos['id_producto']) || empty($datos['cantidad']) || empty($datos['precio']) || empty($datos['maode'])) {
            $conex->rollBack();
            return ['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Datos incompletos'];
        }

        $sqlDetalle = "INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, maode)
                       VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario, :maode)";
        $stmtDetalle = $conex->prepare($sqlDetalle);

        $sqlStock = "UPDATE producto SET stock = stock - :cantidad WHERE id_producto = :id_producto";
        $stmtStock = $conex->prepare($sqlStock);

        foreach ($datos['id_producto'] as $i => $producto) {
            $paramDetalle = [
                'id_venta'      => $id_venta,
                'id_producto'   => $producto,
                'cantidad'      => $datos['cantidad'][$i],
                'precio_unitario' => $datos['precio'][$i],
                'maode'         => $datos['maode'][$i]
            ];
            $stmtDetalle->execute($paramDetalle);

            $paramStock = [
                'cantidad'    => $datos['cantidad'][$i],
                'id_producto' => $producto
            ];
            $stmtStock->execute($paramStock);
        }

        if (!empty($datos['id_metodopago'])) {
            $sqlPago = "INSERT INTO detalle_pago (id_venta, id_metodopago, monto_dolares, monto_bs, referencia)
                        VALUES (:id_venta, :id_metodopago, :monto_dolares, :monto_bs, :referencia)";
            $stmtPago = $conex->prepare($sqlPago);

            foreach ($datos['id_metodopago'] as $i => $metodo) {
                $ref = isset($datos['referencia'][$i]) ? $datos['referencia'][$i] : '';
                $stmtPago->execute([
                    'id_venta'       => $id_venta,
                    'id_metodopago'  => $metodo,
                    'monto_dolares'  => $datos['monto_dolares'][$i],
                    'monto_bs'       => $datos['monto_bs'][$i],
                    'referencia'     => $ref
                ]);
            }
        }

        $sqlCierre = "INSERT INTO detalle_cierre (id_cierre, id_venta, hora)
                      VALUES (:id_cierre, :id_venta, :hora)";
        $stmtCierre = $conex->prepare($sqlCierre);
        $stmtCierre->execute([
            'id_cierre' => $datos['id_Cierre'],
            'id_venta'  => $id_venta,
            'hora'      => $datos['hora_Cierre']
        ]);

        // Confirmar transacción
        $conex->commit();
        $conex = null;

        return ['respuesta' => 1, 'accion' => 'registrar'];

    } catch (\PDOException $e) {
        if ($conex) {
            $conex->rollBack();
            $conex = null;
        }
        return ['respuesta' => 0, 'accion' => 'registrar', 'text' => $e->getMessage()];
    }
}
//-----------
    public function buscarProducto($buscar = '', $limit = 50) {
        try{
            $conex = $this->conex;
            $conex ->beginTransaction();
                
            $sql = "SELECT pro.*, 
                   m.nombre AS nombre_marca, 
                   p.nombre AS nombre_presentacion 
                    FROM producto pro
                    INNER JOIN marca m ON pro.id_marca = m.id_marca
                    INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
                    WHERE pro.estatus = 1";

            if (!empty($buscar)) {
                $sql .= " AND (pro.nombre LIKE :buscar OR m.nombre LIKE :buscar)";
            }
            $sql .= " ORDER BY pro.id_producto DESC LIMIT :limite";

            $stmt = $this->conex->prepare($sql);
            if (!empty($buscar)) {
                $stmt->bindValue(':buscar', "%$buscar%", PDO::PARAM_STR);
            }
            $stmt->bindValue(':limite', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            $Datos =  $stmt->fetchAll(PDO::FETCH_ASSOC);

            $conex->commit();
            $conex = null;
            return $Datos;       

        }catch(\PDOException $e){
            if($conex){
                $conex -> rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
//----------------
    public function obtenerCierreActivo(){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $query = "SELECT * FROM cierre_diario WHERE estatus = 1 LIMIT 1";
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
//---------------------
     public function obtenerDolar() {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $query = "SELECT * FROM dolar WHERE id_dolar = 1";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            $Datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

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
//-------------------
     public function obtenerMetodopago() {
        try{ 
            $conex = $this->conex;
            $conex->beginTransaction();  
            
            $query = "SELECT * FROM metodopago WHERE estatus =1";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $conex ->commit();
            $conex = null;
            return $datos;

        } catch (\PDOException $e){
            if($conex){
                $conex -> rollBack();
                $conex = null;
            }
            throw $e;
        }
    } 
//---------------------
    public function obtenerNroVenta() {
        try{ 
            $conex = $this->conex;
            $conex->beginTransaction();  

            $query = "SELECT id_venta FROM venta ORDER BY id_venta DESC LIMIT 1";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            $Datos =  $consulta->fetch(PDO::FETCH_ASSOC);
        
            $conex ->commit();
            $conex =null;
            return $Datos;
        } catch (\PDOException $e){
            if($conex){
                $conex -> rollBack();
                $conex = null;
            }
            throw $e;
        }
    } 
//----------------------
    public function consultarVenta(){
        try{
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
                WHERE v.fecha = CURDATE()
                ORDER BY v.id_venta DESC";

            $consulta = $this->conex->prepare($registro);
            $resul = $consulta->execute();
            $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $conex->commit();
            $conex = null;
            return $resul ? $datos : 0;
            
        } catch (\PDOException $e){
            if($conex){
                $conex ->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
//---
}
?>        