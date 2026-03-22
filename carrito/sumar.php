<?php
    session_start();
    $id = $_GET['id'];
    foreach($_SESSION['carrito'] as &$producto){
        if($producto['id'] == $id){
            $producto['cantidad']++;
        }
    }
    header("Location: carrito.php");
?>