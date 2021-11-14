<?php
    //INDEX DE ARTICULOS
    session_start(); //Se usarán al momento de mostrar mensajes 

    require dirname(__DIR__,2)."/vendor/autoload.php";
    use Tienda\{Articulos, Categorias}; //Usaremos Categorias para traer el nombre del id de la categoria

    /* AQUI GENERAREMOS LOS ARTICULOS */ 
    // SE HACE DE ESTA FORMA PORQUE ANTES DE HABER ARTICULOS DEBE DE HABER CATEGORIAS
    if((new Articulos)->hayArticulos()==false){
        //SI entra quiere decir que no hay articulos, los generamos
        (new Articulos)->generarAleatorios(30);
    }

    $listadoArticulos=(new Articulos)->readAll(); //Trae todos los articulos
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
    <title>Articulos</title>
</head>
<body style="background-color:silver">
    <h3 class="text-center">Listado de articulos</h3>
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

    <a href="crearArticulo.php" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo articulo</a>
    <table class="table table-dark table-striped mt-2">
        <thead>
            <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Precio</th>
            <th scope="col">Categoria</th>
            <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
                while($fila=$listadoArticulos->fetch(PDO::FETCH_OBJ)){
                    //Esta linea nos devolverá el nombre de la categoria pasandole el id de la categoria que tiene el articulo
                    $nombreCategoria=(new Categorias)->devolverNombreCategoria($fila->categoria_id);
                    echo <<< TXT
                    <tr>
                    <th scope="row">{$fila->nombre}</th>
                    <td>{$fila->precio} €</td>
                    <td>$nombreCategoria</td>
                    <td>
                        <form name='s' action='borrarArticulo.php' method='POST'> 
                        <input type='hidden' name='id' value='{$fila->id}'>
                        <a href="updateArticulo.php?id={$fila->id}" class="btn btn-warning"><i class="far fa-edit"></i></a>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea borrar el articulo?')"><i class="far fa-trash-alt"></i></button>
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