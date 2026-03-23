
<?php
session_start();
include("conexion.php");

// Verificar que el usuario esté logueado
if(!isset($_SESSION['id_usuario'])){
    header("Location: formularios/login.php");
    exit;
}

// Verificar que llegue un ID de pedido
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: historial.php");
    exit;
}

$id_pedido = $_GET['id'];
$id_usuario = $_SESSION['id_usuario'];

// Obtener datos del pedido
$pedido = $conn->query("SELECT * FROM pedidos WHERE id_pedido = $id_pedido AND id_usuario = $id_usuario");

if(!$pedido || $pedido->num_rows == 0){
    echo "Pedido no encontrado";
    exit;
}

$datos_pedido = $pedido->fetch_assoc();

// Obtener detalles del pedido
$detalles = $conn->query("
    SELECT d.*, p.nombre, p.imagen 
    FROM detalle_pedido d
    JOIN producto p ON d.id_producto = p.id_producto
    WHERE d.id_pedido = $id_pedido
");

// Obtener datos del usuario
$usuario = $conn->query("SELECT * FROM usuarios WHERE id_usuario = $id_usuario")->fetch_assoc();
$nombre_cliente = $usuario['nombre'];

// Definir mensaje según método de pago
$mensaje_pago = '';
$pago_icono = '';
switch($datos_pedido['metodo_pago']) {
    case 'efectivo':
        $mensaje_pago = 'Pago en efectivo al recibir';
        $pago_icono = '💵';
        break;
    case 'transferencia':
        $mensaje_pago = 'Transferencia bancaria - Esperando comprobante';
        $pago_icono = '🏦';
        break;
    case 'tarjeta':
        $mensaje_pago = 'Pago con tarjeta';
        $pago_icono = '💳';
        break;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de compra - RUSH Café</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/barra_menu_1.css">
    <link rel="stylesheet" href="css/cont_tienda.css">
    <link rel="stylesheet" href="css/footer.css">
    <style>
        /* ===== ESTILOS TICKET CON ANIMACIONES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FDF8F0 0%, #F5EDE3 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        /* ===== HOJAS DE CAFÉ ANIMADAS ===== */
        .coffee-leaves {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        
        .leaf {
            position: absolute;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%238B5A2B"><path d="M12,2C9,7,4,9,4,14c0,4,4,6,8,6s8-2,8-6C20,9,15,7,12,2z"/></svg>');
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.12;
            animation: floatLeaf linear infinite;
        }
        
        @keyframes floatLeaf {
            0% {
                transform: translateY(-10vh) rotate(0deg);
                opacity: 0;
            }
            15% {
                opacity: 0.12;
            }
            85% {
                opacity: 0.12;
            }
            100% {
                transform: translateY(110vh) rotate(360deg);
                opacity: 0;
            }
        }
        
        .leaf-small { width: 20px; height: 20px; opacity: 0.08; }
        .leaf-medium { width: 35px; height: 35px; }
        .leaf-large { width: 55px; height: 55px; opacity: 0.06; }
        
        /* Header con animación sutil */
        .header {
            position: relative;
            z-index: 10;
            background: rgba(74, 44, 44, 0.95);
            backdrop-filter: blur(10px);
        }
        
        /* Contenedor del ticket con animación de entrada */
        .ticket-container {
            max-width: 650px;
            margin: 40px auto;
            background: white;
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            overflow: hidden;
            position: relative;
            z-index: 2;
            animation: slideUpFade 0.6s ease-out;
            transition: transform 0.3s ease;
        }
        
        .ticket-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        }
        
        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Header del ticket con efecto de grano de café */
        .ticket-header {
            background: linear-gradient(135deg, #4a2c2c 0%, #3a2222 100%);
            color: white;
            padding: 28px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .ticket-header::before {
            content: "☕";
            position: absolute;
            font-size: 100px;
            opacity: 0.08;
            right: -20px;
            bottom: -30px;
            transform: rotate(-15deg);
            pointer-events: none;
        }
        
        .ticket-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 1px;
        }
        
        .ticket-header p {
            margin: 8px 0 0;
            opacity: 0.85;
            font-size: 14px;
        }
        
        /* Cuerpo del ticket */
        .ticket-body {
            padding: 28px;
        }
        
        /* Información del pedido con efecto de tarjeta */
        .ticket-info {
            background: linear-gradient(135deg, #FFF9F0 0%, #FFF5E8 100%);
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 25px;
            border: 1px solid #F0E4D4;
            transition: all 0.3s ease;
        }
        
        .ticket-info:hover {
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .ticket-info p {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .ticket-info strong {
            color: #4a2c2c;
        }
        
        .estado-badge {
            display: inline-block;
            background: #FFF3CD;
            color: #856404;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        /* Productos */
        .ticket-items h3 {
            color: #4a2c2c;
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #E8DCCC;
        }
        
        .ticket-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #F0E4D4;
            transition: all 0.3s ease;
        }
        
        .ticket-item:hover {
            background: #FDF8F0;
            padding-left: 8px;
            transform: translateX(4px);
        }
        
        .item-nombre {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .item-cantidad {
            background: #F0E4D4;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #4a2c2c;
        }
        
        /* Total */
        .ticket-total {
            text-align: right;
            font-size: 1.5em;
            font-weight: bold;
            color: #C97E3A;
            padding: 20px 0 15px;
            border-top: 2px solid #E8DCCC;
            margin-top: 15px;
        }
        
        /* Mensaje de agradecimiento */
        .thank-you {
            text-align: center;
            margin-top: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #F9EEDE 0%, #F5E5D3 100%);
            border-radius: 20px;
            color: #5D3A1A;
            transition: all 0.3s ease;
        }
        
        .thank-you:hover {
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .thank-you p {
            margin: 8px 0;
        }
        
        .coffee-icon {
            font-size: 32px;
            margin-bottom: 10px;
            animation: bounce 1s ease infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        /* Botones con efectos */
        .buttons-container {
            text-align: center;
            max-width: 650px;
            margin: 0 auto 40px;
            position: relative;
            z-index: 2;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-imprimir, .btn-volver {
            border: none;
            padding: 12px 28px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-imprimir {
            background: #C97E3A;
            color: white;
            box-shadow: 0 4px 12px rgba(201, 126, 58, 0.3);
        }
        
        .btn-imprimir:hover {
            background: #A55D2A;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(201, 126, 58, 0.4);
        }
        
        .btn-volver {
            background: #6c757d;
            color: white;
        }
        
        .btn-volver:hover {
            background: #5a6268;
            transform: translateY(-3px);
        }
        
        /* Footer */
        .footer {
            position: relative;
            z-index: 2;
            background: #2C1E12;
            margin-top: 40px;
        }
        
        /* Estilos para impresión */
        @media print {
            .coffee-leaves, .btn-imprimir, .btn-volver, .header, .footer, .chat-toggle, .chat-container {
                display: none !important;
            }
            .ticket-container {
                box-shadow: none;
                margin: 0;
                width: 100%;
                border-radius: 0;
            }
            body {
                padding: 0;
                margin: 0;
                background: white;
            }
            .ticket-item:hover {
                background: none;
                transform: none;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .ticket-container {
                margin: 20px;
                border-radius: 20px;
            }
            .ticket-body {
                padding: 20px;
            }
            .buttons-container {
                margin: 0 20px 30px;
            }
            .ticket-info p {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <!-- HOJAS DE CAFÉ ANIMADAS -->
    <div class="coffee-leaves" id="coffeeLeaves"></div>
    
    <header class="header">
        <div class="header-left">
            <img src="img/iconos/cafe.png" alt="Logo RUSH" class="logo-img">
            <span class="logo-text">RUSH</span>
        </div>
        <nav class="header-menu">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="tienda.php">Menú</a></li>
                <li><a href="historial.php">Mis Compras</a></li>
                <li><a href="sobre_nosotros.php">Sobre Nosotros</a></li>
            </ul>
        </nav>
        <div class="header-right">
            <?php if (isset($_SESSION["usuario"])): ?>
            <div class="usuario-info">
                <span class="nombre-usuario"><?php echo $_SESSION["usuario"]; ?></span>
                <div class="menu-usuario">
                    <span class="flecha-usuario" onclick="toggleMenu()">▼</span>
                    <div class="dropdown-usuario" id="submenuUsuario">
                        <a href="perfil/perfil.php">Ver perfil</a>
                        <a href="formularios/logout.php">Cerrar sesión</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <div class="ticket-container">
        <div class="ticket-header">
            <h1>RUSH Café ☕</h1>
            <p>Ticket de compra</p>
        </div>
        
        <div class="ticket-body">
            <div class="ticket-info">
                <p><strong>📄 Pedido #<?php echo $datos_pedido['id_pedido']; ?></strong></p>
                <p><strong>📅 Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($datos_pedido['fecha'])); ?></p>
                <p><strong>👤 Cliente:</strong> <?php echo htmlspecialchars($nombre_cliente); ?></p>
                <p><strong><?php echo $pago_icono; ?> Método de pago:</strong> <?php echo $mensaje_pago; ?></p>
                <p><strong>📌 Estado:</strong> <span class="estado-badge"><?php echo $datos_pedido['estado']; ?></span></p>
                <?php if(!empty($datos_pedido['datos_pago']) && $datos_pedido['metodo_pago'] == 'tarjeta'): ?>
                <p><strong>💳 Tarjeta:</strong> <?php echo $datos_pedido['datos_pago']; ?></p>
                <?php endif; ?>
                <?php if(!empty($datos_pedido['comprobante']) && $datos_pedido['metodo_pago'] == 'transferencia'): ?>
                <p><strong>📎 Comprobante:</strong> <a href="comprobantes/<?php echo $datos_pedido['comprobante']; ?>" target="_blank" style="color: #C97E3A;">Ver archivo</a></p>
                <?php endif; ?>
            </div>

            <div class="ticket-items">
                <h3>🛒 Productos</h3>
                <?php 
                while($item = $detalles->fetch_assoc()): 
                ?>
                <div class="ticket-item">
                    <div class="item-nombre">
                        <span class="item-cantidad"><?php echo $item['cantidad']; ?>x</span>
                        <span><?php echo htmlspecialchars($item['nombre']); ?></span>
                    </div>
                    <span>$<?php echo number_format($item['subtotal'], 2); ?></span>
                </div>
                <?php endwhile; ?>
            </div>

            <div class="ticket-total">
                TOTAL: $<?php echo number_format($datos_pedido['total'], 2); ?>
            </div>

            <div class="thank-you">
                <div class="coffee-icon">☕✨</div>
                <p><strong>¡Gracias por tu compra!</strong></p>
                <p>📍 Centro Histórico, Ciudad, México</p>
                <p>📞 +52 555 123 4567</p>
                <p>✉️ contacto@rushcafe.com</p>
                <p style="margin-top: 12px; font-size: 12px;">¡Vuelve pronto! 🫶</p>
            </div>
        </div>
    </div>

    <div class="buttons-container">
        <button class="btn-imprimir" onclick="window.print()">🖨️ Imprimir ticket</button>
        <a href="historial.php" class="btn-volver">← Volver al historial</a>
    </div>

    <footer class="footer">
        <div class="footer-top">
            <div class="footer-social">
                <h4>Redes Sociales</h4>
                <div class="social-icons">
                    <a href="#"><img src="img/iconos/facebook.png"></a>
                    <a href="#"><img src="img/iconos/instagram.png"></a>
                    <a href="#"><img src="img/iconos/tik-tok.png"></a>
                </div>
            </div>
            <div class="footer-brand">
                <h3>RUSH Café</h3>
                <p>El aroma que despierta tus sentidos</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 RUSH Café. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        // ===== CREAR HOJAS DE CAFÉ ANIMADAS =====
        function createCoffeeLeaves() {
            const container = document.getElementById('coffeeLeaves');
            if (!container) return;
            
            const leafCount = 24;
            
            for (let i = 0; i < leafCount; i++) {
                const leaf = document.createElement('div');
                leaf.classList.add('leaf');
                
                // Tamaño aleatorio
                const sizeRandom = Math.random();
                if (sizeRandom < 0.33) leaf.classList.add('leaf-small');
                else if (sizeRandom < 0.66) leaf.classList.add('leaf-medium');
                else leaf.classList.add('leaf-large');
                
                // Posición horizontal aleatoria
                leaf.style.left = Math.random() * 100 + '%';
                
                // Duración aleatoria (10-25 segundos)
                leaf.style.animationDuration = (10 + Math.random() * 15) + 's';
                
                // Retraso aleatorio
                leaf.style.animationDelay = (Math.random() * 20) + 's';
                
                container.appendChild(leaf);
            }
        }
        
        // Función para el menú de usuario
        function toggleMenu(){
            let menu = document.getElementById("submenuUsuario");
            let flecha = document.querySelector(".flecha-usuario");
            if(menu && flecha){
                menu.classList.toggle("activo");
                flecha.classList.toggle("rotada");
            }
        }
        
        document.addEventListener("click", function(e){
            let menu = document.getElementById("submenuUsuario");
            let flecha = document.querySelector(".flecha-usuario");
            if(menu && flecha && !menu.contains(e.target) && !flecha.contains(e.target)){
                menu.classList.remove("activo");
                flecha.classList.remove("rotada");
            }
        });
        
        // Iniciar animaciones cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            createCoffeeLeaves();
        });
    </script>
    <script src="js/chat.js"></script>
</body>
</html>