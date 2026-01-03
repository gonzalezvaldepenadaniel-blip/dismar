<?php
session_start();

if (!isset($_SESSION["correo_usuario"]) || $_SESSION["rol"] !== "admin") {
    exit;
}

require_once("../config/conexion.php");

/* ===== CONEXIÓN ===== */
$conectar = new Conectar();
$conexion = $conectar->conexion();
$conectar->set_names();

/* ===== FILTROS ===== */
$folio     = $_POST['folio']     ?? '';
$cedis     = $_POST['cedis']     ?? '';
$inicio    = $_POST['inicio']    ?? '';
$fin       = $_POST['fin']       ?? '';
$estado    = $_POST['estado']    ?? '';
$prioridad = $_POST['prioridad'] ?? '';

/* ===== VALIDACIÓN FECHAS (🔥 IMPORTANTE) ===== */
if (!empty($inicio) && !empty($fin) && $inicio > $fin) {
    echo '
    <tr>
        <td colspan="11" class="text-center text-danger font-weight-bold">
            ⚠️ La fecha inicial no puede ser mayor a la fecha final
        </td>
    </tr>';
    exit;
}

/* ===== QUERY BASE ===== */
$sql = "SELECT ticket_id, solicita, correo, tipo_solicitud,
               descripcion, prioridad, cedis, fecha_solicitud,
               estado, evidencia, comentario_admin
        FROM tm_ticket
        WHERE 1=1";

$params = [];

/* ===== APLICAR FILTROS ===== */
if (!empty($folio)) {
    $sql .= " AND ticket_id = ?";
    $params[] = $folio;
}

if (!empty($cedis)) {
    $sql .= " AND cedis = ?";
    $params[] = $cedis;
}

if (!empty($inicio)) {
    $sql .= " AND fecha_solicitud >= ?";
    $params[] = $inicio . " 00:00:00";
}

if (!empty($fin)) {
    $sql .= " AND fecha_solicitud <= ?";
    $params[] = $fin . " 23:59:59";
}

if (!empty($estado)) {
    $sql .= " AND estado = ?";
    $params[] = $estado;
}

if (!empty($prioridad)) {
    $sql .= " AND prioridad = ?";
    $params[] = $prioridad;
}

/* ===== ORDEN ===== */
$sql .= " ORDER BY fecha_solicitud DESC";

/* ===== EJECUTAR ===== */
$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===== RESPUESTA HTML ===== */
if (!$reportes) {
    echo '
    <tr>
        <td colspan="11" class="text-center text-muted">
            No se encontraron resultados
        </td>
    </tr>';
    exit;
}

foreach ($reportes as $r):
?>
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
