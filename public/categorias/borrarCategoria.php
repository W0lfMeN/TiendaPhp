<?php
//Aqui borramos la Categoria que se le ha pasado por post

session_start();

//Si no llega nada por post nos vamos de aqui
if(!isset($_POST['id'])){
    header('Location:index.php');
    die();
}

require dirname(__DIR__,2)."/vendor/autoload.php";
use Tienda\Categorias;

//Se borra la categoria
(new Categorias)->delete($_POST['id']);

//Mensaje de notificacion
$_SESSION['mensaje']="Categoria eliminada con exito";
header('Location:index.php');