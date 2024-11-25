<?php
include 'php/conexion_be.php';

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$mensaje = '';

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_precio = $_POST['precio'];
    
    // Preparar la consulta SQL base
    $sql = "UPDATE productos SET precio = ?";
    $params = array($nuevo_precio);
    $tipos = "d"; // d para double (precio)
    
    // Si se subió una nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['size'] > 0) {
        $imagen_temporal = $_FILES['imagen']['tmp_name'];
        $imagen_contenido = file_get_contents($imagen_temporal);
        $sql .= ", imagen = ?";
        $params[] = $imagen_contenido;
        $tipos .= "s"; // s para string (imagen blob)
    }
    
    $sql .= " WHERE id = ?";
    $params[] = $id;
    $tipos .= "i"; // i para integer (id)
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param($tipos, ...$params);
    
    if ($stmt->execute()) {
        $mensaje = "Producto actualizado correctamente";
    } else {
        $mensaje = "Error al actualizar el producto: " . $conexion->error;
    }
    $stmt->close();
}

// Obtener datos actuales del producto
$stmt = $conexion->prepare("SELECT nombre, precio, imagen FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Vincula los resultados a variables
$stmt->bind_result($nombre, $precio, $imagen);

$producto = [];
if ($stmt->fetch()) {
    $producto = [
        'nombre' => $nombre,
        'precio' => $precio,
        'imagen' => $imagen,
    ];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
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
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        .form-group {
            margin-bottom:30px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="number"],
        input[type="file"] {
            width: 250px;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 15px;
            background-color:#fefefe ;
        }
        .current-image {
            max-width: 200px;
            margin: 10px 0;
        }
        .btn-submit {
            background-color: #ff4c4c ;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #ffc539 ;
            color: black;
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
            background-color: #ffc539 ;
            color: black;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Editar Producto: <?php echo htmlspecialchars($producto['nombre']); ?></h2>
    
    <?php if ($mensaje): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Precio actual:</label>
            <input type="number" name="precio" value="<?php echo $producto['precio']; ?>" required>
        </div>

        <div class="form-group">
            <label>Imagen actual:</label>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($producto['imagen']); ?>" 
                 alt="Imagen actual" class="current-image">
            <label>Cambiar imagen (opcional):</label>
            <input type="file" name="imagen" accept="image/*">
        </div>

        <button type="submit" class="btn-submit">Guardar cambios</button>
    </form>

    <a href="admin.php" class="btn-volver">Volver a administrador</a>
    </div>
</body>
</html>