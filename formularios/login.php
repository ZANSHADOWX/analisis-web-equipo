<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Login - RUSH</title>
        <link rel="stylesheet" href="../css/formularios.css">
    </head>

    <body>
        <div class="form-container">
            <h2>Iniciar Sesión</h2>
            <form action="validar_login.php" method="POST">
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Ingresar</button>
                <a href="../index.php" class="btn-cancelar"> Cancelar operación </a>
            </form>
            <div class="form-links">
                <a href="registro.php">Crear cuenta</a>
                <a href="recuperar.php">¿Olvidaste tu contraseña?</a>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
</html>