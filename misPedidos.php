<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'php/conexion_be.php';

// Verificar si el usuario tiene un correo registrado en la sesión
if (!isset($_SESSION['correo'])) {
    header("Location: ../NoinicoSesion.html");
    exit();
}

$correo = $_SESSION['correo'];

// Consulta para obtener el estado de sesión del usuario
$usuarioQuery = "SELECT id, nombres, apellidos, estado_sesion FROM usuarios WHERE correo = ?";
$usuarioStmt = $conexion->prepare($usuarioQuery);
$usuarioStmt->bind_param("s", $correo);
$usuarioStmt->execute();
$usuarioStmt->bind_result($id, $nombres, $apellidos, $estado_sesion);

// Inicializar variables
$usuarioLogueado = false;

// Verificar si se encontró el usuario y si tiene la sesión activa
if ($usuarioStmt->fetch() && $estado_sesion == 1) {
    $usuarioLogueado = true;
}
$usuarioStmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Carnicería</title>
    <!-- Estilos aquí -->
</head>
<body>
<header>
    Mis Pedidos
</header>
<div class="container">
    <?php
    if ($usuarioLogueado) {
        // Consulta para obtener todos los pedidos del usuario
        $sql_pedidos = "
            SELECT p.id AS pedido_id, o.fecha, m.Descripcion AS metodo_pago
            FROM Pedido p
            JOIN OrdenPago o ON p.id = o.id_pedido
            JOIN MetodoPago m ON o.id_metodopago = m.ID
            WHERE p.id_cliente = ?
            ORDER BY p.id DESC
        ";
    
        $stmt_pedidos = $conexion->prepare($sql_pedidos);
        $stmt_pedidos->bind_param("i", $id); // Usamos el ID del usuario logueado
        $stmt_pedidos->execute();
        $stmt_pedidos->bind_result($pedido_id, $fecha_pedido, $metodo_pago);
    
        $hayPedidos = false;
    
        // Procesar pedidos
        while ($stmt_pedidos->fetch()) {
            $hayPedidos = true;
            echo "<div class='pedido'>";
            echo "<h3>Pedido ID: " . $pedido_id . "</h3>";
            echo "<p class='fecha'>Fecha del pedido: " . $fecha_pedido . "</p>";
            echo "<p class='metodo_pago'>Método de pago: " . $metodo_pago . "</p>";
    
            // Consulta para obtener los detalles de cada pedido
            $sql_detalle = "
                SELECT dp.cantidad, pr.nombre, pr.precio, pr.imagen 
                FROM DetallePedido dp
                JOIN productos pr ON dp.id_producto = pr.id
                WHERE dp.id_pedido = ?
            ";
    
            $stmt_detalle = $conexion->prepare($sql_detalle);
            $stmt_detalle->bind_param("i", $pedido_id);
            $stmt_detalle->execute();
            $stmt_detalle->bind_result($cantidad, $nombre, $precio, $imagen);
    
            // Mostrar los detalles del pedido y calcular el total
            echo "<div class='detalle'>";
            $total_pedido = 0;
            $cantidad_total = 0;
            while ($stmt_detalle->fetch()) {
                $total_pedido += $cantidad * $precio;
                $cantidad_total += $cantidad;
    
                echo "<div class='producto-info'>";
                echo "<div>Producto: " . $nombre . "</div>";
                echo "<div>Cantidad: " . $cantidad . "</div>";
                echo "<div>Precio: " . number_format($precio, 2) . "</div>";
                if ($imagen) {
                    echo "<div><img src='data:image/jpeg;base64," . base64_encode($imagen) . "' alt='" . $nombre . "' /></div>";
                } else {
                    echo "<div><img src='img/default.jpg' alt='Imagen no disponible' /></div>";
                }
                echo "</div>";
            }
            echo "</div>";
    
            // Cierra la consulta de detalles
            $stmt_detalle->free_result();
            $stmt_detalle->close();
    
            // Si la cantidad total es menor a 7, sumar 30,000 al total
            if ($cantidad_total < 7) {
                $total_pedido += 30000;
            }
    
            echo "<p class='total'>Total del pedido: " . number_format($total_pedido, 2) . "</p>";
            echo "</div>";
        }
    
        if (!$hayPedidos) {
            echo "<p class='no-pedidos'>No hay pedidos para este usuario</p>";
        }
    
        // Cierra la consulta de pedidos
        $stmt_pedidos->free_result();
        $stmt_pedidos->close();
    } else {
        echo "<p class='no-pedidos'>El usuario no está logueado o la sesión está inactiva</p>";
    }
    ?>
    <a href="index2.php" class="btn-volver">Volver a la página principal</a>
</div>
</body>
</html>
