<?php
    session_start();
    include("../conexion.php");

    $id = $_POST['id'];

    $sql = "SELECT * FROM producto WHERE id_producto='$id'";
    $result = mysqli_query($conn,$sql);
    $producto = mysqli_fetch_assoc($result);

    if(!isset($_SESSION['carrito'])){
        $_SESSION['carrito'] = [];
    }

    if(isset($_SESSION['carrito'][$id])){
        $_SESSION['carrito'][$id]['cantidad']++;
    }else{
        $_SESSION['carrito'][$id] = [
            "id" => $producto['id_producto'],
            "nombre" => $producto['nombre'],
            "precio" => $producto['precio'],
            "imagen" => $producto['imagen'],
            "cantidad" => 1
        ];
    }

    $cantidad = 0;

    foreach($_SESSION['carrito'] as $prod){
        $cantidad += $prod['cantidad'];
    }

    echo $cantidad;
?>