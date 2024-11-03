<?php
// Conexión a la base de datos 'inventario'
$conexion = mysqli_connect("localhost", "root", "", "inventario");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Variables iniciales
$nombre = '';
$categoria = '';
$cantidad = '';
$precio = '';

// Manejar el formulario de inserción
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];

    // Insertar un nuevo producto
    $sqlInsert = $conexion->query("INSERT INTO productos (nombre, categoria, cantidad, precio) VALUES ('$nombre', '$categoria', '$cantidad', '$precio')");
    if ($sqlInsert) {
        $mensaje = "Producto insertado correctamente";
        $idProducto = $conexion->insert_id;

        // Manejo de la imagen
        if (!empty($_FILES['imagen']['tmp_name'])) {
            $tmpimagen = $_FILES['imagen']['tmp_name'];
            $urlnueva = 'static/image/products/' . $idProducto . '.jpg';

            if (is_uploaded_file($tmpimagen)) {
                if (move_uploaded_file($tmpimagen, $urlnueva)) {
                    $mensaje .= "<br>Imagen cargada con éxito";
                } else {
                    $mensaje .= "<br>Error al cargar la imagen";
                }
            } else {
                $mensaje .= "<br>No se subió ninguna imagen";
            }
        }
    } else {
        $mensaje = "Error al insertar el producto: " . $conexion->error;
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
    <title>Agregar Producto</title>
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
        <h1>Agregar Producto</h1>
        <form action="../modifyproducts.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                <label for="nombre" class="form-label">Nombre del Producto:</label>
            </div>

            <div class="form-group">
                <input type="text" id="categoria" name="categoria" value="<?php echo htmlspecialchars($categoria); ?>" required>
                <label for="" class="form-label">Categoría:</label>
            </div>

            <div class="form-group">
                <input type="number" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($cantidad); ?>" required>
                <label for="" class="form-label">Cantidad:</label>
            </div>

            <div class="form-group">
                <input type="text" id="precio" name="precio" value="<?php echo htmlspecialchars($precio); ?>" required>
                <label for="" class="form-label">Precio:</label>
            </div>

            <div class="form-group">
                <input type="file" id="imagen" name="imagen">
                <label for="imagen" class="form-label">Imagen del Producto (opcional):</label>
            </div>

            <button type="submit" class="btn" data-front="Agregar Producto" data-back="Producto Agregado"></button>
            <button type="button" class="btn" data-front="regresar" data-back="regresando a inicio"onclick="window.location.href='../pr.php'"></button>

        </form>
    </div>
</body>
</html>
