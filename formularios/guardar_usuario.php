<?php
    include("../conexion.php");

    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $verificar = "SELECT * FROM usuarios WHERE email='$email'";
    $resultado = $conn->query($verificar);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Registro</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>

        <?php

            if($resultado->num_rows > 0){

                echo "
                <script>
                Swal.fire({
                icon: 'warning',
                title: 'Correo ya registrado',
                text: 'Intenta con otro correo',
                confirmButtonColor:'#6d4c41'
                }).then(() => {
                window.location = 'registro.php';
                });
                </script>
                ";

            }else{

                $sql = "INSERT INTO usuarios (nombre,email,telefono,password)
                VALUES ('$nombre','$email','$telefono','$password')";

                if($conn->query($sql)){

                    echo "
                    <script>
                    Swal.fire({
                    icon: 'success',
                    title: 'Registro exitoso',
                    text: 'Tu cuenta fue creada correctamente',
                    confirmButtonColor:'#6d4c41'
                    }).then(() => {
                    window.location = 'login.php';
                    });
                    </script>
                    ";

                }else{

                    echo "
                    <script>
                    Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo registrar el usuario',
                    confirmButtonColor:'#6d4c41'
                    }).then(() => {
                    window.location = 'registro.php';
                    });
                    </script>
                    ";

                }

            }

        ?>

    </body>
</html>