<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargando...</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body { background-color: #292929; }

        section {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loader {
            width: 80px;
            height: 80px;
            border: transparent solid 3px;
            border-left-color: #fff;
            border-top-color: #fff;
            border-radius: 100%;
            position: relative;
            animation: loading 1.5s infinite linear;
        }

        .loader::before {
            position: absolute;
            content: '';
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: transparent solid 3px;
            border-left-color: #fff;
            border-top-color: #fff;
            border-radius: 100%;
            animation: loading 1.5s infinite linear reverse;
        }

        .loader::after {
            position: absolute;
            content: '';
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: transparent solid 3px;
            border-left-color: #fff;
            border-top-color: #fff;
            border-radius: 100%;
            animation: loading 1.5s infinite linear;
        }

        @keyframes loading {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = 'proveedores.php'; // Redirige después de 2 segundos
        }, 2000);
    </script>
</head>
<body>
    <section>
        <div class="loader"></div>
    </section>
</body>
</html>
