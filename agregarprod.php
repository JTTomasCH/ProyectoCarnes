<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Incluir la conexión a la base de datos
    include 'php/conexion_be.php';

    // Verificar si la conexión fue exitosa
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    
    // Manejo de la imagen
    $imagen = $_FILES['imagen']['tmp_name'];
    $imagen_nombre = $_FILES['imagen']['name'];
    $imagen_tipo = $_FILES['imagen']['type'];
    $imagen_contenido = file_get_contents($imagen);

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO productos (nombre, precio, imagen) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $nombre, $precio, $imagen_contenido);

    if ($stmt->execute()) {
        echo "<p>Producto agregado con éxito.</p>";
        // Redirigir a la página principal después de agregar el producto
        header("Location: admin.php");
        exit();
    } else {
        echo "<p>Error al agregar el producto: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: linear-gradient(to bottom, #fd3f34 ,#fdfa34);
            background-size: cover;
            background-position: center;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #ff4c4c;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 15px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 300px;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 15px;
            background-color:#fefefe ;
        }

        .btn-submit {
            background-color: #ff4c4c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #ffc539;
            color: black;
        }

        .current-image {
            max-width: 200px;
            margin: 10px 0;
        }

        .mensaje {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .btn-volver {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff4c4c;
            color: white;
            text-decoration: none;
            border-radius: 15px;
            margin-top: 20px;
        }

        .btn-volver:hover {
            background-color: #ffc539;
            color: black;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>Agregar Nuevo Producto</h2>
        <form action="agregarprod.php" method="POST" enctype="multipart/form-data">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="precio">Precio:</label>
            <input type="text" id="precio" name="precio" required>

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required>

            <button type="submit" class="btn-submit">Agregar Producto</button>
        </form>
    </div>

</body>

</html>