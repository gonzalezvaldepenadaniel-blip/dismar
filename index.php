<?php
require_once("config/conexion.php");

if (isset($_POST["enviar"]) && $_POST["enviar"] == "si") {
    require_once("models/Usuario.php");
    $usuario = new Usuario();
    $usuario->login("user");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dismar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="public/css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/lib/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="index.css">
</head>

<body>

<div class="login-wrapper">
    <div class="login-box">

   <a href="/Dismar/admin-login.php" class="logo-link">
    <img src="public/img/dismar.png" alt="Dismar">
</a>


       

        <?php if (isset($_GET["m"])): ?>
            <div class="alert alert-danger text-center">
                <?= $_GET["m"] == 1 ? "Usuario y/o contraseña incorrectos" : "Campos vacíos" ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <div class="form-group mb-3">
                <input type="text" name="usu_correo"
                       class="form-control"
                       placeholder="Email"
                       required>
            </div>

            <div class="form-group password-group mb-3">
                <input type="password" id="usu_pass" name="usu_pass"
                       class="form-control"
                       placeholder="Contraseña"
                       required>

                <span class="toggle-password" onclick="verPassword()">
                    <i id="icono" class="fa fa-eye"></i>
                </span>
            </div>

            <input type="hidden" name="enviar" value="si">

            <button class="btn btn-primary w-100">
                Iniciar Sesión
            </button>

          

        </form>
    </div>
</div>

<!-- JS -->
<script src="public/js/lib/jquery/jquery.min.js"></script>
<script src="public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="index.js"></script>

</body>
</html>
