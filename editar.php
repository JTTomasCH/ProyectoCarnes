<?php
session_start();

include 'php/conexion_be.php';

if (isset($_GET['id'])) {
    $id_usuario = intval($_GET['id']);

    // Consulta para obtener los datos del usuario
    $consulta = "SELECT id, nombres, apellidos, correo, telefono, activo FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    
    // Vinculamos los resultados a variables
    $stmt->bind_result($id, $nombres, $apellidos, $correo, $telefono, $activo);

    if ($stmt->fetch()) {
        $usuario = [
            'id' => $id,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'correo' => $correo,
            'telefono' => $telefono,
            'activo' => $activo,
        ];
    } else {
        header("Location: admin.php?mensaje=Usuario no encontrado");
        exit();
    }

    $stmt->close();
} else {
    header("Location: admin.php?mensaje=ID no válido");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    echo "Nombres: $nombres, Apellidos: $apellidos, Correo: $correo, Teléfono: $telefono, ID: $id_usuario";

    // Actualizar el usuario en la base de datos
    $consulta_actualizar = "UPDATE usuarios SET nombres = ?, apellidos = ?, correo = ?, telefono = ? WHERE id = ?";
    $stmt_actualizar = $conexion->prepare($consulta_actualizar);
    $stmt_actualizar->bind_param("ssssi", $nombres, $apellidos, $correo, $telefono, $id_usuario);

    if ($stmt_actualizar->execute()) {
        header("Location: admin.php?mensaje=Usuario actualizado correctamente");
    } else {
        echo "Error al actualizar: " . $stmt_actualizar->error;
        exit();
    }
    
    $stmt_actualizar->close();
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Itim&family=Nerko+One&family=Pompiere&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Itim", cursive;
            font-weight: 400;
            font-style: normal;
            margin: 0; /* Eliminar márgenes por defecto */
            height: 100vh; /* Asegura que el cuerpo ocupe toda la altura de la ventana */
            display: flex; /* Usar flexbox para centrar */
            justify-content: center; /* Centrar horizontalmente */
            align-items: center; /* Centrar verticalmente */
            background-color: #f2f2f2; /* Color de fondo opcional */
        }

        .form-container {
            width: 300px;
            align-items: center;
            background: white; /* Color de fondo blanco para el formulario */
            padding: 20px; /* Espaciado interno */
            border-radius: 15px; /* Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"] {
            width: 280px;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 15px;
        }

        input[type="submit"] {
            background-color: #ff7474;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 15px;
            cursor: pointer;
            transition: background-color 0.3s; /* Transición para el hover */
        }

        input[type="submit"]:hover {
            background-color: #e60000; /* Cambia a un rojo más oscuro al pasar el mouse */
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>Editar Usuario</h2>
        <form action="" method="post">
            <label for="nombres">Nombres de la carniceria</label>
            <input type="text" id="nombres" name="nombres" value="<?php echo htmlspecialchars($usuario['nombres']); ?>" required>

            <label for="apellidos">Años de la carniceria</label>
            <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>" required>

            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>

            <label for="telefono">Teléfono</label>
            <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>

            <input type="submit" value="Actualizar Usuario">
        </form>
    </div>

</body>

</html>