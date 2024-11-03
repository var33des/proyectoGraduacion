<?php
// Conexión a la base de datos 'inventario'
$conexion = mysqli_connect("localhost", "root", "", "inventario");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Variables iniciales
$idProducto = '';
$idUsuario = '';
$cantidad = '';
$tipo = '';
$mensaje = ''; // Para mostrar mensajes

// Manejar el formulario de inserción
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['idProducto']) && !empty($_POST['idUsuario'])) {
    $idProducto = mysqli_real_escape_string($conexion, $_POST['idProducto']);
    $idUsuario = mysqli_real_escape_string($conexion, $_POST['idUsuario']);
    $cantidad = mysqli_real_escape_string($conexion, $_POST['cantidad']);
    $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);

    // Validar tipo de movimiento
    if ($tipo !== 'entrada' && $tipo !== 'salida') {
        $mensaje = "Tipo de movimiento inválido.";
    } else {
        // Insertar un nuevo movimiento de inventario
        $sqlInsert = $conexion->query("INSERT INTO movimiento_inventario (idProducto, idUsuario, tipo, cantidad) VALUES ('$idProducto', '$idUsuario', '$tipo', '$cantidad')");
        if ($sqlInsert) {
            $mensaje = "Movimiento de inventario insertado correctamente";
        } else {
            $mensaje = "Error al insertar el movimiento: " . $conexion->error;
        }
    }
}

// Cerrar la conexión
$conexion->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Movimiento de Inventario</title>
    <style>
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

        .form-group input {
            padding: 8px 10px;
            font-size: 18px;
            border-radius: 5px;
            border: #acacac solid 2px;
            background-color: transparent;
            color: #eee;
            transition: 0.15s all ease;
            width: calc(100% - 22px);
        }

        .form-group input:focus {
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
            text-decoration: none;
            background-color: #e94b35;
            padding: 10px;
            border-radius: 5px;
            transition: 0.3s all ease;
            margin-top: 20px;
            display: inline-block;
            text-align: center;
        } 
        
        .btn::before {
            position: absolute;
            content: attr(data-front);
            left: 0;
            right: 0;
            top: 0;
            padding: 8px 0;
            background-color: #e94b35;
            transition: 0.9s all ease;
            opacity: 1;
            transform: translateY(0) rotateX(0);
        }

        .btn::after {
            position: absolute;
            content: attr(data-back);
            left: 0;
            right: 0;
            top: 0;
            padding: 8px 0;
            background-color: #00bd9c;
            transition: 0.5s all ease;
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
        <h1>Agregar Movimiento de Inventario</h1>
        <form action="modifymov.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
            <input type="number" id="idProducto" name="idProducto" value="<?php echo htmlspecialchars($idProducto); ?>" required>
            <label for="idProducto" class="form-label">ID del Producto:</label>
             </div>

            <div class="form-group">
                <input type="number" id="idUsuario" name="idUsuario" value="<?php echo htmlspecialchars($idUsuario); ?>" required>
                <label for="idUsuario" class="form-label">ID del Usuario:</label>
            </div>

            <div class="form-group">
                 <input type="number" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($cantidad); ?>" required>
                <label for="cantidad" class="form-label">Cantidad:</label>
            </div>

            <div class="form-group">
                <label for="tipo" class="form-label">Tipo de Movimiento:</label>
                <select id="tipo" name="tipo" required>
                    <option value="entrada" <?php echo $tipo == 'entrada' ? 'selected' : ''; ?>>Entrada</option>
                    <option value="salida" <?php echo $tipo == 'salida' ? 'selected' : ''; ?>>Salida</option>
                </select>
            </div>

            <button type="submit" class="btn" data-front="Agregar Movimiento" data-back="Producto Agregado">Agregar Movimiento</button>
        </form>

        <!-- Mostrar mensaje de éxito o error -->
        <?php if (!empty($mensaje)): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
