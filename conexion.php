<?php
// Configuración de la conexión
$servername = "localhost";
$username = "root";
$password = "";
$database = "carnes";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta para obtener productos
$sql = "SELECT id, nombre, precio, imagen FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
</head>
<body>
    <h1>Lista de Productos</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Imagen</th>
        </tr>

        <?php
        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            // Recorrer y mostrar los datos de cada producto
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nombre']}</td>
                        <td>{$row['precio']}</td>
                        <td><img src='data:images/jpeg;base64," . base64_encode($row['imagen']) . "' alt='{$row['nombre']}' width='100'></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay productos disponibles</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>