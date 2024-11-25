<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Incluir la conexión a la base de datos
    include 'php/conexion_be.php';

    // Verificar si la conexión fue exitosa
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Recibir los datos del formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO usuarios (nombres, apellidos, correo, telefono, contraseña, activo) VALUES (?, ?, ?, ?, ?, 1)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssss", $nombres, $apellidos, $correo, $telefono, $contraseña);

    if ($stmt->execute()) {
        echo "<p>Usuario agregado con éxito.</p>";
        // Redirigir a la página principal después de agregar el usuario
        header("Location: https://proyectocarnes.opticasolsj.com/admin.php");
        exit();
    } else {
        echo "<p>Error al agregar el usuario: " . $stmt->error . "</p>";
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
    <title>Agregar Usuario</title>
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
        input[type="email"],
        input[type="tel"],
        input[type="password"] {
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
        <h2>Agregar Nuevo Usuario</h2>
        <form action="agregar_usuario.php" method="POST">
            <label for="nombres">Nombres:</label>
            <input type="text" id="nombres" name="nombres" required>

            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" required>

            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" required>

            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>

            <button type="submit" class="btn-submit">Agregar Usuario</button>
        </form>
    </div>

</body>

</html>
