<?php
session_start();
include("conexion.php");
?>
<?php
$cantidad_carrito = 0;

if(isset($_SESSION['carrito'])){
    foreach($_SESSION['carrito'] as $producto){
        $cantidad_carrito += $producto['cantidad'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>

        <meta charset="UTF-8">
        <title>Tienda - RUSH</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/barra_menu_2.css">
        <link rel="stylesheet" href="css/cont_tienda.css">
        <link rel="stylesheet" href="css/chat.css">
        <link rel="stylesheet" href="css/icon_carrito.css">
        <link rel="stylesheet" href="css/footer.css">
    </head>

    <body>
       <header class="header">

            <!-- FILA SUPERIOR -->
            <div class="header-top">

                <div class="header-left">
                    <img src="img/iconos/logo cafe .png" class="logo-img">
                </div>

                <nav class="header-menu">
                    <ul>
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="#">Menú</a></li>
                        <li><a href="historial.php">Historial</a></li>
                        <li><a href="sobre_nosotros.php">Sobre Nosotros</a></li>
                    </ul>
                </nav>

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
                <a href="formularios/login.php">
                    <img src="img/iconos/avatar.png" class="icono-avatar">
                </a>
                <?php endif; ?>
            </div>

            <!-- FILA INFERIOR BUSCADOR -->
            <div class="header-search">
                <form class="search-box" action="tienda.php" method="GET">
                    <input type="text" name="q" placeholder="Buscar producto..."
                    value="<?php if(isset($_GET['q'])) echo $_GET['q']; ?>">
                    <button type="submit">🔍</button>
                </form>
            </div>
        </header>

        <!-- CONTENIDO DE LA TIENDA -->
        <!-- CONTENEDOR DE CATEGORÍAS -->
        <div class="categorias-wrapper">
        <div class="categorias-container">
            <a href="tienda.php" class="cat-btn active">
            <i class="fa fa-home"></i> <span class="cat-text">Todos</span>
            </a>
            <a href="tienda.php?cat=1" class="cat-btn">
            <i class="fa fa-birthday-cake"></i> <span class="cat-text">Postres</span>
            </a>
            <a href="tienda.php?cat=2" class="cat-btn">
            <i class="fa fa-coffee"></i> <span class="cat-text">Bebidas frías</span>
            </a>
            <a href="tienda.php?cat=3" class="cat-btn">
            <i class="fa fa-mug-hot"></i> <span class="cat-text">Bebidas calientes</span>
            </a>
            <a href="tienda.php?cat=4" class="cat-btn">
            <i class="fa fa-egg"></i> <span class="cat-text">Desayunos</span>
            </a>
        </div>
        </div>

        <!-- Asegúrate de incluir Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

        <!-- PRODUCTOS -->
        <div class="productos-container">
        <?php
        $sql = "SELECT * FROM producto";
        $condiciones = [];

        /* FILTRO POR CATEGORIA */
        if(isset($_GET['cat'])){
            $cat = $_GET['cat'];
            $condiciones[] = "id_categoria='$cat'";
        }

        /* BUSCADOR */
        if(isset($_GET['q']) && $_GET['q'] != ""){
            $buscar = $_GET['q'];
            $condiciones[] = "nombre LIKE '%$buscar%'";
        }

        /* UNIR CONDICIONES */
        if(count($condiciones) > 0){
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }

        $result = mysqli_query($conn,$sql);

        /* MOSTRAR RESULTADOS */
        if($result && mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
        ?>
            <div class="producto-card">
                <div class="producto-img">
                    <img src="img/imgenes/productos/<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>">
                </div>
                <div class="producto-info">
                    <h3><?php echo $row['nombre']; ?></h3>
                    <p class="precio">$<?php echo $row['precio']; ?></p>
                </div>

                <button 
                class="btn-carrito agregar-carrito"
                data-id="<?php echo $row['id_producto']; ?>">
                    Agregar al carrito
                </button>
            </div>
        <?php
            }
        }else{
            echo "<p style='width:100%; text-align:center;'>No se encontraron productos</p>";
        }
        ?>
        </div>
        
        <!-- CARRITO FLOTANTE -->
        <a href="carrito/carrito.php" class="carrito-float">
            <img src="img/iconos/carrito-de-compras.png">
            <?php if($cantidad_carrito > 0){ ?>
            <span class="carrito-count" id="contadorCarrito">
                <?php echo $cantidad_carrito; ?>
            </span>
            <?php } ?>
        </a>
        
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
        <script src="js/menu.js"></script>
        <script src="js/AJAX.js"></script>
    </body>
</html>