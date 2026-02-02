<?php

require_once 'conexion.php';
class Cierre extends Conexion{
//-------------------    
    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }
//------------------
    public function procesarCierre($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                
                case 'iniciar':
                    if (!$this->verificarCierreActivo()) {
                        return ['respuesta' => 0, 'accion' => 'iniciar', 'text' => 'Ya hay un cierre Activo'];
                    }

                    if (!$this->verificarLimiteCierres()) {
                        echo json_encode(['respuesta' => 0, 'accion' => 'iniciar','text' => 'Límite de cierres alcanzado']);
                        exit;
                    }


                    return $this->ejecutarRegistroIniciar($datosProcesar);
                   
                case 'cierre':
                    if (!$this->verificarExistenciaCierre($datosProcesar['id_cierre'])) {
                        return ['respuesta' => 0, 'accion' => 'cierre', 'text' => 'No existe el nro de cierre'];
                    }
                   
                    return $this->EjecutarCierreDiario($datosProcesar);    
            
                       
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (\Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
//---------------------
    private function verificarExistenciaCierre($id_cierre) {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM cierre_diario 
                    WHERE id_cierre = ?";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute([$id_cierre]);

            return $stmt->fetchColumn() > 0;

        } catch (PDOException $e) {
            throw $e;
        }
    }
//----------------------
    private function verificarCierreActivo() {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM cierre_diario 
                    WHERE estatus = 1";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute();

            return $stmt->fetchColumn() == 0; // true si NO hay cierre activo

        } catch (PDOException $e) {
            throw $e;
        }
    }
//---------    
    private function verificarLimiteCierres() {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM cierre_diario
                    WHERE DATE(fecha_creacion) = CURDATE() AND estatus IN (1, 2)";

            $stmt = $this->conex->prepare($sql);
            $stmt->execute();

            return $stmt->fetchColumn() < 3; // true si puede continuar

        } catch (PDOException $e) {
            throw $e;
        }
    }
//-----------------------    
    private function ejecutarRegistroIniciar($datos){
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

                $registro ="INSERT INTO cierre_diario(fecha_creacion, tasa, estatus)
                            VALUES(:fecha, :tasa, 1)";

                $param = [
                    'fecha' => $datos['fecha'],
                    'tasa' => $datos['tasa'],
                ];

                $stmt = $conex->prepare($registro);
                $stmt->execute($param);

            if ($stmt->rowCount() > 0) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'iniciar'];
            }

            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'iniciar', 'text' => 'Error al momento de ejecutar'];

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
                return ['respuesta' => 0, 'accion' => 'iniciar', 'text' => $e->getMessage()];
            }
            throw $e;
        }
    } 
//--------------------
    private function EjecutarCierreDiario($datos) {
        try {
            $conex = $this->conex;
            $conex->beginTransaction();

            $id_cierre = $datos['id_cierre'];
            $fecha     = $datos['fecha'];

            /* =========================
            * PASO 1: OBTENER VENTAS
            * ========================= */
            $sqlVentas = "SELECT id_venta 
                        FROM detalle_cierre 
                        WHERE id_cierre = :id_cierre";

            $stmtVentas = $conex->prepare($sqlVentas);
            $stmtVentas->execute(['id_cierre' => $id_cierre]);
            $ventas = $stmtVentas->fetchAll(PDO::FETCH_COLUMN);

            /* =========================
            * SI NO HAY VENTAS
            * ========================= */
            if (empty($ventas)) {

                $sqlReset = "UPDATE cierre_diario SET 
                    fecha_cierre = :fecha,
                    total_dia_dl = 0,
                    total_dia_bs = 0,
                    total_pagomovil_dl = 0,
                    total_pagomovil_bs = 0,
                    total_punto_dl = 0,
                    total_punto_bs = 0,
                    total_efectivo_dl = 0,
                    total_efectivo_bs = 0,
                    total_divisas_dl = 0,
                    total_divisas_bs = 0,
                    estatus = 2
                    WHERE id_cierre = :id_cierre";

                $stmtReset = $conex->prepare($sqlReset);
                $stmtReset->execute([
                    'fecha'     => $fecha,
                    'id_cierre' => $id_cierre
                ]);

                $conex->commit();
                $conex = null;

                return ['respuesta' => 1, 'accion' => 'cierre'];
            }

            /* =========================
            * PASO 2: TOTALES DE VENTA
            * ========================= */
            $listaVentas = implode(',', array_map('intval', $ventas));

            $sqlTotales = "SELECT 
                SUM(total_dl) AS total_dl,
                SUM(total_bs) AS total_bs
                FROM venta
                WHERE id_venta IN ($listaVentas)";

            $stmtTotales = $conex->prepare($sqlTotales);
            $stmtTotales->execute();
            $totalesVenta = $stmtTotales->fetch(PDO::FETCH_ASSOC);

            /* =========================
            * PASO 3: TOTALES POR MÉTODO
            * ========================= */
            $sqlPagos = "SELECT 
                id_metodopago,
                SUM(monto_dolares) AS total_dolares,
                SUM(monto_bs) AS total_bs
                FROM detalle_pago
                WHERE id_venta IN ($listaVentas)
                GROUP BY id_metodopago";

            $stmtPagos = $conex->prepare($sqlPagos);
            $stmtPagos->execute();
            $pagos = $stmtPagos->fetchAll(PDO::FETCH_ASSOC);

            $totalesMetodo = [
                1 => ['dl' => 0, 'bs' => 0],
                2 => ['dl' => 0, 'bs' => 0],
                3 => ['dl' => 0, 'bs' => 0],
                4 => ['dl' => 0, 'bs' => 0],
            ];

            foreach ($pagos as $pago) {
                if (isset($totalesMetodo[$pago['id_metodopago']])) {
                    $totalesMetodo[$pago['id_metodopago']]['dl'] = $pago['total_dolares'];
                    $totalesMetodo[$pago['id_metodopago']]['bs'] = $pago['total_bs'];
                }
            }

            /* =========================
            * PASO 4: ACTUALIZAR CIERRE
            * ========================= */
            $sqlUpdate = "UPDATE cierre_diario SET 
                fecha_cierre = :fecha,
                total_dia_dl = :total_dl,
                total_dia_bs = :total_bs,
                total_pagomovil_dl = :pm_dl,
                total_pagomovil_bs = :pm_bs,
                total_punto_dl = :punto_dl,
                total_punto_bs = :punto_bs,
                total_efectivo_dl = :efectivo_dl,
                total_efectivo_bs = :efectivo_bs,
                total_divisas_dl = :divisas_dl,
                total_divisas_bs = :divisas_bs,
                estatus = 2
                WHERE id_cierre = :id_cierre";

            $stmtUpdate = $conex->prepare($sqlUpdate);
            $stmtUpdate->execute([
                'fecha'        => $fecha,
                'total_dl'     => $totalesVenta['total_dl'],
                'total_bs'     => $totalesVenta['total_bs'],
                'pm_dl'        => $totalesMetodo[1]['dl'],
                'pm_bs'        => $totalesMetodo[1]['bs'],
                'punto_dl'     => $totalesMetodo[2]['dl'],
                'punto_bs'     => $totalesMetodo[2]['bs'],
                'efectivo_dl'  => $totalesMetodo[3]['dl'],
                'efectivo_bs'  => $totalesMetodo[3]['bs'],
                'divisas_dl'   => $totalesMetodo[4]['dl'],
                'divisas_bs'   => $totalesMetodo[4]['bs'],
                'id_cierre'    => $id_cierre
            ]);

            $conex->commit();
            $conex = null;

            return ['respuesta' => 1, 'accion' => 'cierre'];

        } catch (\PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            return [
                'respuesta' => 0,
                'accion' => 'cierre',
                'text' => 'hola'
            ];
        }
    }



    public function procesarCierreDiario($id_cierre) {
    // Paso 1: Obtener todos los id_venta asociados al cierre
    $queryVentas = "SELECT id_venta FROM detalle_cierre WHERE id_cierre = :id_cierre";
    $stmtVentas = $this->conex->prepare($queryVentas);
    $stmtVentas->bindParam(':id_cierre', $id_cierre);
    $stmtVentas->execute();
    $ventas = $stmtVentas->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($ventas)) {
        $queryReset = "UPDATE cierre_diario SET 
            fecha_cierre = :fecha,
            total_dia_dl = 0,
            total_dia_bs = 0,
            total_pagomovil_dl = 0,
            total_pagomovil_bs = 0,
            total_punto_dl = 0,
            total_punto_bs = 0,
            total_efectivo_dl = 0,
            total_efectivo_bs = 0,
            total_divisas_dl = 0,
            total_divisas_bs = 0,
            estatus = 2
            WHERE id_cierre = :id_cierre";

        $stmtReset = $this->conex->prepare($queryReset);
        $stmtReset->bindParam(':fecha', $this->fecha);
        $stmtReset->bindParam(':id_cierre', $id_cierre);
        $stmtReset->execute();

        if($stmtReset){
            $res=array('respuesta'=>1,'accion'=>'cierre');
        }else{
            $res=array('respuesta'=>0,'accion'=>'cierre');
        }

        return $res;
    
    }
    // Convertir array de IDs en lista para IN()
    $listaVentas = implode(',', array_map('intval', $ventas));

    // Paso 2: Sumar total_dl y total_bs desde la tabla venta
    $queryTotalesVenta = "SELECT 
        SUM(total_dl) AS total_dl,
        SUM(total_bs) AS total_bs 
        FROM venta 
        WHERE id_venta IN ($listaVentas)";
    $stmtTotalesVenta = $this->conex->prepare($queryTotalesVenta);
    $stmtTotalesVenta->execute();
    $totalesVenta = $stmtTotalesVenta->fetch(PDO::FETCH_ASSOC);

    // Paso 3: Sumar montos por método de pago desde detalle_pago
    $queryPagos = "SELECT 
        id_metodopago,
        SUM(monto_dolares) AS total_dolares,
        SUM(monto_bs) AS total_bs 
        FROM detalle_pago 
        WHERE id_venta IN ($listaVentas)
        GROUP BY id_metodopago";
    $stmtPagos = $this->conex->prepare($queryPagos);
    $stmtPagos->execute();
    $pagos = $stmtPagos->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar totales por método
    $totalesMetodo = [
        1 => ['dl' => 0, 'bs' => 0], // Pago móvil
        2 => ['dl' => 0, 'bs' => 0], // Punto
        3 => ['dl' => 0, 'bs' => 0], // Efectivo
        4 => ['dl' => 0, 'bs' => 0], // Divisas
    ];

    foreach ($pagos as $pago) {
        $id = $pago['id_metodopago'];
        if (isset($totalesMetodo[$id])) {
            $totalesMetodo[$id]['dl'] = $pago['total_dolares'];
            $totalesMetodo[$id]['bs'] = $pago['total_bs'];
        }
    }

    // Paso 4: Actualizar la tabla cierre_diario
    $queryUpdate = "UPDATE cierre_diario SET 
        fecha_cierre = :fecha,
        total_dia_dl = :total_dia_dl,
        total_dia_bs = :total_dia_bs,
        total_pagomovil_dl = :pm_dl,
        total_pagomovil_bs = :pm_bs,
        total_punto_dl = :punto_dl,
        total_punto_bs = :punto_bs,
        total_efectivo_dl = :efectivo_dl,
        total_efectivo_bs = :efectivo_bs,
        total_divisas_dl = :divisas_dl,
        total_divisas_bs = :divisas_bs,
        estatus = 2
        WHERE id_cierre = :id_cierre";

    $stmtUpdate = $this->conex->prepare($queryUpdate);
    
    $stmtUpdate->bindParam(':fecha', $this->fecha);
    $stmtUpdate->bindParam(':total_dia_dl', $totalesVenta['total_dl']);
    $stmtUpdate->bindParam(':total_dia_bs', $totalesVenta['total_bs']);
    $stmtUpdate->bindParam(':pm_dl', $totalesMetodo[1]['dl']);
    $stmtUpdate->bindParam(':pm_bs', $totalesMetodo[1]['bs']);
    $stmtUpdate->bindParam(':punto_dl', $totalesMetodo[2]['dl']);
    $stmtUpdate->bindParam(':punto_bs', $totalesMetodo[2]['bs']);
    $stmtUpdate->bindParam(':efectivo_dl', $totalesMetodo[3]['dl']);
    $stmtUpdate->bindParam(':efectivo_bs', $totalesMetodo[3]['bs']);
    $stmtUpdate->bindParam(':divisas_dl', $totalesMetodo[4]['dl']);
    $stmtUpdate->bindParam(':divisas_bs', $totalesMetodo[4]['bs']);
    $stmtUpdate->bindParam(':id_cierre', $id_cierre);

    $stmtUpdate->execute();

     if($stmtUpdate){
            $res=array('respuesta'=>1,'accion'=>'cierre');
        }else{
            $res=array('respuesta'=>0,'accion'=>'cierre');
        }

        return $res;
}

 
 // Metodo para llamar las medidas existentes
    public function obtenerDolar() {
        $query = "SELECT * FROM dolar WHERE id_dolar = 1";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }





        public function obtenerCierreActivo(){
            $query = "SELECT * FROM cierre_diario WHERE estatus = 1 LIMIT 1";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            return $consulta->fetch(PDO::FETCH_ASSOC);

        }

    
        public function contarVentas() {
            $query = "SELECT COUNT(d.id_venta) as total 
                    FROM detalle_cierre d
                    INNER JOIN cierre_diario c ON d.id_cierre = c.id_cierre
                    WHERE c.estatus = 1";
                    
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            return $consulta->fetch(PDO::FETCH_ASSOC);
        }
        


        public function contarCierrePorFecha() {
            // Usamos DATE() por si la columna tiene horas, así comparamos solo el día
            $query = "SELECT COUNT(*) as totalcontar FROM cierre_diario WHERE DATE(fecha_creacion) = CURDATE()";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            
            // fetch(PDO::FETCH_ASSOC) devuelve ['totalcontar' => valor]
            $datos = $consulta->fetch(PDO::FETCH_ASSOC);
            
            return $datos ? $datos : ['totalcontar' => 0];
        }

    
  
    public function consultar($limite = 100){

        $registro ="SELECT *
        FROM cierre_diario
        ORDER BY id_cierre DESC LIMIT :limite";
        $consulta = $this->conex->prepare($registro);
        $consulta->bindParam(':limite', $limite, PDO::PARAM_INT);
        $resul = $consulta->execute();

        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
            if ($resul){
                return $datos;
            } else{
                return $res = 0;
            }
    
    }


    public function contarTotal(){
            $sql = "SELECT COUNT(*) AS total FROM cierre_diario WHERE estatus < 3";
            $consulta = $this->conex->prepare($sql);
            $consulta->execute();
            $fila = $consulta->fetch(PDO::FETCH_ASSOC);
            return $fila['total'];
    }


    public function consultarCierreActivo($fecha){

        $registro ="SELECT * FROM cierre_diario WHERE fecha_creacion = :fecha AND estatus = 1";
        $consulta = $this->conex->prepare($registro);
        $consulta->bindParam(':fecha', $fecha);
        $resul = $consulta->execute();

        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            $dato['datos'] = $datos;
            $dato['accion'] = 'consultarcierre';
            return $dato;
        } else {
            return $res = 0;
        }
    
    } //fin consultar






}
?>        