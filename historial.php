
<?php
session_start();
include("conexion.php");

// Verificar que el usuario esté logueado
if(!isset($_SESSION['id_usuario'])){
    header("Location: formularios/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener todos los pedidos del usuario
$pedidos = $conn->query("
    SELECT * FROM pedidos 
    WHERE id_usuario = $id_usuario 
    ORDER BY fecha DESC
");

// Obtener información del usuario
$usuario = $conn->query("SELECT * FROM usuarios WHERE id_usuario = $id_usuario")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Compras - RUSH</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/barra_menu_1.css">
    <link rel="stylesheet" href="css/cont_tienda.css">
    <link rel="stylesheet" href="css/footer.css">
    <style>
        .historial-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }
        .historial-header {
            background: linear-gradient(135deg, #4a2c2c 0%, #2c1a1a 100%);
            color: white;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 30px;
            text-align: center;
            animation: fadeInDown 0.5s ease-out;
        }
        .historial-header h1 {
            margin: 0;
            font-size: 28px;
        }
        .historial-header p {
            margin: 8px 0 0;
            opacity: 0.9;
        }
        .pedido-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 16px;
            margin-bottom: 25px;
            overflow: hidden;
            transition: all 0.3s ease;
            animation: fadeInUp 0.5s ease-out;
            animation-fill-mode: both;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .pedido-card:hover {
            box-shadow: 0 12px 28px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }
        .pedido-card:nth-child(1) { animation-delay: 0.05s; }
        .pedido-card:nth-child(2) { animation-delay: 0.1s; }
        .pedido-card:nth-child(3) { animation-delay: 0.15s; }
        .pedido-card:nth-child(4) { animation-delay: 0.2s; }
        .pedido-card:nth-child(5) { animation-delay: 0.25s; }

        .pedido-header {
            background: #faf7f2;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            border-bottom: 1px solid #e9ecef;
        }
        .pedido-numero {
            font-size: 1.2em;
            font-weight: bold;
            color: #4a2c2c;
        }
        .pedido-fecha {
            color: #6c757d;
            font-size: 0.85em;
            margin-left: 10px;
        }
        .pedido-total {
            font-size: 1.3em;
            font-weight: bold;
            color: #28a745;
        }
        .estado-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75em;
            font-weight: bold;
        }
        .estado-pendiente { background: #fff3cd; color: #856404; }
        .estado-pagado { background: #d4edda; color: #155724; }
        .estado-enviado { background: #cce5ff; color: #004085; }
        .estado-entregado { background: #d1e7dd; color: #0f5132; }
        .estado-cancelado { background: #f8d7da; color: #721c24; }
        .pedido-body {
            padding: 20px;
        }
        .productos-lista {
            margin-bottom: 15px;
        }
        .producto-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.2s ease;
        }
        .producto-item:hover {
            background: #fef9e6;
            transform: translateX(5px);
        }
        .producto-imagen {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .producto-nombre {
            flex: 2;
            font-weight: 500;
        }
        .producto-cantidad {
            flex: 1;
            text-align: center;
            color: #6c757d;
        }
        .producto-precio {
            flex: 1;
            text-align: right;
            font-weight: bold;
            color: #4a2c2c;
        }
        .productos-header {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 5px;
            font-weight: bold;
            color: #6c757d;
            font-size: 0.85em;
        }
        .productos-header .producto-nombre { flex: 2; }
        .productos-header .producto-cantidad { flex: 1; text-align: center; }
        .productos-header .producto-precio { flex: 1; text-align: right; }
        .pedido-footer {
            background: #faf7f2;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e9ecef;
        }
        .btn-ver-ticket {
            background: #4a2c2c;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.9em;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-ver-ticket:hover {
            background: #2c1a1a;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .metodo-pago-badge {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 500;
        }
        .sin-compras {
            text-align: center;
            padding: 60px;
            background: #f9f9f9;
            border-radius: 16px;
            animation: fadeIn 0.5s ease-out;
        }
        .sin-compras p {
            font-size: 1.2em;
            color: #666;
            margin-bottom: 20px;
        }
        .btn-ver-tienda {
            background: #4a2c2c;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-ver-tienda:hover {
            background: #2c1a1a;
            transform: scale(1.05);
        }
        .resumen-info {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        /* ========== ANIMACIONES ========== */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
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

    <div class="historial-container">
        <div class="historial-header">
            <h1>📋 Mis Compras</h1>
            <p><?php echo $usuario['nombre']; ?> | <?php echo $usuario['email']; ?></p>
        </div>

        <?php if($pedidos && $pedidos->num_rows > 0): ?>
            <?php while($pedido = $pedidos->fetch_assoc()): 
                // Obtener productos de este pedido con imágenes
                $detalles = $conn->query("
                    SELECT d.*, p.nombre, p.imagen 
                    FROM detalle_pedido d
                    JOIN producto p ON d.id_producto = p.id_producto
                    WHERE d.id_pedido = {$pedido['id_pedido']}
                ");
                
                // Obtener método de pago formateado
                $metodo_pago_texto = '';
                $metodo_icono = '';
                switch($pedido['metodo_pago']) {
                    case 'efectivo': 
                        $metodo_pago_texto = 'Efectivo'; 
                        $metodo_icono = '💵';
                        break;
                    case 'transferencia': 
                        $metodo_pago_texto = 'Transferencia bancaria'; 
                        $metodo_icono = '🏦';
                        break;
                    case 'tarjeta': 
                        $metodo_pago_texto = 'Tarjeta de crédito/débito'; 
                        $metodo_icono = '💳';
                        break;
                    default: $metodo_pago_texto = $pedido['metodo_pago']; $metodo_icono = '💰';
                }
            ?>
            <div class="pedido-card">
                <div class="pedido-header">
                    <div>
                        <span class="pedido-numero">Pedido #<?php echo $pedido['id_pedido']; ?></span>
                        <span class="pedido-fecha">📅 <?php echo date('d/m/Y H:i', strtotime($pedido['fecha'])); ?></span>
                    </div>
                    <div class="resumen-info">
                        <span class="metodo-pago-badge"><?php echo $metodo_icono; ?> <?php echo $metodo_pago_texto; ?></span>
                        <span class="pedido-total">💰 $<?php echo number_format($pedido['total'], 2); ?></span>
                    </div>
                </div>
                
                <div class="pedido-body">
                    <div class="productos-lista">
                        <div class="productos-header">
                            <span class="producto-nombre">Producto</span>
                            <span class="producto-cantidad">Cantidad</span>
                            <span class="producto-precio">Subtotal</span>
                        </div>
                        <?php while($detalle = $detalles->fetch_assoc()): ?>
                        <div class="producto-item">
                            <img src="img/imgenes/productos/<?php echo $detalle['imagen']; ?>" 
                                 class="producto-imagen" 
                                 alt="<?php echo $detalle['nombre']; ?>"
                                 onerror="this.src='img/iconos/cafe.png'">
                            <span class="producto-nombre"><?php echo $detalle['nombre']; ?></span>
                            <span class="producto-cantidad">✖ <?php echo $detalle['cantidad']; ?></span>
                            <span class="producto-precio">$<?php echo number_format($detalle['subtotal'], 2); ?></span>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <div class="pedido-footer">
                    <span class="estado-badge estado-<?php echo strtolower($pedido['estado']); ?>">
                        📦 Estado: <?php echo $pedido['estado']; ?>
                    </span>
                    <a href="ticket.php?id=<?php echo $pedido['id_pedido']; ?>" class="btn-ver-ticket">
                        🎫 Ver ticket completo
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="sin-compras">
                <p>🛒 Aún no has realizado ninguna compra</p>
                <a href="tienda.php" class="btn-ver-tienda">✨ Ver productos</a>
            </div>
        <?php endif; ?>
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