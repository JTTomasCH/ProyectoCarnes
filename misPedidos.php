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
    <style>
        /* Estilos Globales */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            color: #4B3A29;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        header {
            background-color: #C62828; /* Rojo carne */
            padding: 25px;
            color: white;
            text-align: center;
            font-size: 34px;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Pedido */
        .pedido {
            background-color: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .pedido:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .pedido h3 {
            color: #C62828; /* Rojo carne */
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .pedido .fecha,
        .pedido .metodo_pago {
            font-size: 14px;
            color: #7B4F3E;
            margin-bottom: 8px;
        }

        .pedido .total {
            font-size: 22px;
            font-weight: bold;
            color: #C62828; /* Rojo carne */
            margin-top: 20px;
        }

        .pedido .detalle {
            margin-top: 20px;
        }

        /* Detalles de los productos */
        .producto-info {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-top: 10px;
            font-size: 16px;
            color: #333;
        }

        .producto-info div {
            width: 30%;
            padding: 10px;
        }

        .producto-info div:first-child {
            width: 35%;
        }

        /* Imagen de producto */
        .pedido img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px; /* Bordes rectos pero suavizados */
            margin-right: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .pedido img:hover {
            transform: scale(1.05); /* Agranda un poco la imagen */
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2); /* Sombra más pronunciada */
        }

        /* Imagen por defecto */
        .pedido img[src='img/default.jpg'] {
            border-radius: 8px;
            border: 2px dashed #C62828; /* Borde discontinuo para imagen por defecto */
        }

        /* No hay pedidos */
        .no-pedidos {
            text-align: center;
            font-size: 22px;
            color: #C62828; /* Rojo carne */
            font-weight: bold;
        }

        /* Botón Volver */
        .btn-volver {
            background-color: #388E3C;
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            display: block;
            margin: 40px auto;
            width: 220px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-volver:hover {
            background-color: #2C6B31;
            transform: translateY(-3px);
        }

        /* Estilo adicional para dar ambiente rústico */
        h3, .total {
            font-family: 'Georgia', serif; /* Fuente con estilo más rústico */
            letter-spacing: 1px;
        }

        /* Fondo de página */
        body {
            background-image: url('img/wood-texture.jpg'); /* Textura de madera de fondo */
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: #4B3A29;
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            .pedido {
                padding: 20px;
            }

            .producto-info {
                flex-direction: column;
                align-items: flex-start;
            }

            .producto-info div {
                width: 100%;
                margin-bottom: 10px;
            }

            .pedido img {
                margin-right: 0;
                margin-bottom: 15px;
            }

            .btn-volver {
                width: 180px;
            }
        }

    </style>
</head>
<body>
    <header>
        Mis Pedidos 
    </header>

    <div class="container">

        <?php
        if ($usuarioLogueado) {
            // Consulta para obtener todos los pedidos del usuario en orden descendente por ID, junto con el número de pedido, método de pago y fecha
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
            $stmt_pedidos->store_result(); // Usamos store_result() para poder acceder a los datos sin get_result()

            // Verificar si hay pedidos
            if ($stmt_pedidos->num_rows > 0) {
                // Mostrar todos los pedidos con detalles
                $stmt_pedidos->bind_result($pedido_id, $fecha_pedido, $metodo_pago);
                while ($stmt_pedidos->fetch()) {
                    $total_pedido = 0;
                    $cantidad_total = 0;

                    // Consultar los detalles del pedido
                    $sql_detalle = "
                        SELECT dp.cantidad, pr.nombre, pr.precio, pr.imagen 
                        FROM DetallePedido dp
                        JOIN productos pr ON dp.id_producto = pr.id
                        WHERE dp.id_pedido = ?
                    ";

                    $stmt_detalle = $conexion->prepare($sql_detalle);
                    $stmt_detalle->bind_param("i", $pedido_id);
                    $stmt_detalle->execute();
                    $stmt_detalle->store_result();
                    $stmt_detalle->bind_result($cantidad, $nombre_producto, $precio, $imagen);

                    echo "<div class='pedido'>";
                    echo "<h3>Pedido Nº: " . $pedido_id . "</h3>";
                    echo "<p class='fecha'>Fecha: " . $fecha_pedido . "</p>";
                    echo "<p class='metodo_pago'>Método de pago: " . $metodo_pago . "</p>";

                    echo "<div class='detalle'>";
                    while ($stmt_detalle->fetch()) {
                        $total_pedido += $cantidad * $precio;
                        $cantidad_total += $cantidad;

                        echo "<div class='producto-info'>";
                        echo "<div>Producto: " . $nombre_producto . "</div>";
                        echo "<div>Cantidad: " . $cantidad . "</div>";
                        echo "<div>Precio: " . number_format($precio, 2) . "</div>";

                        // Verificar si existe imagen
                        if ($imagen) {
                            echo "<div><img src='data:image/jpeg;base64," . base64_encode($imagen) . "' alt='" . $nombre_producto . "' /></div>";
                        } else {
                            echo "<div><img src='img/default.jpg' alt='Imagen no disponible' /></div>";
                        }

                        echo "</div>";
                    }
                    echo "</div>";

                    // Si la cantidad total es menor a 7, sumar 30,000 al total
                    if ($cantidad_total < 7) {
                        $total_pedido += 30000;
                    }

                    echo "<p class='total'>Total del pedido: " . number_format($total_pedido, 2) . "</p>";
                    echo "</div>";

                    $stmt_detalle->close();
                }
            } else {
                echo "<p class='no-pedidos'>No hay pedidos para este usuario</p>";
            }

            $stmt_pedidos->close();
        } else {
            echo "<p class='no-pedidos'>No se pudo verificar el usuario. Por favor, inicie sesión nuevamente.</p>";
        }
        ?>

        <a href="index2.php" class="btn-volver">Volver al inicio</a>

    </div>

</body>
</html>