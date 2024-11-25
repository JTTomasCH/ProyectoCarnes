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
    <title>Sobre Nosotros</title>
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
                                    <img class="brand-logo-dark" src="logoC.png" alt="" width="198" height="66" />
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
                                    <li class="rd-nav-item "><a class="rd-nav-link" href="index2.php">Inicio</a>
                                    </li>
                                    <li class="rd-nav-item "><a class="rd-nav-link" href="about2.php">Sobre Nosotros
                                        </a></li>
                                    <li class="rd-nav-item"><a class="rd-nav-link" href="php/pr.php">Productos</a>
                                    </li>
                                    <li class="rd-nav-item active"><a class="rd-nav-link" href="../contactanos2.php">Contáctanos</a>
                                    </li>
                                    <li class="rd-nav-item"><a class="rd-nav-link" href="php/Clientes.php">Nuestros
                                            Clientes</a></li>
                                    <div class="nav-actions">
                                        <h5><?php echo htmlspecialchars($nombres); ?></h5>
                                        <a href="iniciarSesion.html" class="btn btn-primary login-button">Cerrar
                                            Sesión</a>
                                        <a href="php/cart.php" class="cart-button">
                                            <img src="images/carro1.png" width="20" height="20" alt="Carrito" />
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
            <li><a href="index.html">Inicio</a></li>
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