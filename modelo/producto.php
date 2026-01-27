<?php
require_once 'conexion.php';
class Producto extends Conexion{
//---------
    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }
//------
    public function procesarProducto($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                
                case 'registrar':
                    if (!$this->verificarExistenciaMarca($datosProcesar['id_marca'])) {
                        return ['respuesta' => 0, 'accion' => 'incluir', 'text' => 'La marca no existe'];
                    }
                    if (!$this->verificarExistenciaPresentacion($datosProcesar['id_presentacion'])) {
                        return ['respuesta' => 0, 'accion' => 'incluir', 'text' => 'La presentacion no existe'];
                    }
                    return $this->ejecutarRegistrarProducto($datosProcesar);
                   
                case 'modificar':
                    if (!$this->verificarExistenciaMarca($datosProcesar['id_marca'])) {
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'La marca no existe'];
                    }
                    if (!$this->verificarExistenciaPresentacion($datosProcesar['id_presentacion'])) {
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'La presentacion no existe'];
                    }
                    if (!$this->verificarExistenciaProducto($datosProcesar['id_producto'])) {
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'El producto no existe'];
                    }

                    return $this->ejecutarActualizarProducto($datosProcesar);    
             
                case 'eliminar':
                    if (!$this->verificarExistenciaProducto($datosProcesar['id_producto'])) {
                        return ['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'El producto no existe'];
                    }
                    return $this->ejecutarEliminarProducto($datosProcesar);
            
                case 'activar':
                    if (!$this->verificarExistenciaProducto($datosProcesar['id_producto'])) {
                        return ['respuesta' => 0, 'accion' => 'estatus', 'text' => 'El producto no existe'];
                    }
                    return $this->ejecutarActivarProducto($datosProcesar);

                case 'desactivar':
                    if (!$this->verificarExistenciaProducto($datosProcesar['id_producto'])) {
                        return ['respuesta' => 0, 'accion' => 'estatus', 'text' => 'El producto no existe'];
                    }
                    return $this->ejecutarDesactivarProducto($datosProcesar);            
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (\Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
//------------------
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
//-----------------
    private function verificarExistenciaMarca($id_marca) {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM marca 
                    WHERE id_marca = ?";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute([$id_marca]);

            return $stmt->fetchColumn() > 0;

        } catch (PDOException $e) {
            throw $e;
        }
    }
//--------------
    private function verificarExistenciaPresentacion($id_presentacion) {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM presentacion 
                    WHERE id_presentacion = ?";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute([$id_presentacion]);

            return $stmt->fetchColumn() > 0;

        } catch (PDOException $e) {
            throw $e;
        }
    }
//-----------------
    private function ejecutarRegistrarProducto($datos) {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "INSERT INTO producto
                    (nombre, id_marca, id_presentacion, stock, stock_minimo, 
                    precio_unitario, precio_mayor, cantidad_mayor, estatus)
                    VALUES
                    (:nombre, :marca, :presentacion, 0, :stock_min, 
                    :precio, :preciomayor, :cantidadmayor, 1)";

            $param = [
                'nombre'        => $datos['nombre'],
                'marca'         => $datos['id_marca'],
                'presentacion' => $datos['id_presentacion'],
                'stock_min'     => $datos['stockMin'],
                'precio'        => $datos['precio'],
                'preciomayor'   => $datos['precioMayor'],
                'cantidadmayor' => $datos['cantidadMayor']
            ];

            $stmt = $conex->prepare($sql);
            $stmt->execute($param);

            if ($stmt->rowCount() > 0) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'registrar'];
            }

            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'registrar', 'text' => 'Error al momento de ejecutar'];

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
                return ['respuesta' => 0, 'accion' => 'registrar', 'text' => $e->getMessage()];
            }
            throw $e;
        }
    }
//------------------------
    private function ejecutarActualizarProducto($datos) {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "UPDATE producto SET 
                        nombre           = :nombre,
                        id_marca         = :marca,
                        id_presentacion = :presentacion,
                        stock_minimo     = :stock_min,
                        precio_unitario = :precio,
                        precio_mayor    = :preciomayor,
                        cantidad_mayor  = :cantidadmayor
                    WHERE id_producto   = :id_producto";

            $stmt = $conex->prepare($sql);
            $stmt->execute([
                'nombre'        => $datos['nombre'],
                'marca'         => $datos['id_marca'],
                'presentacion' => $datos['id_presentacion'],
                'stock_min'     => $datos['stockMin'],
                'precio'        => $datos['precio'],
                'preciomayor'   => $datos['precioMayor'],
                'cantidadmayor' => $datos['cantidadMayor'],
                'id_producto'   => $datos['id_producto']
            ]);

            $conex->commit();

            return [ 'respuesta' => 1, 'accion' => 'actualizar'];

        } catch (PDOException $e) {
            if ($conex) $conex->rollBack();
            return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => $e->getMessage()];
        }
    }
//----------    
    private function ejecutarEliminarProducto($datos){ 
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "UPDATE producto  SET estatus = 5 WHERE id_producto = :id_producto";

            $param = [
                'id_producto' => $datos['id_producto']
            ];

            $stmt = $conex->prepare($sql);
            $stmt->execute($param);

            if ($stmt->rowCount() > 0) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'eliminar'];
            }

            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'Error en el momento de ejecutar'];

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
                return ['respuesta' => 0, 'accion' => 'eliminar', 'text' => $e->getMessage()];
            }
            throw $e;
        }
    }
//-------------
    private function ejecutarActivarProducto($datos) {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "UPDATE producto 
                    SET estatus = 1 
                    WHERE id_producto = :id_producto";

            $param = [
                'id_producto' => $datos['id_producto']
            ];

            $stmt = $conex->prepare($sql);
            $stmt->execute($param);

            if ($stmt->rowCount() > 0) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 2, 'accion' => 'estatus'];
            }

            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'estatus', 'text' => 'Error en el momento de ejecutar'];

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
                return ['respuesta' => 0, 'accion' => 'estatus', 'text' => $e->getMessage()];
            }
            throw $e;
        }
    }
//----------------------------
    private function ejecutarDesactivarProducto($datos) {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "UPDATE producto 
                    SET estatus = 2 
                    WHERE id_producto = :id_producto";

            $param = [
                'id_producto' => $datos['id_producto']
            ];

            $stmt = $conex->prepare($sql);
            $stmt->execute($param);

            if ($stmt->rowCount() > 0) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'estatus'];
            }

            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'estatus', 'text' => 'Error en el momento de ejecutar'];

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
                return ['respuesta' => 0, 'accion' => 'estatus', 'text' => $e->getMessage()];
            }
            throw $e;
        }
    }
//--------------
    public function consultar($limite = 200){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $registro ="SELECT pro.*, m.nombre AS nombre_marca, p.nombre AS nombre_presentacion 
            FROM producto pro
            INNER JOIN marca m ON pro.id_marca = m.id_marca
            INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
            WHERE pro.estatus <5
            ORDER BY pro.id_producto DESC LIMIT :limite";
            $consulta = $this->conex->prepare($registro);
            $consulta->bindParam(':limite', $limite, PDO::PARAM_INT);
            $resul = $consulta->execute();

            $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
        
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
//--------------    
    public function buscarMarca($buscar) {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "SELECT id_marca, nombre
                    FROM marca
                    WHERE estatus = 1
                    AND nombre LIKE :buscar
                    ORDER BY nombre
                    LIMIT 20";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindValue(':buscar', "%$buscar%");
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
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
//------------
    public function ultimasMarcas() {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();
         
            $sql = "SELECT id_marca, nombre
                    FROM marca
                    WHERE estatus = 1
                    ORDER BY id_marca DESC
                    LIMIT 20";

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
//---------
    public function buscarPresentacion($buscar2) {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "SELECT id_presentacion, nombre
                    FROM presentacion
                    WHERE estatus = 1
                    AND nombre LIKE :buscar
                    ORDER BY nombre
                    LIMIT 20";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindValue(':buscar', "%$buscar2%");
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    public function ultimasPresentacion() {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "SELECT id_presentacion, nombre
                    FROM presentacion
                    WHERE estatus = 1
                    ORDER BY id_presentacion DESC
                    LIMIT 20";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
//---------------
    public function contarTotal(){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $sql = "SELECT COUNT(*) AS total FROM producto WHERE estatus < 5";
            $consulta = $this->conex->prepare($sql);
            $consulta->execute();
            $fila = $consulta->fetch(PDO::FETCH_ASSOC);
           
            $conex->commit();
            $conex = null;
            return $fila['total'];

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }    
    }
//--------------
    public function StockMenor(){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $registro ="SELECT pro.*, 
                            m.nombre AS nombre_marca, 
                            p.nombre AS nombre_presentacion 
                        FROM producto pro
                        INNER JOIN marca m 
                            ON pro.id_marca = m.id_marca
                        INNER JOIN presentacion p 
                            ON pro.id_presentacion = p.id_presentacion
                        WHERE pro.estatus = 1
                        AND pro.stock <= pro.stock_minimo
                        ORDER BY pro.id_producto DESC";

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
//-----------------
    public function buscar($data){
         try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $this->id_producto = $data;
            $registro= "SELECT pro.*, m.nombre AS nombre_marca, p.nombre AS nombre_presentacion 
            FROM producto pro
            INNER JOIN marca m ON pro.id_marca = m.id_marca
            INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
            WHERE pro.id_producto='".$this->id_producto."'";
            $resultado = "";

            $dato = $this->conex->prepare($registro);
            $resul = $dato->execute();
            $resultado=$dato->fetch(PDO::FETCH_ASSOC);
           
            $conex->commit();
            $conex = null;
            return $resul ? $resultado : 0;

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }  
	}
//--------------
}
?>        