<?php include('PHP/conexion.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="stylesheet" href="/CSS/inventario.css">
</head>
<body class="body">
    <header class="header">
        <h1 class="header__titulo">Inventario</h1>
    </header>
    <section class="seccion__formulario">
        <form class="formulario centrar" method="post" onsubmit="false;" action="index.php">
            <div class="formulario__posicion">
                <label class="texto" for="codigo">Codigo</label>
                <input class="entrada" type="text" name="codigo" id="codigo" required="true">
            </div>
            <div class="formulario__posicion">
                <label class="texto" for="nombre">Nombre</label>
                <input class="entrada" type="text" name="nombre" id="nombre">
            </div>
            <div class="formulario__posicion">
                <label class="texto" for="categoria">Categoria</label>
                <input class="entrada" type="text" name="categoria" id="categoria">
            </div>
            <div class="formulario__posicion">
                <label class="texto" for="precio">Precio</label>
                <input class="entrada" type="text" name="precio" id="precio">
            </div>
            <div class="formulario__posicion">
                <label class="texto" for="cantidad">Cantidad</label>
                <input class="entrada" type="number" name="cantidad" id="cantidad" required="true">
            </div>
            <div class="cont__btn">
                <button class="btn btn__capturar" name="enviar">Agregar Producto</button>
                <button class="btn btn__capturar" name="actualizar">Actualizar Stock</button>
            </div>
        </form>
</section>
<section class="seccion__btn">
    <div class="btn">
        <a href="/PHP/mostrarTabla.php">Mostrar Registros</a>
        <a href="venta.php">Vender Producto</a>
    </div>
</section>
<?=
selecc($conexion);

// funcion para seleccionar el evento del boton a seleccionar
function selecc($conexion){
    if(isset($_POST['enviar'])){
        insertar($conexion);
    }
    if(isset($_POST['actualizar'])){
        actualizar($conexion);
    }
}

function validarCodigo($conexion, $codigo) {
    // Consulta para verificar si el código existe
    $consulta = "SELECT COUNT(*) AS total FROM inventario WHERE Codigo = '$codigo'";
    $resultado = mysqli_query($conexion, $consulta);
    $fila = mysqli_fetch_assoc($resultado);
    return $fila['total'] > 0; // Devuelve true si el código existe, false en caso contrario
}

function insertar($conexion){
        // almacenamos los registros del formulario
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $categoria = $_POST['categoria'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        date_default_timezone_set("America/Mexico_City");
        $fechaInventario = date("d-m-Y H:i:s");

            // Validamos si el código ya existe
    if (validarCodigo($conexion, $codigo)) {
        echo "<script>
        alert('El código ingresado ya existe. Intente con otro código.');
        </script>";
        return;
    }
        
        // consulta para ingresar registros y se almacenan en una variable
        $consulta = "INSERT INTO inventario(Codigo, Nombre, Categoria, Precio, Cantidad, FechaInventario)
        VALUES('$codigo', '$nombre', '$categoria', '$precio', '$cantidad', '$fechaInventario')";
        

        // hacemos la consulta a la base de datos y despues la cerramos
        mysqli_query($conexion, $consulta);
        mysqli_close($conexion);
        // mostramos una alerta de que el registro ha sido agregado
        echo "<script>
        alert('Registro agregado correctamente');
        </script>";
}
function actualizar($conexion){
        // almacenamos los registros del formulario
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $categoria = $_POST['categoria'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        date_default_timezone_set("America/Mexico_City");
        $fechaInventario = date("d-m-Y H:i:s");

        $consulta = "UPDATE inventario SET Cantidad = $cantidad, FechaInventario = '$fechaInventario' WHERE (Codigo = $codigo)";

        
        // hacemos la consulta a la base de datos y despues la cerramos
        mysqli_query($conexion, $consulta);
        mysqli_close($conexion);
        // mostramos una alerta de que el registro ha sido agregado
        echo "<script>
        alert('Registro actualizado correctamente');
    </script>";
}

    ?>
</body>
</html>