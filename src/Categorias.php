<?php
namespace Tienda;
use Faker;
use PDO;
use PDOException;

class Categorias extends Conexion{
    private $id;
    private $nombre;
    private $descripcion;

    public function __construct(){
        parent::__construct(); //Inicializa la conexion
    }

    //-------------------CRUD-------------------
    public function create(){
        $q="insert into categorias (nombre, descripcion) values (:n, :d)";
        $stmt=parent::$conexion->prepare($q);

        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':d'=>$this->descripcion
            ]);
        }catch(PDOException $ex){
            die("Error en la insercion".$ex->getMessage());
        }

        parent::$conexion=null;
    }

    public function read($id){
        $q="select * from categorias where id=:i";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':i'=>$id
            ]);
        }catch(PDOException $ex){
            die("Error al leer la categoria: ".$ex->getMessage());
        }

        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ);

    }

    public function update($id){
        $q="update categorias set nombre=:n, descripcion=:d where id=:i";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':d'=>$this->descripcion,
                ':i'=>$id
            ]);
        }catch(PDOException $ex){
            die("Error al actualizar: ".$ex->getMessage());
        }

        parent::$conexion=null;
    }

    public function delete($id){
        $q="delete from categorias where id=:id";
        $stmt=parent::$conexion->prepare($q);

        try{
            $stmt->execute([
                ':id'=>$id
            ]);
        }catch(PDOException $ex){
            die("Error en el borrado de la categoria".$ex->getMessage());
        }

        parent::$conexion=null;
    }
    
    //------------------OTROS METODOS---------------
    public function insertarAleatorios($cantidad){
        $nombre;
        $descripcion;
        $faker=Faker\Factory::create('es_ES');
        for($i=0;$i<$cantidad;$i++){
            $nombre=$faker->unique()->word();
            $descripcion=$faker->text(50);

            (new Categorias)->setNombre($nombre)->setDescripcion($descripcion)->create();
        }
    }

    public function hayCategorias(){
        $q="select * from categorias";
        $stmt=parent::$conexion->prepare($q);

        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error al comprobar si hay categorias: ".$ex->getMessage());
        }
        parent::$conexion=null;

        if($stmt->rowCount()!=0) return true;

        return false;
    }

    public function readAll(){
        $q="select * from categorias order by nombre asc";
        $stmt=parent::$conexion->prepare($q);

        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error al leer todas las categorias: ".$ex->getMessage());
        }
        parent::$conexion=null;

        return $stmt;
    }

    public function existeCategoria($nombre){
        if(isset($this->id)){
            //Si entra aqui quiere decir que hay un id en el objeto, por lo tanto estamos ante una secuencia de update
            //se modifica la consulta para que busque todos los ids de categorias menos el que tiene la categoria que queremos actualizar
            $q="select * from categorias where nombre=:n AND id!={$this->id}";
        }else{
            $q="select * from categorias where nombre=:n";
        }
        $stmt=parent::$conexion->prepare($q);

        try{
            $stmt->execute([
                ':n'=>$nombre
            ]);
        }catch(PDOException $ex){
            die("Error al comprobar si existe la categoria: ".$ex->getMessage());
        }
        parent::$conexion=null;

        return ($stmt->rowCount()==0); //Falso si existe esa categoria
    }

    public function devolverIds(){
        $q="select * from categorias";
        $stmt=parent::$conexion->prepare($q);

        try{
            $stmt->execute([]);
        }catch(PDOException $ex){
            die("Error al devolver todos los ids: ".$ex->getMessage());
        }

        //Ahora metemos todos los ids en un array y lo devolvemos
        
        $ids=[];
        while($fila=$stmt->fetch(PDO::FETCH_OBJ)){
            $ids[]=$fila->id;
        }

        parent::$conexion=null;
        return $ids;
    }

    public function devolverNombreCategoria($id){
        $q="select * from categorias where id=:id";
        $stmt=parent::$conexion->prepare($q);

        try{
            $stmt->execute([
                ':id'=>$id
            ]);
        }catch(PDOException $ex){
            die("Error al devolver el nombre de la categoria: ".$ex->getMessage());
        }

        $nombre=$stmt->fetch(PDO::FETCH_OBJ)->nombre; //Como la consulta devuelve una fila, obtenemos el nombre de la misma y lo retornamos
        parent::$conexion=null;
        return $nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */ 
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Get the value of descripcion
     */ 
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }
}