<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
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

        #btn {
            text-decoration: none;
            cursor: pointer;
            color: #fff;
            font-size: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 20px;
            transition: 0.3s all ease;
            position: relative;
            background-color: #e94b35;
            border-radius: 5px;
            margin-left: 20px;
        }

        #btn:hover {
            color: #292929;
            background-color: #00bd9c;
        }

        #btn::before {
            position: absolute;
            content: '';
            left: 0;
            z-index: -1;
            background-color: #fff;
            width: 38px;
            height: 38px;
            border-radius: 80px;
            transition: 0.3s all cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        #btn:hover::before {
            width: 100%;
        }

        #btn svg {
            width: 30px;
            height: 30px;
            margin-right: 12px;
        }

        #btn svg path {
            fill: #292929;
        }

        #delete-btn {
            text-decoration: none;
            cursor: pointer;
            color: #fff;
            font-size: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 20px;
            transition: 0.3s all ease;
            position: relative;
            background-color: #ff2a05;
            border-radius: 5px;
        }

        #delete-btn:hover {
            background-color: #e5e5e5;
            color: #292929;
        }

        #delete-btn svg {
            width: 30px;
            height: 30px;
            margin-right: 12px;
        }

        #delete-btn svg path {
            fill: #fff;
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
            transition: 0.3s top cubic-bezier(0.175, 0.885, 0.32, 1.275),
                        0.6s background-color linear 0.3s;
        }

        section#popAlert aside {
            width: 90%;
            max-width: 350px;
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 5px 0 rgba(0, 0, 0, 0.6);
        }

        section#popAlert aside h2 {
            font-size: 25px;
            color: #ee2b2b;
        }

        section#popAlert aside p {
            font-size: 16px;
            margin: 5px 0;
        }

        section#popAlert aside button[type=button] {
            width: 100px;
            font-size: 16px;
            font-weight: 600;
            margin-top: 15px;
            border: none;
            border-radius: 3px;
            padding: 5px 0;
            cursor: pointer;
            background-color: #e5e5e5;
            transition: 0.3s all ease-in-out;
        }

        section#popAlert aside button[type=button]:hover {
            background-color: #ee2b2b;
            color: #fff;
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
               placeholder="Buscar producto" style="color: #1F37BD">
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
                <th>Código producto</th>
                <th>Nombre producto</th>
                <th>Categoría</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Editar</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $SQL = "SELECT * FROM productos";
            $dato = mysqli_query($conexion, $SQL);

            if ($dato->num_rows > 0) {
                while ($fetch = mysqli_fetch_assoc($dato)) {
                    ?>
                    <tr>
                        <td><input type="checkbox" name="check[]" value="<?php echo $fetch['idProducto']; ?>"></td>
                        <td><a class="link" href="modifyproducts.php?c=<?php echo $fetch['idProducto']; ?>"><?php echo $fetch['idProducto']; ?></a></td>
                        <td><?php echo $fetch['nombre']; ?></td>
                        <td><?php echo $fetch['categoria']; ?></td>
                        <td><?php echo $fetch['cantidad']; ?></td>
                        <td><?php echo $fetch['precio']; ?></td>
                        <td><a href="modifyproducts.php?c=<?php echo $fetch['idProducto']; ?>">Editar</a></td>
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
    <a href="agregar/addproducts.php" id="btn">
        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><g data-name="Layer 2" id="Layer_2"><path d="M19,26a1,1,0,0,1-.71-.29,1,1,0,0,1,0-1.42L26.59,16l-8.3-8.29a1,1,0,0,1,1.42-1.42l9,9a1,1,0,0,1,0,1.42l-9,9A1,1,0,0,1,19,26Z"/><path d="M28,17H4a1,1,0,0,1,0-2H28a1,1,0,0,1,0,2Z"/></g><g id="frame"><rect fill="none" height="32" width="32"/></g></svg>
        Añadir Producto
    </a>
    <a href="bienvenida.php" id="btn">
        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><g data-name="Layer 2" id="Layer_2"><path d="M19,26a1,1,0,0,1-.71-.29,1,1,0,0,1,0-1.42L26.59,16l-8.3-8.29a1,1,0,0,1,1.42-1.42l9,9a1,1,0,0,1,0,1.42l-9,9A1,1,0,0,1,19,26Z"/><path d="M28,17H4a1,1,0,0,1,0-2H28a1,1,0,0,1,0,2Z"/></g><g id="frame"><rect fill="none" height="32" width="32"/></g></svg>
        Home
    </a>
</div>
<section id="popAlert">
    <aside>
        <h2>Confirmación de eliminación</h2>
        <p>¿Está seguro de que desea eliminar los productos seleccionados?</p>
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