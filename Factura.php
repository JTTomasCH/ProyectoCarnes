<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ordenes</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 5px;
        }

        th {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            
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
            background-color: #4a7f4d;
        }

        .btn2 {
            display: inline-block;
            padding: 15px 15px;
            color: black;
            background-color: #37d9fe ;
            text-decoration: none;
            border-radius: 15px;
            transition: background-color 0.3s;
        }

        .btn1:hover {
            background-color: #4a7f4d;
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
    <div class="product-management">
        <h2>Gestión de Orden de Pago</h2>
        <br>
         <table>
             <thead>
                <tr>
                    <th>ID</th>
                    <th>Id_pedido</th>
                    <th>Id_metodoPago</th>
                    <th>Horario</th>
                    <th>Fecha</th>
                     <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                 <?php
                    include 'php/conexion_be.php';

                    if ($conexion->connect_error) {
                        die("Error de conexión: " . $conexion->connect_error);
                    }

                    // Consulta de productos
                    $consulta = "SELECT ID, id_pedido, id_metodoPago, horario, fecha FROM OrdenPago";
                    $resultado = $conexion->query($consulta);

                    if ($resultado->num_rows > 0) {
                        while ($producto = $resultado->fetch_assoc()) {
                            echo "<tr>
                                <td>{$producto['ID']}</td>
                                <td>{$producto['id_pedido']}</td>
                                <td>{$producto['id_metodoPago']}</td>
                                <td>{$producto['horario']}</td>
                                <td>{$producto['fecha']}</td>
                                <td>
                             <a href='editF.php?id={$producto['ID']}' class='btn1'>Editar</a>
                             <a href='eliminarF.php?id={$producto['ID']}' class='btn' onclick=\"return confirm('¿Estás seguro de eliminar este producto?');\">Eliminar</a>
                       </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay órdenes de pago</td></tr>";
                    }

                    // Cerrar conexión después de terminar el bucle
                    $conexion->close();
                ?>
            </tbody>
        </table>
   </div>
</body>

</html>
    