<?php
session_start();
require_once("../conexion.php");

if(!isset($_SESSION['usuario'])){
header("Location: ../formularios/login.php");
exit();
}

$usuario = $_SESSION['usuario'];

/* SI CONFIRMA LA ELIMINACION */

if(isset($_POST['confirmar'])){

$sql = "DELETE FROM usuarios WHERE nombre='$usuario' LIMIT 1";

if(mysqli_query($conn,$sql)){

session_destroy();

header("Location: confirmacion_eliminacion.php");
exit();

}else{

echo "Error al eliminar";

}

}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<title>Eliminar cuenta</title>

<link rel="stylesheet" href="css/confirmacion.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

<div class="form-container">

<div class="check-icon"></div>

<h2>Eliminar cuenta</h2>

<p class="mensaje">
Se abrirá una confirmación para eliminar tu cuenta.
</p>

</div>

<script>

Swal.fire({
title: '¿Eliminar cuenta?',
text: "Esta acción eliminará tu cuenta permanentemente",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#6d4c41',
cancelButtonColor: '#9e9e9e',
confirmButtonText: 'Sí, eliminar',
cancelButtonText: 'Cancelar'
}).then((result) => {

if(result.isConfirmed){

let form = document.createElement("form");
form.method = "POST";

let input = document.createElement("input");
input.type = "hidden";
input.name = "confirmar";
input.value = "1";

form.appendChild(input);
document.body.appendChild(form);
form.submit();

}else{

window.location="perfil.php";

}

});

</script>

</body>
</html>