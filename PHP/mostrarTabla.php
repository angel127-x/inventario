<?php include('conexion.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="stylesheet" href="/CSS/inventario.css">
</head>
<body>
<header class="encabezado"><h1 class="encabezado_titulo">Listado de registros</h1></header>
    <section class="cargarTabla center">
            <table class="cargarTabla__tabla">
                <thead>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Categoria</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                </thead>
                <tbody>
                    <!-- mandamos a llamar la funcion de cargar tabla en esta seccion del HTML -->
                    <?=cargarTabla($conexion); ?>
                </tbody>
            </table>
</section>
<section class="seccion__btn">
    <div class="btn">
        <a href="../index.php">Invetario</a>
        <a href="../venta.php">Vender Prodcuto</a>
    </div>
</section>
</body>
</html>