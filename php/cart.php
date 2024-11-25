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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="images/icono.jpeg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:100,300,300i,400,500,600,700,900%7CRaleway:500">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="../css/fonts.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <title>Carrito de Compras</title>
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
      padding: 20px 16px;
      border-radius: 4px;
      text-decoration: none;
      color: white;
      background-color: #ff4c4c;
      transition: background-color 0.3s;
    }

    .cart-button img {
      max-width: 100%;
      max-height: 100%;
    }

    .cart-button:hover {
      background-color: #ffab6a;
      color: rgb(11, 11, 11);
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
      background-color: #ffab6a;
      color: rgb(15, 15, 15);
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

    /* Estilos básicos de la página */
    .container {
      max-width: 1000px;
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      display: flex;
      gap: 20px;
    }

    /* Sección de carrito */
    .cart-section {
      flex: 2;
    }

    .cart-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ddd;
      padding: 15px 0;
    }

    .cart-item img {
      width: 70px;
      height: 70px;
      border-radius: 5px;
      margin-right: 15px;
    }

    .product-details {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .product-info {
      display: flex;
      flex-direction: column;
    }

    .quantity-control {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .quantity-control input {
      width: 40px;
      text-align: center;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .delete-btn {
      cursor: pointer;
      color: #ff4c4c;
      font-size: 18px;
    }

    /* Sección de resumen */
    .summary-section {
      flex: 1;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
    }

    .summary-section h3 {
      margin-bottom: 20px;
      color: #333;
    }

    .summary-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      font-size: 16px;
      color: #555;
    }

    .total {
      font-weight: bold;
      font-size: 18px;
      color: #333;
      margin-top: 20px;
    }

    .checkout-btn {
      display: block;
      width: 100%;
      padding: 10px;
      margin-top: 20px;
      text-align: center;
      background-color: #ff4c4c;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .checkout-btn:hover {
      background-color: #ff3333;
    }


    .continue-shopping-btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      font-size: 16px;
      font-weight: bold;
      color: #ff4c4c;
      background-color: transparent;
      border: 2px solid #ff4c4c;
      border-radius: 4px;
      text-decoration: none;
      text-align: center;
      transition: background-color 0.3s, color 0.3s;
    }

    /* Efecto hover */
    .continue-shopping-btn:hover {
      background-color: #ff4c4c;
      color: white;
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
    </div>
    <div class="page">
       <!-- Page Header-->
    <header class="section page-header">
        <!-- RD Navbar-->
        <div class="rd-navbar-wrap">
            <nav class="rd-navbar rd-navbar-modern">
                <div class="rd-navbar-inner-outer">
                    <div class="rd-navbar-inner">
                        <!-- RD Navbar Panel-->
                        <div class="rd-navbar-panel">
                            <!-- RD Navbar Toggle-->
                            <button class="rd-navbar-toggle"
                                data-rd-navbar-toggle=".rd-navbar-nav-wrap"><span></span></button>
                            <!-- RD Navbar Brand-->
                            <div class="rd-navbar-brand">
                                <a class="brand" href="../index2.php">
                                    <img class="brand-logo-dark" src="../logoC.png" alt="" width="198" height="66" />
                                </a>
                            </div>
                        </div>
                        <div class="rd-navbar-right rd-navbar-nav-wrap">
                            <div class="rd-navbar-aside">
                                <ul class="rd-navbar-contacts-2">
                                    <!-- Información de contacto y redes sociales -->
                                </ul>
                                <ul class="list-share-2">
                                    <!-- Redes sociales -->
                                </ul>
                            </div>
                            <div class="rd-navbar-main">
                                <!-- RD Navbar Nav-->
                                <ul class="rd-navbar-nav">
                                    <li class="rd-nav-item active"><a class="rd-nav-link" href="../index2.php">Inicio</a>
                                    </li>
                                    <li class="rd-nav-item"><a class="rd-nav-link" href="../about2.php">Sobre Nosotros
                                        </a></li>
                                    <li class="rd-nav-item"><a class="rd-nav-link" href="pr.php">Productos</a>
                                    </li>
                                    <li class="rd-nav-item"><a class="rd-nav-link" href="../contactanos2.php">Contáctanos</a>
                                    </li>
                                    <li class="rd-nav-item"><a class="rd-nav-link" href="../Clientes.html">Nuestros
                                            Clientes</a></li>
                                    <div class="nav-actions">
                                         <h5><?php echo htmlspecialchars($nombres . ' ' . $apellidos); ?></h5>
                                        <a href="../iniciarSesion.html" class="btn btn-primary login-button">Cerrar
                                            Sesión</a>
                                        <a href="../iniciarSesion.html" class="cart-button">
                                            <img src="../images/carro1.png" width="20" height="20" alt="Carrito" />
                                        </a>
                                    </div>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
        
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