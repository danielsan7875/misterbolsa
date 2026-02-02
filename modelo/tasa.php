<?php

require_once 'conexion.php';
class Tasa extends Conexion{
//--------------
    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }
//-----------------
    public function procesarTasa($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                     
               case 'tasadolar':
                    if (!$datosProcesar['id_dolar'] == 1) {
                        return ['respuesta' => 0, 'accion' => 'tasadolar', 'text' => 'El metodo pago no existe'];
                    }
            
                    return $this->ejecutarTasa($datosProcesar);    
                       
                default:
                    return ['respuesta' => 0, 'tasadolar' => 'Operación no válida'];
            }
        } catch (\Exception $e) {
            return ['respuesta' => 0, 'tasadolar' => $e->getMessage()];
        }
    }
//-------
    public function obtenerDolar() {
        try{
            $conex = $this->conex;
            $conex ->beginTransaction();

            $query = "SELECT * FROM dolar WHERE id_dolar = 1";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            $Datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $conex ->commit();
            $conex = null;
            return $Datos;

        }catch(\PDOException $e){
            if($conex){
                $conex ->rollBack();
                $conex = null;
            }
            throw $e;
        }
    } 
//---------------    
    private function ejecutarTasa($datos){
        try{
            $conex = $this ->conex;
            $conex ->beginTransaction();
            
            $registro = "UPDATE dolar SET id_dolar = :id_dolar, precios = :precios WHERE id_dolar = :id_dolar";
            
            $param =[
                'id_dolar' => $datos['id_dolar'],
                'precios' => $datos['precios']
            ];

            $strExec = $conex->prepare($registro);
            $strExec->execute($param);

            if($strExec->rowCount() > 0){
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1 , 'accion' => 'tasadolar'];

            }

            $conex->rollBack();
            $conex=null;
            return ['respuesta' => 0, 'accion' => 'tasadolar', 'text' => 'Error al momento de ejecutar'];

        }catch(\PDOException $e){
            if($conex){
                $conex ->rollBack();
                $conex = null;
                return ['respuesta' => 0, 'accion' => 'tasadolar', 'text' => $e->getMessage()];
            }
            throw $e;
        }
	}
//------- 
}
?>        