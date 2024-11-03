<?php
// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "inventario");

// Verificación de conexión
if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Consultar el número total de productos
$query_total_productos = "SELECT COUNT(*) AS total FROM productos";
$resultado_total_productos = mysqli_query($conexion, $query_total_productos);
$total_productos = mysqli_fetch_assoc($resultado_total_productos)['total'];

// Consultar productos con bajo inventario (cantidad menor o igual a 10)
$query_bajo_inventario = "SELECT nombre, cantidad FROM productos WHERE cantidad <= 10";
$resultado_bajo_inventario = mysqli_query($conexion, $query_bajo_inventario);
$productos_bajo_inventario = mysqli_fetch_all($resultado_bajo_inventario, MYSQLI_ASSOC);

// Consultar productos con inventario agotado (cantidad igual a 0)
$query_agotado = "SELECT nombre FROM productos WHERE cantidad = 0";
$resultado_agotado = mysqli_query($conexion, $query_agotado);
$productos_agotado = mysqli_fetch_all($resultado_agotado, MYSQLI_ASSOC);

// Consultar valor total del inventario
$query_valor_inventario = "SELECT SUM(cantidad * precio) AS valor_total FROM productos";
$resultado_valor_inventario = mysqli_query($conexion, $query_valor_inventario);
$valor_inventario = mysqli_fetch_assoc($resultado_valor_inventario)['valor_total'];

// Consultar cantidad total de productos por categoría
$query_categorias = "SELECT categoria, SUM(cantidad) AS cantidad_total FROM productos GROUP BY categoria";
$resultado_categorias = mysqli_query($conexion, $query_categorias);
$categorias = mysqli_fetch_all($resultado_categorias, MYSQLI_ASSOC);

// Consultar movimientos recientes (últimos 5 movimientos)
$query_movimientos_recentes = "
    SELECT 
        p.nombre AS producto, 
        mi.tipo, 
        mi.cantidad, 
        mi.fecha 
    FROM 
        movimiento_inventario mi
    JOIN 
        productos p ON mi.idProducto = p.idProducto
    ORDER BY 
        mi.fecha DESC 
    LIMIT 5
";

$resultado_movimientos_recentes = mysqli_query($conexion, $query_movimientos_recentes);
$movimientos_recentes = mysqli_fetch_all($resultado_movimientos_recentes, MYSQLI_ASSOC);


$query_tendencia_ventas = "
    SELECT 
        YEAR(fecha) AS anio, 
        MONTH(fecha) AS mes, 
        SUM(CASE WHEN tipo = 'salida' THEN cantidad ELSE 0 END) AS total_vendido
    FROM 
        movimiento_inventario
    GROUP BY 
        anio, mes
    ORDER BY 
        anio DESC, mes DESC
";

$resultado_tendencia_ventas = mysqli_query($conexion, $query_tendencia_ventas);
$tendencia_ventas = mysqli_fetch_all($resultado_tendencia_ventas, MYSQLI_ASSOC);

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Inventario</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .titulo {
            position: relative;
            left: 300px;
            max-width: 800px;
        }
        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .dashboard h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 40px;
            color: #4a4a4a;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            grid-gap: 20px;
        }
        .card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .profile-button {
            position: absolute;
            right: 20px;
            top: 20px;
            background: #4a4a4a;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
        }
        .dropdown {
            display: none;
            position: absolute;
            right: 20px;
            top: 60px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .dropdown a {
            display: block;
            padding: 10px 15px;
            color: #4a4a4a;
            text-decoration: none;
        }
        .dropdown a:hover {
            background-color: #f1f1f1;
        }
        .chart-container {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenido al Dashboard de Inventario</h1>
        <button class="profile-button" onclick="toggleDropdown()">Perfil</button>
        <div class="dropdown" id="profileDropdown">
            <a href="mi_perfil.php">Mi Información</a>
            <a href="index.php">Salir</a>
        </div>
    </header>

    <div class="container">
        <nav class="sidebar">
            <ul>
                <li><a href="bienvenida.php">Home</a></li> <!-- Redirigir a la página pr.php -->
                <li><a href="#" onclick="mostrarSeccion('resumen')">Resumen General</a></li>
                <li><a href="#" onclick="mostrarSeccion('alertas')">Alertas de Inventario</a></li>
                <li><a href="#" onclick="mostrarSeccion('categorias')">Distribución por Categoría</a></li>
                <li><a href="#" onclick="mostrarSeccion('movimientos')">Movimientos Recientes</a></li>
                <li><a href="pr.php">Gestión de Productos</a></li> <!-- Redirigir a la página pr.php -->
                <li><a href="proveedores.php">Gestión de Proveedores</a></li> <!-- Redirigir a la página pr.php -->
                <li><a href="user.php">Gestión de usuarios</a></li> <!-- Redirigir a la página pr.php -->
            </ul>
        </nav>

        <main class="content">
            <div id="grafica" class="seccion">
                <div class="dashboard">
                    <h1>Dashboard de Control de Inventario</h1>
                    <div class="grid-container">
                        <div class="card">
                            <h2>Productos más Vendidos</h2>
                            <canvas id="productosMasVendidosChart"></canvas>
                        </div>

                        <div class="card">
                            <h2>Productos con Inventario Bajo</h2>
                            <canvas id="inventarioBajoChart"></canvas>
                        </div>

                        <div class="card">
                            <h2>Distribución por Categorías</h2>
                            <canvas id="distribucionCategoriasChart"></canvas>
                        </div>

                        <div class="card">
                            <h2>Tendencia de Ventas Mensuales</h2>
                            <canvas id="tendenciaVentasMensualesChart"></canvas>
                        </div>

                        <div class="card">
                            <h2>Movimientos Recientes</h2>
                            <canvas id="movimientosRecientesChart"></canvas>
                        </div>
                    </div>
                </div>

                <script>
                     function toggleDropdown() {
                        const dropdown = document.getElementById('profileDropdown');
                        dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
                    }
                    // Datos para las gráficas
                    const productosBajoInventario = <?php echo json_encode(array_column($productos_bajo_inventario, 'nombre')); ?>;
                    const cantidadBajoInventario = <?php echo json_encode(array_column($productos_bajo_inventario, 'cantidad')); ?>;
                    const categorias = <?php echo json_encode(array_column($categorias, 'categoria')); ?>;
                    const cantidadPorCategoria = <?php echo json_encode(array_column($categorias, 'cantidad_total')); ?>;

                    // Gráfico de Productos más Vendidos
                    const ctxProductosVendidos = document.getElementById('productosMasVendidosChart').getContext('2d');
                    new Chart(ctxProductosVendidos, {
                        type: 'bar',
                        data: {
                            labels: productosBajoInventario,
                            datasets: [{
                                label: 'Cantidad Vendida',
                                data: cantidadBajoInventario,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    // Gráfico de Productos con Inventario Bajo
                    const ctxInventarioBajo = document.getElementById('inventarioBajoChart').getContext('2d');
                    new Chart(ctxInventarioBajo, {
                        type: 'pie',
                        data: {
                            labels: productosBajoInventario,
                            datasets: [{
                                label: 'Cantidad en Inventario',
                                data: cantidadBajoInventario,
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true
                        }
                    });

                    // Gráfico de Distribución por Categorías
                    const ctxDistribucionCategorias = document.getElementById('distribucionCategoriasChart').getContext('2d');
                    new Chart(ctxDistribucionCategorias, {
                        type: 'doughnut',
                        data: {
                            labels: categorias,
                            datasets: [{
                                label: 'Total por Categoría',
                                data: cantidadPorCategoria,
                                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true
                        }
                    });

                    // Gráfico de Movimientos Recientes
                    const ctxMovimientosRecientes = document.getElementById('movimientosRecientesChart').getContext('2d');
                    const movimientosRecientes = <?php echo json_encode($movimientos_recentes); ?>;
                    const productosMovimientos = movimientosRecientes.map(m => m.tipo);
                    const cantidadesMovimientos = movimientosRecientes.map(m => m.cantidad);
                    
                    new Chart(ctxMovimientosRecientes, {
                        type: 'bar',
                        data: {
                            labels: productosMovimientos,
                            datasets: [{
                                label: 'Movimientos Recientes',
                                data: cantidadesMovimientos,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    
                </script>
            </div>

            <!-- Resumen General -->
            <div id="resumen" class="seccion" style="display:none;">
                <h2>Resumen General</h2>
                <div class="stat-card">
                    <h3>Total de productos</h3>
                    <p><?php echo $total_productos; ?></p>
                </div>

                <div class="stat-card">
                    <h3>Valor total del inventario</h3>
                    <p><?php echo number_format($valor_inventario, 2, ',', '.'); ?> €</p>
                </div>
            </div>

            <!-- Alertas de Inventario -->
            <div id="alertas" class="seccion" style="display:none;">
                <h2>Alertas de Inventario</h2>
                <h3>Productos con bajo inventario</h3>
                <ul>
                    <?php foreach ($productos_bajo_inventario as $producto): ?>
                        <li><?php echo $producto['nombre']; ?>: <?php echo $producto['cantidad']; ?></li>
                    <?php endforeach; ?>
                </ul>

                <h3>Productos agotados</h3>
                <ul>
                    <?php foreach ($productos_agotado as $producto): ?>
                        <li><?php echo $producto['nombre']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Distribución por Categoría -->
            <div id="categorias" class="seccion" style="display:none;">
                <h2>Distribución por Categoría</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Cantidad Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr>
                                <td><?php echo $categoria['categoria']; ?></td>
                                <td><?php echo $categoria['cantidad_total']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Movimientos Recientes -->
            <div id="movimientos" class="seccion" style="display:none;">
    <h2>Movimientos Recientes</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimientos_recentes as $movimiento): ?>
                <tr>
                    <td><?php echo $movimiento['producto']; ?></td>
                    <td><?php echo $movimiento['tipo']; ?></td>
                    <td><?php echo $movimiento['cantidad']; ?></td>
                    <td><?php echo $movimiento['fecha']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

        </main>
    </div>

    <script>
        function mostrarSeccion(seccion) {
            const secciones = document.querySelectorAll('.seccion');
            secciones.forEach(s => s.style.display = 'none');
            document.getElementById(seccion).style.display = 'block';
        }
    </script>
</body>
</html>
