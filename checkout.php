
<?php
session_start();
include("conexion.php");

// Verificar que el usuario haya iniciado sesión
if(!isset($_SESSION['id_usuario'])){
    header("Location: formularios/login.php?redirect=checkout.php");
    exit;
}

// Verificar que el carrito no esté vacío
if(!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0){
    header("Location: tienda.php?error=carrito_vacio");
    exit;
}

$carrito = $_SESSION['carrito'];
$total = 0;
foreach($carrito as $item){
    $total += $item['precio'] * $item['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Finalizar compra - RUSH Café</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/barra_menu_1.css">
    <link rel="stylesheet" href="css/cont_tienda.css">
    <link rel="stylesheet" href="css/footer.css">
    <style>
        /* ===== FONDO CON TAZAS DE CAFÉ ANIMADAS ===== */
        body {
            position: relative;
            overflow-x: hidden;
            background: linear-gradient(135deg, #FDF8F0 0%, #F5EDE3 100%);
            min-height: 100vh;
        }
        
        .coffee-cups-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        
        .floating-cup {
            position: absolute;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.12;
            animation: floatCup linear infinite;
            filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));
        }
        
        @keyframes floatCup {
            0% {
                transform: translateY(-10vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.12;
            }
            90% {
                opacity: 0.12;
            }
            100% {
                transform: translateY(110vh) rotate(15deg);
                opacity: 0;
            }
        }
        
        /* Tamaños de tazas */
        .cup-small { width: 55px; height: 55px; }
        .cup-medium { width: 85px; height: 85px; }
        .cup-large { width: 120px; height: 120px; }
        
        /* Contenedor principal */
        .checkout-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            position: relative;
            z-index: 2;
            animation: fadeInUp 0.6s ease-out;
        }
        
        .resumen-pedido {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(2px);
            padding: 25px;
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .resumen-pedido:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 45px rgba(0,0,0,0.12);
        }
        
        .metodos-pago {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(2px);
            padding: 25px;
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .metodos-pago:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 45px rgba(0,0,0,0.12);
        }
        
        .producto-resumen {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 12px;
            border-bottom: 1px solid #F0E4D4;
            transition: all 0.3s ease;
            animation: slideInRight 0.3s ease-out;
            animation-fill-mode: both;
            border-radius: 12px;
        }
        
        .producto-resumen:hover {
            transform: translateX(8px);
            background: #FDF8F0;
        }
        
        .producto-resumen img {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 12px;
            margin-right: 15px;
            transition: transform 0.3s ease;
        }
        
        .producto-resumen:hover img {
            transform: scale(1.05);
        }
        
        .producto-info {
            flex: 1;
        }
        
        .producto-info h4 {
            margin: 0 0 5px 0;
            color: #4A2C2C;
            font-size: 16px;
        }
        
        .producto-info p {
            margin: 0;
            color: #9B7B5C;
            font-size: 13px;
        }
        
        .producto-precio {
            font-weight: 600;
            color: #C97E3A;
            font-size: 16px;
        }
        
        .total-final {
            font-size: 1.5em;
            font-weight: bold;
            color: #C97E3A;
            margin-top: 20px;
            padding-top: 15px;
            text-align: right;
            border-top: 2px solid #E8DCCC;
        }
        
        .metodo-pago {
            margin: 15px 0;
            padding: 15px 18px;
            border: 2px solid #F0E4D4;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        
        .metodo-pago:hover {
            border-color: #C97E3A;
            transform: translateX(8px);
            background: #FFF9F0;
            box-shadow: 0 5px 15px rgba(201, 126, 58, 0.1);
        }
        
        .metodo-pago.seleccionado {
            border-color: #2E7D32;
            background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
        }
        
        .metodo-pago input {
            margin-right: 12px;
            accent-color: #C97E3A;
            transform: scale(1.1);
            cursor: pointer;
        }
        
        .metodo-pago label strong {
            font-size: 16px;
            color: #4A2C2C;
        }
        
        .btn-confirmar {
            background: linear-gradient(135deg, #C97E3A 0%, #A55D2A 100%);
            color: white;
            padding: 16px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1.2em;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 25px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-confirmar:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #A55D2A 0%, #8B4513 100%);
            box-shadow: 0 10px 25px rgba(201, 126, 58, 0.3);
        }
        
        .btn-confirmar:active {
            transform: translateY(0);
        }
        
        .subir-comprobante, .subir-tarjeta {
            margin-top: 20px;
            padding: 20px;
            background: #FDF8F0;
            border-radius: 20px;
            display: none;
            animation: fadeInUp 0.3s ease-out;
        }
        
        .input-tarjeta {
            width: 100%;
            padding: 12px 16px;
            margin: 8px 0;
            border: 2px solid #F0E4D4;
            border-radius: 16px;
            font-size: 14px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        .input-tarjeta:focus {
            outline: none;
            border-color: #C97E3A;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(201, 126, 58, 0.2);
        }
        
        .fila-tarjeta {
            display: flex;
            gap: 15px;
            margin-top: 8px;
        }
        
        .fila-tarjeta .input-tarjeta {
            width: auto;
            flex: 1;
        }
        
        .volver-carrito {
            display: inline-block;
            margin-top: 20px;
            color: #C97E3A;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .volver-carrito:hover {
            transform: translateX(-5px);
            color: #A55D2A;
        }
        
        h2 {
            color: #4A2C2C;
            margin-bottom: 20px;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 20px;
            border-radius: 16px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #f5c6cb;
            animation: fadeInUp 0.3s ease-out;
        }
        
        /* Animaciones */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .producto-resumen:nth-child(1) { animation-delay: 0.05s; }
        .producto-resumen:nth-child(2) { animation-delay: 0.1s; }
        .producto-resumen:nth-child(3) { animation-delay: 0.15s; }
        .producto-resumen:nth-child(4) { animation-delay: 0.2s; }
        .producto-resumen:nth-child(5) { animation-delay: 0.25s; }
        .producto-resumen:nth-child(6) { animation-delay: 0.3s; }
        
        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
                gap: 20px;
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>
    <!-- TAZAS DE CAFÉ ANIMADAS CON TU IMAGEN coffeess.png -->
    <div class="coffee-cups-bg" id="coffeeCupsBg"></div>
    
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

    <div class="checkout-container">
        <div class="resumen-pedido">
            <h2>☕ Resumen de tu pedido</h2>
            <?php foreach($carrito as $item): 
                $subtotal = $item['precio'] * $item['cantidad'];
            ?>
            <div class="producto-resumen">
                <img src="img/imgenes/productos/<?php echo $item['imagen']; ?>" alt="<?php echo $item['nombre']; ?>">
                <div class="producto-info">
                    <h4><?php echo $item['nombre']; ?></h4>
                    <p>Cantidad: <?php echo $item['cantidad']; ?> x $<?php echo number_format($item['precio'], 2); ?></p>
                </div>
                <div class="producto-precio">$<?php echo number_format($subtotal, 2); ?></div>
            </div>
            <?php endforeach; ?>
            <div class="total-final">Total a pagar: $<?php echo number_format($total, 2); ?></div>
            <!-- BOTÓN CORREGIDO: Ahora redirige a tienda.php -->
            <a href="tienda.php" class="volver-carrito">← Volver a la tienda</a>
        </div>

        <div class="metodos-pago">
            <h2>💳 Método de pago</h2>
            
            <?php if(isset($_GET['error']) && $_GET['error'] == 'metodo_invalido'): ?>
                <div class="alert-error">❌ Método de pago no válido. Por favor selecciona una opción.</div>
            <?php endif; ?>
            
            <?php if(isset($_GET['error']) && $_GET['error'] == 'carrito_vacio'): ?>
                <div class="alert-error">❌ Tu carrito está vacío. Agrega productos para continuar.</div>
            <?php endif; ?>
            
            <form id="form-pago" action="procesar_pago.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="total" value="<?php echo $total; ?>">
                
                <!-- Método 1: Efectivo -->
                <div class="metodo-pago" onclick="seleccionarMetodo('efectivo', this)">
                    <input type="radio" name="metodo_pago" id="efectivo" value="efectivo" required>
                    <label for="efectivo"><strong>💵 Efectivo</strong><br><span style="font-size: 12px; color: #9B7B5C; margin-left: 28px;">Paga al recibir tu pedido</span></label>
                </div>

                <!-- Método 2: Transferencia -->
                <div class="metodo-pago" onclick="seleccionarMetodo('transferencia', this)">
                    <input type="radio" name="metodo_pago" id="transferencia" value="transferencia">
                    <label for="transferencia"><strong>🏦 Transferencia bancaria</strong><br><span style="font-size: 12px; color: #9B7B5C; margin-left: 28px;">Sube tu comprobante</span></label>
                </div>

                <!-- Método 3: Tarjeta de crédito/débito -->
                <div class="metodo-pago" onclick="seleccionarMetodo('tarjeta', this)">
                    <input type="radio" name="metodo_pago" id="tarjeta" value="tarjeta">
                    <label for="tarjeta"><strong>💳 Tarjeta de crédito/débito</strong><br><span style="font-size: 12px; color: #9B7B5C; margin-left: 28px;">Visa, Mastercard, American Express</span></label>
                </div>

                <!-- Área para comprobante (transferencia) -->
                <div class="subir-comprobante" id="area-comprobante">
                    <label style="font-weight: 600; color: #4A2C2C;">📎 Sube tu comprobante:</label>
                    <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf" style="margin-top: 10px; padding: 8px;">
                    <p style="font-size:0.8em; color:#9B7B5C; margin-top:8px;">Formatos: JPG, PNG, PDF (máx 5MB)</p>
                </div>

                <!-- Área para datos de tarjeta -->
                <div class="subir-tarjeta" id="area-tarjeta">
                    <h4 style="margin-bottom: 15px; color: #4A2C2C;">💳 Datos de la tarjeta</h4>
                    <input type="text" name="numero_tarjeta" class="input-tarjeta" placeholder="Número de tarjeta" maxlength="19" oninput="formatCardNumber(this)">
                    <div class="fila-tarjeta">
                        <input type="text" name="fecha_expiracion" class="input-tarjeta" placeholder="MM/AA" maxlength="5" oninput="formatExpiry(this)">
                        <input type="text" name="cvv" class="input-tarjeta" placeholder="CVV" maxlength="4">
                    </div>
                    <input type="text" name="nombre_titular" class="input-tarjeta" placeholder="Nombre del titular">
                    <p style="font-size:0.8em; color:#9B7B5C; margin-top:12px;">🔒 Datos seguros. Tu información está protegida.</p>
                </div>

                <button type="submit" class="btn-confirmar">
                    <span>✅</span> Confirmar pedido
                </button>
            </form>
        </div>
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
        // ===== CREAR TAZAS DE CAFÉ ANIMADAS CON TU IMAGEN =====
        function createFloatingCups() {
            const container = document.getElementById('coffeeCupsBg');
            if (!container) return;
            
            // Usar tu imagen coffeess.png (cambia la ruta si es necesario)
            const cupImageUrl = 'img/coffeess.png';
            
            const cupCount = 24;
            
            for (let i = 0; i < cupCount; i++) {
                const cup = document.createElement('div');
                cup.classList.add('floating-cup');
                
                // Tamaño aleatorio
                const sizeRandom = Math.random();
                if (sizeRandom < 0.33) cup.classList.add('cup-small');
                else if (sizeRandom < 0.66) cup.classList.add('cup-medium');
                else cup.classList.add('cup-large');
                
                // Posición horizontal aleatoria
                cup.style.left = Math.random() * 100 + '%';
                
                // Duración aleatoria (10-25 segundos)
                cup.style.animationDuration = (10 + Math.random() * 15) + 's';
                
                // Retraso aleatorio
                cup.style.animationDelay = (Math.random() * 20) + 's';
                
                // Aplicar tu imagen de taza de café
                cup.style.backgroundImage = `url('${cupImageUrl}')`;
                cup.style.backgroundSize = 'contain';
                cup.style.backgroundRepeat = 'no-repeat';
                cup.style.backgroundPosition = 'center';
                
                // Variación de opacidad
                const opacityVariation = 0.05 + Math.random() * 0.1;
                cup.style.opacity = opacityVariation;
                
                container.appendChild(cup);
            }
        }
        
        // ===== SELECCIÓN DE MÉTODO DE PAGO =====
        function seleccionarMetodo(metodo, elemento) {
            document.querySelectorAll('.metodo-pago').forEach(el => el.classList.remove('seleccionado'));
            elemento.classList.add('seleccionado');
            document.getElementById(metodo).checked = true;
            
            document.getElementById('area-comprobante').style.display = 'none';
            document.getElementById('area-tarjeta').style.display = 'none';
            
            if(metodo === 'transferencia') {
                document.getElementById('area-comprobante').style.display = 'block';
            } else if(metodo === 'tarjeta') {
                document.getElementById('area-tarjeta').style.display = 'block';
            }
        }
        
        function formatCardNumber(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 16) value = value.slice(0, 16);
            value = value.replace(/(\d{4})/g, '$1 ').trim();
            input.value = value;
        }
        
        function formatExpiry(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 4) value = value.slice(0, 4);
            if (value.length >= 3) {
                value = value.slice(0, 2) + '/' + value.slice(2);
            }
            input.value = value;
        }
        
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
        
        document.addEventListener('DOMContentLoaded', function() {
            createFloatingCups();
        });
    </script>
    <script src="js/chat.js"></script>
</body>
</html>