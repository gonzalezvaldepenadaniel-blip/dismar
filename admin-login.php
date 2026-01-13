<?php
require_once("config/conexion.php");

if (isset($_POST["enviar"]) && $_POST["enviar"] == "si") {
    require_once("models/Usuario.php");
    $usuario = new Usuario();
    $usuario->login("admin");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dismar</title>

  

    
    <link rel="stylesheet" href="public/css/login.css">
</head>
<body>

<div class="admin-login">
    <div class="card">

        <h3 class="text-center">ADMINISTRADOR</h3>

        <?php if (isset($_GET["m"]) && $_GET["m"] == 1): ?>
            <div class="alert alert-danger text-center">
                Credenciales incorrectas
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="email" name="usu_correo" class="form-control" placeholder="Email">
            <input type="password" name="usu_pass" class="form-control" placeholder="Contraseña">

            <input type="hidden" name="enviar" value="si">

            <button class="btn btn-dark btn-block">
                Entrar
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="index.php">Volver</a>
        </div>

    </div>
</div>

</body>
</html>
