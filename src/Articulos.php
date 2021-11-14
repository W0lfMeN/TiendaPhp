<?php
namespace Tienda;
use Faker;
use PDO;
use PDOException;

class Articulos extends Conexion{
    private $id;
    private $nombre;
    private $precio;
    private $categoria_id;

    public function __construct(){
        parent::__construct();
    }

    //--------------CRUD-------------------
    public function create(){
        $q="insert into articulos (nombre, precio, categoria_id) values (:n, :p, :ci)";
        $stmt=parent::$conexion->prepare($q);

        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':p'=>$this->precio,
                ':ci'=>$this->categoria_id
            ]);
        }catch(PDOException $ex){
            die("Error al insertar el articulo: ".$ex->getMessage());
        }

        parent::$conexion=null;
    }

    public function read($id){
        $q="select * from articulos where id=:id";
        $stmt=parent::$conexion->prepare($q);

        try{
            $stmt->execute([
                ':id'=>$id
            ]);
        }catch(PDOException $ex){
            die("Error al leer el articulo: ".$ex->getMessage());
        }

        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function update($id){
        $q="update articulos set nombre=:n, precio=:p, categoria_id=:ci where id=:i";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':p'=>$this->precio,
                ':ci'=>$this->categoria_id,
                ':i'=>$id
            ]);
        }catch(PDOException $ex){
            die("Error al actualizar el articulo: ".$ex->getMessage());
        }

        parent::$conexion=null;
    }

    public function delete($id){
        $q="delete from articulos where id=:id";
        $stmt=parent::$conexion->prepare($q);

        try{
            $stmt->execute([
                ':id'=>$id
            ]);
        }catch(PDOException $ex){
            die("Error al borrar el articulo: ".$ex->getMessage());
        }

        parent::$conexion=null;
    }

    //------------------------------------

    //--------------OTROS METODOS-----------
    public function hayArticulos(){
        $q="select * from articulos";
        $stmt=parent::$conexion->prepare($q);

        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error al comprobar si hay articulos: ".$ex->getMessage());
        }

        parent::$conexion=null;
        if($stmt->rowCount()!=0) return true;

        return false;
    }

    public function generarAleatorios($cantidad){
        $faker=Faker\Factory::create("es_ES");

        $idCategorias=(new Categorias)->devolverIds(); //me traigo toda la lista de ids de categorias

        for($i=0;$i<$cantidad;$i++){
            $nombre=$faker->sentence(5);
            $precio=$faker->randomFLoat(2,0,999.99); //Genera un float con 2 decimales entre 0 y 999.99
            $categoria_id=$idCategorias[array_rand($idCategorias, 1)]; //Sacamos un id aleatorio del array

            //Insertamos el articulo
            (new Articulos)->setNombre($nombre)->setPrecio($precio)->setCategoria_id($categoria_id)->create();
        }
    }

    public function readAll(){
        $q="select * from articulos order by nombre asc";
        $stmt=parent::$conexion->prepare($q);

        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error al leer todos los articulos: ".$ex->getMessage());
        }

        parent::$conexion=null;
        return $stmt;
    }

    public function existeArticulo($nombre){
        $q="select * from articulos where nombre=:n";
        $stmt=parent::$conexion->prepare($q);

        try{
            $stmt->execute([
                ':n'=>$nombre
            ]);
        }catch(PDOException $ex){
            die("Error al comprobar si existe el articulo: ".$ex->getMessage());
        }
        parent::$conexion=null;

        return ($stmt->rowCount()==0); //Falso si existe esa categoria
    }


    //--------------------------------------
    /**
     * Get the value of categoria_id
     */ 
    public function getCategoria_id()
    {
        return $this->categoria_id;
    }

    /**
     * Set the value of categoria_id
     *
     * @return  self
     */ 
    public function setCategoria_id($categoria_id)
    {
        $this->categoria_id = $categoria_id;

        return $this;
    }

    /**
     * Get the value of precio
     */ 
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set the value of precio
     *
     * @return  self
     */ 
    public function setPrecio($precio)
    {
        $this->precio = $precio;

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
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
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
}