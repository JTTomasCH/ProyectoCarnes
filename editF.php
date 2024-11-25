<?php
include 'php/conexion_be.php';

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$mensaje = '';

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pedido = $_POST['id_pedido'];
    $id_metodoPago = $_POST['id_metodoPago'];
    $horario = $_POST['horario'];

    // Preparar la consulta SQL base
    $sql = "UPDATE OrdenPago SET id_pedido = ?, id_metodoPago = ?, horario = ?";
    $params = array($id_pedido, $id_metodoPago, $horario);
    $tipos = "iis"; // i para integer (id_pedido y id_metodoPago), s para string (horario)

    $sql .= " WHERE ID = ?";
    $params[] = $id;
    $tipos .= "i"; // i para integer (ID)

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param($tipos, ...$params);

    if ($stmt->execute()) {
        $mensaje = "Orden de pago actualizada correctamente";
    } else {
        $mensaje = "Error al actualizar la orden de pago: " . $conexion->error;
    }
    $stmt->close();
}

// Obtener datos actuales de la orden
$stmt = $conexion->prepare("SELECT id_pedido, id_metodoPago, horario FROM OrdenPago WHERE ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Vincula los resultados a variables
$stmt->bind_result($id_pedido, $id_metodoPago, $horario);

$orden = [];
if ($stmt->fetch()) {
    $orden = [
        'id_pedido' => $id_pedido,
        'id_metodoPago' => $id_metodoPago,
        'horario' => $horario,
    ];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Orden de Pago</title>
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
            background-image: linear-gradient(to bottom, #fd3f34, #fdfa34);
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
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="file"] {
            width: 250px;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 15px;
            background-color: #fefefe;
        }
        .current-image {
            max-width: 200px;
            margin: 10px 0;
        }
        .btn-submit {
            background-color: #ff4c4c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #ffc539;
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
            background-color: #ffc539;
            color: black;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Editar Orden de Pago</h2>

    <?php if ($mensaje): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Id Pedido:</label>
            <input type="text" name="id_pedido" value="<?php echo htmlspecialchars($orden['id_pedido']); ?>" required>
        </div>

        <div class="form-group">
            <label>Id Método de Pago:</label>
            <input type="text" name="id_metodoPago" value="<?php echo htmlspecialchars($orden['id_metodoPago']); ?>" required>
        </div>

        <div class="form-group">
            <label>Horario:</label>
            <input type="text" name="horario" value="<?php echo htmlspecialchars($orden['horario']); ?>" required>
        </div>

        <button type="submit" class="btn-submit">Guardar cambios</button>
    </form>

    <a href="admin.php" class="btn-volver">Volver a administrador</a>
</div>
</body>
</html>
