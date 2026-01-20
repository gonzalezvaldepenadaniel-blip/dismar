<?php
session_start();
if (!isset($_SESSION["correo_usuario"]) || $_SESSION["rol"] !== "admin") exit;

require_once("../config/conexion.php");

$conectar = new Conectar();
$conexion = $conectar->conexion();

$folio     = $_POST['folio'] ?? '';
$cedis     = $_POST['cedis'] ?? '';
$inicio    = $_POST['inicio'] ?? '';
$fin       = $_POST['fin'] ?? '';
$estado    = $_POST['estado'] ?? '';
$prioridad = $_POST['prioridad'] ?? '';

$sql = "SELECT 
    t.*,
    CONCAT(u.usu_nombre, ' ', u.usu_apellido) AS admin_asignado
FROM tm_ticket t
LEFT JOIN tm_usuario u 
    ON u.usu_id = t.usu_asignado
WHERE 1=1";

$params = [];

if ($folio)     { $sql .= " AND t.folio LIKE ?";         $params[] = "%$folio%"; }
if ($cedis)     { $sql .= " AND t.cedis = ?";            $params[] = $cedis; }
if ($inicio)    { $sql .= " AND t.fecha_solicitud >= ?"; $params[] = "$inicio 00:00:00"; }
if ($fin)       { $sql .= " AND t.fecha_solicitud <= ?"; $params[] = "$fin 23:59:59"; }
if ($estado)    { $sql .= " AND t.estado = ?";           $params[] = $estado; }
if ($prioridad) { $sql .= " AND t.prioridad = ?";        $params[] = $prioridad; }

$sql .= " ORDER BY t.fecha_solicitud DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$data) {
    echo "<tr><td colspan='11' class='text-center'>Sin resultados</td></tr>";
    exit;
}

foreach ($data as $r) {

    if ($r['estado'] == 1) {
        $estatus = "<span class='badge badge-success'>Abierto</span>";
    } elseif ($r['estado'] == 2) {
        $estatus = "<span class='badge badge-warning'>En proceso</span>";
    } else {
        $estatus = "<span class='badge badge-secondary'>Cerrado</span>";
    }

    // ===== EVIDENCIA =====
    if (!empty($r['evidencia']) && ($_SERVER['DOCUMENT_ROOT']."/Dismar/uploads/".$r['evidencia'])) {

        $evidencia = "<a href='/Dismar/public/evidencias/".htmlspecialchars($r['evidencia'])."' target='_blank' class='btn btn-link btn-sm'>Ver</a>";
    } else {
        $evidencia = "<span class='text-muted'>—</span>";
    }

    echo "<tr>
        <td><b>{$r['folio']}</b></td>
        <td>{$r['solicita']}</td>
        <td>{$r['correo']}</td>
        <td>{$r['tipo_solicitud']}</td>
        <td>{$r['descripcion']}</td>
        <td>{$r['prioridad']}</td>
        <td>{$r['cedis']}</td>
        <td>".date('d/m/Y H:i', strtotime($r['fecha_solicitud']))."</td>
        <td>$estatus</td>
        <td class='text-center'>$evidencia</td>
        <td>
            <button
                class='btn btn-primary btn-sm atender'
                data-id='{$r['ticket_id']}'
                data-estado='{$r['estado']}'
                data-comentario='".htmlspecialchars($r['comentario_admin'] ?? '', ENT_QUOTES)."'
                data-asignado='{$r['usu_asignado']}'>
                Atender
            </button>
        </td>
    </tr>";
}
