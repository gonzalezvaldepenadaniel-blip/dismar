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

  


    <link rel="stylesheet" href="public/css/lib/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="public/css/lib/font-awesome/font-awesome.min.css">
<link rel="stylesheet" href="index.css">

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

        <div class="text-center mt-3">
            <a href="index.php">Volver</a>
        </div>

    </div>
</div>

<script src="public/js/lib/jquery/jquery.min.js"></script>
<script src="public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="index.js"></script>


</body>
</html>
