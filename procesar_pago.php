
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

// Validar método de pago (AHORA INCLUYE TARJETA)
if(!in_array($metodo_pago, ['efectivo', 'transferencia', 'tarjeta'])){
    header("Location: checkout.php?error=metodo_invalido");
    exit;
}

// Procesar comprobante (solo para transferencia)
$comprobante_nombre = NULL;
if($metodo_pago == 'transferencia' && isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] == 0){
    $extension = pathinfo($_FILES['comprobante']['name'], PATHINFO_EXTENSION);
    $comprobante_nombre = 'comp_' . time() . '_' . $id_usuario . '.' . $extension;
    if(!file_exists('comprobantes')) mkdir('comprobantes', 0777, true);
    move_uploaded_file($_FILES['comprobante']['tmp_name'], 'comprobantes/' . $comprobante_nombre);
}

// Procesar datos de tarjeta (solo guardamos los últimos 4 dígitos - NUNCA guardes el número completo)
$datos_tarjeta = NULL;
if($metodo_pago == 'tarjeta'){
    // Limpiar el número (solo dígitos)
    $numero = preg_replace('/[^0-9]/', '', $_POST['numero_tarjeta'] ?? '');
    $ultimos_4 = substr($numero, -4);
    $datos_tarjeta = "**** **** **** " . $ultimos_4;
}

// Asegurar que la columna datos_pago existe en la tabla pedidos
$conn->query("ALTER TABLE pedidos ADD COLUMN IF NOT EXISTS datos_pago VARCHAR(100) NULL AFTER comprobante");

// Insertar pedido
$sql_pedido = "INSERT INTO pedidos (id_usuario, total, metodo_pago, estado, comprobante, datos_pago) 
               VALUES ($id_usuario, $total, '$metodo_pago', 'Pendiente', " . 
               ($comprobante_nombre ? "'$comprobante_nombre'" : "NULL") . ", " .
               ($datos_tarjeta ? "'$datos_tarjeta'" : "NULL") . ")";
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
    'fecha' => date('Y-m-d H:i:s')
];

// Vaciar carrito
unset($_SESSION['carrito']);

// Redirigir al ticket
header("Location: ticket.php?id=" . $id_pedido);
exit;
?>