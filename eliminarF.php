<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id'])) {
    // Incluye la conexión a la base de datos
    include 'php/conexion_be.php';

    // Escapa el ID de la orden para evitar inyecciones SQL
    $id_orden = intval($_GET['id']);

    // Verifica si la orden de pago existe
    $consulta_verificacion = "SELECT ID FROM OrdenPago WHERE ID = ?";
    $stmt_verificacion = $conexion->prepare($consulta_verificacion);
    $stmt_verificacion->bind_param("i", $id_orden);
    $stmt_verificacion->execute();

    // Vincula el resultado a una variable
    $stmt_verificacion->bind_result($id_resultado);

    if ($stmt_verificacion->fetch()) {
        $stmt_verificacion->close(); // Cierra el statement de verificación

        // La orden de pago existe, procedemos a eliminarla
        $consulta_eliminar = "DELETE FROM OrdenPago WHERE ID = ?";
        $stmt_eliminar = $conexion->prepare($consulta_eliminar);
        $stmt_eliminar->bind_param("i", $id_orden);

        // Ejecuta la consulta y verifica el resultado
        if ($stmt_eliminar->execute()) {
            // Redirige de vuelta a la página de gestión de órdenes con un mensaje de éxito
            header("Location: admin.php?mensaje=Orden de pago eliminada correctamente");
            exit();
        } else {
            // Redirige de vuelta con un mensaje de error
            header("Location: admin.php?mensaje=Error al eliminar la orden de pago");
            exit();
        }
    } else {
        $stmt_verificacion->close(); // Cierra el statement en caso de que no se encuentre el registro
        // La orden de pago no existe
        header("Location: admin.php?mensaje=La orden de pago no existe");
        exit();
    }
} else {
    // Redirige si no se ha pasado un ID válido
    header("Location: admin.php?mensaje=ID de orden de pago no válido");
    exit();
}
?>
