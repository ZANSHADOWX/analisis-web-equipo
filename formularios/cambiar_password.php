<?php
    include("../conexion.php");

        if(!isset($_GET['token'])){
        header("Location: recuperar.php");
        exit();
    }

    $token = $_GET['token'];
?>

<!DOCTYPE html>
<html lang="es">
    <head>

    <meta charset="UTF-8">
        <title>Nueva contraseña</title>

        <link rel="stylesheet" href="../css/cambio_password.css">

    </head>

    <body>

        <div class="form-container">

            <h2>Nueva contraseña</h2>

            <form action="guardar_password.php" method="POST">

                <input type="hidden" name="token" value="<?php echo $token; ?>">

                <label>Nueva contraseña</label>
                <input type="password" name="password" required>

                <button type="submit">Guardar contraseña</button>

                <a href="login.php" class="cancelar">Cancelar</a>

            </form>

        </div>

    </body>
</html>