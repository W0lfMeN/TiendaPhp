<?php
namespace Tienda;
use PDO;
use PDOException;

class Conexion{
    protected static $conexion;

    public function __construct(){
        if(self::$conexion==null){
            //Creamos la conexion
            self::crearConexion();
        }
    }

    private function crearConexion(){
        //Primero buscamos el fichero .conf
        $fichero=dirname(__DIR__,1)."/.conf";
        //Parseamos el fichero para sacar los datos
        $opciones=parse_ini_file($fichero);

        //Sacamos los campos
        $user=$opciones['user'];
        $server=$opciones['host'];
        $pass=$opciones['password'];
        $bbdd=$opciones['bbdd'];

        //Hacemos la dns
        $dns="mysql:host=$server;dbname=$bbdd;charset=utf8mb4";

        //iniciamos la conexion
        try{
            self::$conexion=new PDO($dns, $user, $pass);

            //Esta linea si estamos en desarrollo
            //self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $ex){
            die("Error al conectar a crudpost: ".$ex->getMessage());
        }
    }
}