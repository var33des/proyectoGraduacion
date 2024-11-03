<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styler.css">
    <title>Login</title>
    <style>
        .message {
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            text-align: center;
        }
        .message.error {
            background-color: #ffdddd;
            color: #d8000c;
            border: 1px solid #d8000c;
        }
        .message.success {
            background-color: #ddffdd;
            color: #4caf50;
            border: 1px solid #4caf50;
        }
    </style>
</head>
<body>
<section>
    <form action="ingresar.php" method="POST">
        <h1>Login</h1>
        
        <?php
        session_start(); // Iniciar la sesión
        
        // Mostrar el mensaje si existe
        if (isset($_SESSION['message'])) {
            $messageClass = strpos($_SESSION['message'], 'incorrectos') !== false ? 'error' : 'success';
            echo "<div class='message $messageClass'>" . $_SESSION['message'] . "</div>";
            // Limpiar el mensaje después de mostrarlo
            unset($_SESSION['message']);
        }
        ?>

        <div class="inputbox">
            <ion-icon name="mail-outline"></ion-icon>
            <input type="email" name="usuario" id="usuario" required>
            <label for="usuario">Email</label>
        </div>
        <div class="inputbox">
            <ion-icon name="lock-closed-outline"></ion-icon>
            <input type="password" name="clave" id="clave" required>
            <label for="clave">Password</label>
        </div>
        <div class="message">
            <!-- Aquí es donde se mostrará el mensaje -->
        </div>
        <div class="forget">
            <label for=""><input type="checkbox"> Remember Me</label>
            <a href="#">Forget Password</a>
        </div>
        <button type="submit">Log in</button>
        <div class="register">
            <p>Don't have an account? <a href="nuevouser.php">Register</a></p>
        </div>
    </form>
</section>
</body>
</html>

