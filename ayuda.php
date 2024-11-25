<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Contactos</title>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #007bff; /* Cambiar a azul */
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
            background-color: #fe6d6d; /* Color de fondo rojo */
            text-decoration: none;
            border-radius: 15px;
            transition: background-color 0.3s;
        }

        .btn1 {
            display: inline-block;
            padding: 8px 12px;
            color: black;
            background-color: #5dcc57; /* Color de fondo verde */
            text-decoration: none;
            border-radius: 15px;
            transition: background-color 0.3s;
        }

        .btn2 {
            display: inline-block;
            padding: 8px 12px;
            color: black;
            background-color: #ffdd79; /* Color de fondo amarillo */
            text-decoration: none;
            border-radius: 15px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3; /* Color más oscuro al pasar el mouse */
        }

        .btn1:hover {
            background-color: #0056b3; /* Color más oscuro al pasar el mouse */
        }

        .btn2:hover {
            background-color: #0056b3; /* Color más oscuro al pasar el mouse */
        }
    </style>
</head>

<body>

    <div class="contact-management">
        <h2>Gestión de Contactos</h2>

        <!-- Tabla de Contactos -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Servicio</th>
                    <th>Teléfono</th>
                    <th>Mensaje</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Incluir la conexión a la base de datos
                include 'php/conexion_be.php';

                // Verificar la conexión
                if ($conexion->connect_error) {
                    die("Error de conexión: " . $conexion->connect_error);
                }

                // Consulta para obtener los contactos
                $consulta = "SELECT id, nombre, correo, servicio, telefono, mensaje FROM Contactanos";
                $resultado = $conexion->query($consulta);

                if ($resultado->num_rows > 0) {
                    while ($contacto = $resultado->fetch_assoc()) {
                        echo "<tr>
                            <td>{$contacto['id']}</td>
                            <td>{$contacto['nombre']}</td>
                            <td>{$contacto['correo']}</td>
                            <td>{$contacto['servicio']}</td>
                            <td>{$contacto['telefono']}</td>
                            <td>{$contacto['mensaje']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay mensajes de contacto registrados.</td></tr>";
                }

                $conexion->close();
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>