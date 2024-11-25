<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombres']) || !isset($_SESSION['apellidos'])) {
    // Si no ha iniciado sesión, redirigir al inicio de sesión
    header("Location: ../NoinicoSesion.html");
    exit();
}
$nombres = $_SESSION['nombres'];
$apellidos = $_SESSION['apellidos'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Itim&family=Nerko+One&family=Pompiere&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Itim", cursive;
            font-weight: 400;
            font-style: normal;
        }

        h2 {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Itim", cursive;
            font-weight: 700;
            font-style: normal;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* Menú Lateral */
        .sidebar {
            width: 250px;
            height: 4000px;
            background: linear-gradient(180deg, #ff6a79, #fdadac);
            color: #151414;
            padding: 20px;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .menu ul {
            list-style: none;
        }

        .menu ul li {
            margin: 15px 0;
        }

        .menu ul li a {
            color: #090808;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            transition: background-color 0.3s;
        }

        .menu ul li a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Contenido Principal */
        .main-content {
            flex: 1;
            background-color: #f4f4f9;
            padding: 20px;
        }

        .header {
            background: linear-gradient(180deg, #ff6a79, #fdadac);
            color: #fff;
            padding: 15px;
            border-radius: 15px;
        }

        .content {
            margin-top: 20px;
            font-size: 16px;
        }

     .logout-button {
    display: block;
    margin-top: 20px;
    padding: 12px;
    text-align: center;
    background: linear-gradient(90deg, #ff4d4d, #ff6a79);
    color: white;
    font-size: 18px;
    font-weight: bold;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.logout-button:hover {
    background: linear-gradient(90deg, #ff6a79, #ff4d4d);
    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
    transform: scale(1.05);
}

    </style>
</head>

<body>
    <div class="container">
        <!-- Menú Lateral -->
        <aside class="sidebar">
            <img src="images/logoC.png" alt="Logo" style="width: 100%; height: auto; margin-bottom: 20px;">
            <h5><?php echo htmlspecialchars($nombres); ?></h5>
            <nav class="menu">
                <nav class="menu">
                    <ul>
                        <li><a href="#" id="usuario-link">Usuarios</a></li>
                        <li><a href="#" id="detalle-pedidos-link">Detalle de pedidos</a></li>
                        <li><a href="#" id="productos-link">Productos</a></li>
                        <li><a href="#" id="ayuda-link">Comentarios clientes</a></li>
                        <li><a href="#" id="orden-link">Orden de Pago</a></li>
                    </ul>
                     <a href="iniciarSesion.html" class="logout-button">Cerrar sesión</a>
                </nav>
        </aside>

        <!-- Contenido Principal -->
        <main class="main-content">
            <header class="header">
                <h1>Bienvenido al Panel de Administración</h1>
            </header>
            <section class="content" id="contenido-principal">
                <p>Este es el contenido principal de la página de administración. Selecciona una opción en el menú para
                    ver más detalles.</p>
            </section>
        </main>
    </div>

    <script>
        document.getElementById('usuario-link').addEventListener('click', function () {
            fetch('usuario.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('contenido-principal').innerHTML = data;
                })
                .catch(error => console.error('Error al cargar usuarios:', error));
        });
        document.getElementById('detalle-pedidos-link').addEventListener('click', function () {
            fetch('pedido.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('contenido-principal').innerHTML = data;
                })
                .catch(error => console.error('Error al cargar detalles de pedidos:', error));
        });

        document.getElementById('productos-link').addEventListener('click', function () {
            fetch('tablaprod.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('contenido-principal').innerHTML = data;
                })
                .catch(error => console.error('Error al cargar detalles de pedidos:', error));
        });
        document.getElementById('ayuda-link').addEventListener('click', function () {
            fetch('ayuda.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('contenido-principal').innerHTML = data;
                })
                .catch(error => console.error('Error al cargar detalles de pedidos:', error));
        });

        document.getElementById('orden-link').addEventListener('click', function () {
            fetch('Factura.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('contenido-principal').innerHTML = data;
                })
                .catch(error => console.error('Error al cargar detalles de pedidos:', error));
        });

    </script>
</body>

</html>