<?php
    session_start();

    if(!isset($_GET['id'])){
        header('Location:index.php');
        die();
    }

    require dirname(__DIR__,2)."/vendor/autoload.php";
    use Tienda\Categorias;

    $error=false; //suponemos que no hay ningun problema 

    //Obtenemos los datos de la categoria por su id
    $id=$_GET['id']; //guardamos el valor en la variable id
    $categoria=(new Categorias)->read($id);

    function hayError($name, $description){
        global $id;
        global $error;
        //Comprobamos si hay cadenas vacias
        if(strlen($name)==0){
            $error=true;
            $_SESSION['error_nombre']="El nombre no puede estar vacio";
        }

        if(strlen($description)==0){
            $error=true;
            $_SESSION['error_descripcion']="La descripcion no puede estar vacia";
        }

        //Comprobamos que el nombre no exista ya en la base de datos comprobando tambien el id
        if(!(new Categorias)->setId($id)->existeCategoria($name)){
           $error=true;
           $_SESSION['error_nombre']="La categoria ya existe"; 
        }
    }

    if(isset($_POST['btnUpdate'])){
        $nombre=trim(ucfirst($_POST['nombre']));
        $descripcion=trim(ucfirst($_POST['descripcion']));

        hayError($nombre, $descripcion);

        if($error==false){
            //Si entra aqui quiere decir que todo está OK, procedemos a actualizar la categoria pasandole el id
            (new Categorias)->setNombre($nombre)->setDescripcion($descripcion)->update($categoria->id);

            $_SESSION['mensaje']="Categoria actualizada correctamente";

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
    <title>Actualizar categorias</title>
</head>
<body style="background-color:silver">
    <h3 class="text-center">Actualizar Categoria</h3>
    <div class="container mt-3">
        <div class="bg-success p-4 text-white rounded shadow-lg mx-auto" style="width:48rem">
            <form name="cCategoria" method="POST" action="<?php echo $_SERVER['PHP_SELF']."?id=$id" ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre de la categoria</label>
                <input type="text" class="form-control" id="name" placeholder="Categoria" name="nombre" value="<?php echo $categoria->nombre ?>" require>
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
                <label for="description" class="form-label">Descripcion</label>
                <textarea class="form-control" id="description" rows="3" placeholder="Datos de la categoria" name="descripcion"><?php echo $categoria->descripcion; ?></textarea>
                <?php
                    if(isset($_SESSION['error_descripcion'])){
                        echo <<< TXT
                            <div class="mb-2 text-danger fw-bold" style=font-size:small">
                                {$_SESSION['error_descripcion']}
                            </div>
                        TXT;
                        unset($_SESSION['error_descripcion']);
                    }
                ?>
            </div>
            <div class="mt-3">
                <button type='submit' name="btnUpdate" class="btn btn-info"><i class="fas fa-save"></i> Actualizar</button>
                <button type='reset' name="btnLimpiar" class="btn btn-warning"><i class="fas fa-broom"></i> Limpiar</button>
            </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php } ?>