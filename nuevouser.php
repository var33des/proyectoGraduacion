<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styler.css">
    <title>Registro</title>
</head>
<body>
    <?php
    session_start(); // Iniciar la sesión

    // Mostrar el mensaje si existe
    if (isset($_SESSION['message'])) {
        echo "<p>" . $_SESSION['message'] . "</p>";
        // Limpiar el mensaje después de mostrarlo
        unset($_SESSION['message']);
    }
    ?>

    <section>
        <form action="registro.php" method="POST">
            <h1>Registro</h1>

            <div class="form-grid">
                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="number" name="codigo" id="codigo" required>
                    <label for="codigo">Código de Empleado</label>
                </div>

                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="text" name="nombre" id="nombre" required>
                    <label for="nombre">Nombre</label>
                </div>

                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="text" name="apellido" id="apellido" required>
                    <label for="apellido">Apellido</label>
                </div>

                <div class="inputbox">
                    <ion-icon name="mail-outline"></ion-icon>
                    <input type="text" name="usuario" id="usuario" required>
                    <label for="usuario">Correo</label>
                </div>

                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="clave" id="clave" required>
                    <label for="clave">Password</label>
                </div>

                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="confirmclave" id="confirmclave" required>
                    <label for="confirmclave">Confirm Password</label>
                </div>
                
                <label for="confirmclave">elegir rol</label>  
                <div class="role-checkboxes">
              
    
    <input type="radio" name="roles" id="rol_usuario" value="usuario" required>
    <label for="rol_usuario">Usuario</label>
</div>


                <button type="submit">Registrar</button>
            </div>

        </form>
    </section>
</body>
</html>
