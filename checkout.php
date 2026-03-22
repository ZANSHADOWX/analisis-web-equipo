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
    <title>Finalizar compra - RUSH</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/barra_menu_1.css">
    <link rel="stylesheet" href="css/cont_tienda.css">
    <link rel="stylesheet" href="css/footer.css">
    <style>
        .checkout-container { max-width: 1200px; margin: 40px auto; padding: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .resumen-pedido { background: #f9f9f9; padding: 20px; border-radius: 8px; }
        .metodos-pago { background: white; padding: 20px; border-radius: 8px; border: 1px solid #ddd; }
        .producto-resumen { display: flex; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .producto-resumen img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; margin-right: 15px; }
        .total-final { font-size: 1.5em; font-weight: bold; color: #4a2c2c; margin-top: 20px; text-align: right; }
        .metodo-pago { margin: 15px 0; padding: 15px; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s; }
        .metodo-pago:hover { border-color: #4a2c2c; background: #f5f5f5; }
        .metodo-pago.seleccionado { border-color: #28a745; background: #e8f5e9; }
        .metodo-pago input { margin-right: 10px; }
        .btn-confirmar { background: #28a745; color: white; padding: 15px 30px; border: none; border-radius: 4px; font-size: 1.2em; cursor: pointer; width: 100%; margin-top: 20px; }
        .subir-comprobante { margin-top: 15px; padding: 15px; background: #f0f0f0; border-radius: 4px; display: none; }
        .subir-tarjeta { margin-top: 15px; padding: 15px; background: #f9f9f9; border-radius: 8px; display: none; }
        .input-tarjeta { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
        .input-tarjeta:focus { outline: none; border-color: #4a2c2c; }
        .fila-tarjeta { display: flex; gap: 10px; margin-top: 10px; }
        .fila-tarjeta .input-tarjeta { width: auto; flex: 1; }
        .volver-carrito { display: inline-block; margin-top: 20px; color: #4a2c2c; text-decoration: none; }
        h4 { margin: 0 0 10px 0; color: #4a2c2c; }
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

    <div class="checkout-container">
        <div class="resumen-pedido">
            <h2>Resumen de tu pedido</h2>
            <?php foreach($carrito as $item): 
                $subtotal = $item['precio'] * $item['cantidad'];
            ?>
            <div class="producto-resumen">
                <img src="img/imgenes/productos/<?php echo $item['imagen']; ?>">
                <div style="flex-grow:1;">
                    <h4><?php echo $item['nombre']; ?></h4>
                    <p>Cantidad: <?php echo $item['cantidad']; ?> x $<?php echo number_format($item['precio'], 2); ?></p>
                </div>
                <div>$<?php echo number_format($subtotal, 2); ?></div>
            </div>
            <?php endforeach; ?>
            <div class="total-final">Total a pagar: $<?php echo number_format($total, 2); ?></div>
            <a href="carrito.php" class="volver-carrito">← Volver al carrito</a>
        </div>

        <div class="metodos-pago">
            <h2>Método de pago</h2>
            <form id="form-pago" action="procesar_pago.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="total" value="<?php echo $total; ?>">
                
                <!-- Método 1: Efectivo -->
                <div class="metodo-pago" onclick="seleccionarMetodo('efectivo')">
                    <input type="radio" name="metodo_pago" id="efectivo" value="efectivo" required>
                    <label for="efectivo"><strong>💵 Efectivo</strong><p style="margin:5px 0 0 20px;">Paga al recibir tu pedido</p></label>
                </div>

                <!-- Método 2: Transferencia -->
                <div class="metodo-pago" onclick="seleccionarMetodo('transferencia')">
                    <input type="radio" name="metodo_pago" id="transferencia" value="transferencia">
                    <label for="transferencia"><strong>🏦 Transferencia bancaria</strong><p style="margin:5px 0 0 20px;">Sube tu comprobante</p></label>
                </div>

                <!-- Método 3: Tarjeta de crédito/débito -->
                <div class="metodo-pago" onclick="seleccionarMetodo('tarjeta')">
                    <input type="radio" name="metodo_pago" id="tarjeta" value="tarjeta">
                    <label for="tarjeta"><strong>💳 Tarjeta de crédito/débito</strong><p style="margin:5px 0 0 20px;">Visa, Mastercard, American Express</p></label>
                </div>

                <!-- Área para comprobante (transferencia) -->
                <div class="subir-comprobante" id="area-comprobante">
                    <label>Sube tu comprobante:</label>
                    <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf">
                </div>

                <!-- Área para datos de tarjeta -->
                <div class="subir-tarjeta" id="area-tarjeta">
                    <h4>Datos de la tarjeta</h4>
                    <input type="text" name="numero_tarjeta" class="input-tarjeta" placeholder="Número de tarjeta" maxlength="19">
                    <div class="fila-tarjeta">
                        <input type="text" name="fecha_expiracion" class="input-tarjeta" placeholder="MM/AA" maxlength="5">
                        <input type="text" name="cvv" class="input-tarjeta" placeholder="CVV" maxlength="4">
                    </div>
                    <input type="text" name="nombre_titular" class="input-tarjeta" placeholder="Nombre del titular">
                    <p style="font-size:0.8em; color:#666; margin-top:10px;">🔒 Datos seguros. Tu información está protegida.</p>
                </div>

                <button type="submit" class="btn-confirmar">Confirmar pedido</button>
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
        function seleccionarMetodo(metodo) {
            // Quitar clase seleccionado de todos
            document.querySelectorAll('.metodo-pago').forEach(el => el.classList.remove('seleccionado'));
            // Agregar clase al seleccionado
            event.currentTarget.classList.add('seleccionado');
            // Marcar radio button
            document.getElementById(metodo).checked = true;
            
            // Ocultar todas las áreas
            document.getElementById('area-comprobante').style.display = 'none';
            document.getElementById('area-tarjeta').style.display = 'none';
            
            // Mostrar según método
            if(metodo === 'transferencia') {
                document.getElementById('area-comprobante').style.display = 'block';
            } else if(metodo === 'tarjeta') {
                document.getElementById('area-tarjeta').style.display = 'block';
            }
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
    </script>
    <script src="js/chat.js"></script>
</body>
</html>