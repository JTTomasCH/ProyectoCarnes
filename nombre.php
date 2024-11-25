<?php
// Incluir la conexión a la base de datos
include 'conexion_be.php';

// Activar el reporte de errores para facilitar la depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$nombreCompleto = ''; // Inicializar como cadena vacía

if (isset($_SESSION['correo'])) {
    $correo = $_SESSION['correo'];

    // Obtener el ID, nombre y apellido del usuario basado en el correo
    $usuarioQuery = "SELECT id, nombres, apellidos FROM usuarios WHERE correo = ?";
    $usuarioStmt = $conexion->prepare($usuarioQuery);
    $usuarioStmt->bind_param("s", $correo);
    $usuarioStmt->execute();
    $usuarioResult = $usuarioStmt->get_result();

    // Verificar si se encontró un usuario
    if ($usuarioResult->num_rows > 0) {
        $usuario = $usuarioResult->fetch_assoc();
        $id_usuario = $usuario['id']; // Obtener el id del usuario
        
        // Consultar el estado actual de la sesión
        $query = "SELECT estado_sesion FROM usuarios WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->bind_result($estado_sesion);
        $stmt->fetch();
        $stmt->close();

        // Verificar si `estado_sesion` está en `1`
        if ($estado_sesion == 1) {
            // Si el estado es 1, formar el nombre completo
            $nombreCompleto = $usuario['nombres'] . ' ' . $usuario['apellidos']; // Mostrar el nombre completo
        } 
    } 
} 
// Cerrar la conexión
$conexion->close();
?>
