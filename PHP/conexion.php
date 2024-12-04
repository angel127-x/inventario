<?php
// Agregamos las variables para la conexion a la base de datos
$servidor = '35.222.112.48';
$usuario = 'sistema';
$contrasenia = 'admin123';
$bd = 'inventarioBD';

// consulta para la conexion a la base de datos
$conexion = mysqli_connect($servidor, $usuario, $contrasenia, $bd);
if($conexion -> connect_error){
    die("Error al conectar la base de datos" .$conexion->connect_error);
}

// mandamos a llamar la funcion para seleccionar el tipo de evento que se va a presentar
seleccion($conexion);

// funcion para seleccionar el evento del boton que se presione
function seleccion($conexion){

    if(isset($_POST['registros'])){
        cargarTabla($conexion);
        // header("Location: /HTML/index.php");
    }
}

// funcion para cargar la tabla con todos los registros de la base de datos
function cargarTabla($conexion){
    // hacemos la consulta de la tabla de los registros
    $consulta = "SELECT * FROM  inventario";
    // almacenamos la consulta en una variable
    $resultado = mysqli_query($conexion, $consulta);

    // convertimos la consulta en un arreglo e imprimimos los diferentes campos de la tabla
    while($fila = mysqli_fetch_array($resultado)){
        echo "<tr>";
        echo "<td>".$fila['Codigo'];
        echo "<td>".$fila['Nombre'];
        echo "<td>".$fila['Categoria'];
        echo "<td>".$fila['Precio'];
        echo "<td>".$fila['Cantidad'];
        echo "</tr>";
    }
    echo "</tbody>
    </table>
    </section>";
    echo "";
    // cerramos la conexion de la base de datos
    mysqli_close($conexion);
}
?>