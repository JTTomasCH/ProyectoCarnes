<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'php/conexion_be.php'; // Verifica que este archivo esté correctamente configurado

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verifica si el token existe en la base de datos
    $sql = "SELECT correo FROM usuarios WHERE token = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($email);

    $usuarioValido = false;

    // Verificar si el token es válido y obtener el correo
    while ($stmt->fetch()) {
        $usuarioValido = true;
    }

    if ($usuarioValido) {
        // Actualiza la columna activo a 0 para desactivar el usuario
        $updateSql = "UPDATE usuarios SET activo = 0 WHERE correo = ?";
        $updateStmt = $conexion->prepare($updateSql);
        $updateStmt->bind_param("s", $email);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            echo "<script>alert('Usuario desactivado exitosamente.'); window.location.href='index.html';</script>";
        } else {
            echo "<script>alert('Error al desactivar el usuario. Inténtalo nuevamente.'); window.location.href='index.html';</script>";
        }

        $updateStmt->close();
    } else {
        echo "<script>alert('Token inválido o expirado.'); window.location.href='index.html';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Acceso no permitido.'); window.location.href='index.html';</script>";
}

$conexion->close();
?>
