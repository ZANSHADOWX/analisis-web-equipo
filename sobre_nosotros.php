<?php
session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RUSH - SOBRE NOSOTROS</title>

     <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap"
      rel="stylesheet"
    />
<link rel="stylesheet" href="css/barra_menu_1.css" />
  <link rel="stylesheet" href="css/cont_snosotros.css" />
  <link rel="stylesheet" href="css/chat.css" />
  <link rel="stylesheet" href="css/footer.css" />
  </head>

  <body>

    <!-- HEADER / MENU -->
    <header class="header">
      
      <!-- LOGO IZQUIERDA -->
      <div class="header-left">
        <img src="img/iconos/logo cafe .png" alt="Logo RUSH" class="logo-img">
      </div>

      <!-- MENU -->
      <nav class="header-menu">
        <ul>
          <li><a href="index.php">Inicio</a></li>
          <li><a href="tienda.php">Menú</a></li>
          <li><a href="#">Sobre Nosotros</a></li>
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
          <!-- LOGO COMO BOTÓN DE LOGIN -->
          <a href="formularios/login.php">
            <img src="img/iconos/avatar.png" class="logo-img login-logo" alt="Login">
          </a>
        <?php endif; ?>
      </div>

    </header>


    <!-- HERO -->
    <section class="hero-sobre">
      <h1>Sobre Nosotros</h1>
    </section>

    <!-- HISTORIA -->
    <section class="section-container historia">
      <h2>Nuestra Historia ☕</h2>
      <p>
        RUSH Café nació de una idea simple: crear un espacio donde el café no solo se beba, sino se viva.
        Todo comenzó en 2022, cuando un pequeño grupo de amigos apasionados por el café decidió abrir
        una barra en el corazón de la ciudad.
      </p>
      <p>
        Con recursos limitados pero mucha pasión, iniciamos con una pequeña máquina de espresso y granos
        seleccionados cuidadosamente. Poco a poco, gracias al apoyo de nuestros clientes, fuimos creciendo,
        mejorando nuestras recetas y creando un ambiente único.
      </p>
      <p>
        Hoy, RUSH Café es más que una cafetería: es un punto de encuentro, un lugar para relajarse,
        trabajar o compartir momentos especiales.
      </p>
    </section>

    <!-- VALORES -->
    <section class="section-container valores">
      <h2>Nuestros Valores 🌟</h2>
      <div class="valores-grid">
        <div class="valor-card">
          <h3>Calidad</h3>
          <p>Seleccionamos los mejores granos para ofrecer un café excepcional.</p>
        </div>
        <div class="valor-card">
          <h3>Pasión</h3>
          <p>Amamos lo que hacemos y eso se refleja en cada taza.</p>
        </div>
        <div class="valor-card">
          <h3>Comunidad</h3>
          <p>Creamos un espacio donde todos son bienvenidos.</p>
        </div>
        <div class="valor-card">
          <h3>Innovación</h3>
          <p>Siempre buscamos nuevas experiencias y sabores.</p>
        </div>
      </div>
    </section>

    <!-- MISIÓN Y VISIÓN -->
    <section class="section-container">
      <div class="info-grid">
        <div class="info-card">
          <h2>Nuestra Misión ☕</h2>
          <p>
            Brindar experiencias únicas a través de café de alta calidad,
            creando un ambiente cálido donde cada cliente se sienta como en casa.
          </p>
        </div>

        <div class="info-card">
          <h2>Nuestra Visión 🌎</h2>
          <p>
            Convertirnos en la cafetería de referencia en la ciudad,
            reconocida por nuestro sabor, innovación y servicio excepcional.
          </p>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <section class="section-container faq">
      <h2>Preguntas Frecuentes ❓</h2>

      <div class="faq-item">
        <div class="faq-question">
          <span>¿Qué tipo de café utilizan?</span>
          <span class="faq-icon">▼</span>
        </div>
        <div class="faq-answer">
          Utilizamos granos seleccionados de alta calidad, cuidadosamente tostados para resaltar su sabor.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>¿Ofrecen servicio para llevar?</span>
          <span class="faq-icon">▼</span>
        </div>
        <div class="faq-answer">
          Sí, puedes pedir tu café para llevar o disfrutarlo en nuestras instalaciones.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>¿Tienen opciones sin azúcar o veganas?</span>
          <span class="faq-icon">▼</span>
        </div>
        <div class="faq-answer">
          Claro, contamos con alternativas como leches vegetales y opciones sin azúcar.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">
          <span>¿Se puede reservar mesa?</span>
          <span class="faq-icon">▼</span>
        </div>
        <div class="faq-answer">
          Actualmente no manejamos reservas, pero siempre buscamos atenderte lo más rápido posible.
        </div>
      </div>

    </section>

    <!-- UBICACIÓN -->
    <section class="ubicacion">
      <h2>Ubicación 📍</h2>
      <p>Centro Histórico, Ciudad, México</p>

      <div class="mapa">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3780.6088079919155!2d-91.83692052504682!3d18.636656982479522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85f1a96089b6c417%3A0x84907fe36c707b2e!2sRush%20Barra%20de%20Caf%C3%A9%20-%20Suc.%20Centro!5e0!3m2!1ses-419!2smx!4v1771465719881!5m2!1ses-419!2smx"
          allowfullscreen=""
          loading="lazy"
        ></iframe>
      </div>
    </section>

    <!-- CONTACTO -->
    <section class="section-container contacto">
      <h2>Contáctanos</h2>
      <div class="contacto-grid">
        <div class="contact-item">
          <h3>Teléfono</h3>
          <p>938 123 4567</p>
        </div>
        <div class="contact-item">
          <h3>Email</h3>
          <p>contacto@rushcafe.com</p>
        </div>
        <div class="contact-item">
          <h3>Horario</h3>
          <p>8:00 AM - 9:00 PM</p>
        </div>
      </div>

      <!-- BOTON FORMULARIO -->
      <div class="mensaje-icono" onclick="abrirFormulario()">
        <img src="img/iconos/chat.png" alt="Mensaje">
        <span>Enviar mensaje</span>
      </div>
    </section>


    <!-- FORMULARIO EMERGENTE -->
    <div class="formulario-overlay" id="formOverlay">
      <div class="formulario-container">
        <span class="cerrar-form" onclick="cerrarFormulario()">✖</span>
        <h3>Envíanos tu mensaje</h3>
        <form action="formularios/enviar_mensaje.php" method="POST">
          <input type="text" name="nombre" placeholder="Tu nombre" required>
          <input type="email" name="correo" placeholder="Tu correo" required>
          <select name="tipo">
            <option value="Queja">Queja</option>
            <option value="Sugerencia">Sugerencia</option>
            <option value="Opinión">Opinión</option>
          </select>
          <textarea name="mensaje" placeholder="Escribe tu mensaje..." required></textarea>
          <button type="submit">Enviar</button>
        </form>
      </div>
    </div>

    <!-- BOTÓN CHAT -->
    <div class="chat-toggle" onclick="toggleChat()">
      <img src="img/iconos/chatbot.png" alt="Chat" />
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
        <input
          type="text"
          id="userInput"
          placeholder="Escribe tu mensaje..."
          onkeypress="if (event.key === 'Enter') sendMessage();"
        />
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

    <!-- SCRIPTS -->
    <script src="js/chat.js"></script>
    <script src="js/formulario.js"></script>
    <script src="js/menu.js"></script>
    <script src="js/acordeon.js"></script>

  </body>
</html>