<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Procesos de Pedidos</title>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #007bff;
            color: #fff;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            color: black;
            background-color: #fe6d6d;
            text-decoration: none;
            border-radius: 15px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #c44d4d;
        }
    </style>
</head>

<body>

    <div class="process-management">
        <h2>Gestión de Procesos de Pedidos</h2>

        <!-- Tabla de Procesos -->
        <table>
            <thead>
                <tr>
                    <th>#Proceso</th>
                    <th>#Pedido</th>
                    <th>Estado</th>
                    <th>Fecha del Proceso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Incluir conexión a la base de datos
                include 'php/conexion_be.php';

                if ($conexion->connect_error) {
                    die("Error de conexión: " . $conexion->connect_error);
                }

                // Consulta para obtener los procesos junto con el ID del detalle de pedido y el nombre del producto
                $consulta = "
                    SELECT p.id AS id_proceso, p.id_pedido, dp.id AS Pedido, p.estado, p.fecha
                    FROM proceso p
                    LEFT JOIN DetallePedido dp ON p.id_pedido = dp.id_pedido
                    LEFT JOIN productos pr ON dp.id_producto = pr.id
                ";
                $resultado = $conexion->query($consulta);

                if ($resultado->num_rows > 0) {
                    while ($proceso = $resultado->fetch_assoc()) {
                        echo "<tr>
                            <td>{$proceso['id_proceso']}</td>
                            <td>" . ($proceso['Pedido'] ?? 'Sin ID') . "</td>
                            <td>{$proceso['estado']}</td>
                            <td>" . ($proceso['fecha'] ?? 'Sin fecha') . "</td>
                            <td>
                                <a href='eliminar_proceso.php?id={$proceso['id_proceso']}' class='btn' onclick=\"return confirm('¿Estás seguro de eliminar este usuario?');\">Eliminar</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay procesos registrados.</td></tr>";
                }

                $conexion->close();
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>