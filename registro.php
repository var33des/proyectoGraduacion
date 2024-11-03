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
    header("Location: nuevouser.php");
    exit();
}

// Escapamos los valores de los inputs del formulario para evitar inyecciones SQL
$nombre = mysqli_real_escape_string($connection, $_POST['nombre']);
$apellido = mysqli_real_escape_string($connection, $_POST['apellido']);
$correo_electronico = mysqli_real_escape_string($connection, $_POST['usuario']);
$clave = mysqli_real_escape_string($connection, $_POST['clave']);
$confirmclave = mysqli_real_escape_string($connection, $_POST['confirmclave']);
$idusuario = mysqli_real_escape_string($connection, $_POST['codigo']);
$rol = mysqli_real_escape_string($connection, $_POST['roles']);

// Validar que la contraseña y la confirmación coincidan
if ($clave !== $confirmclave) {
    $_SESSION['message'] = "Las contraseñas no coinciden.";
    header("Location: nuevouser.php");
    exit();
}

// Encriptamos la clave antes de insertarla en la base de datos
$clave_encriptada = mysqli_real_escape_string($connection, openssl_encrypt($clave, 'aes-256-cbc', 'tu_clave_secreta', 0, '1234567890123456'));

// Insertamos los datos en la tabla
$instruccion_sql = "INSERT INTO usuarios (idusuario, nombre, apellido, correo_electronico, clave, rol)
                    VALUES ('$idusuario', '$nombre', '$apellido', '$correo_electronico', '$clave_encriptada', '$rol')";

$resultado = mysqli_query($connection, $instruccion_sql);

// Verificamos si la consulta fue exitosa
if (!$resultado) {
    $_SESSION['message'] = "Error al insertar los datos: " . mysqli_error($connection);
} else {
    $_SESSION['message'] = "Datos insertados correctamente.";
}

// Cerramos la conexión
mysqli_close($connection);

// Redirigimos al formulario
header("Location: nuevouser.php");
exit();
?>
