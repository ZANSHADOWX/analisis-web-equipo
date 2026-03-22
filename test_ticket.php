
<?php
session_start();
include("conexion.php");

$id_pedido = $_GET['id'] ?? 8;

echo "<h2>Diagnóstico Ticket ID: $id_pedido</h2>";

// 1. Verificar conexión
if($conn){
    echo "✅ Conexión a BD exitosa<br><br>";
} else {
    echo "❌ Error de conexión<br>";
}

// 2. Verificar pedido
$sql = "SELECT * FROM pedidos WHERE id_pedido = $id_pedido";
$result = $conn->query($sql);

if($result && $result->num_rows > 0){
    $pedido = $result->fetch_assoc();
    echo "✅ Pedido encontrado:<br>";
    echo "ID: {$pedido['id_pedido']}<br>";
    echo "Usuario: {$pedido['id_usuario']}<br>";
    echo "Total: \${$pedido['total']}<br>";
    echo "Método: {$pedido['metodo_pago']}<br>";
    echo "Estado: {$pedido['estado']}<br><br>";
    
    // 3. Verificar detalles
    $sql_det = "SELECT d.*, p.nombre 
                FROM detalle_pedido d
                JOIN producto p ON d.id_producto = p.id_producto
                WHERE d.id_pedido = $id_pedido";
    $detalles = $conn->query($sql_det);
    
    if($detalles && $detalles->num_rows > 0){
        echo "✅ Productos encontrados:<br>";
        while($item = $detalles->fetch_assoc()){
            echo "- {$item['nombre']} x {$item['cantidad']} = \${$item['subtotal']}<br>";
        }
    } else {
        echo "❌ No hay productos para este pedido<br>";
    }
} else {
    echo "❌ Pedido ID $id_pedido NO encontrado<br>";
    echo "Error SQL: " . $conn->error . "<br><br>";
    
    // Mostrar todos los pedidos existentes
    $todos = $conn->query("SELECT * FROM pedidos");
    if($todos && $todos->num_rows > 0){
        echo "Pedidos existentes:<br>";
        while($p = $todos->fetch_assoc()){
            echo "- ID: {$p['id_pedido']} | Total: \${$p['total']} | Método: {$p['metodo_pago']}<br>";
        }
    } else {
        echo "No hay pedidos en la base de datos<br>";
    }
}
?>