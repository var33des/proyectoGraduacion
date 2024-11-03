<?php
// Conexión a la base de datos 'inventario'
$conexion = mysqli_connect("localhost", "root", "", "inventario");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Variables iniciales
$idProveedor = null;
$nombre = '';
$contacto = '';
$direccion = '';
$telefono = '';

// Comprobar si se ha proporcionado un idProveedor en la URL para editar
if (isset($_GET['c'])) {
    $idProveedor = mysqli_real_escape_string($conexion, $_GET['c']);

    // Obtener los datos del proveedor
    $sqlProveedor = $conexion->query("SELECT * FROM proveedores WHERE idProveedor = '$idProveedor'");
    $proveedor = $sqlProveedor->fetch_assoc();

    if ($proveedor) {
        $nombre = $proveedor['nombre'];
        $contacto = $proveedor['contacto'];
        $direccion = $proveedor['direccion'];
        $telefono = $proveedor['telefono'];
    } else {
        die("Proveedor no encontrado");
    }
}

// Manejar el formulario de actualización o inserción
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nombre']) && !empty($_POST['contacto']) && !empty($_POST['direccion']) && !empty($_POST['telefono'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $contacto = mysqli_real_escape_string($conexion, $_POST['contacto']);
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);

    if ($idProveedor) {
        // Actualizar el proveedor existente
        $sqlUpdate = $conexion->query("UPDATE proveedores SET nombre='$nombre', contacto='$contacto', direccion='$direccion', telefono='$telefono' WHERE idProveedor='$idProveedor'");
        if ($sqlUpdate) {
            $mensaje = "Proveedor actualizado correctamente";
        } else {
            $mensaje = "Error al actualizar el proveedor: " . $conexion->error;
        }
    } else {
        // Insertar un nuevo proveedor
        $sqlInsert = $conexion->query("INSERT INTO proveedores (nombre, contacto, direccion, telefono) VALUES ('$nombre', '$contacto', '$direccion', '$telefono')");
        if ($sqlInsert) {
            $mensaje = "Proveedor insertado correctamente";
        } else {
            $mensaje = "Error al insertar el proveedor: " . $conexion->error;
        }
    }

    // Cerrar la conexión
    $conexion->close();

    // Redirigir a la página de carga
    header("Location: loadingpr.php");
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
        <h1><?php echo $idProveedor ? "Modificar Proveedor" : "Agregar Proveedor"; ?></h1>
        <form action="modifyproviders.php<?php echo $idProveedor ? "?c=$idProveedor" : ''; ?>" method="post">
            <div class="form-group">
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                <label for="nombre" class="form-label">Nombre del Proveedor:</label>
            </div>

            <div class="form-group">
                <input type="text" id="contacto" name="contacto" value="<?php echo htmlspecialchars($contacto); ?>" required>
                <label for="contacto" class="form-label">Contacto:</label>
            </div>

            <div class="form-group">
                <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($direccion); ?>" required>
                <label for="direccion" class="form-label">Dirección:</label>
            </div>

            <div class="form-group">
                <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>" required>
                <label for="telefono" class="form-label">Teléfono:</label>
            </div>

            <button type="submit" class="btn" data-front="<?php echo $idProveedor ? 'Actualizar Proveedor' : 'Agregar Proveedor'; ?>" data-back="Proveedor Procesado"><?php echo $idProveedor ? 'Actualizar Proveedor' : 'Agregar Proveedor'; ?></button>
        </form>
        <?php if (isset($mensaje)): ?>
            <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
