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
                                    <li class="rd-nav-item active"><a class="rd-nav-link" href="about2.php">Sobre Nosotros
                                        </a></li>
                                    <li class="rd-nav-item"><a class="rd-nav-link" href="php/pr.php">Productos</a>
                                    </li>
                                    <li class="rd-nav-item"><a class="rd-nav-link" href="../contactanos2.php">Contáctanos</a>
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

      <!-- parallax top-->
      <!-- Breadcrumbs -->
      <section class="bg-gray-7">
        <div class="breadcrumbs-custom box-transform-wrap context-dark">
          <div class="container">
            <h3 class="breadcrumbs-custom-title">Sobre Nosotros</h3>
            <div class="breadcrumbs-custom-decor"></div>
          </div>
          <div class="box-transform" style="background-image: url(images/1.png);"></div>
        </div>
        <div class="container">
          <ul class="breadcrumbs-custom-path">
            <li><a href="index2.html">Home</a></li>
            <li class="active">Sobre Nosotros</li>
          </ul>
        </div>
      </section>
      <section class="section section-lg bg-default">
        <div class="container">
          <div class="tabs-custom row row-50 justify-content-center flex-lg-row-reverse text-center text-md-left" id="tabs-4">
            <div class="col-lg-4 col-xl-3">
              <h5 class="text-spacing-200 text-capitalize">10+ años de experiencia</h5>
              <ul class="nav list-category list-category-down-md-inline-block">
                <li class="list-category-item wow fadeInRight" role="presentation" data-wow-delay="0s"><a class="active" href="#tabs-4-1" data-toggle="tab">SOBRE NOSOTROS</a></li>
                <li class="list-category-item wow fadeInRight" role="presentation" data-wow-delay=".1s"><a href="#tabs-4-2" data-toggle="tab">NUESTRA MISION</a></li>
                <li class="list-category-item wow fadeInRight" role="presentation" data-wow-delay=".2s"><a href="#tabs-4-3" data-toggle="tab">NUESTRA META</a></li>
                <li class="list-category-item wow fadeInRight" role="presentation" data-wow-delay=".3s"><a href="#tabs-4-4" data-toggle="tab">NUESTROS VALORES</a></li>
              </ul><a class="button button-xl button-primary button-winona" href="contactanos2.html">Contact us</a>
            </div>
            <div class="col-lg-8 col-xl-9">
              <!-- Tab panes-->
              <div class="tab-content tab-content-1">
                <div class="tab-pane fade show active" id="tabs-4-1">
                  <h4>UNAS PEQUEÑAS PALABRAS SOBRE NOSOTROS</h4>
                  <p>En Carnes B&R, nos apasiona ofrecer lo mejor de la carne para que cada comida sea una celebración. Con más de 10 años de experiencia en el sector, seleccionamos cuidadosamente nuestros productos, priorizando la calidad y la sostenibilidad.</p>
                  <p>Nuestro compromiso es brindar cortes frescos y deliciosos, provenientes de ganaderos que comparten nuestra filosofía de respeto hacia el animal y el medio ambiente. Creemos que cada bocado cuenta, y trabajamos con esmero para que nuestros clientes disfruten de una experiencia gastronómica inigualable.</p><img src="images/2.png" alt="" width="835" height="418"/>
                  <p> Gracias por elegir Carnes B y R. ¡Esperamos ser parte de tus mejores momentos en la cocina!</p>
                </div>
                <div class="tab-pane fade" id="tabs-4-2">
                  <h4>OFRECE LAS MEJORES CARNES</h4>
                  <p>Nuestra misión es proveer carnes frescas y de alta calidad directamente a nuestros clientes, ofreciendo un servicio de pedidos personalizado que se adapte a sus necesidades y preferencias. Nos comprometemos a garantizar que cada producto cumpla con los más altos estándares de frescura y calidad, seleccionando cuidadosamente nuestras carnes de proveedores de confianza que practican métodos sostenibles y éticos.</p>
                  <p>A través de nuestra plataforma digital intuitiva, buscamos facilitar una experiencia de compra conveniente y sin complicaciones, donde cada cliente pueda explorar una variedad de cortes y opciones, recibir recomendaciones basadas en sus gustos culinarios y realizar pedidos desde la comodidad de su hogar.</p><img src="images/5.png" alt="" width="835" height="418"/>
                </div>
                <div class="tab-pane fade" id="tabs-4-3">
                  <h4>OFRECIENDO UN SERVICIO AL CLIENTE DE PRIMER NIVEL</h4>
                  <p>En Carnes B&R, nuestra meta es ofrecer productos cárnicos de la más alta calidad, asegurando frescura y sabor en cada corte. Nos comprometemos a proporcionar una experiencia de compra única, donde la tradición y la innovación se encuentran para satisfacer las necesidades de nuestros clientes. </p>
                  <p> Creemos en la importancia de la trazabilidad y la sostenibilidad, por lo que trabajamos con proveedores responsables y apoyamos prácticas de producción que respeten el bienestar animal y el medio ambiente. Nuestro objetivo es convertirnos en el referente de confianza para quienes buscan carnes premium y un servicio excepcional, tanto en nuestra tienda física como en nuestra plataforma digital.</p><img src="images/6.png" alt="" width="835" height="418"/>
                </div>
                <div class="tab-pane fade" id="tabs-4-4">
                  <h4>COMPROMISO CON LA CALIDAD Y LA SOSTENIBILIDAD</h4>
                  <p>Garantizamos que nuestros productos lleguen frescos y de alta calidad, promoviendo la confianza y la transparencia sobre su origen y preparación. Nuestro servicio personalizado se adapta a las necesidades de cada cliente, asegurando un proceso de compra ágil y puntual.</p>
                  <p>Estamos comprometidos con la sostenibilidad a través de empaques ecológicos y el apoyo a la producción local. Implementamos tecnología para mejorar la experiencia de compra y priorizamos la seguridad alimentaria, cumpliendo con los más altos estándares de higiene y manipulación. </p><img src="images/4.png" alt="" width="835" height="418"/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Icon Classic-->
      <section class="section section-lg bg-gray-100">
        <div class="container">
          <div class="row row-md row-50">
            <div class="col-sm-6 col-xl-4 wow fadeInUp" data-wow-delay="0s">
              <article class="box-icon-classic">
                <div class="unit unit-spacing-lg flex-column text-center flex-md-row text-md-left">
                  <div class="unit-left">
                    <div class="box-icon-classic-icon linearicons-helicopter"></div>
                  </div>
                  <div class="unit-body">
                    <h5 class="box-icon-classic-title"><a href="#">Entrega gratis</a></h5>
                    <p class="box-icon-classic-text">Si pides más de 3 kilos con gusto te los entregaremos gratis.</p>
                  </div>
                </div>
              </article>
            </div>
            <div class="col-sm-6 col-xl-4 wow fadeInUp" data-wow-delay=".1s">
              <article class="box-icon-classic">
                <div class="unit unit-spacing-lg flex-column text-center flex-md-row text-md-left">
                  <div class="unit-left">
                    <div class="box-icon-classic-icon linearicons-steak"></div>
                  </div>
                  <div class="unit-body">
                    <h5 class="box-icon-classic-title"><a href="#">15+ Opciones de Carnes </a></h5>
                    <p class="box-icon-classic-text">Perfectas para asar, guisar o grillar, cada elección promete una experiencia culinaria única.</p>
                  </div>
                </div>
              </article>
            </div>
            <div class="col-sm-6 col-xl-4 wow fadeInUp" data-wow-delay=".2s">
              <article class="box-icon-classic">
                <div class="unit unit-spacing-lg flex-column text-center flex-md-row text-md-left">
                  <div class="unit-left">
                    <div class="box-icon-classic-icon linearicons-leaf"></div>
                  </div>
                  <div class="unit-body">
                    <h5 class="box-icon-classic-title"><a href="#">Cortes Frescos</a></h5>
                    <p class="box-icon-classic-text">Disfruta de la frescura y el sabor auténtico, sabiendo que eliges lo mejor para ti y tu familia.</p>
                  </div>
                </div>
              </article>
            </div>
          </div>
        </div>
      </section>
      <section class="section section-lg bg-gray-100 text-left section-relative">
        <div class="container">
          <div class="row row-60 justify-content-center justify-content-xxl-between">
            <div class="col-lg-6 col-xxl-5 position-static">
              <h3>Nuestra historia</h3>
              <div class="tabs-custom" id="tabs-5">
                <div class="tab-content tab-content-1">
                  <div class="tab-pane fade" id="tabs-5-1">
                    <h5 class="font-weight-normal text-transform-none text-spacing-75">Establecimiento de carnicería y primeros clientes satisfechos</h5>
                    <p>Nuestra carnicería ya está abierta, y los primeros clientes están encantados con la calidad y el servicio. ¡Ven y descúbrelo!</p>
                  </div>
                  <div class="tab-pane fade" id="tabs-5-2">
                    <h5 class="font-weight-normal text-transform-none text-spacing-75">Crecimiento Inicial</h5>
                    <p>La carnicería comienza a atraer clientes de restaurantes y tiendas. Se realizan las primeras promociones para incentivar la compra local y se mejora la visibilidad en redes sociales.</p>
                  </div>
                  <div class="tab-pane fade" id="tabs-5-3">
                    <h5 class="font-weight-normal text-transform-none text-spacing-75">Desafio de la pandemia</h5>
                    <p>La pandemia del COVID-19 nos obligó a adaptarnos. Implementamos medidas de higiene y opciones de entrega, y agradecemos a nuestra comunidad por su apoyo.</p>
                  </div>
                  <div class="tab-pane fade show active" id="tabs-5-4">
                    <h5 class="font-weight-normal text-transform-none text-spacing-75">Compromiso con el Futuro</h5>
                    <p>Gracias al apoyo de nuestros clientes y nuestro compromiso con la calidad, seguimos avanzando en el mercado. Estamos emocionados por el futuro y por seguir brindando lo mejor a todos.</p>
                  </div>
                </div>
                <div class="list-history-wrap">
                  <ul class="nav list-history">
                    <li class="list-history-item" role="presentation"><a href="#tabs-5-1" data-toggle="tab">
                        <div class="list-history-circle"></div>2014</a></li>
                    <li class="list-history-item" role="presentation"><a href="#tabs-5-2" data-toggle="tab">
                        <div class="list-history-circle"></div>2017</a></li>
                    <li class="list-history-item" role="presentation"><a href="#tabs-5-3" data-toggle="tab">
                        <div class="list-history-circle"></div>2020</a></li>
                    <li class="list-history-item" role="presentation"><a class="active" href="#tabs-5-4" data-toggle="tab">
                        <div class="list-history-circle"></div>2024</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-9 col-lg-6 position-static index-1">
              <div class="bg-image-right-1 bg-image-right-lg"><img src="images/12.png" alt="" width="1110" height="710"/>
                <div class></a>
                  <div class="link-play-modern-decor"></div>
                </div>
                <div class="box-transform" style="background-image: url(images/12.png);"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Our clients-->
      <section class="section section-lg bg-default text-md-left">
        <div class="container">
          <div class="row row-60 justify-content-center flex-lg-row-reverse">
            <div class="col-md-8 col-lg-6 col-xl-5">
              <div class="offset-left-xl-70">
                <h6 class="heading-3">¿Qué Dice la Gente sobre carnes B&R?</h6>
                <div class="slick-quote">
                  <!-- Slick Carousel-->
                  <div class="slick-slider carousel-parent" data-autoplay="true" data-swipe="true" data-items="1" data-child="#child-carousel-5" data-for="#child-carousel-5" data-slide-effect="true">
                    <div class="item">
                      <!-- Quote Modern-->
                      <article class="quote-modern">
                        <h5 class="quote-modern-text"><span class="q">La carne que compré en B & R fue la mejor que he probado. El chuletón estaba jugoso y lleno de sabor. ¡Definitivamente volveré!</span></h5>
                        <h5 class="quote-modern-author">Andres Lopez,</h5>
                        <p class="quote-modern-status">Cliente Regular</p>
                      </article>
                    </div>
                    <div class="item">
                      <!-- Quote Modern-->
                      <article class="quote-modern">
                        <h5 class="quote-modern-text"><span class="q">Me encanta el trato personalizado que recibo. Siempre me recomiendan el mejor corte para mis asados y nunca me han fallado.</span></h5>
                        <h5 class="quote-modern-author">Jesica Hurtado,</h5>
                        <p class="quote-modern-status">Cliente regular</p>
                      </article>
                    </div>
                    <div class="item">
                      <!-- Quote Modern-->
                      <article class="quote-modern">
                        <h5 class="quote-modern-text"><span class="q">Admiro el compromiso con la sostenibilidad. Saber que la carne proviene de ganaderos locales me hace sentir bien al comprar aquí.</span></h5>
                        <h5 class="quote-modern-author">Sofia Jimenez,</h5>
                        <p class="quote-modern-status">Cliente Regular</p>
                      </article>
                    </div>
                    <div class="item">
                      <!-- Quote Modern-->
                      <article class="quote-modern">
                        <h5 class="quote-modern-text"><span class="q">Siempre encuentro una gran variedad de cortes, desde los clásicos hasta opciones más exóticas. ¡Es mi carnicería favorita en Bogotá!</span></h5>
                        <h5 class="quote-modern-author">Carlos Lopez,</h5>
                        <p class="quote-modern-status">Cliente Regular</p>
                      </article>
                    </div>
                  </div>
                  <div class="slick-slider child-carousel" id="child-carousel-5" data-arrows="true" data-for=".carousel-parent" data-items="4" data-sm-items="4" data-md-items="4" data-lg-items="4" data-xl-items="4" data-slide-to-scroll="1">
                    <div class="item"><img class="img-circle" src="images/negrito.jpeg" alt="" width="83" height="83"/>
                    </div>
                    <div class="item"><img class="img-circle" src="images/mafeee.jpeg" alt="" width="83" height="83"/>
                    </div>
                    <div class="item"><img class="img-circle" src="images/mag.jpeg" alt="" width="83" height="83"/>
                    </div>
                    <div class="item"><img class="img-circle" src="images/señor.jpeg" alt="" width="83" height="83"/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-xl-7"><img src="images/13.png" alt="" width="669" height="447"/>
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
                <div class="col-md-7 col-xl-6"><a class="brand" href="index2.html"><img
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
  </div>
    <!-- Global Mailform Output-->
    <div class="snackbars" id="form-output-global"></div>
    <!-- Javascript-->
    <script src="js/core.min.js"></script>
    <script src="js/script.js"></script>
  </body>
</html>