<?php
// Incluir la conexión a la base de datos
include 'php/conexion_be.php';

// Activar el reporte de errores para facilitar la depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Verificar si el correo está en la sesión
if (isset($_SESSION['correo'])) {
    echo "Correo de sesión: " . $_SESSION['correo'] . "<br>"; // Depuración
    $correo = $_SESSION['correo'];

    // Obtener el ID del usuario basado en el correo
    $usuarioQuery = "SELECT id FROM usuarios WHERE correo = ?";
    $usuarioStmt = $conexion->prepare($usuarioQuery);
    $usuarioStmt->bind_param("s", $correo);
    $usuarioStmt->execute();
    $usuarioStmt->bind_result($id_usuario);
    $usuarioStmt->fetch();
    $usuarioStmt->close();

    // Verificar si se encontró un usuario
    if ($id_usuario) {
        echo "ID del usuario: " . $id_usuario . "<br>"; // Depuración

        // Consultar el estado actual de la sesión
        $query = "SELECT estado_sesion FROM usuarios WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->bind_result($estado_sesion);
        $stmt->fetch();
        $stmt->close();

        // Mostrar el estado de la sesión
        echo "Estado de sesión: " . $estado_sesion . "<br>"; // Depuración

        // Verificar si `estado_sesion` está en `1`
        if ($estado_sesion == 1) {
            // Actualizar el estado de la sesión en la base de datos a `0`
            $update_query = "UPDATE usuarios SET estado_sesion = 0 WHERE id = ?";
            $update_stmt = $conexion->prepare($update_query);
            $update_stmt->bind_param("i", $id_usuario);

            if ($update_stmt->execute()) {
                echo "Estado de sesión actualizado correctamente.<br>"; // Depuración
                $update_stmt->close();
                
                // Cerrar sesión eliminando la sesión actual
                session_unset();
                session_destroy();
                
                // Redirigir al usuario a la página de inicio de sesión
                header("Location: iniciarSesion.html");
                exit();
            } else {
                echo "Error al cerrar sesión.";
                $update_stmt->close();
            }
        } else {
            // Si `estado_sesion` ya está en `0`, solo cerrar la sesión
            session_unset();
            session_destroy();
            header("Location: iniciarSesion.html");
            exit();
        }
    } else {
        // Si no se encuentra el usuario, redirigir a la página de inicio de sesión
        echo "No se encontró el usuario con el correo proporcionado.<br>"; // Depuración
        session_unset();
        session_destroy();
        header("Location: iniciarSesion.html");
        exit();
    }
} else {
    // Si no hay correo en la sesión, redirigir al usuario a la página de inicio de sesión
    echo "No hay correo en la sesión.<br>"; // Depuración
    header("Location: iniciarSesion.html");
    exit();
}

// Cerrar la conexión
$conexion->close();
?>
