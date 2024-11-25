<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Detalles de Pedidos</title>
    
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

        /* Estilo de los botones */
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

        .btn1 {
            display: inline-block;
            padding: 8px 12px;
            color: black;
            background-color: #7fd163;
            text-decoration: none;
            border-radius: 15px;
            transition: background-color 0.3s;
        }

        .btn1:hover {
            background-color: #c44d4d;
        }

        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    <div class="order-management">
        <h2>Gestión de Detalles de Pedidos</h2>

        <!-- Verificar si se ha solicitado la eliminación de un pedido -->
        <?php
        if (isset($_GET['eliminar_id'])) {
            $eliminar_id = $_GET['eliminar_id'];

            include 'php/conexion_be.php';

            if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
            }

            // Eliminar los detalles del pedido asociados al id_pedido
            $sqlEliminarDetalles = "DELETE FROM DetallePedido WHERE id_pedido = ?";
            $stmt = $conexion->prepare($sqlEliminarDetalles);
            $stmt->bind_param("i", $eliminar_id);
            $stmt->execute();
            $stmt->close();

            // Eliminar el pedido
            $sqlEliminarPedido = "DELETE FROM Pedido WHERE id = ?";
            $stmtPedido = $conexion->prepare($sqlEliminarPedido);
            $stmtPedido->bind_param("i", $eliminar_id);
            $stmtPedido->execute();
            $stmtPedido->close();

            echo "<p>El pedido y sus detalles han sido eliminados.</p>";
        }
        ?>

        <!-- Tabla de Detalles de Pedido -->
        <table>
            <thead>
                <tr>
                    <th>#Pedido</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Fecha del Pedido</th>
                    <th>Producto</th>
                    <th>Imagen</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'php/conexion_be.php';

                if ($conexion->connect_error) {
                    die("Error de conexión: " . $conexion->connect_error);
                }

                // Consulta de detalles de pedido
                $consulta = "
                    SELECT dp.id, dp.cantidad, dp.total, p.fecha, pr.nombre AS producto_nombre, pr.imagen,
                           u.nombres AS usuario_nombre, u.correo AS usuario_correo, u.telefono AS usuario_telefono, u.direccion AS usuario_direccion,
                           p.id AS id_pedido
                    FROM DetallePedido dp
                    LEFT JOIN Pedido p ON dp.id_pedido = p.id
                    LEFT JOIN productos pr ON dp.id_producto = pr.id
                    LEFT JOIN usuarios u ON dp.id_usuarios = u.id
                ";
                $resultado = $conexion->query($consulta);

                if ($resultado->num_rows > 0) {
                    while ($detalle = $resultado->fetch_assoc()) {
                        echo "<tr>
                        <td>{$detalle['id']}</td>
                        <td>{$detalle['cantidad']}</td>
                        <td>{$detalle['total']}</td>
                        <td>" . ($detalle['fecha'] ?? 'Sin fecha') . "</td>
                        <td>" . ($detalle['producto_nombre'] ?? 'Sin producto') . "</td>
                        <td><img src='data:image/jpeg;base64," . base64_encode($detalle['imagen'] ?? '') . "' alt='" . ($detalle['producto_nombre'] ?? 'Imagen no disponible') . "'></td>
                        <td>" . ($detalle['usuario_nombre'] ?? 'Sin nombre') . "</td>
                        <td>" . ($detalle['usuario_correo'] ?? 'Sin correo') . "</td>
                        <td>" . ($detalle['usuario_telefono'] ?? 'Sin teléfono') . "</td>
                        <td>" . ($detalle['usuario_direccion'] ?? 'Sin dirección') . "</td>
                        <td>
                             <a href='eliminar2.php?id={$detalle['id']}' class='btn' onclick=\"return confirm('¿Estás seguro de eliminar este usuario?');\">Eliminar</a>
                        </td>
                    </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No hay detalles de pedidos registrados.</td></tr>";
                }

                $conexion->close();
                ?>
            </tbody>
        </table>

    </div>

</body>

</html>
