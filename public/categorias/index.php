<?php
    //INDEX DE CATEGORIAS
    session_start(); //Se usarán al momento de mostrar mensajes 

    require dirname(__DIR__,2)."/vendor/autoload.php";
    use Tienda\Categorias;

    $listadoCategorias=(new Categorias)->readAll(); //Trae todas las categorias
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
    <title>Categorias</title>
</head>
<body style="background-color:silver">
    <h3 class="text-center">Listado de categorias</h3>
    <div class="container mt-4">

    <!-- Aqui mostramos los distintos mensajes que llegan -->
    <?php
        if(isset($_SESSION['mensaje'])){
            echo <<< TXT
                <div class="alert alert-primary" role="alert">
                    {$_SESSION['mensaje']}
                </div>
            TXT;

            unset($_SESSION['mensaje']);
        }
    ?>

    <a href="crearCategoria.php" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva categoria</a>
    <table class="table table-dark table-striped mt-2">
        <thead>
            <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Descripcion</th>
            <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
                while($fila=$listadoCategorias->fetch(PDO::FETCH_OBJ)){
                    echo <<< TXT
                    <tr>
                    <th scope="row">{$fila->nombre}</th>
                    <td>{$fila->descripcion}</td>
                    <td>
                        <form name='s' action='borrarCategoria.php' method='POST'> 
                        <input type='hidden' name='id' value='{$fila->id}'>
                        <a href="updateCategoria.php?id={$fila->id}" class="btn btn-warning"><i class="far fa-edit"></i></a>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea borrar la categoria?')"><i class="far fa-trash-alt"></i></button>
                        </form>
                    </td>
                    </tr>
                TXT;
                }
            ?>
        </tbody>
    </table>
    </div>
</body>
</html>