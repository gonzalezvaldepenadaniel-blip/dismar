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
   LISTAR REPORTES
========================= */
$sql = "SELECT ticket_id, solicita, correo, tipo_solicitud,
               descripcion, prioridad, cedis, fecha_solicitud,
               estado, evidencia, comentario_admin
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
<title>Dismarr</title>

<link rel="stylesheet" href="../public/css/lib/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="administrador.css">
</head>

<body>

<!-- ================= MENÚ ================= -->
<div class="navbar-admin">
  <a href="#" data-toggle="modal" data-target="#modalUsuarios"
     onclick="document.getElementById('seccionReportes').style.display='none'">
     Usuarios
  </a>

  <a href="#" onclick="mostrarReportes()">Reportes</a>

  <a href="../index.php">Cerrar sesión</a>
</div>

<!-- ================= INICIO ================= -->
<div class="content">
  <h2>Bienvenido, <?= htmlspecialchars($_SESSION["usu_nombre"]) ?></h2>
</div>

<!-- ================= MODAL USUARIOS ================= -->
<div class="modal fade" id="modalUsuarios" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Usuarios registrados</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
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
              <th>Cedis</th>
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
              <td><?= htmlspecialchars($u["cedis"]) ?></td>
              <td>
                <button class="btn btn-warning btn-sm editar"
                  data-id="<?= $u["usu_id"] ?>"
                  data-nombre="<?= $u["usu_nombre"] ?>"
                  data-apellido="<?= $u["usu_apellido"] ?>"
                  data-correo="<?= $u["usu_correo"] ?>"
                  data-rol="<?= $u["rol"] ?>"
                  data-cedis="<?= $u["cedis"] ?>">
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

<!-- ================= MODAL ACTUALIZAR TICKET ================= -->
<div class="modal fade" id="modalTicket" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Actualizar Ticket</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form id="formTicket">
        <div class="modal-body">

          <input type="hidden" name="ticket_id" id="ticket_id">

          <div class="form-group">
            <label>Estatus</label>
            <select name="estado" id="estado" class="form-control">
              <option value="1">Abierto</option>
              <option value="2">En proceso</option>
              <option value="3">Cerrado</option>
            </select>
          </div>

          <div class="form-group">
            <label>Comentarios</label>
            <textarea name="comentario_admin"
              class="form-control" rows="4"></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- ================= REPORTES ================= -->
<div class="content" id="seccionReportes" style="display:none">

  <h4>Reportes</h4>

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
        <th>Acciones</th>
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
        <td><?= htmlspecialchars($r['cedis']) ?></td>
        <td><?= date('d/m/Y H:i', strtotime($r['fecha_solicitud'])) ?></td>
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
        <td>
          <button class="btn btn-primary btn-sm atender"
            data-id="<?= $r['ticket_id'] ?>"
            data-estado="<?= $r['estado'] ?>"
            data-comentario="<?= htmlspecialchars($r['comentario_admin'] ?? '') ?>">
            Atender
          </button>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- ================= SCRIPTS ================= -->
<script src="../public/js/lib/jquery/jquery.min.js"></script>
<script src="../public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="usuario.js"></script>

<script>
function mostrarReportes() {
  $('.modal').modal('hide');
  document.getElementById('seccionReportes').style.display = 'block';
}
</script>

</body>
</html>
