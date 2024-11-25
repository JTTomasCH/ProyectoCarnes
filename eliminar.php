<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id'])) {
    // Incluye la conexión a la base de datos
    include 'php/conexion_be.php';

    // Escapa el ID del usuario para evitar inyecciones SQL
    $id_usuario = intval($_GET['id']);

    // Primero, obtener todos los id_pedido asociados al usuario
    $consulta_id_pedido = "SELECT id_pedido FROM DetallePedido WHERE id_usuarios = ?";
    $stmt_id_pedido = $conexion->prepare($consulta_id_pedido);
    $stmt_id_pedido->bind_param("i", $id_usuario);
    $stmt_id_pedido->execute();
    $stmt_id_pedido->bind_result($id_pedido);

    // Array para almacenar los id_pedido a eliminar
    $id_pedidos = [];
    while ($stmt_id_pedido->fetch()) {
        $id_pedidos[] = $id_pedido;
    }
    $stmt_id_pedido->close();

    // Eliminar los detalles de pedido asociados al usuario
    $consulta_detalle = "DELETE FROM DetallePedido WHERE id_usuarios = ?";
    $stmt_detalle = $conexion->prepare($consulta_detalle);
    $stmt_detalle->bind_param("i", $id_usuario);
    $stmt_detalle->execute();
    $stmt_detalle->close();

    // Si hay id_pedidos, proceder a eliminarlos
    if (!empty($id_pedidos)) {
        // Usar IN para eliminar múltiples pedidos a la vez
        $id_pedidos_imploded = implode(',', array_map('intval', $id_pedidos));
        $consulta_pedidos = "DELETE FROM Pedido WHERE id IN ($id_pedidos_imploded)";
        $conexion->query($consulta_pedidos); // No necesitas preparar aquí ya que es una consulta simple
    }

    // Finalmente, eliminar al usuario
    $consulta_usuario = "DELETE FROM usuarios WHERE id = ?";
    $stmt_usuario = $conexion->prepare($consulta_usuario);
    $stmt_usuario->bind_param("i", $id_usuario);

    // Ejecuta la consulta y verifica el resultado
    if ($stmt_usuario->execute()) {
        // Redirige de vuelta a la página de gestión de usuarios con un mensaje de éxito
        header("Location: admin.php?mensaje=Usuario y sus pedidos eliminados correctamente");
        exit();
    } else {
        // Redirige de vuelta con un mensaje de error
        header("Location: admin.php?mensaje=Error al eliminar el usuario");
        exit();
    }
} else {
    // Redirige si no se ha pasado un ID válido
    header("Location: admin.php?mensaje=ID de usuario no válido");
    exit();
}
