<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica si se ha pasado un ID de detalle de pedido
if (isset($_GET['id'])) {
    // Incluye la conexión a la base de datos
    include 'php/conexion_be.php';

    // Escapa el ID del detalle de pedido para evitar inyecciones SQL
    $id_detalle = intval($_GET['id']);

    // Primero, obtenemos el id_pedido asociado al detalle que se va a eliminar
    $consulta_id_pedido = "SELECT id_pedido FROM DetallePedido WHERE id = ?";
    $stmt_id_pedido = $conexion->prepare($consulta_id_pedido);
    $stmt_id_pedido->bind_param("i", $id_detalle);
    $stmt_id_pedido->execute();
    $stmt_id_pedido->bind_result($id_pedido);

    // Verificamos que se encontró el pedido
    if ($stmt_id_pedido->fetch()) {
        $stmt_id_pedido->close();  // Cierra el statement después de obtener el resultado

        // Prepara la consulta para eliminar el detalle del pedido
        $consulta_detalle = "DELETE FROM DetallePedido WHERE id = ?";
        $stmt_detalle = $conexion->prepare($consulta_detalle);
        $stmt_detalle->bind_param("i", $id_detalle);
        
        // Ejecuta la consulta para eliminar el detalle
        if ($stmt_detalle->execute()) {
            $stmt_detalle->close();  // Cierra el statement para liberar recursos

            // Ahora eliminamos el pedido correspondiente
            $consulta_pedido = "DELETE FROM Pedido WHERE id = ?";
            $stmt_pedido = $conexion->prepare($consulta_pedido);
            $stmt_pedido->bind_param("i", $id_pedido);
            $stmt_pedido->execute();
            $stmt_pedido->close();  // Cierra el statement después de ejecutar

            // Redirige de vuelta a la página de gestión de pedidos con un mensaje de éxito
            header("Location: admin.php?mensaje=Detalle de pedido y pedido eliminado correctamente");
            exit();
        } else {
            $stmt_detalle->close();
            // Redirige de vuelta con un mensaje de error al eliminar el detalle
            header("Location: admin.php?mensaje=Error al eliminar el detalle de pedido");
            exit();
        }
    } else {
        $stmt_id_pedido->close();
        // Redirige si no se encontró el pedido correspondiente
        header("Location: admin.php?mensaje=No se encontró el pedido asociado");
        exit();
    }
} else {
    // Redirige si no se ha pasado un ID válido
    header("Location: admin.php?mensaje=ID de detalle de pedido no válido");
    exit();
}
?>
