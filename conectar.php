<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'conexion_be.php'; // Asegúrate de que este archivo esté en el mismo directorio

if ($conexion->connect_error) {
    error_log("Conexión fallida: " . $conexion->connect_error);
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta para obtener todos los registros de la tabla 'pedido' junto con el id_productos de la tabla 'productos'
$query = "SELECT p.ID, p.FECHA, p.id_cliente, pr.id_productos
          FROM pedido p
          JOIN productos pr ON p.id_producto = pr.id_productos"; // Ajusta la condición de JOIN según tu diseño de base de datos

$resultado = $conexion->query($query);

if (!$resultado) {
    error_log("Error en la consulta: " . $conexion->error);
    die("Error en la consulta: " . $conexion->error);
}

// Almacenar los registros en un array
$pedidos = [];
while ($fila = $resultado->fetch_assoc()) {
    $pedidos[] = $fila;
}

// Si necesitas imprimir los resultados
foreach ($pedidos as $pedido) {
    echo "ID: " . $pedido['ID'] . " - Fecha: " . $pedido['FECHA'] . " - ID Cliente: " . $pedido['id_cliente'] . " - ID Producto: " . $pedido['id_productos'] . "<br>";
}

// Cerrar la conexión
$conexion->close();
?>