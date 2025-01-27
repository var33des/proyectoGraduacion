<?php
// Conexión a la base de datos 'inventario'
$conexion = mysqli_connect("localhost", "root", "", "inventario");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Variables iniciales
$nombre = '';
$apellido = '';
$correo_electronico = '';
$clave = '';
$rol = 'usuario'; // Valor por defecto

// Manejar el formulario de inserción
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo_electronico = $_POST['correo_electronico'];
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT); // Encriptar la clave
    $rol = $_POST['rol'];

    // Insertar un nuevo usuario
    $sqlInsert = $conexion->query("INSERT INTO usuarios (nombre, apellido, correo_electronico, clave, rol) VALUES ('$nombre', '$apellido', '$correo_electronico', '$clave', '$rol')");
    
    if ($sqlInsert) {
        $mensaje = "Usuario insertado correctamente";
        $idUsuario = $conexion->insert_id;
    } else {
        $mensaje = "Error al insertar el usuario: " . $conexion->error;
    }

    // Cerrar la conexión
    $conexion->close();

    // Redirigir a la página de carga
    header("Location: loading.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
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
<h1>Agregar usuario</h1>
        <form action="modifyusers.php<?php echo $idUsuario ? "?c=$idUsuario" : ''; ?>" method="post">
        <section>  
            <div class="form-group">
                <input type="text" id="nombre" name="nombre" placeholder="dier" value="<?php echo htmlspecialchars($nombre); ?>" required>
                <label for="nombre" class="form-label">Nombre:</label>
            </div>

            <div class="form-group">
                <input type="text" id="apellido" name="apellido" placeholder="dier" value="<?php echo htmlspecialchars($apellido); ?>" required>
                <label for="apellido" class="form-label">Apellido:</label>
            </div>

            <div class="form-group">
                <input type="email" id="correo_electronico" name="correo_electronico" placeholder="dier" value="<?php echo htmlspecialchars($correo_electronico); ?>" required>
                <label for="correo_electronico" class="form-label">Correo Electrónico:</label>
            </div>

            <div class="form-group">
                <label for="rol" class="form-label1">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="usuario" <?php echo $rol === 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                    <option value="admin" <?php echo $rol === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <div class="form-group">
                <input type="password" id="clave" name="clave" placeholder="dier" value="<?php echo htmlspecialchars($clave); ?>" required>
                <label for="clave" class="form-label">Contraseña:</label>
            </div>
            <button type="submit" class="btn" data-front="Agregar Usuario" data-back="Usuario Agregado"></button>
            <br>
            <br>
            <button type="button" class="btn" data-front="regresar" data-back="regresando a inicio" onclick="window.location.href='../user.php'"></button>
         
        </section>
         </form>     
    </div>
</body>
</html>