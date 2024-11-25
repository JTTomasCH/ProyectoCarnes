<?php
session_start();
include 'php/conexion_be.php';

if (isset($_GET['id'])) {
    $id_usuario = intval($_GET['id']);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nueva_contrasena = $_POST['nueva_contrasena'];
        
        // Asegúrate de validar la nueva contraseña aquí
        if (strlen($nueva_contrasena) < 8) {
            echo "La contraseña debe tener al menos 8 caracteres.";
            exit();
        }

        // Encriptar la contraseña
        $nueva_contrasena = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $consulta_actualizar = "UPDATE usuarios SET contraseña = ? WHERE id = ?";
        $stmt = $conexion->prepare($consulta_actualizar);
        $stmt->bind_param("si", $nueva_contrasena, $id_usuario);

        if ($stmt->execute()) {
            header("Location: admin.php?mensaje=Contraseña actualizada correctamente");
        } else {
            echo "Error al actualizar la contraseña: " . $stmt->error;
        }

        exit();
    }
} else {
    header("Location: admin.php?mensaje=ID no válido");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <style>
        body {
            display: flex; /* Usar flexbox para centrar */
            justify-content: center; /* Centrar horizontalmente */
            align-items: center; /* Centrar verticalmente */
            height: 100vh; /* Asegura que el cuerpo ocupe toda la altura de la ventana */
            margin: 0; /* Eliminar márgenes por defecto */
            background-color: #f2f2f2; /* Color de fondo */
            font-family: Arial, sans-serif; /* Fuente por defecto */
        }

        .form-container {
            background: white; /* Color de fondo blanco para el formulario */
            padding: 20px; /* Espaciado interno */
            border-radius: 15px; /* Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
            width: 300px; /* Ancho del formulario */
            text-align: center; /* Centrar texto */
        }

        input[type="password"] {
            width: 250px; /* Ancho completo */
            padding: 10px; /* Espaciado interno */
            margin-bottom: 10px; /* Espacio debajo del input */
            border: 1px solid #ccc; /* Borde gris */
            border-radius: 15px; /* Bordes redondeados */
        }

        input[type="submit"] {
            background-color: #ff69b4; /* Color rosa */
            color: white; /* Color del texto en blanco */
            border: none; /* Sin borde */
            padding: 10px; /* Espaciado interno */
            border-radius: 15px; /* Bordes redondeados */
            cursor: pointer; /* Cambia el cursor a mano */
            transition: background-color 0.3s; /* Transición suave para el hover */
            width: 100%; /* Ancho completo del botón */
        }

        input[type="submit"]:hover {
            background-color: #ff1493; /* Cambia a un rosa más oscuro al pasar el mouse */
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Cambiar Contraseña</h2>
        <form action="" method="post">
            <label for="nueva_contrasena">Nueva Contraseña:</label>
            <input type="password" id="nueva_contrasena" name="nueva_contrasena" required minlength="8">
            <input type="submit" value="Actualizar Contraseña">
        </form>
    </div>
</body>

</html>