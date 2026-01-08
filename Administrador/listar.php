<?php
session_start();
require_once("../config/conexion.php");

if (!isset($_SESSION["correo_usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../admin-login.php");
    exit();
}

$conexion = (new Conectar())->conexion();
$sql = "SELECT usu_id, usu_nombre, usu_apellido, usu_correo, rol, estado FROM tm_usuario";
$usuarios = $conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Usuarios</title>
<link rel="stylesheet" href="../public/css/lib/bootstrap/bootstrap.min.css">
</head>
<body>

<h3 class="m-3">Lista de Usuarios</h3>

<button class="btn btn-success m-3" onclick="nuevoUsuario()">
➕ Nuevo Usuario
</button>

<table class="table table-bordered m-3">
<thead>
<tr>
<th>Nombre</th>
<th>Correo</th>
<th>Rol</th>
<th>Estado</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
<?php foreach ($usuarios as $u): ?>
<tr>
<td><?= htmlspecialchars($u["usu_nombre"]." ".$u["usu_apellido"]) ?></td>
<td><?= htmlspecialchars($u["usu_correo"]) ?></td>
<td><?= htmlspecialchars($u["rol"]) ?></td>
<td><?= $u["estado"] ? "Activo" : "Inactivo" ?></td>
<td>

<button class="btn btn-warning btn-sm editar"
data-id="<?= $u["usu_id"] ?>"
data-nombre="<?= htmlspecialchars($u["usu_nombre"]) ?>"
data-apellido="<?= htmlspecialchars($u["usu_apellido"]) ?>"
data-correo="<?= htmlspecialchars($u["usu_correo"]) ?>"
data-rol="<?= htmlspecialchars($u["rol"]) ?>">
✏️
</button>

<button class="btn btn-danger btn-sm eliminar"
data-id="<?= $u["usu_id"] ?>">🗑</button>

</td>
</tr>
<?php endforeach ?>
</tbody>
</table>

<?php include("modales.php"); ?>

<script src="../public/js/lib/jquery/jquery.min.js"></script>
<script src="../public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="usuarios.js"></script>

</body>
</html>
