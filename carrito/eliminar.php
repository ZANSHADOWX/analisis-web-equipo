<?php
    session_start();
    $id = $_GET['id'];
    foreach($_SESSION['carrito'] as $key => $producto){
        if($producto['id'] == $id){
            unset($_SESSION['carrito'][$key]);
        }
    }
    header("Location: carrito.php");
?>