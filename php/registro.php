<?php
require_once '../vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion_be.php';  // Verifica que este archivo esté correctamente configurado

// Verificar conexión a la base de datos
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['dir'];
    $password = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

    // Verificar si el correo ya existe en la base de datos
    $sql = "SELECT id FROM usuarios WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->bind_result($existing_user_id);
    $stmt->fetch();
    $stmt->close();

    if ($existing_user_id) {
        // Redirigir a la página de error si el usuario ya existe
        header("Location: ../NoRegistro.html");
        exit();
    } else {
        // Generar token de activación y establecer fecha de expiración (10 minutos)
        $token = bin2hex(random_bytes(16));
        $token_expira = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        // Insertar usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombres, apellidos, correo, telefono, direccion, contraseña, token, token_expira, activo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssss", $nombres, $apellidos, $correo, $telefono, $direccion, $password, $token, $token_expira);

        // Verificar si la inserción fue exitosa
        if ($stmt->execute()) {
            // Enviar correo con enlace de activación usando mail()
            $enlace_activacion = "https://proyectocarnes.opticasolsj.com/php/activar.php?token=" . $token;
            $subject = 'Activa tu cuenta';
            $message = "Hola $nombres, por favor activa tu cuenta haciendo clic en el siguiente enlace: <a href='$enlace_activacion'>$enlace_activacion</a><br>Este enlace es válido por 10 minutos.";
            $headers = "From: remitente@ejemplo.com\r\n";
            $headers .= "Reply-To: remitente@ejemplo.com\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";

            if (mail($correo, $subject, $message, $headers)) {
                echo "<script>alert('Revisa tu correo para activar tu cuenta.'); window.location.href='../iniciarSesion.html';</script>";
            } else {
                echo "El mensaje no se pudo enviar.";
            }
        } else {
            echo "<script>alert('Error al registrar el usuario.'); window.location.href='../Registro.html';</script>";
        }
    }

    // Cerrar la conexión y liberar los recursos
    $stmt->close();
    $conexion->close();
}
?>
