<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'php/conexion_be.php';  // Verifica que este archivo esté correctamente configurado

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Consulta para verificar si el correo existe
    $sql = "SELECT id FROM usuarios WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($userId);

    $usuarioExiste = false;

    // Verificar si hay resultados
    while ($stmt->fetch()) {
        $usuarioExiste = true;
    }

    if ($usuarioExiste) {
        // Generar un token único
        $token = bin2hex(random_bytes(16));

        // Guardar el token en la base de datos
        $updateSql = "UPDATE usuarios SET token = ? WHERE correo = ?";
        $updateStmt = $conexion->prepare($updateSql);
        $updateStmt->bind_param("ss", $token, $email);
        $updateStmt->execute();

        $link = "https://proyectocarnes.opticasolsj.com/desact1.php?token=" . urlencode($token);
        $subject = "Desactivar cuenta";
        $message = "Haz clic en el siguiente enlace para desactivar tu usuario: $link";
        $headers = "From: noreply@tu_dominio.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('Se ha enviado un enlace a tu correo electrónico.');window.location.href='iniciarSesion.html'</script>";
        } else {
            echo "<script>alert('Error al enviar el correo.'); window.location.href='iniciarSesion.html'</script>";
        }

        $updateStmt->close();
    } else {
        echo "<script>alert('El correo no está registrado.');</script>";
    }

    $stmt->close();
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desactivar Cuenta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://via.placeholder.com/1920x1080/ffe6e6/ffffff?text=Carnicer%C3%ADa+La+Estrella');
            background-size: cover;
            background-repeat: no-repeat;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .overlay {
            background: rgba(255, 255, 255, 0.8);
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            width: 100%;
            max-width: 600px;
            text-align: center;
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: #d83434;
            font-size: 2.5em;
        }
        p {
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        form {
            margin-top: 20px;
        }
        input[type="email"] {
            width: calc(100% - 24px);
            padding: 15px;
            border: 2px solid #d83434;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 1.1em;
        }
        button {
            background: #d83434;
            color: #fff;
            border: none;
            padding: 15px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.2em;
            transition: all 0.3s ease;
        }
        button:hover {
            background: #b52a2a;
            transform: scale(1.05);
        }
        .mensaje {
            margin-top: 20px;
            font-size: 1.2em;
            color: #333;
            font-weight: bold;
        }
        footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="overlay">
        <div class="container">
            <h1>Carnes B&R</h1>
            <p>Ingresa tu correo electrónico para desactivar tu cuenta.</p>
            <form action="" method="post">
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <button type="submit">Enviar</button>
            </form>
            <?php if (!empty($mensaje)): ?>
                <div class="mensaje"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            <footer>&copy; 2024 Carnes B&R</footer>
        </div>
    </div>
</body>
</html>
