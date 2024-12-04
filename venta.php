<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vender Producto</title>
    <link rel="stylesheet" href="/CSS/inventario.css">
</head>
<body>
    <header class="header">
        <h1 class="header__titulo">Vender producto</h1>
    </header>
    <!-- Formulario HTML para la venta -->
     <section class="seccion__formulario">
         <form class="formulario centrar" method="POST" onsubmit='false;' action="venta.php">
            <div class="formulario__posicion">
                <label class="texto" for="nombre">Nombre del cliente</label>
                <input class="entrada" type="text" id="nombre" name="nombre" required>
            </div>
            <div class="formulario__posicion">
                <label class="texto" for="codigo">Código del producto</label>
                <input class="entrada" type="text" id="codigo" name="codigo" required>
            </div>
             <div class="formulario__posicion">
                 <label class="texto" for="cantidad">Cantidad a vender</label>
                 <input class="entrada" type="number" id="cantidad" name="cantidad" required>
            </div>
            <div class="formulario__posicion">
                <label class="texto" for="cantidad">ID de venta</label>
                <input class="entrada" type="number" id="idVenta" name="idVenta" required>
            </div>
            <div class="cont__btn">
                <button class="btn btn__capturar" name="enviar" type="submit">Vender</button>
            </div>
        </form>
    </section>
    <section style='margin: 100px 0; width: 100%' class="seccion__formulario">
            <form class="formulario centrar" action="/PHP/factura.php" method="get">
                <div style='width: 20%;' class="formulario__posicion">
                    <label class="texto" for="idVenta">Número de venta</label>
                    <input class="entrada" type="number" id="idVenta" name="idVenta" required>
                </div>
                <div class="cont__btn">
                    <button class="btn btn__capturar" type="submit">Generar factura</button>
                </div>
            </form>
    </section>
    <section class="seccion__btn">
        <div class="btn">
            <a href="index.php">Invetario</a>
            <a href="/PHP/mostrarTabla.php">Mostrar Registros</a>
        </div>
    </section>
</body>
</html>
<?php
// Procesamiento de la venta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Agregamos las variables para la conexion a la base de datos
    $servidor = '35.222.112.48';
    $usuario = 'sistema';
    $contrasenia = 'admin123';
    $bd = 'inventarioBD';

    // consulta para la conexion a la base de datos
    $conn = mysqli_connect($servidor, $usuario, $contrasenia, $bd);
    if($conn -> connect_error){
        die("Error al conectar la base de datos" .$conexion->connect_error);
    }

    // Obtener los datos del formulario
    $nombreCliente = $_POST['nombre'];  // Nombre del cliente
    $codigo_producto = $_POST['codigo'];  // Código del producto
    $cantidad_vendida = $_POST['cantidad'];  // Cantidad vendida
    $idVenta = $_POST['idVenta'];  // ID de venta

    // Verificar si el producto existe y obtener el precio y la cantidad actual
    $sql = "SELECT Nombre, Precio, Cantidad FROM inventario WHERE Codigo = $codigo_producto";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombreProducto = $row['Nombre'];
        $precio_unitario = $row['Precio'];
        $cantidad_actual = $row['Cantidad'];

        // Verificar si hay suficiente cantidad en stock
        if ($cantidad_actual >= $cantidad_vendida) {
            // Calcular el total de la venta
            $total_venta = $cantidad_vendida * $precio_unitario;

            // Actualizar la cantidad del producto en la tabla productos
            $nueva_cantidad = $cantidad_actual - $cantidad_vendida;
            $sql_update = "UPDATE inventario SET Cantidad = $nueva_cantidad WHERE Codigo = $codigo_producto";

            // Registrar la venta en la tabla ventas
            $sql_insert_venta = "INSERT INTO ventas(idVenta, nombreCliente, nombreProducto, cantidadVendida, precioUnitario, ventaTotal)
                                 VALUES('$idVenta', '$nombreCliente', '$nombreProducto', '$cantidad_vendida', '$precio_unitario', '$total_venta')";

            // Ejecutar ambas consultas
            if ($conn->query($sql_update) === TRUE && $conn->query($sql_insert_venta) === TRUE) {
                echo "<script>
        alert('Venta registrada y procesada correctamente');
    </script>";
            } else {
                echo "Error al procesar la venta: " . $conn->error;
            }
        } else {
            echo "<script>
        alert('No hay suficiente cantidad en stock');
    </script>";
        }
    } else {
        echo "<script>
        alert('Producto no encontrado');
    </script>";
    }

    $conn->close();
}
?>
