
<?php
session_start();
include("conexion.php");

// Usuario Aride (id=8)
$_SESSION['id_usuario'] = 8;
$_SESSION['usuario'] = 'Aride';

echo "<h2>✅ Sesión iniciada manualmente</h2>";
echo "ID Usuario: " . $_SESSION['id_usuario'] . "<br>";
echo "Usuario: " . $_SESSION['usuario'] . "<br>";
echo '<a href="checkout.php">Ir a checkout</a>';
?>