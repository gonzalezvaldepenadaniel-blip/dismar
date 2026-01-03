<?php
require_once("../config/conexion.php");

$conectar = new Conectar();
$conexion = $conectar->conexion();
$conectar->set_names();

$sql = "SELECT * FROM tm_ticket WHERE 1=1";
$params = [];

if (!empty($_POST['folio'])) {
    $sql .= " AND ticket_id = ?";
    $params[] = $_POST['folio'];
}

if (!empty($_POST['cedis'])) {
    $sql .= " AND cedis = ?";
    $params[] = $_POST['cedis'];
}

if (!empty($_POST['inicio'])) {
    $sql .= " AND fecha_solicitud >= ?";
    $params[] = $_POST['inicio'];
}

if (!empty($_POST['fin'])) {
    $sql .= " AND fecha_solicitud <= ?";
    $params[] = $_POST['fin'];
}

if (!empty($_POST['estado'])) {
    $sql .= " AND estado = ?";
    $params[] = $_POST['estado'];
}

if (!empty($_POST['prioridad'])) {
    $sql .= " AND prioridad = ?";
    $params[] = $_POST['prioridad'];
}

$sql .= " ORDER BY fecha_solicitud DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    echo '<tr><td colspan="11" class="text-center">Sin resultados</td></tr>';
    exit;
}

foreach ($rows as $r):
?>
<tr>
<td><?= $r['ticket_id'] ?></td>
<td><?= htmlspecialchars($r['solicita']) ?></td>
<td><?= htmlspecialchars($r['correo']) ?></td>
<td><?= $r['tipo_solicitud'] ?></td>
<td><?= $r['descripcion'] ?></td>
<td><?= $r['prioridad'] ?></td>
<td><?= $r['cedis'] ?></td>
<td><?= date('d/m/Y H:i', strtotime($r['fecha_solicitud'])) ?></td>
<td><?= $r['estado']==1?'Abierto':($r['estado']==2?'En proceso':'Cerrado') ?></td>
<td><?= $r['evidencia']?'<a href="../uploads/'.$r['evidencia'].'" target="_blank">Ver</a>':'—' ?></td>
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
