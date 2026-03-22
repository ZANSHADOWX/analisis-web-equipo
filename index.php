<?php
session_start();
include("conexion.php");

/* CONSULTA PRODUCTOS */
$favoritos = $conn->query("
    SELECT * FROM producto
    WHERE activo = 1
    ORDER BY RAND()
    LIMIT 6
");
?>

<!doctype html>
<html lang="es">

  <!--HEAD -->
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUSH - INICIO</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/barra_menu_1.css">
    <link rel="stylesheet" href="css/cont_index.css">
    <link rel="stylesheet" href="css/chat.css">
    <link rel="stylesheet" href="css/footer.css">
  </head>

  <body>

    <!-- HEADER / MENU -->
    <header class="header">
      
      <!-- IZQUIERDA -->
      <div class="header-left">
        <img src="img/iconos/logo cafe .png" alt="Logo RUSH" class="logo-img">
      </div>

      <!-- MENU -->
      <nav class="header-menu">
        <ul>
          <li><a href="#">Inicio</a></li>
          <li><a href="tienda.php">Menú</a></li>
          <li><a href="sobre_nosotros.php">Sobre Nosotros</a></li>
        </ul>
      </nav>

      <!-- DERECHA -->
      <div class="header-right">
        <?php if (isset($_SESSION["usuario"])): ?>
          <div class="usuario-info">
            <span class="nombre-usuario">
              <?php echo $_SESSION["usuario"]; ?>
            </span>

            <div class="menu-usuario">
              <span class="flecha-usuario" onclick="toggleMenu()">▼</span>
              <div class="dropdown-usuario" id="submenuUsuario">
                <a href="perfil/perfil.php">Ver perfil</a>
                <a href="formularios/logout.php">Cerrar sesión</a>
              </div>
            </div>
          </div>

        <?php else: ?>
          <!-- AVATAR -->
          <a href="formularios/login.php">
            <img src="img/iconos/avatar.png" class="logo-img" alt="Login">
          </a>
        <?php endif; ?>
      </div>

    </header>

    <!-- BANNER PRINCIPAL -->
    <section class="hero">
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <h1>RUSH Café</h1>
        <p>
        Bienvenido al lugar donde el aroma del café despierta tus sentidos.
        Disfruta momentos únicos en un ambiente cálido y acogedor.
        </p>
        <a href="sobre_nosotros.php" class="hero-btn"> Ver más... </a>
      </div>
    </section>

<!-- FAVORITOS -->
<section class="favoritos">
  <div class="favoritos-header">
    <h2>Favoritos & Promociones Especiales</h2>
  </div>

  <div class="carrusel-container">
    
    <button class="arrow left" onclick="scrollCarrusel(-1)">&#10094;</button>

    <div class="carrusel" id="carrusel">

      <!-- 🔥 TRACK NECESARIO PARA EFECTO PRO -->
      <div class="carrusel-track" id="track">

        <?php if($favoritos->num_rows > 0): ?>
          <?php while($producto = $favoritos->fetch_assoc()): ?>
            
            <div class="producto-card">

              <div class="producto-img">
                <img src="img/imgenes/productos/<?php echo $producto['imagen']; ?>" 
                    alt="<?php echo $producto['nombre']; ?>">
              </div>

              <div class="producto-info">
                <h3><?php echo $producto['nombre']; ?></h3>
                <p class="precio">
                  $<?php echo number_format($producto['precio'],2); ?> MXN
                </p>
              </div>

              <!-- BOTÓN -->
              <a href="tienda.php?id=<?php echo $producto['id_producto']; ?>" class="btn-carrito">
                Ver producto
              </a>

            </div>

          <?php endwhile; ?>
        <?php else: ?>
          <p>No hay productos disponibles</p>
        <?php endif; ?>

      </div>
      <!-- 🔥 FIN TRACK -->

    </div>

    <button class="arrow right" onclick="scrollCarrusel(1)">&#10095;</button>

  </div>
</section>

    <!-- COLLAGE IMAGENES -->
    <section class="collage">
      <h2>Momentos RUSH ☕</h2>
      <div class="collage-grid">
        <div class="collage-item large">
          <img src="img/imgenes/img_extras/1000048954.jpg">
        </div>
        <div class="collage-item">
          <img src="img/imgenes/img_extras/1000048955.jpg">
        </div>
        <div class="collage-item">
          <img src="img/imgenes/img_extras/1000048956.jpg">
        </div>
        <div class="collage-item">
          <img src="img/imgenes/img_extras/1000048957.jpg">
        </div>
        <div class="collage-item">
          <img src="img/imgenes/img_extras/comida_delisiosa.png">
        </div>
        <div class="collage-item wide">
          <img src="img/imgenes/img_extras/keg-coyboy.jpg">
        </div>
        <div class="collage-item">
          <img src="img/imgenes/img_extras/mesa_caafe.png">
        </div>
        <div class="collage-item">
          <img src="img/imgenes/img_extras/preparacion.png">
        </div>
      </div>
    </section>

    <!-- CHAT BOTON -->
    <div class="chat-toggle" onclick="toggleChat()">
      <img src="img/iconos/chatbot.png">
    </div>

    <!-- CHAT -->
    <div class="chat-container" id="chatContainer">
      <div class="chat-header">
        RUSH Café ☕
        <span onclick="toggleChat()">✖</span>
      </div>
      <div class="chat-body" id="chatBody">
        <div class="bot-message">
          ¡Hola! 👋 Bienvenido a RUSH Café. ¿En qué puedo ayudarte?
        </div>
      </div>
      <div class="chat-footer">
        <input type="text"
        id="userInput"
        placeholder="Escribe tu mensaje..."
        onkeypress="if (event.key === 'Enter') sendMessage();">
        <button onclick="sendMessage()">Enviar</button>
      </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
      <div class="footer-top">
        <div class="footer-brand">
          <img src="img/iconos/logo cafe .png" alt="RUSH Café" class="footer-logo">
          <p>El aroma que despierta tus sentidos</p>
        </div>

        <div class="footer-social">
          <h4>Redes Sociales</h4>
          <div class="social-icons">
            <a href="extras_2/error404.html"><img src="img/iconos/facebook.png"></a>
            <a href="extras_2/error404.html"><img src="img/iconos/instagram.png"></a>
            <a href="extras_2/error404.html"><img src="img/iconos/twitter.png"></a>
          </div>
        </div>

        <div class="footer-column footer-contacto">
          <h4>Contacto</h4>
          <p>📍 Ciudad del Carmen</p>
          <p>📞 938 123 4567</p>
          <p>📧 contacto@rushcafe.com</p>
          <a href="sobre_nosotros.php" class="btn-footer-info">
            Más información
          </a>
        </div>

        <div class="footer-column footer-legal">
          <h4>Legal</h4>
          <ul>
            <li><a href="extras_1/terminos.html" target="_blank">Términos y Condiciones</a></li>
            <li><a href="extras_1/avisos.html" target="_blank">Aviso de Privacidad</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p>© 2026 Equipo 1. Todos los derechos reservados. (pagina de prueba).</p>
      </div>
    </footer>

    <!-- SCRIPTS JS -->
    <script src="js/chat.js"></script>
    <script src="js/favoritos.js"></script>
    <script src="js/menu.js"></script>
  </body>
</html>