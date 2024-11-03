<?php
$conexion = mysqli_connect("localhost", "root", "", "inventario");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

if (isset($_POST['check'])) {
    $ids = $_POST['check'];
    foreach ($ids as $id) {
        $id = mysqli_real_escape_string($conexion, $id);
        // Cambia 'productos' por 'movimiento_inventario' y 'idProducto' por 'idMovimiento'
        $sql = "DELETE FROM movimiento_inventario WHERE idMovimiento = '$id'";
        mysqli_query($conexion, $sql);
    }
}

// Redirecciona de nuevo a la página después de borrar
header("Location: movimientoinventa.php");
exit();
?>

