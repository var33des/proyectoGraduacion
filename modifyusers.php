<?php
// Conexión a la base de datos 'inventario'
$conexion = mysqli_connect("localhost", "root", "", "inventario");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Variables iniciales
$idUsuario = null;
$nombre = '';
$apellido = '';
$correo_electronico = '';
$rol = 'usuario'; // Valor por defecto
$clave = ''; // Contraseña, por defecto vacía

// Comprobar si se ha proporcionado un idUsuario en la URL para editar
if (isset($_GET['c'])) {
    $idUsuario = mysqli_real_escape_string($conexion, $_GET['c']);

    // Obtener los datos del usuario
    $sqlUsuario = $conexion->query("SELECT * FROM usuarios WHERE idUsuario = '$idUsuario'");
    $usuario = $sqlUsuario->fetch_assoc();

    if ($usuario) {
        $nombre = $usuario['nombre'];
        $apellido = $usuario['apellido'];
        $correo_electronico = $usuario['correo_electronico'];
        $rol = $usuario['rol'];
    } else {
        die("Usuario no encontrado");
    }
}

// Manejar el formulario de actualización o inserción
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['correo_electronico']) && !empty($_POST['rol'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $correo_electronico = mysqli_real_escape_string($conexion, $_POST['correo_electronico']);
    $rol = mysqli_real_escape_string($conexion, $_POST['rol']);
    
    // Verificar si se ha ingresado una nueva contraseña
    if (!empty($_POST['clave'])) {
        $clave = mysqli_real_escape_string($conexion, $_POST['clave']);
        $clave = password_hash($clave, PASSWORD_DEFAULT); // Encriptar la nueva contraseña
    }

    if ($idUsuario) {
        // Actualizar el usuario existente
        if ($clave) {
            // Si se proporciona una nueva contraseña, actualizarla
            $sqlUpdate = $conexion->query("UPDATE usuarios SET nombre='$nombre', apellido='$apellido', correo_electronico='$correo_electronico', rol='$rol', clave='$clave' WHERE idUsuario='$idUsuario'");
        } else {
            // Si no se proporciona nueva contraseña, solo actualizar otros datos
            $sqlUpdate = $conexion->query("UPDATE usuarios SET nombre='$nombre', apellido='$apellido', correo_electronico='$correo_electronico', rol='$rol' WHERE idUsuario='$idUsuario'");
        }
        
        if ($sqlUpdate) {
            $mensaje = "Usuario actualizado correctamente";
        } else {
            $mensaje = "Error al actualizar el usuario: " . $conexion->error;
        }
    } else {
        // Insertar un nuevo usuario
        $clave = password_hash($clave, PASSWORD_DEFAULT); // Encriptar la nueva contraseña
        $sqlInsert = $conexion->query("INSERT INTO usuarios (nombre, apellido, correo_electronico, rol, clave) VALUES ('$nombre', '$apellido', '$correo_electronico', '$rol', '$clave')");
        if ($sqlInsert) {
            $mensaje = "Usuario insertado correctamente";
        } else {
            $mensaje = "Error al insertar el usuario: " . $conexion->error;
        }
    }

    // Cerrar la conexión
    $conexion->close();

    // Redirigir a la página de carga
    header("Location: loadinguser.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $idUsuario ? "Modificar Usuario" : "Agregar Usuario"; ?></title>
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
        <h1><?php echo $idUsuario ? "Modificar Usuario" : "Agregar Usuario"; ?></h1>
        <form action="modifyusers.php<?php echo $idUsuario ? "?c=$idUsuario" : ''; ?>" method="post">
            <div class="form-group">
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                <label for="nombre" class="form-label">Nombre:</label>
            </div>

            <div class="form-group">
                <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($apellido); ?>" required>
                <label for="apellido" class="form-label">Apellido:</label>
            </div>

            <div class="form-group">
                <input type="email" id="correo_electronico" name="correo_electronico" value="<?php echo htmlspecialchars($correo_electronico); ?>" required>
                <label for="correo_electronico" class="form-label">Correo Electrónico:</label>
            </div>

            <div class="form-group">
                <select id="rol" name="rol" required>
                    <option value="usuario" <?php echo $rol === 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                    <option value="admin" <?php echo $rol === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
                <label for="rol" class="form-label">Rol:</label>
            </div>

            <div class="form-group">
                <input type="password" id="clave" name="clave" placeholder="Nueva Contraseña (dejar vacío para no cambiar)">
                <label for="clave" class="form-label">Contraseña:</label>
            </div>

            <button type="submit" class="btn" data-front="<?php echo $idUsuario ? 'Actualizar Usuario' : 'Agregar Usuario'; ?>" data-back="Usuario Procesado"><?php echo $idUsuario ? 'Actualizar Usuario' : 'Agregar Usuario'; ?></button>
        </form>
        <?php if (isset($mensaje)): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
