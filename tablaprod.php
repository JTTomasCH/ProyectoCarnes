<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
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
        <h2>Gestión de Productos</h2>
        <br>
        <?php
        if (isset($_GET['eliminar_id'])) {
            $eliminar_id = $_GET['eliminar_id'];

            include 'php/conexion_be.php';

            if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
            }
            $sqlEliminarProducto = "DELETE FROM productos WHERE id = ?";
            $stmt = $conexion->prepare($sqlEliminarProducto);
            $stmt->bind_param("i", $eliminar_id);
            $stmt->execute();
            $stmt->close();

            echo "<p>El producto ha sido eliminado.</p>";
        }
        ?>
        <table>
        <a href="agregarprod.php" class="btn2" onclick="return confirm('¿Deseas agregar un producto?');">AGREGAR UN NUEVO PRODUCTO</a>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Imagen</th>
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
                $consulta = "SELECT id, nombre, precio, imagen FROM productos";
                $resultado = $conexion->query($consulta);

                if ($resultado->num_rows > 0) {
                    while ($producto = $resultado->fetch_assoc()) {
                        echo "<tr>
                        <td>{$producto['id']}</td>
                        <td>{$producto['nombre']}</td>
                        <td>{$producto['precio']}</td>
                        <td><img src='data:image/jpeg;base64," . base64_encode($producto['imagen']) . "' alt='" . $producto['nombre'] . "'></td>
                        <td>
                             <a href='editarprod.php?id={$producto['id']}' class='btn1'>Editar</a>
                             <a href='eliminarprod.php?id={$producto['id']}' class='btn' onclick=\"return confirm('¿Estás seguro de eliminar este producto?');\">Eliminar</a>
                       </td>
                    </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay productos registrados.</td></tr>";
                }
                $conexion->close();
                ?>
            </tbody>
        </table>

    </div>

</body>

</html>