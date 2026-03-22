<?php
session_start();
require_once("../conexion.php");

$usuario=$_SESSION['usuario'];

$nombre=$_POST['nombre'];
$email=$_POST['email'];
$telefono=$_POST['telefono'];

$sql="UPDATE usuarios 
SET nombre='$nombre',
email='$email',
telefono='$telefono'
WHERE nombre='$usuario'";

if(mysqli_query($conn,$sql)){

$_SESSION['usuario']=$nombre;

header("Location: confirmacion_actualizacion.php");

}else{

echo "Error al actualizar";

}
?>