<?php
session_start();
date_default_timezone_set('America/Mexico_City');

require_once("../../config/conexion.php");

/* 🔐 VALIDAR LOGIN */
if (!isset($_SESSION['correo_usuario'])) {
    header("Location: ../../index.php");
    exit;
}

/* CONEXIÓN */
$conectar = new Conectar();
$conexion = $conectar->conexion();
$conectar->set_names();

$correo_usuario = $_SESSION['correo_usuario'];

/* =========================
   GUARDAR TICKET
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {

    $solicita    = $_POST['solicita'];
    $cedis       = $_POST['cedis'];
    $tipo        = $_POST['tipo_solicitud'];
    $descripcion = $_POST['descripcion'];
    $prioridad   = $_POST['prioridad'];
    $matriz      = $_POST['matriz'];
    $fecha       = date('Y-m-d H:i:s');

    /* SUBIR EVIDENCIA */
    $evidencia = null;
    if (!empty($_FILES['evidencia']['name'])) {
        $carpeta = __DIR__ . "/../../uploads/";
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $evidencia = time() . "_" . $_FILES['evidencia']['name'];
        move_uploaded_file($_FILES['evidencia']['tmp_name'], $carpeta . $evidencia);
    }

    $sql = "INSERT INTO tm_ticket
        (solicita, correo, cedis, tipo_solicitud, descripcion, prioridad, matriz, evidencia, fecha_solicitud)
        VALUES
        (:solicita, :correo, :cedis, :tipo, :descripcion, :prioridad, :matriz, :evidencia, :fecha)";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ":solicita" => $solicita,
        ":correo" => $correo_usuario,
        ":cedis" => $cedis,
        ":tipo" => $tipo,
        ":descripcion" => $descripcion,
        ":prioridad" => $prioridad,
        ":matriz" => $matriz,
        ":evidencia" => $evidencia,
        ":fecha" => $fecha
    ]);

    header("Location: index.php");
    exit;
}

/* =========================
   LISTAR TICKETS
========================= */
$sql = "SELECT ticket_id, tipo_solicitud, descripcion, prioridad, cedis,
               fecha_solicitud, estado, evidencia
        FROM tm_ticket
        WHERE correo = :correo
        ORDER BY fecha_solicitud DESC";

$stmt = $conexion->prepare($sql);
$stmt->bindParam(":correo", $correo_usuario);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tickets de Atención</title>
    <link rel="stylesheet" href="../Home/home.css">
</head>
<body>

<!-- ================= FORMULARIO ================= -->
<div class="ticket-container">

    <div class="header-ticket">
        <img src="../../public/img/dismar.png" class="logo-ticket">
        <div class="title-ticket">
            <h1>SERVICIOS CORPORATIVOS</h1>
            <h2>Tickets de Atención</h2>
        </div>
<div class="logout-box">
       
        <a href="../../index.php" class="btn-logout">Cerrar sesión</a>
    </div>


    </div>

    <form method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label>Fecha Solicitud</label>
            <input type="text" value="<?= date('Y-m-d H:i') ?>" readonly>
        </div>

        <div class="form-group">
            <label>Quién solicita</label>
            <input type="text" name="solicita" required>
        </div>

        <div class="form-group">
            <label>Correo</label>
            <input type="email" value="<?= htmlspecialchars($correo_usuario) ?>" readonly>
        </div>

        <div class="form-group">
            <label>Cedis</label>
            <select name="cedis" required>
                <option value="">Seleccione</option>
                <option>Iztapalapa</option>
                <option>Ecatepec</option>
                <option>Chicoloapan</option>
            </select>
        </div>

        <div class="form-group">
            <label>Tipo de Solicitud</label>
            <select name="tipo_solicitud" required>
                <option value="">Seleccione</option>
                <option>Mantenimiento</option>
                <option>Compras</option>
                <option>Sistemas</option>
            </select>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="4" required></textarea>
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
            <input type="file" name="evidencia">
        </div>

        <button type="submit" name="guardar" class="btn">Guardar Ticket</button>

    </form>
</div>

<!-- ================= LISTADO ================= -->
<?php if (!empty($tickets)) { ?>
<section class="tickets-section">


    <div class="tickets-list">
        <div class="tickets-card">
                    <h3 class="section-title">Mis Tickets</h3>
            <div class="table-responsive">
                <table class="tickets-table">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Prioridad</th>
                            <th>Cedis</th>
                            <th>Fecha y Hora</th>
                            <th>Estatus</th>
                            <th>Evidencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $t) { ?>
                        <tr>
                            <td><?= $t['ticket_id'] ?></td>
                            <td><?= $t['tipo_solicitud'] ?></td>
                            <td><?= $t['descripcion'] ?></td>
                            <td><?= $t['prioridad'] ?></td>
                            <td><?= $t['cedis'] ?></td>
                            <td>
                                <?= date('d/m/Y', strtotime($t['fecha_solicitud'])) ?><br>
                                <small><?= date('H:i', strtotime($t['fecha_solicitud'])) ?></small>
                            </td>
                            <td class="estado">
                                <?php
                                if ($t['estado'] == 1) echo '<strong class="abierto">Abierto</strong>';
                                elseif ($t['estado'] == 2) echo '<strong class="proceso">En proceso</strong>';
                                else echo '<strong class="cerrado">Cerrado</strong>';
                                ?>
                            </td>
                            <td>
                                <?= $t['evidencia'] ? '<a href="../../uploads/'.$t['evidencia'].'" target="_blank">Ver</a>' : '—'; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</section>
<?php } ?>

</body>
</html>
