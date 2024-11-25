<?php
include 'conexion_be.php';

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['correo'];
    $nuevaContraseña = $_POST['nuevaContraseña'];
    $confirmarContraseña = $_POST['confirmarContraseña'];

    if ($nuevaContraseña === $confirmarContraseña) {
        // Encriptar la nueva contraseña
        $contraseñaEncriptada = password_hash($nuevaContraseña, PASSWORD_DEFAULT);

        // Actualizar la contraseña y limpiar el token
        $sql = "UPDATE usuarios SET contraseña = ?, token = NULL WHERE correo = ?";
        $stmt = $conexion->prepare($sql);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }

        $stmt->bind_param("ss", $contraseñaEncriptada, $email);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<script>alert('Contraseña restablecida con éxito.');</script>";
                header("Location:../iniciarSesion.html");
                exit();
            } else {
                echo "<script>alert('No se encontró el usuario o la contraseña no cambió.');</script>";
            }
        } else {
            echo "<script>alert('Error en la ejecución de la consulta: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Las contraseñas no coinciden.'); window.location.href='/site/contraseña.html'</script>";
    }
} else {
    // Verificar el token
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $sql = "SELECT correo FROM usuarios WHERE token = ?";
        $stmt = $conexion->prepare($sql);
        
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }

        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $email = $row['correo']; // Aquí asignamos el correo a la variable
        } else {
            echo "<script>alert('Token inválido.');</script>";
            exit();
        }

        $stmt->close();
    } else {
        echo "<script>alert('Token no proporcionado.');</script>";
        exit();
    }
}

$conexion->close();
?>
