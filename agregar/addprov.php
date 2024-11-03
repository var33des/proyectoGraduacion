<?php
// Conexión a la base de datos 'inventario'
$conexion = mysqli_connect("localhost", "root", "", "inventario");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Variables iniciales
$nombre = '';
$contacto = '';
$telefono = '';
$direccion = '';

// Manejar el formulario de inserción
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
    $contacto = $_POST['contacto'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    // Insertar un nuevo proveedor
    $sqlInsert = $conexion->query("INSERT INTO proveedores (nombre, contacto, telefono, direccion) VALUES ('$nombre', '$contacto', '$telefono', '$direccion')");
    if ($sqlInsert) {
        $mensaje = "Proveedor insertado correctamente";
        $idProveedor = $conexion->insert_id;

        // Manejo de la imagen        
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
    <title>Agregar Proveedor</title>
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

    section {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100vh;
        flex-flow: column;
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
    }

    .form-group input {
        padding: 8px 10px;
        font-size: 18px;
        border-radius: 5px;
        border: #acacac solid 2px;
        background-color: transparent;
        color: #eee;
        transition: 0.15s all ease;
    }

    .form-group input:focus {
        border-color: #eee;
    }

    .form-group input::placeholder {
        color: transparent;
    }

    .form-group .form-label {
        position: absolute;
        top: 9px;
        left: 10px;
        font-size: 18px;
        padding: 0 10px;
        color: #acacac;
        pointer-events: none;
        transition: 0.15s all ease;
    }

    .form-group input:focus + .form-label,
    .form-group input:not(:placeholder-shown) + .form-label {
        transform: translate(5px, -22px);
        background-color: #383838;
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
        <h1>Agregar Proveedor</h1>
        <form action="../modifyproviders.php" method="post" enctype="multipart/form-data">
         <section>   
            <div class="form-group">
                <input type="text" id="nombre" name="nombre" placeholder="nom" value="<?php echo htmlspecialchars($nombre); ?>" required>
                <label  class="form-label">Nombre del Proveedor:</label>
            </div>

            <div class="form-group">
                <input type="text" id="contacto" name="contacto" placeholder="cat" value="<?php echo htmlspecialchars($contacto); ?>" required>
                <label class="form-label">Contacto:</label>
            </div>

            <div class="form-group">
                <input type="text" id="telefono" name="telefono" placeholder="tel" value="<?php echo htmlspecialchars($telefono); ?>" required>
                <label class="form-label">Teléfono:</label>
            </div>

            <div class="form-group">
                <input type="text" id="direccion" name="direccion" placeholder="dier" value="<?php echo htmlspecialchars($direccion); ?>" required>
                <label  class="form-label">Dirección:</label>
            </div>
            <button type="submit" class="btn" data-front="Agregar Proveedor" data-back="Proveedor Agregado"></button>
            <br>
            <br>
            <button type="button" class="btn" data-front="regresar" data-back="regresando a inicio" onclick="window.location.href='../pr.php'"></button>
         </section>
            
            
        </form>
    </div>
</body>
</html>
