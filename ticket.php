
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de compra - RUSH</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/barra_menu_1.css">
    <link rel="stylesheet" href="css/cont_tienda.css">
    <link rel="stylesheet" href="css/footer.css">
    <style>
        .ticket-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .ticket-header {
            background: #4a2c2c;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .ticket-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .ticket-header p {
            margin: 5px 0 0;
            opacity: 0.8;
        }
        .ticket-body {
            padding: 20px;
        }
        .ticket-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .ticket-info p {
            margin: 8px 0;
        }
        .ticket-items {
            margin-bottom: 20px;
        }
        .ticket-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .ticket-total {
            text-align: right;
            font-size: 1.3em;
            font-weight: bold;
            color: #4a2c2c;
            padding-top: 15px;
            border-top: 2px solid #4a2c2c;
            margin-top: 10px;
        }
        .btn-imprimir, .btn-volver {
            background: #4a2c2c;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
            width: calc(100% - 20px);
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        .btn-volver {
            background: #6c757d;
        }
        .btn-imprimir:hover, .btn-volver:hover {
            opacity: 0.9;
        }
        @media print {
            .btn-imprimir, .btn-volver, .header, .footer, .chat-toggle, .chat-container {
                display: none !important;
            }
            .ticket-container {
                box-shadow: none;
                margin: 0;
                width: 100%;
            }
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
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
                <p><strong>Pedido #<?php echo $datos_pedido['id_pedido']; ?></strong></p>
                <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($datos_pedido['fecha'])); ?></p>
                <p><strong>Cliente:</strong> <?php echo $nombre_cliente; ?></p>
                <p><strong>Método de pago:</strong> 
                    <?php 
                    if($datos_pedido['metodo_pago'] == 'efectivo'){
                        echo '💵 Efectivo';
                    } elseif($datos_pedido['metodo_pago'] == 'transferencia'){
                        echo '🏦 Transferencia bancaria';
                    } else {
                        echo '💳 Tarjeta de crédito/débito';
                        if(!empty($datos_pedido['datos_pago'])){
                            echo ' (' . $datos_pedido['datos_pago'] . ')';
                        }
                    }
                    ?>
                </p>
                <p><strong>Estado:</strong> 
                    <span style="color: #856404; background: #fff3cd; padding: 2px 8px; border-radius: 12px;">
                        <?php echo $datos_pedido['estado']; ?>
                    </span>
                </p>
            </div>

            <div class="ticket-items">
                <h3>Productos</h3>
                <?php 
                while($item = $detalles->fetch_assoc()): 
                ?>
                <div class="ticket-item">
                    <span>
                        <?php echo $item['cantidad']; ?>x <?php echo $item['nombre']; ?>
                    </span>
                    <span>$<?php echo number_format($item['subtotal'], 2); ?></span>
                </div>
                <?php endwhile; ?>
            </div>

            <div class="ticket-total">
                TOTAL: $<?php echo number_format($datos_pedido['total'], 2); ?>
            </div>

            <div style="text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px dashed #ddd; color: #666;">
                <p>¡Gracias por tu compra!</p>
                <p>📍 Centro Histórico, Ciudad, México</p>
                <p>📞 +52 555 123 4567</p>
                <p>✉️ contacto@rushcafe.com</p>
            </div>
        </div>
    </div>

    <div style="text-align: center; max-width: 600px; margin: 0 auto 40px;">
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
    </script>
    <script src="js/chat.js"></script>
</body>
</html>