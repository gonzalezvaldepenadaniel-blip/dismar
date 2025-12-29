<?php
require_once("config/conexion.php");

if (isset($_POST["enviar"]) && $_POST["enviar"] == "si") {
    require_once("models/Usuario.php");
    $usuario = new Usuario();
    $usuario->login("admin"); // 🔐 SOLO ADMIN
}
?>

<!DOCTYPE html>
<html>
<head lang="es">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Dismar | Administrador</title>



	<link rel="stylesheet" href="public/css/separate/pages/login.min.css">
	<link rel="stylesheet" href="public/css/lib/font-awesome/font-awesome.min.css">
	<link rel="stylesheet" href="public/css/lib/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="public/css/main.css">
</head>

<body>

<div class="page-center">
	<div class="page-center-in">
		<div class="container-fluid">
			<form class="sign-box" action="" method="post">
				
				<div class="sign-avatar">
					<img src="public/img/dismar.png" alt="">
				</div>

				<header class="sign-title">
					Acceso Administrador
				</header>

				<?php
				if (isset($_GET["m"])) {
					if ($_GET["m"] == "1") {
				?>
					<div class="alert alert-danger">
						Usuario y/o contraseña incorrectos.
					</div>
				<?php
					} elseif ($_GET["m"] == "2") {
				?>
					<div class="alert alert-danger">
						Campos vacíos.
					</div>
				<?php
					}
				}
				?>

				<div class="form-group">
					<input type="text" name="usu_correo" class="form-control" placeholder="Email">
				</div>

				<div class="form-group">
					<input type="password" name="usu_pass" class="form-control" placeholder="Contraseña">
				</div>

				<input type="hidden" name="enviar" value="si">

				<button type="submit" class="btn btn-rounded btn-primary">
					Ingresar
				</button>

				<br><br>

				<a href="index.php" class="btn btn-light btn-block">
					Volver
				</a>

			</form>
		</div>
	</div>
</div>

<script src="public/js/lib/jquery/jquery.min.js"></script>
<script src="public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="public/js/app.js"></script>

</body>
</html>
