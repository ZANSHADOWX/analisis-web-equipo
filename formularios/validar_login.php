<?php
    session_start();
    include("../conexion.php");

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Validando usuario</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>

        <?php

        if($resultado->num_rows > 0){

            $usuario = $resultado->fetch_assoc();

                if(password_verify($password,$usuario['password'])){

                    $_SESSION['usuario'] = $usuario['nombre'];
                    header("Location: ../index.php");
                    exit();

                }else{

                    echo "
                    <script>
                    Swal.fire({
                    icon: 'error',
                    title: 'Contraseña incorrecta',
                    confirmButtonColor:'#6d4c41'
                    }).then(() => {
                    window.location = 'login.php';
                    });
                    </script>
                    ";

                }

            }else{

                echo "
                <script>
                Swal.fire({
                icon: 'error',
                title: 'Usuario no encontrado',
                confirmButtonColor:'#6d4c41'
                }).then(() => {
                window.location = 'login.php';
                });
                </script>
                ";

            }

        ?>

    </body>
</html>