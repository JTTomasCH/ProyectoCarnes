<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id'])) {
    // Incluye la conexión a la base de datos
    include 'php/conexion_be.php';

    // Escapa el ID del producto para evitar inyecciones SQL
    $id_producto = intval($_GET['id']);

    // Primero, verificamos si el producto existe
    $consulta_verificacion = "SELECT id FROM productos WHERE id = ?";
    $stmt_verificacion = $conexion->prepare($consulta_verificacion);
    $stmt_verificacion->bind_param("i", $id_producto);
    $stmt_verificacion->execute();
    
    // Vincula el resultado a una variable
    $stmt_verificacion->bind_result($id_resultado);

    if ($stmt_verificacion->fetch()) {
        // El producto existe, procedemos a eliminarlo
        $stmt_verificacion->close(); // Cierra el statement de verificación

        $consulta_eliminar = "DELETE FROM productos WHERE id = ?";
        $stmt_eliminar = $conexion->prepare($consulta_eliminar);
        $stmt_eliminar->bind_param("i", $id_producto);

        // Ejecuta la consulta y verifica el resultado
        if ($stmt_eliminar->execute()) {
            // Redirige de vuelta a la página de gestión de productos con un mensaje de éxito
            header("Location: admin.php?mensaje=Producto eliminado correctamente");
            exit();
        } else {
            // Redirige de vuelta con un mensaje de error
            header("Location: admin.php?mensaje=Error al eliminar el producto");
            exit();
        }
    } else {
        // El producto no existe
        header("Location: admin.php?mensaje=El producto no existe");
        exit();
    }
    
    $stmt_verificacion->close(); // Cierra el statement después de su uso
} else {
    // Redirige si no se ha pasado un ID válido
    header("Location: admin.php?mensaje=ID de producto no válido");
    exit();
}
?>
