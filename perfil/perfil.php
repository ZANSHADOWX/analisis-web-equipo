<?php
session_start();
require_once("../conexion.php");

if(!isset($_SESSION['usuario'])){
header("Location: ../formularios/login.php");
exit();
}

$usuario = $_SESSION['usuario'];

$sql = "SELECT nombre,email,telefono FROM usuarios WHERE nombre='$usuario'";
$resultado = mysqli_query($conn,$sql);
$datos = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<title>Perfil</title>

<link rel="stylesheet" href="css/perfil.css">

</head>

<body>

<div class="perfil-card">

<h2>Perfil de usuario</h2>

<p><strong>Nombre:</strong> <?php echo $datos['nombre']; ?></p>
<p><strong>Correo:</strong> <?php echo $datos['email']; ?></p>
<p><strong>Teléfono:</strong> <?php echo $datos['telefono']; ?></p>

<div class="botones">

<a href="editar_perfil.php">
<button class="editar">Editar datos</button>
</a>

<a href="eliminar_usuario.php">
<button class="eliminar">Eliminar cuenta</button>
</a>

<a href="../index.php">
<button class="volver">Volver</button>
</a>

</div>

</div>

</body>
</html>