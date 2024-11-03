<?php
$conexion = mysqli_connect("localhost", "root", "", "inventario");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

if (isset($_POST['check'])) {
    $ids = $_POST['check'];
    foreach ($ids as $id) {
        // Sanitizar el ID
        $id = mysqli_real_escape_string($conexion, $id);

        // Consulta de eliminación para la tabla 'proveedores'
        $sql = "DELETE FROM proveedores WHERE idProveedor = '$id'";
        
        // Ejecutar la consulta y comprobar si tuvo éxito
        if (mysqli_query($conexion, $sql)) {
            echo "Proveedor eliminado: $id<br>";
        } else {
            echo "Error eliminando el proveedor $id: " . mysqli_error($conexion) . "<br>";
        }
    }
} else {
    echo "No se seleccionaron proveedores para eliminar.<br>";
}

// Redirigir de vuelta a la página correspondiente después de 3 segundos
header("Location: loadingpr.php");
exit();
?>
