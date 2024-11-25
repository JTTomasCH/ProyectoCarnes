<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'conexion_be.php';

// Verificar la conexión
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

// Lógica para agregar al carrito
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['producto_id'])) {
  // Obtener el correo del usuario desde la sesión
  $correo = $_SESSION['correo'];

  // Obtener el ID del usuario basado en el correo
  $usuarioQuery = "SELECT id, estado_sesion FROM usuarios WHERE correo = ?";
  $usuarioStmt = $conexion->prepare($usuarioQuery);
  $usuarioStmt->bind_param("s", $correo);
  $usuarioStmt->execute();

  // Vincular los resultados a variables
  $usuarioStmt->bind_result($id, $estado_sesion);

  // Verificar si se encontró el usuario
  if ($usuarioStmt->fetch()) {
    // Solo permitir agregar al carrito si el estado de sesión es 1
    if ($estado_sesion == 1) {
      $producto_id = $_POST['producto_id'];
      $nombre = $_POST['nombre'];
      $precio = $_POST['precio'];
      $cantidad = $_POST['cantidad']; // Ahora capturamos la cantidad del formulario

      // Verificar si el carrito está inicializado en la sesión
      if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
      }

      // Agregar el producto al carrito (sesión)
      $producto_encontrado = false;

      // Comprobar si el producto ya está en el carrito
      foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id'] == $producto_id) {
          $item['cantidad'] += $cantidad; // Sumar la nueva cantidad
          $producto_encontrado = true;
          break;
        }
      }

      // Si el producto no se encontró, agregarlo
      if (!$producto_encontrado) {
        $_SESSION['carrito'][] = [
          'id' => $producto_id,
          'nombre' => $nombre,
          'precio' => $precio,
          'cantidad' => $cantidad,
          'imagen' => $_POST['imagen'] // Aquí se agrega la imagen
        ];
      }

      echo "<script>alert('Producto agregado al carrito.');</script>";
    } else {
      echo "<script>alert('No tienes permiso para agregar productos al carrito.');</script>";
    }
  } else {
    echo "<script>alert('No se encontró el usuario.');</script>";
  }
  $usuarioStmt->free_result();
}

// Consulta para obtener productos
$sql = "SELECT id, nombre, precio, imagen FROM productos";

$result = $conexion->query($sql);

if (!isset($_SESSION['nombres']) || !isset($_SESSION['apellidos'])) {
  header("Location: ../NoinicoSesion.html");
  exit();
}
$nombres = $_SESSION['nombres'];
$apellidos = $_SESSION['apellidos'];
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
      
      <li class="rd-nav-item active"><a class="rd-nav-link" href="pr.php">Productos</a></li>
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
        <div class="productos">
          <?php
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<div class='producto'>
            <img src='data:image/jpeg;base64," . base64_encode($row['imagen']) . "' alt='{$row['nombre']}'>
            <h7><strong>{$row['nombre']}</strong></h7>
            <p>Precio: $ {$row['precio']} kilo</p>
            <form method='POST' action=''>
                <input type='hidden' name='producto_id' value='{$row['id']}'>
                <input type='hidden' name='nombre' value='{$row['nombre']}'>
                <input type='hidden' name='precio' value='{$row['precio']}'>
                <div class='cantidad-container'>
                    <button type='button' class='cantidad-btn disminuir'>-</button>
                    <input type='number' name='cantidad' min='1' value='1'>
                    <button type='button' class='cantidad-btn aumentar'>+</button>
                    <input type='hidden' name='imagen' value='" . base64_encode($row['imagen']) . "'> 
                </div>
                <button type='submit' class='agregar-carrito'>Agregar al carrito</button>
            </form>
          </div>";
            }
          } else {
            echo "<p>No hay productos disponibles.</p>";
          }
          ?>

        </div>
      </main>
      <script src="../js/core.min.js"></script>
      <script src="../js/script.js"></script>
      <script src="../js/registro.js"></script>
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          const productos = document.querySelectorAll('.producto');

          productos.forEach(producto => {
            const disminuirBtn = producto.querySelector('.disminuir');
            const aumentarBtn = producto.querySelector('.aumentar');
            const cantidadInput = producto.querySelector('input[type="number"]');

            function actualizarCantidad(valor) {
              let cantidad = parseInt(cantidadInput.value);
              cantidad += valor;

              if (cantidad < 1) cantidad = 1;
              if (cantidad > 99) cantidad = 99;

              cantidadInput.value = cantidad;
            }

            disminuirBtn.addEventListener('click', () => actualizarCantidad(-1));
            aumentarBtn.addEventListener('click', () => actualizarCantidad(1));

            cantidadInput.addEventListener('change', function () {
              let cantidad = parseInt(this.value);

              if (isNaN(cantidad)) {
                cantidad = 1;
              }

              if (cantidad < 1) cantidad = 1;
              if (cantidad > 99) cantidad = 99;

              this.value = cantidad;
            });

            cantidadInput.addEventListener('keypress', function (e) {
              if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
              }
            });
          });
        });
      </script>
    </body>

</html>