<?php
    include("../conexion.php");

    if(!isset($_POST['token'])){
     header("Location: recuperar.php");
    exit();
    }

    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "UPDATE usuarios SET password='$password', token=NULL WHERE token='$token'";
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Actualizando contraseña</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>

        <?php

            if($conn->query($sql)){

                echo "
                <script>

                Swal.fire({
                icon:'success',
                title:'Contraseña actualizada',
                text:'Ahora puedes iniciar sesión',
                confirmButtonColor:'#6d4c41'
                }).then(()=>{
                window.location='login.php';
                });

                </script>
                ";

            }else{

                echo "
                <script>

                Swal.fire({
                icon:'error',
                title:'Error al actualizar contraseña'
                }).then(()=>{
                window.location='recuperar.php';
                });

                </script>
                ";

            }

        ?>

    </body>
</html>