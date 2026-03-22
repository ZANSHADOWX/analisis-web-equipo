<?php
    include("../conexion.php");

    if(!isset($_POST['email'])){
        header("Location: recuperar.php");
        exit();
    }

    $email = $_POST['email'];

    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Recuperación</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>

        <?php

            if($resultado->num_rows > 0){

                $token = bin2hex(random_bytes(16));

                $conn->query("UPDATE usuarios SET token='$token' WHERE email='$email'");

                $link = "http://localhost/project_coffe/formularios/cambiar_password.php?token=$token";

                echo "
                <script>

                Swal.fire({
                icon:'success',
                title:'Correo encontrado',
                html:'Presiona para cambiar contraseña:<br><br><a href=\"$link\">Cambiar contraseña</a>'
                });

                </script>
                ";

            }else{

                echo "
                <script>

                Swal.fire({
                icon:'error',
                title:'Correo no encontrado'
                }).then(()=>{
                window.location='recuperar.php';
                });

                </script>
                ";

            }

        ?>

    </body>
</html>