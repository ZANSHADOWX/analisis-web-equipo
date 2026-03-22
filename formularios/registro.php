<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Registro - RUSH</title>
        <link rel="stylesheet" href="../css/formularios.css">
    </head>

    <body>
        <div class="form-container">
            <h2>Crear Cuenta</h2>
            <form action="guardar_usuario.php" method="POST">
                <input type="text" name="nombre" placeholder="Nombre completo" required>
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <input type="text" name="telefono" placeholder="Teléfono">
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Registrarse</button>
                <a href="../index.php" class="btn-cancelar"> Cancelar operación </a>
            </form>
            <div class="form-links">
                <a href="login.php">Ya tengo cuenta</a>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
</html>