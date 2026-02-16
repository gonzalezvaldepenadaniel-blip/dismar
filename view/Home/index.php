<?php
date_default_timezone_set('America/Mexico_City');

/*  GUARD DE AUTENTICACIÓN */
require_once("../../config/auth_guard.php");

/* 🔌 CONEXIÓN */
require_once("../../config/conexion.php");
$conexion = Conectar::conexion();

/* DATOS SESIÓN */
$correo_usuario = $_SESSION['correo_usuario'];


/* ================= NOTIFICACIONES ================= */
$stmtNoti = $conexion->prepare("
    SELECT COUNT(*) as total
    FROM tm_notificacion
    WHERE correo_usuario = :correo AND leido = 0
");
$stmtNoti->execute([':correo' => $correo_usuario]);
$totalNoti = $stmtNoti->fetchColumn();

/* Últimas 5 notificaciones */
$stmtListado = $conexion->prepare("
    SELECT *
    FROM tm_notificacion
    WHERE correo_usuario = :correo
    AND leido = 0
    ORDER BY fecha DESC
    LIMIT 5
");
$stmtListado->execute([':correo' => $correo_usuario]);
$notificaciones = $stmtListado->fetchAll(PDO::FETCH_ASSOC);

/* OBTENER NOMBRE Y CEDIS */
$stmt = $conexion->prepare("
    SELECT usu_nombre, usu_apellido, cedis
    FROM tm_usuario
    WHERE usu_correo = :correo
    LIMIT 1
");
$stmt->execute([":correo" => $correo_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$nombre_usuario = $usuario ? $usuario['usu_nombre'] . ' ' . $usuario['usu_apellido'] : 'Usuario';
$cedis_usuario  = $usuario ? $usuario['cedis'] : '';

/* OBTENER TICKETS */
$stmtTickets = $conexion->prepare("
    SELECT 
        t.*,
        CONCAT(u.usu_nombre, ' ', u.usu_apellido) AS admin_asignado
    FROM tm_ticket t
    LEFT JOIN tm_usuario u 
        ON u.usu_id = t.usu_asignado
    WHERE t.correo = :correo
    ORDER BY t.fecha_solicitud DESC
");

$stmtTickets->execute([":correo" => $correo_usuario]);
$tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);


/* ================= GUARDAR TICKET ================= */
if (isset($_POST['guardar'])) {
    $descripcion = $_POST['descripcion'];
    $prioridad   = $_POST['prioridad'];
    $matriz      = $_POST['matriz'];
    $cedis       = $_POST['cedis'];
    $tipo        = $_POST['tipo_solicitud'];

    $solicita = $nombre_usuario;
    $correo   = $correo_usuario;
    $fecha    = date('Y-m-d H:i:s');
    $estado   = 1;

    /* ===== FOLIO ===== */
    $anio = date('y'); $mes  = date('m'); $dia  = date('d');
    switch ($tipo) {
        case 'Mantenimiento': $sigla = 'M'; break;
        case 'Compras':       $sigla = 'C'; break;
        case 'Sistemas':      $sigla = 'S'; break;
        default:              $sigla = 'X';
    }
    $numero = random_int(1000, 9999);
    $folio = "DIS{$anio}{$mes}{$dia}-{$sigla}{$numero}";

    /* ===== EVIDENCIA ===== */
 /* ===== EVIDENCIA OBLIGATORIA ===== */
if (empty($_FILES['evidencia']['name'])) {
    $error = "Es obligatorio subir una imagen.";
} else {
    $carpeta = "../../public/evidencias/";
    if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);

    $nombreArchivo = time() . '_' . $_FILES['evidencia']['name'];
    $ruta = $carpeta . $nombreArchivo;

    if (move_uploaded_file($_FILES['evidencia']['tmp_name'], $ruta)) {
        $evidencia = $nombreArchivo;
    } else {
        $error = "Error al subir la imagen.";
    }
}

    /* ===== INSERT ===== */
if (!empty($error)) {
    // Hay error, no guardar
} else {

    $sql = "
        INSERT INTO tm_ticket
        (folio, solicita, correo, cedis, tipo_solicitud, descripcion,
         prioridad, matriz, evidencia, fecha_solicitud, estado)
        VALUES
        (:folio, :solicita, :correo, :cedis, :tipo, :descripcion,
         :prioridad, :matriz, :evidencia, :fecha, :estado)
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ':folio'       => $folio,
        ':solicita'    => $solicita,
        ':correo'      => $correo,
        ':cedis'       => $cedis,
        ':tipo'        => $tipo,
        ':descripcion' => $descripcion,
        ':prioridad'   => $prioridad,
        ':matriz'      => $matriz,
        ':evidencia'   => $evidencia,
        ':fecha'       => $fecha,
        ':estado'      => $estado
    ]);

    header("Location: index.php");
    exit;
}}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dismar</title>
<link rel="stylesheet" href="home.css">


</head>

<body>

<!-- ================= TOP RIGHT BAR ================= -->
<div class="top-right-bar">

    <!--CAMPANA -->
    <div class="top-campana" id="btnCampana">
        🔔
        <?php if ($totalNoti > 0): ?>
            <span class="badge"><?= $totalNoti ?></span>
        <?php endif; ?>
    </div>

<div class="lista-noti" id="listaNoti">
    <?php if (empty($notificaciones)): ?>
        <div class="noti-vacia">No hay notificaciones</div>
    <?php else: ?>
        <?php foreach ($notificaciones as $n): ?>
            <div class="noti-item noti-ticket"
                 data-ticket-id="<?= $n['ticket_id'] ?>"
                 data-noti-id="<?= $n['noti_id'] ?>">
                <?= htmlspecialchars($n['mensaje']) ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>



    <!-- 👤 USUARIO -->
    <div class="top-user" id="topUserBtn">
        <?= htmlspecialchars($nombre_usuario) ?>
          <span class="arrow">▼</span>
        <div class="top-user-dropdown" id="topUserDropdown">
            <a href="../../config/logout.php">Cerrar sesión</a>

        </div>
    </div>

</div>


<!-- ☰ BOTÓN HAMBURGUESA -->
<div id="btnMenu" class="hamburger">☰</div>
<div id="overlay" class="overlay"></div>

<!-- ================= SIDEBAR ================= -->
<aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <img src="../../public/img/avatar-2-128.png" class="sidebar-logo">
        <div class="user">
            <strong><?= htmlspecialchars($nombre_usuario) ?></strong><br>
            <span><?= htmlspecialchars($correo_usuario) ?></span>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li><a href="#" id="btnInicio">Inicio</a></li>
        <li><a href="#" id="btnMis">Mis Tickets</a></li>
        
    </ul>
</aside>

<!-- ================= HOME ================= -->
<section id="seccionHome" class="home-servicios">
    <h1>SERVICIOS CORPORATIVOS</h1>
    <img src="../../public/img/dismar.png" class="logo-home" alt="Dismar">
    <br><br>
    <button id="btnCrearTicket" class="btn-home">Nuevo Ticket</button>
</section>


<!-- ================= NUEVO TICKET ================= -->
<section id="seccionNuevo" class="ticket-container" style="display:none;">

<form method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Fecha Solicitud</label>
        <input type="text" value="<?= date('Y-m-d H:i') ?>" readonly>
    </div>
    <div class="form-group">
        <label>Quién solicita</label>
        <input type="text" value="<?= htmlspecialchars($nombre_usuario) ?>" readonly>
    </div>
    <div class="form-group">
        <label>Correo</label>
        <input type="email" value="<?= htmlspecialchars($correo_usuario) ?>" readonly>
    </div>
    <input type="hidden" name="cedis" value="<?= htmlspecialchars($cedis_usuario) ?>">
    <div class="form-group">
        <label>Tipo de solicitud</label>
        <select name="tipo_solicitud" required>
            <option value="">Seleccione una opción</option>
            <option value="Mantenimiento">Mantenimiento</option>
            <option value="Compras">Compras</option>
            <option value="Sistemas">Sistemas</option>
        </select>
    </div>
    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" required></textarea>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Prioridad</label>
            <select name="prioridad">
                <option>Alta</option>
                <option>Media</option>
                <option>Baja</option>
            </select>
        </div>
        <div class="form-group">
            <label>Matriz</label>
            <select name="matriz">
                <option value="1">Urgente e Importante</option>
                <option value="2">Importante</option>
                <option value="3">Urgente</option>
                <option value="4">No urgente</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label>Evidencia</label>
        <input type="file" name="evidencia" required>

    </div>
    <button type="submit" name="guardar" class="btn">
        Guardar Ticket
    </button>
</form>

</section>

<!-- ================= MIS TICKETS ================= -->
<section id="seccionMis" style="display:none;">
    <div class="tickets-card">
        <h3 class="section-title">Mis Tickets</h3>

        <?php if (!empty($tickets)): ?>
        <div class="table-responsive">
            <table class="tickets-table">
                <thead>
                    <tr>

                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Cedis</th>
                        <th>Tipo Solicitud</th>
                        <th>Descripción</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Comentarios</th>
                        <th>Asignado</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($tickets as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['folio']) ?></td>
                    <td><?= htmlspecialchars($t['fecha_solicitud']) ?></td>
                    <td><?= htmlspecialchars($t['cedis']) ?></td>
                    <td><?= htmlspecialchars($t['tipo_solicitud']) ?></td>

                    <td>
                        <div class="texto-descripcion">
                            <?= nl2br(htmlspecialchars($t['descripcion'])) ?>
                        </div>
                    </td>

                    <td><?= htmlspecialchars($t['prioridad']) ?></td>

                    <td>
                        <?php
                            switch ($t['estado']) {
                                case 1: echo "<span class='estado abierto'>Abierto</span>"; break;
                                case 2: echo "<span class='estado proceso'>En Proceso</span>"; break;
                                case 3: echo "<span class='estado cerrado'>Cerrado</span>"; break;
                                default: echo "<span>-</span>";
                            }
                        ?>
                    </td>

                    <td><?= htmlspecialchars($t['comentario_admin'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($t['admin_asignado'] ?: 'Sin asignar') ?></td>
                </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p style="text-align:center;color:#666;">No tienes tickets registrados.</p>
        <?php endif; ?>
    </div>
</section>

<!-- ================= MODAL TICKET ================= -->
<div id="modalTicket" class="modal-ticket">
  <div class="modal-content-ticket">

    <div class="modal-header">
      <h2>Detalles del Ticket</h2>
      <span class="close-modal">&times;</span>
    </div>

    <div class="modal-body">
      <div class="modal-item">
        <span>Estado:</span>
        <span id="modalEstado" class="badge-estado"></span>
      </div>

      <div class="modal-item">
        <span>Asignado a:</span>
        <span id="modalAsignado"></span>
      </div>

      <div class="modal-item">
        <span>Comentarios:</span>
        <div id="modalComentario" class="comentario-box"></div>
      </div>
    </div>

  </div>
</div>


<script src="home.js"></script>


</body>
</html>