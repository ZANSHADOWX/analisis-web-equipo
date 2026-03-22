<?php

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$tipo = $_POST['tipo'];
$mensaje = $_POST['mensaje'];

$destino = "contacto@rushcafe.com";
$asunto = "Mensaje desde RUSH Café - $tipo";

$contenido = "
Nombre: $nombre
Correo: $correo
Tipo: $tipo

Mensaje:
$mensaje
";

mail($destino,$asunto,$contenido);

header("Location: ../sobre_nosotros.php?mensaje=enviado");

?>