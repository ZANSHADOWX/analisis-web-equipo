<?php
    session_start();
    $total = 0;
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Carrito</title>
        <link rel="stylesheet" href="css/carrito.css">
    </head>

    <body>

        <div class="carrito-container">
            <h1>Carrito de compras</h1>

            <?php if(isset($_SESSION["usuario"])) { ?>
                <p class="usuario">Usuario: <b><?php echo $_SESSION["usuario"]; ?></b></p>
            <?php } else { ?>
                <p class="alerta">
                Debes iniciar sesión para poder finalizar la compra
                </p>
            <?php } ?>


            <?php
                if(!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0){
                    echo "<p class='vacio'>Tu carrito está vacío</p>";
                }else{
            ?>

            <table class="tabla-carrito">
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>

                <?php
                    foreach($_SESSION['carrito'] as $producto){

                    $subtotal = $producto['precio'] * $producto['cantidad'];
                    $total += $subtotal;
                ?>
                <tr>

                    <td class="producto">
                        <img src="../img/imgenes/productos/<?php echo $producto['imagen']; ?>">
                        <span class="nombre-producto">
                            <?php echo $producto['nombre']; ?>
                        </span>
                    </td>
                    
                    <td>
                        $<?php echo $producto['precio']; ?>
                    </td>

                    <td>
                        <div class="cantidad">
                            <a href="restar.php?id=<?php echo $producto['id']; ?>">-</a>
                            <span><?php echo $producto['cantidad']; ?></span>
                            <a href="sumar.php?id=<?php echo $producto['id']; ?>">+</a>
                        </div>
                    </td>

                    <td>
                        $<?php echo $subtotal; ?>
                    </td>

                    <td>
                        <a class="btn-eliminar" href="eliminar.php?id=<?php echo $producto['id']; ?>">
                            Eliminar
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </table>

            <div class="total">
                <h2>Total: $<?php echo $total; ?></h2>
            </div>
            <?php } ?>

            <!-- BOTONES SIEMPRE VISIBLES -->
            <div class="botones">
                <a class="btn-volver-tienda" href="../tienda.php">
                    ← Seguir comprando
                </a>
                <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0){ ?>
                    <a class="btn-vaciar" href="vaciar.php">
                        Vaciar carrito
                    </a>
                    <?php if(isset($_SESSION["usuario"])) { ?>
                        <a class="btn-finalizar" href="../compra/checkout.php">
                            Finalizar compra
                        </a>
                    <?php } else { ?>
                        <a class="btn-finalizar" href="../formularios/login.php">
                            Iniciar sesión para comprar
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </body>
</html>