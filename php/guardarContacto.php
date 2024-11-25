<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'conexion_be.php';  

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Recibir los datos del formulario
$name = $_POST['name'];
$email = $_POST['email'];
$servicio = $_POST['service']; 
$phone = $_POST['phone'];
$message = $_POST['message'];

// Preparar y ejecutar la consulta SQL para insertar los datos
$sql = "INSERT INTO Contactanos (nombre, correo, servicio, telefono, mensaje) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssss", $name, $email, $servicio, $phone, $message);

if ($stmt->execute()) {
    echo "<script>
    alert('Datos guardados correctamente');
    window.location.href = '../contacts.html';
</script>";
} else {
    echo "<script>alert('Error al  guardar datos'); window.location.href='../contacts.html'</script>";
}

// Cerrar conexión
$stmt->close();
$conexion->close();
?>
