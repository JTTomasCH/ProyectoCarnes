<?php
session_start();

if (!isset($_SESSION['nombres']) || !isset($_SESSION['apellidos'])) {
    // Si no ha iniciado sesión, redirigir al inicio de sesión
    header("Location: ../NoinicoSesion.html");
    exit();
}
$nombres = $_SESSION['nombres'];
$apellidos = $_SESSION['apellidos'];

// Inicialización segura del carrito
if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

include 'conexion_be.php';

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Inicializar variables
$total = 0;
$total_cantidad = 0;
$costo_envio = 0;
$total_final = 0;

// Calcular totales de manera segura
if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $producto) {
        if (isset($producto['cantidad']) && isset($producto['precio'])) {
            $total_cantidad += $producto['cantidad'];
            $total += $producto['cantidad'] * $producto['precio'];
        }
    }

    if ($total_cantidad >= 7) {
        $costo_envio = 0;
    } else {
        $costo_envio = 30000;
    }

    $total_final = $total + $costo_envio;
}

// Finalización de compra
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar_compra']) && !empty($_SESSION['carrito'])) {
    if (isset($_SESSION['correo'])) {
        $correo = $_SESSION['correo'];
        $usuarioQuery = "SELECT id FROM usuarios WHERE correo = ?";
        $usuarioStmt = $conexion->prepare($usuarioQuery);
        $usuarioStmt->bind_param("s", $correo);
        $usuarioStmt->execute();

        // Usamos bind_result en lugar de get_result
        $usuarioStmt->bind_result($usuario_id);

        if ($usuarioStmt->fetch()) {
            // Cerramos el statement del usuario antes de proceder
            $usuarioStmt->close();

            $stmt = $conexion->prepare("INSERT INTO Pedido (id_cliente, fecha) VALUES (?, NOW())");
            $stmt->bind_param("i", $usuario_id);

            if ($stmt->execute()) {
                $id_pedido = $stmt->insert_id;
                $_SESSION['id_pedido'] = $id_pedido;

                $stmt_detalle = $conexion->prepare("INSERT INTO DetallePedido (cantidad, total, id_producto, id_pedido, id_usuarios) VALUES (?, ?, ?, ?, ?)");
                foreach ($_SESSION['carrito'] as $producto) {
                    $cantidad = $producto['cantidad'];
                    $precio_unitario = $producto['precio'];
                    $subtotal = $cantidad * $precio_unitario;
                    $id_producto = $producto['id'];
                    $stmt_detalle->bind_param("idiii", $cantidad, $subtotal, $id_producto, $id_pedido, $usuario_id);
                    $stmt_detalle->execute();
                }

                unset($_SESSION['carrito']);
                $_SESSION['carrito'] = [];
                header("Location: ../pago.php?pedido_id=" . $id_pedido);
                exit();
            } else {
                echo "Error al finalizar la compra.";
            }

            $stmt->close();
            $stmt_detalle->close();
        } else {
            // Cerramos el statement del usuario si no se encuentra el usuario
            $usuarioStmt->close();
        }
    }
}

// Eliminar productos del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $index = $_POST['eliminar_id'];
    if (isset($_SESSION['carrito'][$index])) {
        unset($_SESSION['carrito'][$index]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
}

// Actualizar cantidad
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id']) && isset($_POST['cantidad'])) {
    $index = $_POST['update_id'];
    $nueva_cantidad = (int) $_POST['cantidad'];

    if (isset($_SESSION['carrito'][$index]) && $nueva_cantidad > 0 && $nueva_cantidad <= 99) {
        $_SESSION['carrito'][$index]['cantidad'] = $nueva_cantidad;
    }
}

$conexion->close();
?>
<!DOCTYPE html>
<html class="wide wow-animation" lang="en">

<head>
  <title>Productos</title>
  <meta name="format-detection" content="telephone=no">
  <meta name="viewport"
    content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta charset="utf-8">
  <link rel="icon" href="images/icono.jpeg" type="image/x-icon">
  <!-- Stylesheets-->
  <link rel="stylesheet" type="text/css"
    href="//fonts.googleapis.com/css?family=Roboto:100,300,300i,400,500,600,700,900%7CRaleway:500">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link rel="stylesheet" href="../css/fonts.css">
  <link rel="stylesheet" href="../css/estilos.css">
   <style>
/* Estilos mejorados para el menú de navegación */
.rd-navbar-nav {
  display: flex;
  align-items: center;
  gap: 30px;
  padding: 0;
  margin: 0;
  list-style: none;
}

.rd-nav-item {
  position: relative;
  padding: 0;
  margin: 0;
}

.rd-nav-link {
  position: relative;
  padding: 8px 15px;
  font-size: 16px;
  font-weight: 500;
  letter-spacing: 0.02em;
  text-transform: uppercase;
  color: #151515;
  transition: all 0.3s ease;
}

.rd-nav-link:hover {
  color: #ff4c4c;
}

/* Estilos para el dropdown */
.dropdown {
  position: relative;
}

.dropbtn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 15px;
  font-size: 16px;
  font-weight: 500;
  letter-spacing: 0.02em;
  text-transform: uppercase;
  color: #151515;
  background: none;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.dropbtn:hover {
  color: #ff4c4c;
}

.arrow-icon {
  width: 8px;
  height: 8px;
  border-left: 2px solid currentColor;
  border-bottom: 2px solid currentColor;
  transform: rotate(-45deg);
  transition: transform 0.3s ease;
  margin-top: -4px;
}

.arrow-rotate {
  transform: rotate(135deg);
  margin-top: 2px;
}

.dropdown-content {
  display: none;
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  min-width: 200px;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
  padding: 8px 0;
  z-index: 1000;
  opacity: 0;
  transition: opacity 0.3s ease;
  margin-top: 15px;
}

.dropdown-content::before {
  content: '';
  position: absolute;
  top: -8px;
  left: 50%;
  transform: translateX(-50%);
  border-left: 8px solid transparent;
  border-right: 8px solid transparent;
  border-bottom: 8px solid #ffffff;
}

.dropdown-content a {
  display: block;
  padding: 12px 24px;
  color: #151515;
  text-decoration: none;
  font-size: 15px;
  font-weight: 400;
  transition: all 0.3s ease;
  text-align: left;
  white-space: nowrap;
}

.dropdown-content a:hover {
  background-color: #f8f9fa;
  color: #ff4c4c;
  padding-left: 28px;
}

.show {
  display: block;
  opacity: 1;
}

/* Estilos mejorados para las acciones de navegación */
.nav-actions {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-left: 30px;
  padding-left: 30px;
  border-left: 1px solid rgba(21, 21, 21, 0.1);
}

.nav-actions h5 {
  margin: 0;
  font-size: 15px;
  font-weight: 500;
  color: #151515;
}

.login-button {
  padding: 10px 24px;
  border-radius: 6px;
  font-size: 15px;
  font-weight: 500;
  text-decoration: none;
  color: white;
  background-color: #ff4c4c;
  transition: all 0.3s ease;
  border: none;
  text-transform: uppercase;
  letter-spacing: 0.02em;
}

.login-button:hover {
  background-color: #ff3333;
  transform: translateY(-1px);
  box-shadow: 0 4px 15px rgba(255, 76, 76, 0.2);
}

/* Estilos actualizados para el carrito */
.cart-container {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.cart-button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background-color: #f8f9fa;
  border-radius: 8px;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
  padding: 8px;
}

.cart-button:hover {
  background-color: #ff4c4c;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(255, 76, 76, 0.2);
}

.cart-button img {
  width: 24px;
  height: 24px;
  object-fit: contain;
  transition: filter 0.3s ease;
  margin: auto;
}

.cart-button:hover img {
  filter: brightness(0) invert(1);
}

.cart-count {
  position: absolute;
  top: -8px;
  right: -8px;
  background-color: #ff4c4c;
  color: white;
  font-size: 12px;
  font-weight: 600;
  min-width: 20px;
  height: 20px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 6px;
  border: 2px solid white;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Estilos para el botón de Mis Pedidos */
.btn-pedidos {
  padding: 10px 24px;
  border-radius: 6px;
  font-size: 15px;
  font-weight: 500;
  text-decoration: none;
  color: #151515;
  background-color: #f8f9fa;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.02em;
}

.btn-pedidos:hover {
  background-color: #ff4c4c;
  color: white;
  transform: translateY(-1px);
  box-shadow: 0 4px 15px rgba(255, 76, 76, 0.2);
}

/* Asegurarse que el contenedor de navegación tenga el z-index correcto */
.rd-navbar-main {
  position: relative;
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 2rem;
}

/* Media queries para responsividad */
@media (max-width: 1200px) {
  .rd-navbar-main {
    padding: 0 1rem;
  }
  
  .nav-actions {
    gap: 1rem;
    margin-left: 1rem;
    padding-left: 1rem;
  }
  
  .login-button,
  .btn-pedidos {
    padding: 8px 16px;
    font-size: 14px;
  }
  
  .cart-button {
    width: 36px;
    height: 36px;
  }
}

@media (max-width: 992px) {
  .rd-navbar-nav {
    gap: 15px;
  }
  
  .nav-actions {
    gap: 10px;
    margin-left: 15px;
    padding-left: 15px;
  }
  
  .nav-actions h5 {
    font-size: 14px;
  }
}

@media (max-width: 768px) {
  .rd-navbar-main {
    flex-wrap: wrap;
    justify-content: center;
    padding: 10px;
  }
  
  .rd-navbar-nav {
    margin-bottom: 10px;
  }
  
  .nav-actions {
    width: 100%;
    justify-content: center;
    margin: 0;
    padding: 10px 0;
    border-left: none;
    border-top: 1px solid rgba(21, 21, 21, 0.1);
  }
}
  </style>
  <style>


    .productos {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 5px;
      justify-content: center;
      padding: 20px;
    }

    .productos {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      justify-content: center;
      padding: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .producto {
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      padding: 15px;
      text-align: center;
      transition: transform 0.2s ease-in-out;
      display: flex;
      flex-direction: column;
      align-items: center;
      background-color: white;
    }

    .producto:hover {
      transform: scale(1.02);
    }

    .producto img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      margin-bottom: 10px;
    }

    .producto h7 {
      font-size: 1.1rem;
      margin: 10px 0;
      color: #333;
    }

    .producto p {
      margin: 8px 0;
      color: #666;
    }

    .producto form {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      width: 100%;
    }

    .cantidad-container {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
      margin: 10px 0;
    }

    input[type="number"] {
      width: 60px;
      height: 30px;
      text-align: center;
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 0 5px;
      font-size: 0.9rem;
    }

    .cantidad-btn {
      background-color: #ff4c4c;
      color: white;
      border: none;
      border-radius: 4px;
      width: 30px;
      height: 30px;
      cursor: pointer;
      font-size: 1.2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background-color 0.3s;
    }

    .cantidad-btn:hover {
      background-color: #ff3333;
    }

    .agregar-carrito {
      background-color: #ff4c4c;
      color: white;
      padding: 8px 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
      width: 80%;
      font-size: 0.9rem;
      margin-top: 10px;
    }

    .agregar-carrito:hover {
      background-color: #ff3333;
    }

    @media (max-width: 1200px) {
      .productos {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    @media (max-width: 768px) {
      .productos {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 480px) {
      .productos {
        grid-template-columns: 1fr;
      }
    }

    .agregar-carrito {
      background-color: #ff4c4c;
      color: white;
      padding: 8px 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .agregar-carrito:hover {
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

    @media (max-width: 768px) {
      .rd-navbar-nav {
        flex-direction: column;
      }

      .nav-actions {
        margin-left: 0;
        margin-top: 10px;
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
        <nav class="rd-navbar rd-navbar-modern" data-layout="rd-navbar-fixed" data-sm-layout="rd-navbar-fixed"
          data-md-layout="rd-navbar-fixed" data-md-device-layout="rd-navbar-fixed" data-lg-layout="rd-navbar-static"
          data-lg-device-layout="rd-navbar-fixed" data-xl-layout="rd-navbar-static"
          data-xl-device-layout="rd-navbar-static" data-xxl-layout="rd-navbar-static"
          data-xxl-device-layout="rd-navbar-static" data-lg-stick-up-offset="56px" data-xl-stick-up-offset="56px"
          data-xxl-stick-up-offset="56px" data-lg-stick-up="true" data-xl-stick-up="true" data-xxl-stick-up="true">
          <div class="rd-navbar-inner-outer">
            <div class="rd-navbar-inner">
              <!-- RD Navbar Panel-->
              <div class="rd-navbar-panel">
                <!-- RD Navbar Toggle-->
                <button class="rd-navbar-toggle" data-rd-navbar-toggle=".rd-navbar-nav-wrap"><span></span></button>
                <!-- RD Navbar Brand-->
                <div class="rd-navbar-brand"><a class="brand" href="../index.html"><img class="brand-logo-dark"
                      src="../images/logoC.png" alt="" width="198" height="66" /></a></div>
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
                < <div class="rd-navbar-main">
    <ul class="rd-navbar-nav">
      <li class="rd-nav-item "><a class="rd-nav-link" href="../index2.php">Inicio</a></li>
      
      <li class="rd-nav-item "><a class="rd-nav-link" href="pr.php">Productos</a></li>
      <li class="rd-nav-item"><a class="rd-nav-link" href="../contactanos2.php">Contáctanos</a></li>
       <a href="../misPedidos.php" class="btn-pedidos">Mis Pedidos</a>
           <li class="rd-nav-item dropdown">
        <button onclick="toggleDropdown()" class="dropbtn">
          Más <span class="arrow-icon" id="arrow"></span>
        </button>
        <div id="myDropdown" class="dropdown-content">
          <a href="../about2.php">Sobre Nosotros</a>
          <a href="Clientes.php">Nuestros Clientes</a>
        </div>
      </li>
      
      <div class="nav-actions">
        <h5><?php echo htmlspecialchars($nombres); ?></h5>
        <a href="../cerarSesion.php" class="login-button">Cerrar Sesión</a>
        <div class="cart-container">
      <a href="cart.php" class="cart-button">
        <img src="../images/carro1.png" alt="Carrito"/>
      </a>
      </div>
    </ul>
  </div>
    </header>
     <style>
    /* General styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
    color: #333;
    line-height: 1.6;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

/* Cart section styles */
.cart-section {
    padding: 20px;
}

.cart-section h2 {
    font-size: 2rem;
    color: #2c3e50;
    margin-bottom: 30px;
    border-bottom: 2px solid #e74c3c;
    padding-bottom: 10px;
}

.cart-item {
    background-color: white;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    transition: transform 0.2s;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.product-details {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 15px;
}



.product-info {
    flex-grow: 1;
}

.product-info p {
    margin: 5px 0;
}

.product-info strong {
    font-size: 1.1em;
    color: #2c3e50;
}

/* Quantity controls */
.quantity-control {
    display: flex;
    align-items: center;
    gap: 10px;
}

.quantity-btn {
    background-color: #e74c3c;
    color: white;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.2em;
    transition: background-color 0.2s;
}

.quantity-btn:hover {
    background-color: #c0392b;
}

.quantity-input {
    width: 50px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
}

/* Summary section styles */
.summary-section {
    background-color: #f8f9fa;
    padding: 25px;
    border-radius: 8px;
    position: sticky;
    top: 20px;
}

.summary-section h3 {
    color: #2c3e50;
    margin-bottom: 20px;
    font-size: 1.5rem;
    border-bottom: 2px solid #e74c3c;
    padding-bottom: 10px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.total {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 2px solid #2c3e50;
    font-weight: bold;
    font-size: 1.2em;
    color: #2c3e50;
}

/* Buttons */
.checkout-btn {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 25px;
    width: 100%;
    font-size: 1.1em;
    cursor: pointer;
    transition: background-color 0.2s;
    margin-top: 20px;
}

.checkout-btn:hover {
    background-color: #c0392b;
}

.continue-shopping-btn {
    display: inline-block;
    text-decoration: none;
    color: #2c3e50;
    padding: 12px 25px;
    border: 2px solid #2c3e50;
    border-radius: 25px;
    margin-top: 30px;
    transition: all 0.2s;
    text-align: center;
}

.continue-shopping-btn:hover {
    background-color: #2c3e50;
    color: white;
}

.delete-btn {
    background-color: #e74c3c;
    color: white;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.2em;
    transition: background-color 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

.delete-btn:hover {
    background-color: #c0392b;
}

/* Empty cart message */
.cart-section p:empty + p {
    text-align: center;
    padding: 40px;
    font-size: 1.2em;
    color: #7f8c8d;
    background-color: #f8f9fa;
    border-radius: 8px;
    margin-top: 20px;
}

/* Responsive design */
@media (max-width: 768px) {
    .container {
        grid-template-columns: 1fr;
    }
    
    .product-details {
        flex-direction: column;
        text-align: center;
    }
    
    .quantity-control {
        justify-content: center;
    }
}
  </style>
  <style>
/* Estilos mejorados para el menú de navegación */
.rd-navbar-nav {
  display: flex;
  align-items: center;
  gap: 30px;
  padding: 0;
  margin: 0;
  list-style: none;
}

.rd-nav-item {
  position: relative;
  padding: 0;
  margin: 0;
}

.rd-nav-link {
  position: relative;
  padding: 8px 15px;
  font-size: 16px;
  font-weight: 500;
  letter-spacing: 0.02em;
  text-transform: uppercase;
  color: #151515;
  transition: all 0.3s ease;
}

.rd-nav-link:hover {
  color: #ff4c4c;
}

/* Estilos para el dropdown */
.dropdown {
  position: relative;
}

.dropbtn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 15px;
  font-size: 16px;
  font-weight: 500;
  letter-spacing: 0.02em;
  text-transform: uppercase;
  color: #151515;
  background: none;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.dropbtn:hover {
  color: #ff4c4c;
}

.arrow-icon {
  width: 8px;
  height: 8px;
  border-left: 2px solid currentColor;
  border-bottom: 2px solid currentColor;
  transform: rotate(-45deg);
  transition: transform 0.3s ease;
  margin-top: -4px;
}

.arrow-rotate {
  transform: rotate(135deg);
  margin-top: 2px;
}

.dropdown-content {
  display: none;
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  min-width: 200px;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
  padding: 8px 0;
  z-index: 1000;
  opacity: 0;
  transition: opacity 0.3s ease;
  margin-top: 15px;
}

.dropdown-content::before {
  content: '';
  position: absolute;
  top: -8px;
  left: 50%;
  transform: translateX(-50%);
  border-left: 8px solid transparent;
  border-right: 8px solid transparent;
  border-bottom: 8px solid #ffffff;
}

.dropdown-content a {
  display: block;
  padding: 12px 24px;
  color: #151515;
  text-decoration: none;
  font-size: 15px;
  font-weight: 400;
  transition: all 0.3s ease;
  text-align: left;
  white-space: nowrap;
}

.dropdown-content a:hover {
  background-color: #f8f9fa;
  color: #ff4c4c;
  padding-left: 28px;
}

.show {
  display: block;
  opacity: 1;
}

/* Estilos mejorados para las acciones de navegación */
.nav-actions {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-left: 30px;
  padding-left: 30px;
  border-left: 1px solid rgba(21, 21, 21, 0.1);
}

.nav-actions h5 {
  margin: 0;
  font-size: 15px;
  font-weight: 500;
  color: #151515;
}

.login-button {
  padding: 10px 24px;
  border-radius: 6px;
  font-size: 15px;
  font-weight: 500;
  text-decoration: none;
  color: white;
  background-color: #ff4c4c;
  transition: all 0.3s ease;
  border: none;
  text-transform: uppercase;
  letter-spacing: 0.02em;
}

.login-button:hover {
  background-color: #ff3333;
  transform: translateY(-1px);
  box-shadow: 0 4px 15px rgba(255, 76, 76, 0.2);
}

/* Estilos actualizados para el carrito */
.cart-container {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.cart-button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background-color: #f8f9fa;
  border-radius: 8px;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
  padding: 8px;
}

.cart-button:hover {
  background-color: #ff4c4c;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(255, 76, 76, 0.2);
}

.cart-button img {
  width: 24px;
  height: 24px;
  object-fit: contain;
  transition: filter 0.3s ease;
  margin: auto;
}

.cart-button:hover img {
  filter: brightness(0) invert(1);
}

.cart-count {
  position: absolute;
  top: -8px;
  right: -8px;
  background-color: #ff4c4c;
  color: white;
  font-size: 12px;
  font-weight: 600;
  min-width: 20px;
  height: 20px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 6px;
  border: 2px solid white;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Estilos para el botón de Mis Pedidos */
.btn-pedidos {
  padding: 10px 24px;
  border-radius: 6px;
  font-size: 15px;
  font-weight: 500;
  text-decoration: none;
  color: #151515;
  background-color: #f8f9fa;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.02em;
}

.btn-pedidos:hover {
  background-color: #ff4c4c;
  color: white;
  transform: translateY(-1px);
  box-shadow: 0 4px 15px rgba(255, 76, 76, 0.2);
}

/* Asegurarse que el contenedor de navegación tenga el z-index correcto */
.rd-navbar-main {
  position: relative;
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 2rem;
}

/* Media queries para responsividad */
@media (max-width: 1200px) {
  .rd-navbar-main {
    padding: 0 1rem;
  }
  
  .nav-actions {
    gap: 1rem;
    margin-left: 1rem;
    padding-left: 1rem;
  }
  
  .login-button,
  .btn-pedidos {
    padding: 8px 16px;
    font-size: 14px;
  }
  
  .cart-button {
    width: 36px;
    height: 36px;
  }
}

@media (max-width: 992px) {
  .rd-navbar-nav {
    gap: 15px;
  }
  
  .nav-actions {
    gap: 10px;
    margin-left: 15px;
    padding-left: 15px;
  }
  
  .nav-actions h5 {
    font-size: 14px;
  }
}

@media (max-width: 768px) {
  .rd-navbar-main {
    flex-wrap: wrap;
    justify-content: center;
    padding: 10px;
  }
  
  .rd-navbar-nav {
    margin-bottom: 10px;
  }
  
  .nav-actions {
    width: 100%;
    justify-content: center;
    margin: 0;
    padding: 10px 0;
    border-left: none;
    border-top: 1px solid rgba(21, 21, 21, 0.1);
  }
}
  </style>
    <script>
    function toggleDropdown() {
      document.getElementById("myDropdown").classList.toggle("show");
      document.getElementById("arrow").classList.toggle("arrow-rotate");
    }

    // Cerrar el dropdown si el usuario hace clic fuera de él
    window.onclick = function(event) {
      if (!event.target.matches('.dropbtn') && !event.target.matches('.arrow-icon')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var arrows = document.getElementsByClassName("arrow-icon");
        
        for (var i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
        
        for (var i = 0; i < arrows.length; i++) {
          var arrow = arrows[i];
          if (arrow.classList.contains('arrow-rotate')) {
            arrow.classList.remove('arrow-rotate');
          }
        }
      }
    }
  </script>
   <body>
      <main>
        
        <div class="container">
            <div class="cart-section">
                <h2>Mi Carrito</h2>
                <?php if (!empty($_SESSION['carrito'])): ?>
                    <?php foreach ($_SESSION['carrito'] as $index => $producto): ?>
                        <?php
                        $subtotal = $producto['precio'] * $producto['cantidad'];
                        ?>
                        <div class="cart-item">
                            <div class="product-details">
                                <div class="carrito">
                                    <img src="data:image/jpeg;base64,<?php echo $producto['imagen']; ?>" 
                                         alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                                         style="width: 100px; height: auto;">
                                </div>
                                <div class="product-info">
                                    <p><strong><?php echo htmlspecialchars($producto['nombre']); ?></strong></p>
                                    <p>Unidad: $<?php echo number_format($producto['precio'], 2); ?></p>
                                </div>
                                <div class="quantity-control">
                                    <form method="POST" action="" class="quantity-form">
                                        <input type="hidden" name="update_id" value="<?php echo $index; ?>">
                                        <button type="button" class="quantity-btn minus">-</button>
                                        <input type="number" name="cantidad" value="<?php echo $producto['cantidad']; ?>" 
                                               min="1" max="99" class="quantity-input" readonly>
                                        <button type="button" class="quantity-btn plus">+</button>
                                    </form>
                                </div>
                            </div>
                            <p>Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
                            <form method="POST" action="" style="margin-left: 10px;" onsubmit="event.preventDefault(); eliminarProducto(<?php echo $index; ?>);">
                           <button type="submit" class="delete-btn">&times;</button>
                           </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>El carrito está vacío.</p>
                <?php endif; ?>
            </div>

            <div class="summary-section">
                <h3>Resumen del pedido</h3>
                <div class="summary-item">
                    <span>Subtotal</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                 <div class="summary-item">
                    <span>Costo del envio</span>
                    <span>$<?php echo number_format($costo_envio, 2); ?></span>
                </div>
                <div class="total">
                    <span>Total</span>
                    <span>$<?php echo number_format($total_final, 2); ?></span>
                </div>

                <?php if (!empty($_SESSION['carrito'])): ?>
                    <form method="POST" action="">
                        <button type="submit" name="finalizar_compra" class="checkout-btn">Finalizar compra</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <a href="pr.php" class="continue-shopping-btn">Seguir comprando</a>
    </div>
    <script>
    function eliminarProducto(index) {
        const formData = new FormData();
        formData.append('eliminar_id', index);

        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Recargar la sección del carrito
            window.location.reload();
        })
        .catch(error => console.error('Error al eliminar el producto:', error));
    }
</script>

    <script src="../js/core.min.js"></script>
    <script src="../js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityForms = document.querySelectorAll('.quantity-form');

            quantityForms.forEach(form => {
                const minusBtn = form.querySelector('.minus');
                const plusBtn = form.querySelector('.plus');
                const input = form.querySelector('.quantity-input');

                minusBtn.addEventListener('click', () => {
                    let value = parseInt(input.value);
                    if (value > 1) {
                        input.value = value - 1;
                        updateQuantity(form);
                    }
                });

                plusBtn.addEventListener('click', () => {
                    let value = parseInt(input.value);
                    if (value < 99) {
                        input.value = value + 1;
                        updateQuantity(form);
                    }
                });
            });

            function updateQuantity(form) {
                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    </script>
</body>
</html>