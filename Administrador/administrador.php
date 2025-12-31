<?php
session_start();

if (!isset($_SESSION["correo_usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../admin-login.php");
    exit();
}

require_once("usuarios_modal.php");
require_once("crud.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dismar - Admin</title>

<link rel="stylesheet" href="../public/css/lib/bootstrap/bootstrap.min.css">

<style>
body { background:#f8f9fa; font-family:Arial }
.navbar-admin { background:#343a40; padding:10px }
.navbar-admin a { color:#fff; margin-right:15px; font-weight:600; text-decoration:none }
.content { padding:30px }
</style>
</head>

<body>

<div class="navbar-admin">
<a href="#" data-toggle="modal" data-target="#modalUsuarios">Usuarios</a>
<a href="#" data-toggle="modal" data-target="#modalUsuarios">Reportes</a>
<a href="../index.php">Cerrar sesión</a>
</div>

<div class="content">
<h2>Bienvenido, <?= htmlspecialchars($_SESSION["usu_nombre"]) ?></h2>
</div>

<!-- MODAL USUARIOS -->


<!-- MODAL USUARIOS -->
<div class="modal fade" id="modalUsuarios" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Usuarios registrados</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <button class="btn btn-primary mb-2" onclick="nuevoUsuario()">
          Nuevo usuario
        </button>

        <table class="table table-bordered table-hover">
          <thead class="thead-dark">
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
              <td><?= strtoupper($u["rol"]) ?></td>
              <td><?= $u["estado"] == 1 ? "Activo" : "Inactivo" ?></td>
              <td>
                <button class="btn btn-warning btn-sm editar"
                  data-id="<?= $u["usu_id"] ?>"
                  data-nombre="<?= $u["usu_nombre"] ?>"
                  data-apellido="<?= $u["usu_apellido"] ?>"
                  data-correo="<?= $u["usu_correo"] ?>"
                  data-rol="<?= $u["rol"] ?>">
                  Editar
                </button>

                <button class="btn btn-danger btn-sm eliminar"
                  data-id="<?= $u["usu_id"] ?>">
                  Eliminar
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>

      </div>

    </div>
  </div>
</div>


<script src="../public/js/lib/jquery/jquery.min.js"></script>
<script src="../public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="usuario.js"></script>

</body>
</html>
