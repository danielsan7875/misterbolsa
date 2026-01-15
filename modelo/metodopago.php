<?php

    require_once 'conexion.php';

    class Metodopago extends Conexion{

    private $conex;
    private $id_metodo;
    private $nombre;
    private $estatus;

    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }


    public function consultar(){

        $registro ="SELECT * FROM metodopago WHERE estatus <5
        ORDER BY id_metodopago DESC";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();

        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            $dato['datos'] = $datos;
            $dato['accion'] = 'consultar';
            return $dato;
        } else {
            return $res = 0;
        }
    
    } //fin consultar
    
    public function eliminar(){ // Elimanado Logico
        try {

            $registro = "UPDATE metodopago SET estatus = 2 WHERE id_metodopago = :id_metodo";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_metodo', $this->id_metodo);
            $result = $strExec->execute();
    
            if ($result) {
                $res['respuesta'] = 1;
                $res['accion'] = 'eliminar';
            } else {
                $res['respuesta'] = 0; 
                $res['accion'] = 'eliminar';
            }
            return $res;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

     public function activar(){ // Elimanado Logico
        try {

            $registro = "UPDATE metodopago SET estatus = 1 WHERE id_metodopago = :id_metodo";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_metodo', $this->id_metodo);
            $result = $strExec->execute();
    
            if ($result) {
                $res['respuesta'] = 1;
                $res['accion'] = 'incluir';
            } else {
                $res['respuesta'] = 0; 
                $res['accion'] = 'incluir';
            }
            return $res;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function get_Id_metodo(){
        return $this->id_presentacion;
    }

    public function set_Id_metodo($id_metodo){
        $this->id_metodo =$id_metodo;
    }

    public function get_Nombre(){
        return $this->nombre;
    }

    public function set_Nombre($nombre){
        $this->nombre=ucfirst(strtolower($nombre));
    }
    public function get_Estatus(){
        return $this->estatus;
    }

    public function set_Estatus($estatus){
        $this->estatus=$estatus;
    }

        

    }


?>        