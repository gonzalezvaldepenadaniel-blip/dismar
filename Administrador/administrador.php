<?php
session_start();

if (!isset($_SESSION["correo_usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../admin-login.php");
    exit();
}

require_once("../config/conexion.php");
require_once("usuarios_modal.php");
require_once("crud.php");

/* =========================
   CONEXIÓN
========================= */
$conectar = new Conectar();
$conexion = $conectar->conexion();
$conectar->set_names();

/* =========================
   LISTAR REPORTES (TICKETS)
========================= */
$sql = "SELECT ticket_id, solicita, correo, tipo_solicitud,
               descripcion, prioridad, cedis, fecha_solicitud,
               estado, evidencia
        FROM tm_ticket
        ORDER BY fecha_solicitud DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dismar - Administrador</title>

<link rel="stylesheet" href="../public/css/lib/bootstrap/bootstrap.min.css">

<style>
body { background:#f8f9fa; font-family:Arial }
.navbar-admin { background:#343a40; padding:10px }
.navbar-admin a {
    color:#fff;
    margin-right:15px;
    font-weight:600;
    text-decoration:none
}
.content { padding:30px }
</style>
</head>

<body>

<!-- ================= MENÚ ================= -->
<div class="navbar-admin">
 
  <a href="#" data-toggle="modal" data-target="#modalUsuarios">Usuarios</a>
  <a href="#" data-toggle="modal" data-target="#modalReportes">Reportes</a>
  <a href="../index.php">Cerrar sesión</a>
</div>

<!-- ================= INICIO ================= -->
<div class="content">
  <h2>Bienvenido, <?= htmlspecialchars($_SESSION["usu_nombre"]) ?></h2>
</div>

<!-- ================= MODAL USUARIOS ================= -->
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

<!-- ================= MODAL REPORTES ================= -->
<div class="modal fade" id="modalReportes" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Reportes de Usuarios</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <table class="table table-bordered table-hover table-sm">
          <thead class="thead-dark">
            <tr>
              <th>Folio</th>
              <th>Solicita</th>
              <th>Correo</th>
              <th>Tipo</th>
              <th>Descripción</th>
              <th>Prioridad</th>
              <th>Cedis</th>
              <th>Fecha</th>
              <th>Estatus</th>
              <th>Evidencia</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($reportes as $r): ?>
            <tr>
              <td><?= $r['ticket_id'] ?></td>
              <td><?= htmlspecialchars($r['solicita']) ?></td>
              <td><?= htmlspecialchars($r['correo']) ?></td>
              <td><?= $r['tipo_solicitud'] ?></td>
              <td><?= $r['descripcion'] ?></td>
              <td><?= $r['prioridad'] ?></td>
              <td><?= $r['cedis'] ?></td>
              <td>
                <?= date('d/m/Y', strtotime($r['fecha_solicitud'])) ?><br>
                <small><?= date('H:i', strtotime($r['fecha_solicitud'])) ?></small>
              </td>
              <td>
                <?php
                  if ($r['estado'] == 1) echo '<span class="badge badge-success">Abierto</span>';
                  elseif ($r['estado'] == 2) echo '<span class="badge badge-warning">En proceso</span>';
                  else echo '<span class="badge badge-secondary">Cerrado</span>';
                ?>
              </td>
              <td>
                <?= $r['evidencia']
                    ? '<a href="../uploads/'.$r['evidencia'].'" target="_blank">Ver</a>'
                    : '—'; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>

      </div>

    </div>
  </div>
</div>

<!-- ================= SCRIPTS ================= -->
<script src="../public/js/lib/jquery/jquery.min.js"></script>
<script src="../public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="usuario.js"></script>

</body>
</html>
