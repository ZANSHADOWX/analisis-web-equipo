<?php
    session_start();
    include("../conexion.php");
    $id = $_GET['id'];

    /* BUSCAR PRODUCTO */
    $sql = "SELECT * FROM producto WHERE id_producto='$id'";
    $result = mysqli_query($conn,$sql);
    $producto = mysqli_fetch_assoc($result);

    if(!$producto){
        $ref = isset($_GET['ref']) ? $_GET['ref'] : "";
        header("Location: ../tienda.php#$ref");
        exit();
    }

    /* CREAR ARRAY PRODUCTO */
    $item = [
        "id" => $producto['id_producto'],
        "nombre" => $producto['nombre'],
        "precio" => $producto['precio'],
        "imagen" => $producto['imagen'],
        "cantidad" => 1
    ];

    /* CREAR CARRITO SI NO EXISTE */
    if(!isset($_SESSION['carrito'])){
        $_SESSION['carrito'] = [];
    }

    /* VERIFICAR SI YA EXISTE */
    $existe = false;

    foreach($_SESSION['carrito'] as &$prod){
        if($prod['id'] == $id){

            $prod['cantidad']++;
            $existe = true;
            break;

        }
    }

    /* SI NO EXISTE LO AGREGA */
    if(!$existe){
        $_SESSION['carrito'][] = $item;
    }

    /* REGRESA A LA TIENDA */
    header("Location: ../tienda.php");
?>