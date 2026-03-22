<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "rush_cafe";

$conn = new mysqli($host, $user, $password, $db);

// Verificar conexión
if ($conn->connect_error) {
    // Redirigir automáticamente al error 505
    header("Location: extras_2/error505.html");
    exit(); // detener la ejecución del script
}
?>