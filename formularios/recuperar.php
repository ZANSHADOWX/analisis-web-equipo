<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Recuperar contraseña</title>
        <link rel="stylesheet" href="../css/formularios.css">
    </head>

    <body>
        <div class="form-container">
            <h2>Recuperar contraseña</h2>
            <form action="enviar_recuperacion.php" method="POST">
                <input type="email" name="email" placeholder="Ingresa tu correo" required>
                <button type="submit">Enviar enlace</button>
                <a href="../index.php" class="btn-cancelar"> Cancelar operación </a>
            </form>
            <div class="form-links">
                <a href="login.php">Volver al login</a>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
</html>