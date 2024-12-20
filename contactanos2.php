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
<html class="wide wow-animation" lang="en">
<head>
  <title>CARNES B&R</title>
  <meta name="format-detection" content="telephone=no">
  <meta name="viewport"
    content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta charset="utf-8">
  <link rel="icon" href="images/icono.jpeg" type="image/x-icon">

  <link rel="stylesheet" type="text/css"
    href="//fonts.googleapis.com/css?family=Roboto:100,300,300i,400,500,600,700,900%7CRaleway:500">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/fonts.css">
  <link rel="stylesheet" href="css/style.css">
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
                <div class="rd-navbar-brand"><a class="brand" href="index2.php"><img class="brand-logo-dark"
                      src="logoC.png" alt="" width="198" height="66" /></a></div>
              </div>
              <div class="rd-navbar-right rd-navbar-nav-wrap">
                <div class="rd-navbar-aside">
                  <ul class="rd-navbar-contacts-2">
                    <li>
                      <div class="unit unit-spacing-xs">
                        <div class="unit-left"><span class="icon mdi mdi-phone"></span></div>
                        <div class="unit-body"><a class="phone" href="tel:#">+573182575587</a></div>
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
                    <li><a class=></a></li>
                    <li><a class=></a></li>
                    <li><a class=></a></li>
                    <li><a class=></a></li
                  </ul>
                </div>
    < <div class="rd-navbar-main">
    <ul class="rd-navbar-nav">
      <li class="rd-nav-item "><a class="rd-nav-link" href="index2.php">Inicio</a></li>
      
      <li class="rd-nav-item"><a class="rd-nav-link" href="php/pr.php">Productos</a></li>
      <li class="rd-nav-item active"><a class="rd-nav-link" href="contactanos2.php">Contáctanos</a></li>
      
     <a href="misPedidos.php" class="btn-pedidos">Mis Pedidos</a>
           <li class="rd-nav-item dropdown">
        <button onclick="toggleDropdown()" class="dropbtn">
          Más <span class="arrow-icon" id="arrow"></span>
        </button>
        <div id="myDropdown" class="dropdown-content">
          <a href="about2.php">Sobre Nosotros</a>
          <a href="php/Clientes.php">Nuestros Clientes</a>
        </div>
      </li>
      
      <div class="nav-actions">
        <h5><?php echo htmlspecialchars($nombres); ?></h5>
        <a href="cerarSesion.php" class="login-button">Cerrar Sesión</a>
        <div class="cart-container">
      <a href="cart.php" class="cart-button">
        <img src="images/carro1.png" alt="Carrito"/>
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
      <!-- Breadcrumbs -->
      <section class="bg-gray-7">
        <div class="breadcrumbs-custom box-transform-wrap context-dark">
          <div class="container">
            <h3 class="breadcrumbs-custom-title">Contactanos</h3>
            <div class="breadcrumbs-custom-decor"></div>
          </div>
          <div class="box-transform" style="background-image: url(images/10.jpeg);"></div>
        </div>
        <div class="container">
          <ul class="breadcrumbs-custom-path">
            <li><a href="index2.php">Inicio</a></li>
            <li class="active">Contactanos</li>
          </ul>
        </div>
      </section>
      <!-- Contacts-->
<section class="section section-lg bg-default text-md-left">
    <div class="container">
        <div class="row row-60 justify-content-center">
            <div class="col-lg-8">
                <h4 class="text-spacing-25 text-transform-none">Ponte en contacto</h4>
                <form class="rd-form rd-mailform"  data-form-type="contact" method="post" action="php/guardarContacto.php">
                    <div class="row row-20 gutters-20">
                        <div class="col-md-6">
                            <div class="form-wrap">
                                <input class="form-input" id="contact-your-name" type="text" name="name" data-constraints="@Required">
                                <label class="form-label" for="contact-your-name">Tu nombre*</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-wrap">
                                <input class="form-input" id="contact-email" type="email" name="email" data-constraints="@Email @Required">
                                <label class="form-label" for="contact-email">Tu E-mail*</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-wrap">
                                <select class="form-input" name="service" data-minimum-results-for-search="Infinity" data-constraints="@Required">
                                    <option value="">Selecciona un servicio</option>
                                    <option value="pedidos">Pedidos</option>
                                    <option value="entrega">Entrega</option>
                                    <option value="productos">Productos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-wrap">
                                <input class="form-input" id="contact-phone" type="text" name="phone" data-constraints="@Numeric">
                                <label class="form-label" for="contact-phone">Tu teléfono*</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-wrap">
                                <label class="form-label" for="contact-message">Mensaje</label>
                                <textarea class="form-input textarea-lg" id="contact-message" name="message" data-constraints="@Required"></textarea>
                            </div>
                        </div>
                    </div>
                    <button class="button button-secondary button-winona" type="submit">Contáctanos</button>
                     </form>
   </div>
            <div class="col-lg-4">
              <div class="aside-contacts">
                <div class="row row-30">
                  <div class="col-sm-6 col-lg-12 aside-contacts-item">
                    <p class="aside-contacts-title">Redes Sociales</p>
                    <ul class="list-inline contacts-social-list list-inline-sm">
                      <li><a class="icon mdi mdi-facebook" href="#"></a></li>
                      <li><a class="icon mdi mdi-twitter" href="#"></a></li>
                      <li><a class="icon mdi mdi-instagram" href="#"></a></li>
                      <li><a class="icon mdi mdi-google-plus" href="#"></a></li>
                    </ul>
                  </div>
                  <div class="col-sm-6 col-lg-12 aside-contacts-item">
                    <p class="aside-contacts-title">Telefono</p>
                    <div class="unit unit-spacing-xs justify-content-center justify-content-md-start">
                      <div class="unit-left"><span class="icon mdi mdi-phone"></span></div>
                      <div class="unit-body"><a class="phone" href="tel:#">318 2575587</a></div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-lg-12 aside-contacts-item">
                    <p class="aside-contacts-title">E-mail</p>
                    <div class="unit unit-spacing-xs justify-content-center justify-content-md-start">
                      <div class="unit-left"><span class="icon mdi mdi-email-outline"></span></div>
                      <div class="unit-body"><a class="mail" href="mailto:#">carnesB&R@gmail.com</a></div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-lg-12 aside-contacts-item">
                    <p class="aside-contacts-title">Direcccion</p>
                    <div class="unit unit-spacing-xs justify-content-center justify-content-md-start">
                      <div class="unit-left"><span class="icon mdi mdi-map-marker"></span></div>
                      <div class="unit-body"><a class="d-md-none">Calle 79b#70A-31</a></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

    <!-- Page Footer-->
    <footer class="section footer-modern context-dark footer-modern-2">
      <div class="footer-modern-line">
        <div class="container">
          <div class="row row-50">
            <div class="col-md-6 col-lg-4">
              <h5 class="footer-modern-title oh-desktop"><span class="d-inline-block wow slideInLeft">PRODUCTOS</span></h5>
              <ul class="footer-modern-list d-inline-block d-sm-block wow fadeInUp">
                <li><a href="#">Cortes Premium</a></li>
                <li><a href="#">Cortes Estandar</a></li>
                <li><a href="#">Cortes Organicos</a></li>
                <li><a href="#">Cortes Magros</a></li>
              </ul>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
              <h5 class="footer-modern-title oh-desktop"><span class="d-inline-block wow slideInLeft">NUESTRA EMPRESA</span>
              </h5>
              <ul class="footer-modern-list d-inline-block d-sm-block wow fadeInUp">
                <li><a href="about2.php">Sobre Nosotros</a></li>
                <li><a href="#">Aviso Legal</a></li>
                <li><a href="#">Terminos</a></li>
                <li><a href="#">Pago Seguro</a></li>
                <li><a href="contactanos2.php">Contactanos</a></li>
              </ul>
            </div>
            <div class="col-lg-4 col-xl-5">
              
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-modern-line-2">
        <div class="container">
          <div class="row row-30 align-items-center">
            <div class="col-sm-6 col-md-7 col-lg-4 col-xl-4">
              <div class="row row-30 align-items-center text-lg-center">
                <div class="col-md-7 col-xl-6"><a class="brand" href="index.html"><img
                      src="logoC.png" alt="" width="198" height="66" /></a></div>
                <div class="col-md-5 col-xl-6">
                  <div class="iso-1"><span><img src="images/imagen11.png" alt="" width="58"
                        height="25" /></span><span class="iso-1-big">9.4k</span></div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-12 col-lg-8 col-xl-8 oh-desktop">
              <div class="group-xmd group-sm-justify">
                <div class="footer-modern-contacts wow slideInUp">
                  <div class="unit unit-spacing-sm align-items-center">
                    <div class="unit-left"><span class="icon icon-24 mdi mdi-phone"></span></div>
                    <div class="unit-body"><a class="phone" href="tel:#">+573182575587</a></div>
                  </div>
                </div>
                <div class="footer-modern-contacts wow slideInDown">
                  <div class="unit unit-spacing-sm align-items-center">
                    <div class="unit-left"><span class="icon mdi mdi-email"></span></div>
                    <div class="unit-body"><a class="mail" href="mailto:#">carnesbyr@gmail.com</a></div>
                  </div>
                </div>
                <div class="wow slideInRight">
                  <ul class="list-inline footer-social-list footer-social-list-2 footer-social-list-3">
                    <li><a class="icon mdi mdi-facebook" href="#"></a></li>
                    <li><a class="icon mdi mdi-twitter" href="#"></a></li>
                    <li><a class="icon mdi mdi-instagram" href="#"></a></li>
                    <li><a class="icon mdi mdi-google-plus" href="#"></a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-modern-line-3">
        <div class="container">
          <div class="row row-10 justify-content-between">
            <div class="col-md-6"><span>Calle 79b#70A-31<span></div>
            <div class="col-md-auto">
              <!-- Rights-->
              <p class="rights"><span>&copy;&nbsp;</span><span
                  class="copyright-year"></span><span></span><span>.&nbsp;</span><span>All Rights Reserved.</span><span>
                  Design&nbsp;by&nbsp;<a href="https://www.templatemonster.com">TemplateMonster</a></span></p>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- Global Mailform Output-->
    <div class="snackbars" id="form-output-global"></div>
    <!-- Javascript-->
    <script src="js/core.min.js"></script>
    <script src="js/script.js"></script>
  </body>
</html>