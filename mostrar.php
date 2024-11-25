<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #007bff;
            color: #fff;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        /* Estilo de los enlaces para que se parezcan a botones */
        .btn {
            display: inline-block;
            padding: 8px 12px;
            color: black;
            background-color: #fe6d6d;
            text-decoration: none;
            border-radius: 15px;
            transition: background-color 0.3s;
        }
        .btn1 {
            display: inline-block;
            padding: 8px 12px;
            color: black;
            background-color: #5dcc57;
            text-decoration: none;
            border-radius: 15px;
            transition: background-color 0.3s;
        }
        .btn2 {
            display: inline-block;
            padding: 8px 12px;
            color: black;
            background-color: #ffdd79;
            text-decoration: none;
            border-radius: 15px;
            transition: background-color 0.3s;
        }
        .btn:hover, .btn1:hover, .btn2:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="user-management">
        <h2>Gestión de Usuarios</h2>
        <a href="agregar_usuario.php" class="btn2" onclick="return confirm('¿Deseas agregar un nuevo usuario?');">AGREGAR UN NUEVO USUARIO</a>
        
        <!-- Tabla de Usuarios -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombres</th>
                    <th>Correo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
                include 'php/conexion_be.php';

                // Verificar la conexión
                if ($conexion->connect_error) {
                    die("Error de conexión: " . $conexion->connect_error);
                }

                // Consulta de usuarios
                $consulta = "SELECT id, nombres, apellidos, correo, telefono, activo FROM usuarios";
                $resultado = $conexion->query($consulta);

                if ($resultado->num_rows > 0) {
                    while ($usuario = $resultado->fetch_assoc()) {
                        echo "<tr>
                        <td>{$usuario['id']}</td>
                        <td>{$usuario['nombres']}</td>
                        <td>{$usuario['correo']}</td>
                    </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay usuarios registrados.</td></tr>";
                }

                $conexion->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
