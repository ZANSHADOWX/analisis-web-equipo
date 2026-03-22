<?php
session_start();
require_once("../conexion.php");

if(!isset($_SESSION['usuario'])){
header("Location: ../formularios/login.php");
exit();
}

$usuario = $_SESSION['usuario'];

$sql="SELECT nombre,email,telefono,password FROM usuarios WHERE nombre='$usuario'";
$resultado=mysqli_query($conn,$sql);
$datos=mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar perfil</title>
<link rel="stylesheet" href="css/editar_perfil.css">
</head>

<body>

<div class="form-box">

<h2>Editar perfil</h2>

<form action="actualizar_usuario.php" method="POST">

<label>Nombre</label>
<input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" required>

<label>Correo</label>
<input type="email" name="email" value="<?php echo $datos['email']; ?>" required>

<label>Teléfono</label>
<input type="text" name="telefono" value="<?php echo $datos['telefono']; ?>">

<hr>

<h3>Cambiar contraseña</h3>

<label>Contraseña actual</label>
<input type="password" name="password_actual">

<label>Nueva contraseña</label>
<input type="password" name="password_nueva">

<label>Confirmar contraseña</label>
<input type="password" name="confirmar_password">

<button type="submit">Actualizar datos</button>

</form>

<a href="perfil.php">
<button class="cancelar">Cancelar</button>
</a>

</div>

</body>
</html>