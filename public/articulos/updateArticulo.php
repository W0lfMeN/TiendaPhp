<?php
    session_start();

    if(!isset($_GET['id'])){
        header('Location:index.php');
        die();
    }

    $id=$_GET['id'];

    require dirname(__DIR__,2)."/vendor/autoload.php";
    use Tienda\{Articulos,Categorias};

    //leemos el articulo ayudandonos del id que ha venido por get
    $articulo=(new Articulos)->read($id);

    $error=false; //suponemos que no hay ningun problema 

    function hayError($name, $precio){
        global $error;
        //Comprobamos si hay cadenas vacias
        if(strlen($name)==0){
            $error=true;
            $_SESSION['error_nombre']="El nombre no puede estar vacio";
        }

        if(strlen($precio)==0){
            $error=true;
            $_SESSION['error_precio']="El precio no puede estar vacia";
        }

        //Comprobamos que el precio está entre los valores admitidos

        if($precio<0){
            $error=true;
            $_SESSION['error_precio']="El precio no puede ser menor que 0";
        }

        if($precio>999.99){
            $error=true;
            $_SESSION['error_precio']="El precio no puede ser mayor que 999.99";
        }
        //--------
    }

    if(isset($_POST['btnUpdate'])){
        $nombre=trim(ucfirst($_POST['nombre']));
        $precio=trim($_POST['precio']);
        $categoria_id=$_POST['categoria_id'];

        hayError($nombre, $precio);

        if($error==false){
            //Si entra aqui quiere decir que todo está OK, procedemos a crear el articulo
            (new Articulos)->setNombre($nombre)->setPrecio($precio)->setCategoria_id($categoria_id)->update($id);

            $_SESSION['mensaje']="Articulo actualizado correctamente";

            header('Location:index.php');
        }else{
            header("Location:{$_SERVER['PHP_SELF']}?id=$id");
        }

    }else{
        //Pintamos el formulario
    
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fontawesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Actualizar Articulos</title>
</head>
<body style="background-color:silver">
    <h3 class="text-center">Actualizar Articulo</h3>
    <div class="container mt-3">
        <div class="bg-success p-4 text-white rounded shadow-lg mx-auto" style="width:48rem">
            <form name="cCategoria" method="POST" action="<?php echo $_SERVER['PHP_SELF']."?id=$id" ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre del articulo</label>
                <input type="text" class="form-control" id="name" placeholder="Articulo" name="nombre" value="<?php echo $articulo->nombre ?>" require>
                <?php
                    if(isset($_SESSION['error_nombre'])){
                        echo <<< TXT
                            <div class="mb-2 text-danger fw-bold" style=font-size:small">
                                {$_SESSION['error_nombre']}
                            </div>
                        TXT;
                        unset($_SESSION['error_nombre']);
                    }
                ?>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio del articulo</label>
                <input type="number" step="any" class="form-control" id="precio" placeholder="Precio" value="<?php echo $articulo->precio ?>" name="precio" require>
                <?php
                    if(isset($_SESSION['error_precio'])){
                        echo <<< TXT
                            <div class="mb-2 text-danger fw-bold" style=font-size:small">
                                {$_SESSION['error_precio']}
                            </div>
                        TXT;
                        unset($_SESSION['error_precio']);
                    }
                ?>
            </div>
            <div class="mb-3">
                <label for="a" class="form-label">Indica el nombre de la categoria</label>
                <select class="form-select" name="categoria_id" id="a">
                    <?php
                        $categorias=(new Categorias)->readAll(); //Nos traemos todas las categorias

                        //Como readAll devuelve el saco $stmt, lo tratamos como un array con fetchAll y lo vamos recorriendo
                        foreach($categorias->fetchAll(PDO::FETCH_OBJ) as $item){
                            if($item->id == $id){
                                echo "<option selected value='{$item->id}'>{$item->nombre}</option>";
                            }
                            echo "<option value='{$item->id}'>{$item->nombre}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="mt-3">
                <button type='submit' name="btnUpdate" class="btn btn-info"><i class="fas fa-save"></i> Crear</button>
                <button type='reset' name="btnLimpiar" class="btn btn-warning"><i class="fas fa-broom"></i> Limpiar</button>
            </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php } ?>