<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'php/conexion_be.php';  // Verifica que este archivo esté correctamente configurado

// Verificar conexión a la base de datos
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verificar si se ha seleccionado una fecha
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d'); // Por defecto, la fecha actual

// Consulta SQL corregida para incluir las relaciones correctas
$query = "SELECT 
            dp.id_producto,
            p.nombre as nombre_producto, 
            COUNT(*) as frecuencia,
            SUM(dp.cantidad) as total_vendido
          FROM DetallePedido dp
          INNER JOIN productos p ON dp.id_producto = p.ID
          INNER JOIN Pedido ped ON dp.id_pedido = ped.ID
          INNER JOIN OrdenPago op ON ped.ID = op.id_pedido
          WHERE DATE(op.fecha) = '$fecha'  -- Filtramos por la fecha en la tabla OrdenPago
          GROUP BY dp.id_producto, p.nombre 
          ORDER BY frecuencia DESC";

$resultado = mysqli_query($conexion, $query);

// Preparar los datos para el gráfico
$datos = array();
while ($row = mysqli_fetch_assoc($resultado)) {
    $datos[] = $row;
}

// Convertir a JSON para usar en JavaScript
$datosJSON = json_encode($datos);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Análisis de Ventas por Producto</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #000000;
            color: #ffffff;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: 20px auto;
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin-bottom: 40px;
        }

        .table-container {
            margin-top: 30px;
        }

        table {
            width: 500px;
            border-collapse: collapse;
            margin-top: 50px;
            color: #000;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #03b36d;
            text-align: left;
        }

        th {
            background-color: #ffffff;
            color: #000;
        }

        tr {
            background-color: #f5f5f5;
        }
        .logout-btn {
            display: inline-block;
            background-color: white;
            color: black;
            padding: 10px 20px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            border: 2px solid #white;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #fff;
            color: #03b36d;
            border-color: #03b36d;
        }

        .logout-btn-container {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Análisis de Ventas por Producto</h2>
        
        <!-- Formulario con selección de fecha -->
        <form method="POST" id="filterForm">
            <label for="fecha">Selecciona una fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>" max="<?php echo date('Y-m-d'); ?>">
            <button type="submit">Filtrar</button>
        </form>
        
        <div class="chart-container">
            <canvas id="quantityChart"></canvas>
        </div>

        <div class="table-container">
            <h3>Resumen Detallado</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Total Unidades Vendidas</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                </tbody>
            </table>
        </div>
        <!-- Botón de cerrar sesión -->
        <div class="logout-btn-container">
            <a href="cerarSesion.php" class="logout-btn">Cerrar Sesión</a>
        </div>
    
    </div>

    <script>
        // Obtener los datos de PHP
        const datos = <?php echo $datosJSON; ?>;

        // Preparar datos para los gráficos
        const productNames = datos.map(item => item.nombre_producto);
        const frequencies = datos.map(item => parseInt(item.frecuencia));
        const quantities = datos.map(item => parseInt(item.total_vendido));

        new Chart(document.getElementById('quantityChart'), {
            type: 'bar',
            data: {
                labels: productNames,
                datasets: [{
                    label: 'Cantidad Total Vendida',
                    data: quantities,
                    backgroundColor: 'rgba(75, 192, 192, 0.8)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#ffffff',
                        },
                        ticks: {
                            color: '#ffffff'
                        },
                        title: {
                            display: true,
                            text: 'Cantidad Total',
                            color: '#ffffff'
                        }
                    },
                    x: {
                        grid: {
                            color: '#ffffff',
                        },
                        ticks: {
                            color: '#ffffff'
                        },
                        title: {
                            display: true,
                            text: 'Productos',
                            color: '#ffffff'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Cantidad Total Vendida por Producto',
                        color: '#ffffff'
                    },
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    }
                }
            }
        });

        // Llenar la tabla de resumen
        const tableBody = document.getElementById('tableBody');
        datos.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.nombre_producto}</td>
                <td>${item.total_vendido}</td>
            `;
            tableBody.appendChild(row);
        });
    </script>
</body>

</html>
