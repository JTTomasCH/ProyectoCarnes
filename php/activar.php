<?php

include 'conexion_be.php';
// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Buscar usuario por token y verificar que aún no esté activo
    $sql = "SELECT id, token_expira FROM usuarios WHERE token = ? AND activo = 0";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();

    // Vincular los resultados a variables
    $stmt->bind_result($id, $token_expira);

    // Verificar si se encontró un resultado
    if ($stmt->fetch()) {
        $fecha_actual = date("Y-m-d H:i:s");
        
        // Liberar el primer statement antes de la siguiente operación
        $stmt->close();

        // Verificar si el token ha expirado
        if ($fecha_actual < $token_expira) {
            // Actualizar el estado del usuario a activo
            $sql = "UPDATE usuarios SET activo = 1, token = NULL, token_expira = NULL WHERE id = ?";
            $stmt_update = $conexion->prepare($sql);
            $stmt_update->bind_param("i", $id);
            $stmt_update->execute();
            $stmt_update->close();  // Cerrar el segundo statement
            header("Location: ../activacion_exitosa.html");
            exit();
        } else {
            echo "El enlace de activación ha expirado.";
        }
    } else {
        echo "Token no válido o la cuenta ya está activada.";
    }
    
    // Cerrar la conexión
    $conexion->close();
}
?>
