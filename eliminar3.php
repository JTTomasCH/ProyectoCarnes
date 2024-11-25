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
// Asegúrate de convertir el ID a entero

        // Primero, obtenemos el id_pedido asociado al detalle
        $consulta_id_pedido = "SELECT id_pedido FROM DetallePedido WHERE id = ?";
        $stmt_id_pedido = $conexion->prepare($consulta_id_pedido);
        $stmt_id_pedido->bind_param("i", $id_detalle);
        $stmt_id_pedido->execute();
        $resultado_id_pedido = $stmt_id_pedido->get_result();

        if ($row = $resultado_id_pedido->fetch_assoc()) {
            $id_pedido = $row['id_pedido'];

            // Ahora, inserta en la tabla proceso
            $consulta_proceso = "INSERT INTO proceso (id_pedido, estado) VALUES (?, 'Se entrego el pedido')";
            $stmt_proceso = $conexion->prepare($consulta_proceso);
            $stmt_proceso->bind_param("i", $id_pedido);

            // Ejecuta la consulta de inserción
            if (!$stmt_proceso->execute()) {
                // Manejo de errores si la inserción falla
                header("Location: admin.php?mensaje=Error al insertar en la tabla proceso");
                exit();
            }
        } else {
            // Manejo de errores si no se encuentra el id_pedido
            header("Location: admin.php?mensaje=ID de detalle de pedido no válido");
            exit();
        }
    }

    // Redirige de vuelta con un mensaje de éxito
    header("Location: admin.php?mensaje=Proceso actualizado a 'Terminado' para los detalles seleccionados");
    exit();

?>