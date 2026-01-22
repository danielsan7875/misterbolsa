<?php

    require_once 'conexion.php';

    class Home extends Conexion{

    private $conex;

    private $id_dolar;
    private $precios;

    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }


     // Metodo para llamar las medidas existentes
     public function obtenerDolar() {
        $query = "SELECT * FROM dolar WHERE id_dolar = 1";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function contarProductos() {
        $query = "SELECT COUNT(*) as total FROM producto WHERE estatus <5";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function contarCompras() {
        $query = "SELECT COUNT(*) as total FROM compra";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
    
    public function contarVentas() {
        $query = "SELECT COUNT(*) as total FROM venta";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function contarVentasHoy() {
        $query = "SELECT COUNT(*) as total FROM venta WHERE fecha = CURDATE()";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
    
    
    public function tasadolar(){
		$registro = "UPDATE dolar SET id_dolar = :id_dolar, precios = :precios WHERE id_dolar = :id_dolar";

		$strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_dolar', $this->id_dolar);    
        $strExec->bindParam(':precios', $this->precios);

		$resul= $strExec->execute();
			if ($resul) {
                $res['respuesta'] = 1;
                $res['accion'] = 'tasadolar';
			}else{
                $res['respuesta'] = 0;
                $res['accion'] = 'tasadolar';
			 }
		return $res;
	}// fin actulizar
 

   public function consultarUltimasVentas() {

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

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



public function TotalSemana() {
    $query = "
        SELECT 
            SUM(total_dl) AS total_semana
        FROM venta
        WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    ";

    $consulta = $this->conex->prepare($query);
    $consulta->execute();
    return $consulta->fetch(PDO::FETCH_ASSOC);
}


public function TotalHoy() {
    $query = "
        SELECT 
            SUM(total_dl) AS total_hoy
        FROM venta
        WHERE fecha = CURDATE()
    ";

    $consulta = $this->conex->prepare($query);
    $consulta->execute();
    return $consulta->fetch(PDO::FETCH_ASSOC);
}

public function SemanasVentas() {
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
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}



    }


?>        