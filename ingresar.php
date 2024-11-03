<?php
session_start(); // Iniciar la sesión

// Validamos datos del servidor
$user = "root";
$pass = "";
$host = "localhost";
$datab = "inventario";

// Conectamos a la base de datos
$connection = mysqli_connect($host, $user, $pass, $datab);

// Verificamos la conexión a la base de datos
if (!$connection) {
    $_SESSION['message'] = "No se ha podido conectar con el servidor: " . mysqli_connect_error();
    header("Location: index.php");
    exit();
}

// Escapamos los valores de los inputs del formulario para evitar inyecciones SQL
$correo_electronico = mysqli_real_escape_string($connection, $_POST['usuario']);
$clave = mysqli_real_escape_string($connection, $_POST['clave']);

// Consulta para obtener el usuario
$instruccion_sql = "SELECT * FROM usuarios WHERE correo_electronico = '$correo_electronico'";

$resultado = mysqli_query($connection, $instruccion_sql);

// Verificamos si la consulta fue exitosa
if (!$resultado) {
    $_SESSION['message'] = "Error al consultar la base de datos: " . mysqli_error($connection);
    header("Location: index.php");
    exit();
}

// Verificamos si se encontró un usuario con el correo electrónico proporcionado
if (mysqli_num_rows($resultado) == 1) {
    $usuario_data = mysqli_fetch_assoc($resultado);
    // Encriptamos la clave introducida para compararla con la almacenada
    $clave_encriptada = openssl_encrypt($clave, 'aes-256-cbc', 'tu_clave_secreta', 0, '1234567890123456');
    
    if ($clave_encriptada === $usuario_data['clave']) {
        // Guardamos la información del usuario en la sesión
        $_SESSION['usuario'] = $usuario_data['nombre'];
        $_SESSION['rol'] = $usuario_data['rol'];
        
        // Redirigimos según el rol del usuario
        if ($usuario_data['rol'] === 'admin') {
            $_SESSION['message'] = "Inicio de sesión exitoso como Admin.";
            header("Location: bienvenida.php");
        } else {
            $_SESSION['message'] = "Inicio de sesión exitoso como Usuario.";
            header("Location: bienvenidau.php");
        }
        exit();
    } else {
        $_SESSION['message'] = "Usuario o contraseña incorrectos.";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Usuario o contraseña incorrectos.";
    header("Location: index.php");
    exit();
}

// Cerramos la conexión
mysqli_close($connection);
?>