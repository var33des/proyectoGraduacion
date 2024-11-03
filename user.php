<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="stilopr.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Nunito', sans-serif;
        }

        body { background-color: #292929; }

        section {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100vh;
        }

        .table-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-container .table {
            width: 100%;
        }

        #btn, #delete-btn {
            text-decoration: none;
            cursor: pointer;
            color: #fff;
            font-size: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 20px;
            transition: 0.3s all ease;
            background-color: #e94b35;
            border-radius: 5px;
            margin-left: 20px;
        }

        #btn:hover, #delete-btn:hover {
            color: #292929;
            background-color: #00bd9c;
        }

        section#popAlert {
            position: fixed;
            top: -100%;
            left: 0;
            right: 0;
            height: 100vh;
            z-index: 20;
            background-color: rgba(255,255,255,0.0);
            transition: 0.6s top cubic-bezier(0.175, 0.885, 0.32, 1.275),
                        0.1s background-color linear 0s;
        }

        section#popAlert.active {
            top: 0;
            background-color: rgba(255,255,255,0.3);
        }

        section#popAlert aside {
            width: 90%;
            max-width: 350px;
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
        }

        section#popAlert aside h2 {
            font-size: 25px;
            color: #ee2b2b;
        }

        section#popAlert aside p {
            font-size: 16px;
        }
    </style>

    <script>
        function togglePop() {
            let popup = document.getElementById('popAlert');
            popup.classList.toggle('active');
        }

        function confirmDeletion() {
            let popup = document.getElementById('popAlert');
            popup.classList.add('active');
        }

        function handleDeletion() {
            document.getElementById('deleteForm').submit();
        }
    </script>
</head>
<body>

<?php
$conexion = mysqli_connect("localhost", "root", "", "inventario");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
<div class="container-fluid">
    <form class="d-flex">
        <input class="form-control me-2 light-table-filter" data-table="table_id" type="search"
               placeholder="Buscar usuario" style="color: #1F37BD">
        <hr style="margin-top: 30px;">
    </form>
    <br>
</div>
<div class="table-container">
    <form method="POST" id="deleteForm" action="borrar.php">
        <table class="table table-striped table-dark table_id">
            <thead>
            <tr>
                <th><input type="checkbox" onclick="selectedCheckbox(this)"> Todos</th>
                <th>ID Usuario</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Correo Electrónico</th>
                <th>Rol</th>
                <th>Editar</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $SQL = "SELECT * FROM usuarios";
            $dato = mysqli_query($conexion, $SQL);

            if ($dato->num_rows > 0) {
                while ($fetch = mysqli_fetch_assoc($dato)) {
                    ?>
                    <tr>
                        <td><input type="checkbox" name="check[]" value="<?php echo $fetch['idUsuario']; ?>"></td>
                        <td><a class="link" href="modifyusers.php?c=<?php echo $fetch['idUsuario']; ?>"><?php echo $fetch['idUsuario']; ?></a></td>
                        <td><?php echo $fetch['nombre']; ?></td>
                        <td><?php echo $fetch['apellido']; ?></td>
                        <td><?php echo $fetch['correo_electronico']; ?></td>
                        <td><?php echo $fetch['rol']; ?></td>
                        <td><a href="modifyusers.php?c=<?php echo $fetch['idUsuario']; ?>">Editar</a></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr class="text-center">
                    <td colspan="7">No existen registros</td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
    
    <a href="#" id="delete-btn" onclick="confirmDeletion()">
        Borrar Seleccionado
    </a>
    <a href="agregar/addusers.php" id="btn">
        Añadir Usuario
    </a>
    <a href="bienvenida.php" id="btn">
        Home
    </a>
</div>

<section id="popAlert">
    <aside>
        <h2>Confirmación de eliminación</h2>
        <p>¿Está seguro de que desea eliminar los usuarios seleccionados?</p>
        <button type="button" onclick="handleDeletion()">Sí, eliminar</button>
        <button type="button" onclick="togglePop()">Cancelar</button>
    </aside>
</section>

<script src="js/buscador.js"></script>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/script.js"></script>

</body>
</html>
