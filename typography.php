<?php
session_start(); // Iniciar la sesión al comienzo del archivo

// Configuración de la conexión
$servername = "localhost";
$username = "proyectocarnes";
$password = "C4rn33sproy33cto";
$database = "carnesbyr";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Lógica para agregar al carrito
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['producto_id'])) {
    $producto_id = $_POST['producto_id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    // Verificar si el carrito está inicializado en la sesión
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = []; // Inicializar carrito si no existe
    }

    // Agregar el producto al carrito (sesión)
    $_SESSION['carrito'][] = [
        'id' => $producto_id,
        'nombre' => $nombre,
        'precio' => $precio,
        'cantidad' => 1
    ];

    // Verificar que el producto se ha agregado al carrito
    echo "<script>alert('Producto agregado al carrito');</script>";
}

// Consulta para obtener productos
$sql = "SELECT id, nombre, precio, imagen FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html class="wide wow-animation" lang="en">
  <head>
    <title>Productos</title>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <link rel="icon" href="images/icono.jpeg" type="image/x-icon">
    <!-- Stylesheets-->
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:100,300,300i,400,500,600,700,900%7CRaleway:500">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/estilos.css">
    <style>
      .rd-navbar-nav {
        display: flex;
        align-items: center;
        gap: 20px;
      }

      .nav-actions {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-left: 20px;
      }

      .cart-button {
    display: flex;
    align-items: center;
    justify-content: center; /* Agregar esta línea */
    width: 50px; /* Cambia esto al tamaño que necesites */
    height: 50px; /* Cambia esto al tamaño que necesites */
}

.cart-button img {
    max-width: 100%; /* La imagen no excederá el ancho del contenedor */
    max-height: 100%; /* La imagen no excederá la altura del contenedor */
    margin-right: -4px; /* Desplaza la imagen un poco a la izquierda */
}



      .login-button {
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        background-color: #ff4c4c;
        transition: background-color 0.3s;
      }

      .login-button:hover {
        background-color: #ff3333;
      }

      @media (max-width: 768px) {
        .rd-navbar-nav {
          flex-direction: column;
        }
        
        .nav-actions {
          margin-left: 0;
          margin-top: 10px;
        }
      }
    </style>
  </head>
  <body>
    <div class="preloader">
      <div class="wrapper-triangle">
        <div class="pen">
          <div class="line-triangle">
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
          </div>
          <div class="line-triangle">
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
          </div>
          <div class="line-triangle">
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="page">
      <!-- Page Header-->
      <header class="section page-header">
        <!-- RD Navbar-->
        <div class="rd-navbar-wrap">
          <nav class="rd-navbar rd-navbar-modern" data-layout="rd-navbar-fixed" data-sm-layout="rd-navbar-fixed" data-md-layout="rd-navbar-fixed" data-md-device-layout="rd-navbar-fixed" data-lg-layout="rd-navbar-static" data-lg-device-layout="rd-navbar-fixed" data-xl-layout="rd-navbar-static" data-xl-device-layout="rd-navbar-static" data-xxl-layout="rd-navbar-static" data-xxl-device-layout="rd-navbar-static" data-lg-stick-up-offset="56px" data-xl-stick-up-offset="56px" data-xxl-stick-up-offset="56px" data-lg-stick-up="true" data-xl-stick-up="true" data-xxl-stick-up="true">
            <div class="rd-navbar-inner-outer">
              <div class="rd-navbar-inner">
                <!-- RD Navbar Panel-->
                <div class="rd-navbar-panel">
                  <!-- RD Navbar Toggle-->
                  <button class="rd-navbar-toggle" data-rd-navbar-toggle=".rd-navbar-nav-wrap"><span></span></button>
                  <!-- RD Navbar Brand-->
                  <div class="rd-navbar-brand"><a class="brand" href="index.html"><img class="brand-logo-dark" src="images/logoC.png" alt="" width="198" height="66"/></a></div>
                </div>
                <div class="rd-navbar-right rd-navbar-nav-wrap">
                  <div class="rd-navbar-aside">
                    <ul class="rd-navbar-contacts-2">
                      <li>
                        <div class="unit unit-spacing-xs">
                          <div class="unit-left"><span class="icon mdi mdi-phone"></span></div>
                          <div class="unit-body"><a class="phone" href="tel:#">+57 3182575587</a></div>
                        </div>
                      </li>
                      <li>
                        <div class="unit unit-spacing-xs">
                          <div class="unit-left"><span class="icon mdi mdi-map-marker"></span></div>
                          <div class="unit-body"><a class="address" href="#">Calle 79b#70A-31</a></div>
                        </div>
                      </li>
                    </ul>
                    <ul class="list-share-2">
                      <li><a class="icon mdi mdi-facebook" href="#"></a></li>
                      <li><a class="icon mdi mdi-twitter" href="#"></a></li>
                      <li><a class="icon mdi mdi-instagram" href="#"></a></li>
                      <li><a class="icon mdi mdi-google-plus" href="#"></a></li>
                    </ul>
                  </div>
                  <div class="rd-navbar-main">
    <!-- RD Navbar Nav-->
    <ul class="rd-navbar-nav">
        <li class="rd-nav-item"><a class="rd-nav-link" href="index.html">Inicio</a></li>
        <li class="rd-nav-item"><a class="rd-nav-link" href="about-us.html">Sobre Nosotros</a></li>
        <li class="rd-nav-item active"><a class="rd-nav-link" href="typography.php">Productos</a></li>
        <li class="rd-nav-item"><a class="rd-nav-link" href="contacts.html">Contactanos</a></li>

        <!-- Elementos de Iniciar Sesión y Carrito -->
        <li class="rd-nav-item nav-actions">
            <div class="nav-item login">
                <a href="iniciarSesion.html" class="btn btn-primary login-button">Iniciar Sesión</a>
            </div>
            <div class="nav-item cart">
                <a href="carrito.php" class="cart-button">
                    <img src="images/carro1.png" width="20" height="20" alt="Carrito"/>
                </a>
            </div>
        </li>
    </ul>
</div>
                    </ul>
                  </div>
                </div>
      </header>
<body>
    <main>
        <div class="productos">
            <?php
            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                // Obtener el producto
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='producto'>
                            <img src='data:image/jpeg;base64," . base64_encode($row['imagen']) . "' alt='{$row['nombre']}' width='100'>
                            <h7><strong>{$row['nombre']}</strong></h7>
                            <p>Precio: $ {$row['precio']} kilo</p>
                            <form method='POST' action=''>
                                <input type='hidden' name='producto_id' value='{$row['id']}'>
                                <input type='hidden' name='nombre' value='{$row['nombre']}'>
                                <input type='hidden' name='precio' value='{$row['precio']}'>
                                <button type='submit' class='agregar-carrito'>Agregar al carrito</button>
                            </form>
                          </div>";
                }
            } else {
                echo "<p>No hay productos disponibles.</p>";
            }
            $conn->close();
            ?>
        </div>
    </main>
    <script src="js/core.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/registro.js"></script>
</body>
</html>