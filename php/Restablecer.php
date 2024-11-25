<?php 
include 'conexion_be.php';

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $sql = "SELECT id FROM usuarios WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close(); // Cerramos la consulta para evitar el error "Commands out of sync"

    if ($user_id) {
        // Generar un token único
        $token = bin2hex(random_bytes(16));

        // Guardar el token en la base de datos
        $updateSql = "UPDATE usuarios SET token = ? WHERE correo = ?";
        $updateStmt = $conexion->prepare($updateSql);
        $updateStmt->bind_param("ss", $token, $email);
        $updateStmt->execute();

        // Enviar el correo con el enlace
        $link = "https://proyectocarnes.opticasolsj.com/contrasena.html?token=" . urlencode($token);
        $subject = "Restablecer tu contraseña";
        $message = "Haz clic en el siguiente enlace para restablecer tu contraseña: $link";
        $headers = "From: noreply@tu_dominio.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('Se ha enviado un enlace a tu correo electrónico.');window.location.href='../iniciarSesion.html'</script>";
        } else {
            echo "<script>alert('Error al enviar el correo.'); window.location.href='/site/iniciarSesion.html'</script>";
        }

        $updateStmt->close();
    } else {
        echo "<script>alert('El correo no está registrado.');</script>";
    }
}

$conexion->close();
?>
