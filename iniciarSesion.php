<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'conexion_be.php';

if ($conexion->connect_error) {
    error_log("Conexión fallida: " . $conexion->connect_error);
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $contrasena = $_POST['contraseña'];

    error_log("Correo recibido: $correo");

    // Consulta para verificar el usuario y obtener nombre, apellido e id_cargo
    $query = "SELECT contraseña, activo, nombres, apellidos, id_cargo FROM usuarios WHERE correo = ?";
    $stmt = $conexion->prepare($query);
    
    if (!$stmt) {
        error_log("Error en la preparación de la consulta: " . $conexion->error);
        die("Error en la preparación de la consulta.");
    }

    $stmt->bind_param("s", $correo);
    $stmt->execute();

    // Reemplazar get_result con bind_result
    $stmt->bind_result($hashed_password, $activo, $nombres, $apellidos, $id_cargo);
    $stmt->fetch();

    $stmt->close(); // Cerrar la consulta SELECT antes de proceder con la UPDATE

    if ($hashed_password) {
        error_log("Valor de id_cargo: " . $id_cargo);
        error_log("Valor de activo: " . $activo);

        if ($activo == 1 && password_verify($contrasena, $hashed_password)) {
            $_SESSION['nombres'] = $nombres;
            $_SESSION['apellidos'] = $apellidos;
            $_SESSION['correo'] = $correo;
            $_SESSION['id_cargo'] = $id_cargo;
            $_SESSION['estado_sesion'] = 1;

            // Actualiza el estado de sesión en la base de datos
            $updateQuery = "UPDATE usuarios SET estado_sesion = 1 WHERE correo = ?";
            $updateStmt = $conexion->prepare($updateQuery);
            $updateStmt->bind_param("s", $correo);
            $updateStmt->execute();
            $updateStmt->close();

            error_log("Inicio de sesión exitoso para el correo: $correo");
            // Redirigir a la página específica
            if ($correo === 'carnesbyr@gmail.com' && $id_cargo == 1) {
                error_log("Redirigiendo a admin.php");
                header("Location: ../admin.php");
                exit();
            } elseif ($correo === 'byrcarnes@gmail.com' && $id_cargo == 1) {
                
                error_log("Redirigiendo a grafica.php");
                header("Location: ../grafica.php");
                exit();
            } else {
                error_log("Redirigiendo a index2.php");
                header("Location: ../index2.php");
                exit();
            }
            exit();
        } else {
            error_log("Credenciales incorrectas o usuario inactivo para el correo: $correo");
            echo "<script>alert('Credenciales incorrectas o usuario inactivo. Intenta de nuevo.');</script>";
            header("Location: ../NoinicoSesion.html");
            exit();
        }
    } else {
        error_log("No se encontró el usuario con el correo: $correo");
        echo "<script>alert('Credenciales incorrectas. Intenta de nuevo.');</script>";
        header("Location: ../NoinicoSesion.html");
        exit();
    }
}

mysqli_close($conexion);
?>