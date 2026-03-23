
<?php
session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario'])){
    header("Location: formularios/login.php");
    exit;
}

if(!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0){
    header("Location: tienda.php?error=carrito_vacio");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$metodo_pago = $_POST['metodo_pago'] ?? '';
$total = $_POST['total'] ?? 0;

// Validar método de pago
if(!in_array($metodo_pago, ['efectivo', 'transferencia', 'tarjeta'])){
    header("Location: checkout.php?error=metodo_invalido");
    exit;
}

// Procesar comprobante (solo para transferencia)
$comprobante_nombre = NULL;
$estado_pedido = 'Pendiente'; // Por defecto

if($metodo_pago == 'transferencia'){
    $estado_pedido = 'Pendiente'; // Esperando validación
    if(isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] == 0){
        $extension = pathinfo($_FILES['comprobante']['name'], PATHINFO_EXTENSION);
        $comprobante_nombre = 'comp_' . time() . '_' . $id_usuario . '.' . $extension;
        if(!file_exists('comprobantes')) mkdir('comprobantes', 0777, true);
        move_uploaded_file($_FILES['comprobante']['tmp_name'], 'comprobantes/' . $comprobante_nombre);
    }
} elseif($metodo_pago == 'tarjeta'){
    // Pago con tarjeta: se considera EXITOSO automáticamente
    $estado_pedido = 'Pagado';
    
    // Procesar datos de tarjeta (solo guardamos los últimos 4 dígitos)
    $numero = preg_replace('/[^0-9]/', '', $_POST['numero_tarjeta'] ?? '');
    if(strlen($numero) >= 4){
        $ultimos_4 = substr($numero, -4);
        $datos_tarjeta = "**** **** **** " . $ultimos_4;
    } else {
        $datos_tarjeta = "Tarjeta registrada";
    }
} elseif($metodo_pago == 'efectivo'){
    // Efectivo: queda pendiente hasta que se reciba el pago
    $estado_pedido = 'Pendiente';
}

// Insertar pedido
$sql_pedido = "INSERT INTO pedidos (id_usuario, total, metodo_pago, estado, comprobante, datos_pago) 
               VALUES ($id_usuario, $total, '$metodo_pago', '$estado_pedido', " . 
               ($comprobante_nombre ? "'$comprobante_nombre'" : "NULL") . ", " .
               (isset($datos_tarjeta) && $datos_tarjeta ? "'$datos_tarjeta'" : "NULL") . ")";
$conn->query($sql_pedido);
$id_pedido = $conn->insert_id;

// Insertar detalles del pedido
$productos_detalle = [];
foreach($_SESSION['carrito'] as $item){
    $precio_unitario = $item['precio'];
    $subtotal = $precio_unitario * $item['cantidad'];
    $conn->query("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario, subtotal) 
                  VALUES ($id_pedido, {$item['id']}, {$item['cantidad']}, $precio_unitario, $subtotal)");
    $productos_detalle[] = $item;
}

// Guardar datos del pedido en sesión para el ticket
$_SESSION['ultimo_pedido'] = [
    'id' => $id_pedido,
    'total' => $total,
    'metodo_pago' => $metodo_pago,
    'productos' => $productos_detalle,
    'fecha' => date('Y-m-d H:i:s'),
    'estado' => $estado_pedido
];

// Vaciar carrito
unset($_SESSION['carrito']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesando pedido - RUSH Café</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow: hidden;
            background: linear-gradient(135deg, #5D3A1A 0%, #3E2A1A 100%);
        }
        
        .confirmation-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #5D3A1A 0%, #3E2A1A 100%);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.5s ease;
        }
        
        .confirmation-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .confirmation-card {
            background: #FFF9F0;
            border-radius: 48px;
            padding: 48px 40px;
            text-align: center;
            max-width: 90%;
            width: 420px;
            box-shadow: 0 30px 50px rgba(0,0,0,0.3);
            transform: scale(0.8);
            transition: transform 0.5s cubic-bezier(0.34, 1.2, 0.64, 1);
        }
        
        .confirmation-overlay.active .confirmation-card {
            transform: scale(1);
        }
        
        .checkmark-wrapper {
            margin-bottom: 24px;
        }
        
        .checkmark-circle {
            width: 110px;
            height: 110px;
            background: <?php echo $metodo_pago == 'tarjeta' ? '#2E7D32' : '#C97E3A'; ?>;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulsePop 0.6s cubic-bezier(0.34, 1.2, 0.64, 1);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        
        .checkmark {
            width: 55px;
            height: 55px;
            border-right: 6px solid white;
            border-bottom: 6px solid white;
            transform: rotate(45deg);
            margin-top: -10px;
            animation: drawCheck 0.5s ease-out 0.2s forwards;
            opacity: 0;
        }
        
        @keyframes pulsePop {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes drawCheck {
            0% { width: 0; height: 0; opacity: 0; }
            100% { width: 55px; height: 55px; opacity: 1; }
        }
        
        .confirmation-card h2 {
            color: #4A2A12;
            font-size: 28px;
            margin-bottom: 16px;
            font-weight: 600;
        }
        
        .order-info {
            background: #F5EDE3;
            border-radius: 24px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .order-info p {
            color: #5D3A1A;
            margin: 8px 0;
            font-size: 16px;
        }
        
        .order-info strong {
            color: #C97E3A;
        }
        
        .total-amount {
            font-size: 28px;
            font-weight: bold;
            color: #C97E3A;
        }
        
        .estado-mensaje {
            background: <?php echo $metodo_pago == 'tarjeta' ? '#e8f5e9' : '#fff3cd'; ?>;
            padding: 12px;
            border-radius: 16px;
            margin-top: 15px;
            font-size: 14px;
            color: <?php echo $metodo_pago == 'tarjeta' ? '#2e7d32' : '#856404'; ?>;
        }
        
        .ticket-button {
            background: #C97E3A;
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
            font-family: inherit;
        }
        
        .ticket-button:hover {
            background: #A55D2A;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(201, 126, 58, 0.3);
        }
        
        .coffee-leaves {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }
        
        .leaf {
            position: absolute;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23D4A373"><path d="M12,2C9,7,4,9,4,14c0,4,4,6,8,6s8-2,8-6C20,9,15,7,12,2z"/></svg>');
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.2;
            animation: floatLeaf linear infinite;
        }
        
        @keyframes floatLeaf {
            0% { transform: translateY(-10vh) rotate(0deg); opacity: 0; }
            15% { opacity: 0.2; }
            85% { opacity: 0.2; }
            100% { transform: translateY(110vh) rotate(360deg); opacity: 0; }
        }
        
        .leaf-small { width: 25px; height: 25px; opacity: 0.15; }
        .leaf-medium { width: 40px; height: 40px; }
        .leaf-large { width: 65px; height: 65px; opacity: 0.1; }
        
        .redirect-message {
            margin-top: 20px;
            font-size: 13px;
            color: #9B7B5C;
        }
        
        .spinner {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid #9B7B5C;
            border-top-color: #C97E3A;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-left: 8px;
            vertical-align: middle;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="coffee-leaves" id="coffeeLeaves"></div>
    
    <div class="confirmation-overlay" id="confirmationOverlay">
        <div class="confirmation-card">
            <div class="checkmark-wrapper">
                <div class="checkmark-circle">
                    <div class="checkmark"></div>
                </div>
            </div>
            <h2>
                <?php 
                if($metodo_pago == 'tarjeta'){
                    echo '✅ ¡Pago exitoso!';
                } elseif($metodo_pago == 'transferencia'){
                    echo '📤 ¡Pedido recibido!';
                } else {
                    echo '📋 ¡Pedido confirmado!';
                }
                ?>
            </h2>
            <div class="order-info">
                <p><strong>Pedido #<?php echo $id_pedido; ?></strong></p>
                <p>Método de pago: 
                    <strong>
                        <?php 
                        switch($metodo_pago) {
                            case 'efectivo': echo 'Efectivo 💵'; break;
                            case 'transferencia': echo 'Transferencia 🏦'; break;
                            case 'tarjeta': echo 'Tarjeta 💳'; break;
                        }
                        ?>
                    </strong>
                </p>
                <p class="total-amount">Total: $<?php echo number_format($total, 2); ?></p>
                
                <div class="estado-mensaje">
                    <?php if($metodo_pago == 'tarjeta'): ?>
                        🎉 El pago se ha procesado correctamente. Tu pedido está confirmado.
                    <?php elseif($metodo_pago == 'transferencia'): ?>
                        📌 Tu pedido está pendiente. Una vez que validemos tu comprobante, recibirás un correo de confirmación.
                    <?php else: ?>
                        📌 Tu pedido está pendiente. Te contactaremos para coordinar el pago.
                    <?php endif; ?>
                </div>
            </div>
            <button class="ticket-button" onclick="verTicket()">
                📄 Ver mi ticket
            </button>
            <div class="redirect-message">
                Redirigiendo al ticket en <span id="countdown">5</span> segundos <span class="spinner"></span>
            </div>
        </div>
    </div>
    
    <script>
        function createCoffeeLeaves() {
            const container = document.getElementById('coffeeLeaves');
            const leafCount = 20;
            
            for (let i = 0; i < leafCount; i++) {
                const leaf = document.createElement('div');
                leaf.classList.add('leaf');
                
                const sizeRandom = Math.random();
                if (sizeRandom < 0.33) leaf.classList.add('leaf-small');
                else if (sizeRandom < 0.66) leaf.classList.add('leaf-medium');
                else leaf.classList.add('leaf-large');
                
                leaf.style.left = Math.random() * 100 + '%';
                leaf.style.animationDuration = (8 + Math.random() * 12) + 's';
                leaf.style.animationDelay = (Math.random() * 15) + 's';
                
                container.appendChild(leaf);
            }
        }
        
        function verTicket() {
            window.location.href = 'ticket.php?id=<?php echo $id_pedido; ?>';
        }
        
        let seconds = 5;
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            seconds--;
            if (countdownElement) {
                countdownElement.textContent = seconds;
            }
            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = 'ticket.php?id=<?php echo $id_pedido; ?>';
            }
        }, 1000);
        
        document.addEventListener('DOMContentLoaded', () => {
            createCoffeeLeaves();
            setTimeout(() => {
                document.getElementById('confirmationOverlay').classList.add('active');
            }, 100);
        });
    </script>
</body>
</html>
<?php
exit;
?>