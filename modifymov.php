<?php
// Conexión a la base de datos 'inventario'
$conexion = mysqli_connect("localhost", "root", "", "inventario");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Variables iniciales
$idMovimiento = null;
$idProducto = '';
$idUsuario = '';
$tipo = '';
$cantidad = '';
$fecha = '';

// Comprobar si se ha proporcionado un idMovimiento en la URL para editar
if (isset($_GET['c'])) {
    $idMovimiento = mysqli_real_escape_string($conexion, $_GET['c']);

    // Obtener los datos del movimiento
    $sqlMovimiento = $conexion->query("SELECT * FROM movimiento_inventario WHERE idMovimiento = '$idMovimiento'");
    $movimiento = $sqlMovimiento->fetch_assoc();

    if ($movimiento) {
        $idProducto = $movimiento['idProducto'];
        $idUsuario = $movimiento['idUsuario'];
        $tipo = $movimiento['tipo'];
        $cantidad = $movimiento['cantidad'];
        $fecha = $movimiento['fecha'];
    } else {
        die("Movimiento no encontrado");
    }
}

// Manejar el formulario de actualización o inserción
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['idProducto']) && !empty($_POST['idUsuario']) && !empty($_POST['tipo']) && !empty($_POST['cantidad'])) {
    $idProducto = mysqli_real_escape_string($conexion, $_POST['idProducto']);
    $idUsuario = mysqli_real_escape_string($conexion, $_POST['idUsuario']);
    $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
    $cantidad = mysqli_real_escape_string($conexion, $_POST['cantidad']);
    $fecha = date("Y-m-d H:i:s");

    if ($idMovimiento) {
        // Actualizar el movimiento existente
        $sqlUpdate = $conexion->query("UPDATE movimiento_inventario SET idProducto='$idProducto', idUsuario='$idUsuario', tipo='$tipo', cantidad='$cantidad', fecha='$fecha' WHERE idMovimiento='$idMovimiento'");
        if ($sqlUpdate) {
            $mensaje = "Movimiento actualizado correctamente";
        } else {
            $mensaje = "Error al actualizar el movimiento: " . $conexion->error;
        }
    } else {
        // Insertar un nuevo movimiento
        $sqlInsert = $conexion->query("INSERT INTO movimiento_inventario (idProducto, idUsuario, tipo, cantidad, fecha) VALUES ('$idProducto', '$idUsuario', '$tipo', '$cantidad', '$fecha')");
        if ($sqlInsert) {
            $mensaje = "Movimiento insertado correctamente";
        } else {
            $mensaje = "Error al insertar el movimiento: " . $conexion->error;
        }
    }

    // Cerrar la conexión
    $conexion->close();

    // Redirigir a la página de carga
    header("Location: loadingm.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $idMovimiento ? "Modificar Movimiento" : "Agregar Movimiento"; ?></title>
    <style>
        /* Estilos */
         * {
            margin: 0;
            padding: 0;
            font-family: 'Mukta', sans-serif;
        }

        body {
            background-color: #292929;
            color: #fff;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #383838;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .form-group {
            position: relative;
            margin: 12px 0;
            width: 100%;
        }

        .form-group input, .form-group select {
            padding: 8px 10px;
            font-size: 18px;
            border-radius: 5px;
            border: #acacac solid 2px;
            background-color: transparent;
            color: #eee;
            transition: 0.15s all ease;
            width: calc(100% - 22px);
        }

        .form-group input:focus, .form-group select:focus {
            border-color: #eee;
        }

        .form-group input::placeholder {
            color: transparent;
        }

        .form-group .form-label {
            position: absolute;
            top: 50%;
            left: 10px;
            font-size: 18px;
            color: #acacac;
            transform: translateY(-50%);
            pointer-events: none;
            transition: 0.15s all ease;
            background-color: #383838;
            padding: 0 5px;
        }

        .form-group input:focus + .form-label,
        .form-group input:not(:placeholder-shown) + .form-label {
            transform: translateY(-150%);
            font-size: 14px;
            color: #eee;
        }

        .btn {
            position: relative;
            width: 150px;
            font-size: 18px;
            font-weight: 500;
            text-align: center;
            color: #fff;
            background-color: #e94b35;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
            transition: 0.3s all ease;
        }

        .btn::before {
            content: attr(data-front);
            transition: 0.9s all ease;
            opacity: 1;
            transform: translateY(0) rotateX(0);
        }

        .btn::after {
            content: attr(data-back);
            opacity: 0;
            transform: translateY(-50%) rotateX(90deg);
        }

        .btn:hover::before {
            opacity: 0;
            transform: translateY(50%) rotateX(-90deg);
        }

        .btn:hover::after {
            opacity: 1;
            transform: translateY(0) rotateX(0);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1><?php echo $idMovimiento ? "Modificar Movimiento" : "Agregar Movimiento"; ?></h1>
        <form action="modifymov.php<?php echo $idMovimiento ? "?c=$idMovimiento" : ''; ?>" method="post">
            <div class="form-group">
                <input type="text" id="idProducto" name="idProducto" value="<?php echo htmlspecialchars($idProducto); ?>" required>
                <label for="idProducto" class="form-label">ID Producto:</label>
            </div>

            <div class="form-group">
                <input type="text" id="idUsuario" name="idUsuario" value="<?php echo htmlspecialchars($idUsuario); ?>" required>
                <label for="idUsuario" class="form-label">ID Usuario:</label>
            </div>

            <div class="form-group">
                <select id="tipo" name="tipo" required>
                    <option value="entrada" <?php echo $tipo == 'entrada' ? 'selected' : ''; ?>>Entrada</option>
                    <option value="salida" <?php echo $tipo == 'salida' ? 'selected' : ''; ?>>Salida</option>
                </select>
                <label for="tipo" class="form-label">Tipo de Movimiento:</label>
            </div>

            <div class="form-group">
                <input type="number" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($cantidad); ?>" required>
                <label for="cantidad" class="form-label">Cantidad:</label>
            </div>

            <button type="submit" class="btn" data-front="<?php echo $idMovimiento ? 'Actualizar Movimiento' : 'Agregar Movimiento'; ?>" data-back="Movimiento Procesado"><?php echo $idMovimiento ? 'Actualizar Movimiento' : 'Agregar Movimiento'; ?></button>
        </form>
        <?php if (isset($mensaje)): ?>
            <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
